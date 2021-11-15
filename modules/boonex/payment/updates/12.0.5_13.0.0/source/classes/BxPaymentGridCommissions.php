<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxPaymentGridCommissions extends BxBaseModPaymentGridCommissions
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);
    }

    public function performActionAdd()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'add';

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sFormMethod = $oForm->aFormAttrs['method'];

            $sLanguage = BxDolStudioLanguagesUtils::getInstance()->getCurrentLangName(false);

            $sName = BxDolForm::getSubmittedValue('caption-' . $sLanguage, $sFormMethod);
            $sName = uriGenerate(strtolower($sName), $CNF['TABLE_COMMISSIONS'], 'name', 'commission');

            $aValsToAdd = array(
                'name' => $sName,
                'active' => 1,
                'order' => $this->_oModule->_oDb->getCommissions(array('type' => 'max_order')) + 1
            );

            if(($iId = $oForm->insert($aValsToAdd)) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_payment_err_cannot_perform'));

            return echoJson($aRes);
        }

        $sId = $this->_oModule->_oConfig->getHtmlIds('commission', 'popup_' . $sAction);
        $sTitle = _t($this->_sLangsPrefix . 'popup_title_cms_' . $sAction);

        $sContent = BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('commission_form.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }

    public function performActionEdit()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return echoJson(array());

            $aIds = array($iId);
        }

        $iId = (int)array_shift($aIds);
        $aCommission = $this->_oModule->_oDb->getCommissions(array('type' => 'id', 'id' => $iId));
        if(empty($aCommission) || !is_array($aCommission))
            return echoJson(array());

        $sAction = 'edit';

        $oForm = $this->_getFormObject($sAction, $iId);
        $oForm->initChecker($aCommission);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_payment_err_cannot_perform'));

            return echoJson($aRes);
        }

        $sId = $this->_oModule->_oConfig->getHtmlIds('commission', 'popup_' . $sAction);
        $sTitle = _t($this->_sLangsPrefix . 'popup_title_cms_' . $sAction);

        $sContent = BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('commission_form.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }

    protected function _delete($mixedId)
    {
        $aCommission = $this->_oModule->_oDb->getCommissions(array('type' => 'id', 'id' => $mixedId));
        if(empty($aCommission) || !is_array($aCommission))
            return false;

        $mixedResult = parent::_delete($mixedId);
        if($mixedResult !== false) {
            $oLanguage = BxDolStudioLanguagesUtils::getInstance();
            $oLanguage->deleteLanguageString($aCommission['caption']);
            $oLanguage->deleteLanguageString($aCommission['description']);
        }

        return $mixedResult;
    }

    protected function _getFormObject($sAction, $iId = 0)
    {
        $sFormObject = $this->_oModule->_oConfig->getObject('form_commissions');
        $sFormDisplay = $this->_oModule->_oConfig->getObject('form_display_commissions_' . $sAction);

        $aParams = array('o' => $this->_sObject, 'a' => $sAction);
        if(!empty($iId))
            $aParams['id'] = $iId;

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . bx_append_url_params('grid.php', $aParams);

        return $oForm;
    }
}

/** @} */
