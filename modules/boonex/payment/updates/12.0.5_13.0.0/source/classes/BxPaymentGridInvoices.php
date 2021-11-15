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

class BxPaymentGridInvoices extends BxBaseModPaymentGridInvoices
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);
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
        $aInvoice = $this->_oModule->_oDb->getInvoices(array('type' => 'id', 'id' => $iId));
        if(empty($aInvoice) || !is_array($aInvoice))
            return echoJson(array());

        $sAction = 'edit';

        $oForm = $this->_getFormObject($sAction, $iId);
        $oForm->initChecker($aInvoice);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_payment_err_cannot_perform'));

            return echoJson($aRes);
        }

        $sId = $this->_oModule->_oConfig->getHtmlIds('invoice', 'popup_' . $sAction);
        $sTitle = _t($this->_sLangsPrefix . 'popup_title_cms_' . $sAction);

        $sContent = BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('commission_form.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }

    protected function _getFormObject($sAction, $iId = 0)
    {
        $sFormObject = $this->_oModule->_oConfig->getObject('form_invoices');
        $sFormDisplay = $this->_oModule->_oConfig->getObject('form_display_invoices_' . $sAction);

        $aParams = array('o' => $this->_sObject, 'a' => $sAction);
        if(!empty($iId))
            $aParams['id'] = $iId;

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . bx_append_url_params('grid.php', $aParams);

        return $oForm;
    }
}

/** @} */
