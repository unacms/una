<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Sites Sites
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAcl');

define('BX_SITES_ACCOUNT_STATUS_UNCONFIRMED', 'unconfirmed');
define('BX_SITES_ACCOUNT_STATUS_PENDING', 'pending');
define('BX_SITES_ACCOUNT_STATUS_TRIAL', 'trial');
define('BX_SITES_ACCOUNT_STATUS_ACTIVE', 'active');
define('BX_SITES_ACCOUNT_STATUS_CANCELED', 'canceled');
define('BX_SITES_ACCOUNT_STATUS_SUSPENDED', 'suspended');

define('BX_SITES_TRANSACTION_INIT', 'init');
define('BX_SITES_TRANSACTION_TRIAL', 'trial');
define('BX_SITES_TRANSACTION_REGULAR', 'regular');

/**
 * PayPal integration.
 */
define('BX_SITES_PP_DIRECTORY_PATH_API', BX_DIRECTORY_PATH_MODULES . 'boonex/sites/api/');

define('BX_SITES_PP_ACTIVATION_INIT_AMOUNT', 'init_amount');
define('BX_SITES_PP_ACTIVATION_TRIAL_PERIOD', 'trial_period');

define('BX_SITES_PP_PERIOD_TRIAL', 'Trial');
define('BX_SITES_PP_PERIOD_REGULAR', 'Regular');

define('BX_SITES_PP_RPA_CANCEL', 'Cancel');
define('BX_SITES_PP_RPA_SUSPEND', 'Suspend');
define('BX_SITES_PP_RPA_REACTIVATE', 'Reactivate');

/**
 * Sites module
 */
class BxSitesModule extends BxDolModule
{
    protected $_iProfileId;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);

        $this->_iProfileId = bx_get_logged_profile_id();
    }

    // ====== SERVICE METHODS
    public function serviceIsUsed($sDomain)
    {
        return $this->_oDb->isAccount(array('domain' => $sDomain));
    }

    public function serviceBrowse()
    {
        $oGrid = BxDolGrid::getObjectInstance('bx_sites_browse');
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }

    public function serviceSiteCreate()
    {
        bx_import('Forms', $this->_aModule);
        $oForms = new BxSitesForms($this);
        return $oForms->addDataForm();
    }

    public function serviceSiteSubscribe()
    {
        $sToken = bx_process_input(bx_get('token'));
        if($sToken === false)
            return MsgBox(_t('_bx_sites_paypal_err_token_not_found'));

        bx_import('Paypal', $this->_aModule);
        $oPaypal = new BxSitesPaypal($this);
        $sResultMessage = $oPaypal->subscribe($sToken);

        return MsgBox(_t($sResultMessage));
    }

    public function serviceSiteView()
    {
        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if($iId === false)
            return MsgBox(_t('_bx_sites_txt_err_site_is_not_defined'));

        $aAccount = $this->_oDb->getAccount(array('type' => 'id', 'value' => $iId));
        if(empty($aAccount) || !is_array($aAccount))
            return MsgBox(_t('_bx_sites_txt_err_site_is_not_defined'));

        $sMsg = $this->isAllowedView($aAccount);
        if($sMsg !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox($sMsg);

        $oGrid = BxDolGrid::getObjectInstance('bx_sites_overview');
        if(!$oGrid)
            return '';

        $oGrid->setAccount($aAccount);
        return array(
            'title' => $this->getDomain($aAccount['domain']),
            'content' => $this->_oTemplate->getJs(true) . $oGrid->getCode()
        );
    }

    public function serviceSiteEdit()
    {
        $aParams = $this->getSelectParam();
        if($aParams === false)
            return MsgBox(_t('_bx_sites_txt_err_site_is_not_defined'));

        $aAccount = $this->_oDb->getAccount($aParams);
        if(empty($aAccount) || !is_array($aAccount))
            return MsgBox(_t('_bx_sites_txt_err_site_is_not_defined'));

        bx_import('Forms', $this->_aModule);
        $oForms = new BxSitesForms($this);

        return array(
            'title' => _t('_bx_sites_page_block_title_site_manage') . ' ' . $this->getDomain($aAccount['domain']),
            'content' => $oForms->editDataForm($aAccount)
        );
    }

    public function serviceSiteDelete($iId = 0)
    {
        if(!$iId)
            $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iId)
            return MsgBox(_t('_bx_sites_txt_err_site_is_not_defined'));

        $aAccount = $this->_oDb->getAccount(array('type' => 'id', 'value' => $iId));
        if(empty($aAccount) || !is_array($aAccount))
            return MsgBox(_t('_bx_sites_txt_err_site_is_not_defined'));

        bx_import('Forms', $this->_aModule);
        $oProfileForms = new BxSitesForms($this);

        return array(
            'title' => _t('_bx_sites_page_block_title_site_delete') . ' ' . $this->getDomain($aAccount['domain']),
            'content' => $oProfileForms->deleteDataForm($aAccount)
        );
    }

    // ====== ACTION METHODS
    public function actionIpn()
    {
        $aData = &$_POST;
        foreach($aData as $sKey => $sValue)
            $aData[$sKey] = bx_process_input(trim($sValue));

        if(!isset($aData['txn_type']))
            return;

        bx_import('Paypal', $this->_aModule);
        $oPaypal = new BxSitesPaypal($this);
        $oPaypal->process($aData);

        bx_import('Account', $this->_aModule);
        $oAccount = new BxSitesAccount($this);
        if($oPaypal->bProfileCreated) {
            $oAccount->onProfileConfirmed($aData);
        } else if($oPaypal->bProfileCanceled) {
            $oAccount->onProfileCanceled($aData);
        } else if($oPaypal->bPaymentDone) {
            $oAccount->onPaymentReceived($aData, $oPaypal->fPaymentAmout);
        } else if($oPaypal->bPaymentRefund) {
            $oAccount->onPaymentRefunded($aData);
        }
    }

    /*
     * Can be removed if it's not used.
     *
    public function actionReactivate($iId)
    {
        $aResult = array('code' => '1', 'message' => _t('_bx_sites_txt_err_cannot_perform'));

        $sUrl = $this->startSubscription($iId);
        if(!empty($sUrl))
            $aResult = array('code' => '0', 'message' => '', 'redirect' => $sUrl);

        header('Content-Type:text/javascript');
        echo json_encode($aResult);
    }
    */

    // ====== PERMISSION METHODS
    public function isAllowedAdd ($isPerformAction = false)
    {
        $aCheck = checkActionModule($this->_iProfileId, 'create site', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isAllowedView ($aDataEntry, $isPerformAction = false)
    {
        // moderator and owner always have access
        if ($aDataEntry[BxSitesConfig::$FIELD_AUTHOR] == $this->_iProfileId || $this->isModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    public function isAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        // moderator and owner always have access
        if ($aDataEntry[BxSitesConfig::$FIELD_AUTHOR] == $this->_iProfileId || $this->isModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    public function isAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        // moderator and owner always have access
        if ($aDataEntry[BxSitesConfig::$FIELD_AUTHOR] == $this->_iProfileId || $this->isModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    public function isModeratorAccess ($isPerformAction = false)
    {
        $aCheck = checkActionModule($this->_iProfileId, 'manage sites', $this->getName(), $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED;
    }


    // ====== COMMON METHODS
    public function getSelectParam()
    {
        $sType = 'id';
        $mixedId = bx_get('id');
        if($mixedId === false) {
            $mixedId = bx_get('sid');
            if($mixedId === false)
                return false;

            $sType = 'profile_sid';
            $mixedId = bx_process_input($mixedId);
        } else
            $mixedId = bx_process_input($mixedId, BX_DATA_INT);

        return array('type' => $sType, 'value' => $mixedId);
    }

    public function getDomain($sDomain, $bProtocol = false, $bWww = false)
    {
        $sDomain = sprintf($this->_oConfig->getDomainMask(), $sDomain);

        if($bWww)
            $sDomain = 'www.' . $sDomain;

        if($bProtocol)
            $sDomain = 'http://' . $sDomain;

        return $sDomain;
    }

    public function getObject($sClass)
    {
        return bx_instance('BxSites' . $sClass, array($this), $this->_aModule);
    }

    public function startSubscription($iId)
    {
        $oPaypal = $this->getObject('Paypal');
        return $oPaypal->start($iId);
    }

    public function cancelSubscription($sPpProfileId)
    {
        $oPaypal = $this->getObject('Paypal');
        return $oPaypal->performAction($sPpProfileId);
    }
}

/** @} */
