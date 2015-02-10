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

/**
 * Note forms functions
 */
class BxSitesForms extends BxDolProfileForms
{
    protected $_oModule;

    public function __construct(&$oModule)
    {
        parent::__construct();
        $this->_oModule = $oModule;
    }

    /**
     * @return add data html
     */
    public function addDataForm()
    {
        $sMsg = $this->_oModule->isAllowedAdd();
        if($sMsg !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox($sMsg);

        $oForm = BxDolForm::getObjectInstance('bx_sites', 'bx_sites_site_add');
        if(!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        unset($oForm->aInputs['submit_block'][1]);
        $oForm->initChecker();
        if(!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        $sDomain = $oForm->getCleanValue('domain');
        if($this->_oModule->_oDb->isAccount(array('domain' => $sDomain)))
            return MsgBox(_t('_bx_sites_txt_err_site_exists'));

        $iAccountId = $oForm->insert(array(
            'owner_id' => bx_get_logged_profile_id(),
            'created' => time(),
            'status' => BX_SITES_ACCOUNT_STATUS_UNCONFIRMED
        ));

        if(!$iAccountId)
            return MsgBox(_t('_bx_sites_txt_err_site_creation'));

        $oAccount = $this->_oModule->getObject('Account');
        $oAccount->onAccountCreated($iAccountId);

        $sUrl = $this->_oModule->startSubscription($iAccountId);
        header('Location: ' . $sUrl);
        exit;
    }

    /**
     * @return edit data html
     */
    public function editDataForm($aAccount, $sDisplay = 'bx_sites_site_edit')
    {
        $sMsg = $this->_oModule->isAllowedEdit($aAccount);
        if($sMsg !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance('bx_sites', $sDisplay);
        if(!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->aInputs['domain']['required'] = false;
        $oForm->aInputs['domain']['attrs'] = array(
            'disabled' => 'disabled'
        );
        unset($oForm->aInputs['domain']['checker'], $oForm->aInputs['domain']['db']);

        $oForm->initChecker($aAccount);
        $oForm->aInputs['domain']['value'] = $this->_oModule->getDomain($oForm->aInputs['domain']['value']);
        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        if(!$oForm->update($aAccount['id'])) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_bx_sites_txt_err_site_update'));
        }

        // perform action
        $this->_oModule->isAllowedEdit($aAccount, true);

        // create an alert
        bx_alert($this->_oModule->getName(), 'edited', $aAccount['id']);

        // redirect
        $this->_redirectAndExit('page.php?i=site-view&id=' . $aAccount['id']);
    }

    /**
     * @return delete data html
     */
    public function deleteDataForm($aAccount, $sDisplay = 'bx_sites_site_delete')
    {
        $sMsg = $this->_oModule->isAllowedDelete($aAccount);
        if($sMsg !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance('bx_sites', $sDisplay);
        if(!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aAccount);
        if(!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        if(!$oForm->delete($aAccount['id'], $aAccount))
            return MsgBox(_t('_bx_sites_txt_err_site_delete'));

        //delete payment details and history
        if(!empty($aAccount['id'])) {
            $this->_oModule->_oDb->deletePaymentDetails(array('account_id' => $aAccount['id']));
            $this->_oModule->_oDb->deletePaymentHistory(array('account_id' => $aAccount['id']));
        }

        // cancel subscription
        if(!empty($aAccount['pd_profile_id'])) {
            bx_import('Paypal', $this->_oModule->_aModule);
            $oPaypal = new BxSitesPaypal($this->_oModule);
            $oPaypal->performAction($aAccount['pd_profile_id']);
        }

        // perform action
        $this->_oModule->isAllowedDelete($aAccount, true);

        // create an alert
        bx_alert($this->_oModule->getName(), 'deleted', $aAccount['id']);

        // redirect
        $this->_redirectAndExit('page.php?i=sites-home');
    }
}

/** @} */
