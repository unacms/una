<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxStripeConnectGridCommissions extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_aAclLevels;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_stripe_connect';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);

        $this->_aAclLevels = BxDolAcl::getInstance()->getMemberships(false, true, true, true);
    }

    public function performActionAdd()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'add';

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sFormMethod = $oForm->aFormAttrs['method'];

            $sName = $oForm->getCleanValue($CNF['FIELD_CMS_NAME']);
            $sName = uriGenerate(strtolower($sName), $CNF['TABLE_COMMISSIONS'], $CNF['FIELD_CMS_NAME']);

            $iAclId = (int)$oForm->getCleanValue($CNF['FIELD_CMS_ACL_ID']);
            $aCommissionByAcl = $this->_oModule->_oDb->getCommissions(['type' => 'acl_id', 'acl_id' => $iAclId]);
            if(!empty($aCommissionByAcl) && is_array($aCommissionByAcl))
                return echoJson(['msg' => _t('_bx_stripe_connect_err_non_unique_acl'), 'blink' => $aCommissionByAcl[$CNF['FIELD_CMS_ID']]]);

            $aValsToAdd = [
                'name' => $sName,
                'active' => 1,
                'order' => $this->_oModule->_oDb->getCommissions(['type' => 'max_order']) + 1
            ];

            if(($iId = $oForm->insert($aValsToAdd)) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_bx_stripe_connect_err_perform')];

            return echoJson($aRes);
        }

        $sId = $this->_oModule->_oConfig->getHtmlIds('commissions_popup') . $sAction;
        $sTitle = _t('_bx_payment_popup_title_cms_' . $sAction);
        $sContent = BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('commissions_form.html', [
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionEdit()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'edit';

        $iId = $this->_getId();
        $aCommission = $this->_oModule->_oDb->getCommissions(['type' => 'id', 'id' => $iId]);
        if(empty($aCommission) || !is_array($aCommission))
            return echoJson([]);

        $oForm = $this->_getFormObject($sAction, $iId);
        $oForm->initChecker($aCommission);
        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];

            $sName = $oForm->getCleanValue($CNF['FIELD_CMS_NAME']);
            if(strcmp($sName, $aCommission[$CNF['FIELD_CMS_NAME']]) != 0)
                $aValsToAdd[$CNF['FIELD_CMS_NAME']] = uriGenerate(strtolower($sName), $CNF['TABLE_COMMISSIONS'], $CNF['FIELD_CMS_NAME']);

            $iAclId = (int)$oForm->getCleanValue($CNF['FIELD_CMS_ACL_ID']);
            if($iAclId != (int)$aCommission[$CNF['FIELD_CMS_ACL_ID']]) {
                $aCommissionByAcl = $this->_oModule->_oDb->getCommissions(['type' => 'acl_id', 'acl_id' => $iAclId]);
                if(!empty($aCommissionByAcl) && is_array($aCommissionByAcl))
                    return echoJson(['msg' => _t('_bx_stripe_connect_err_non_unique_acl'), 'blink' => $aCommissionByAcl[$CNF['FIELD_CMS_ID']]]);
            }

            if($oForm->update($iId, $aValsToAdd) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_bx_payment_err_cannot_perform')];

            return echoJson($aRes);
        }

        $sId = $this->_oModule->_oConfig->getHtmlIds('commissions_popup') . $sAction;
        $sTitle = _t('_bx_payment_popup_title_cms_' . $sAction);
        $sContent = BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('commissions_form.html', [
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getCellAclId($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = isset($this->_aAclLevels[$mixedValue]) > 0 ? $this->_aAclLevels[$mixedValue] : _t('_all');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getFormObject($sAction, $iId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aParams = ['o' => $this->_sObject, 'a' => $sAction];
        if(!empty($iId))
            $aParams['id'] = $iId;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_COMMISSIONS'], $CNF['OBJECT_FORM_COMMISSIONS_DISPLAY_' . strtoupper($sAction)]);
        $oForm->setId($this->_oModule->_oConfig->getHtmlIds('commissions_form') . $sAction);
        $oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', $aParams));

        return $oForm;
    }

    protected function _getId()
    {
        $aIds = bx_get('ids');
        if(!empty($aIds) && is_array($aIds)) 
            return (int)reset($aIds);

        $iId = bx_get('id');
        if($iId !== false) 
            return (int)$iId;

        return false;
    }
}

/** @} */
