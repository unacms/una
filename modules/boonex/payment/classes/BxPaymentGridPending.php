<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 * 
 * @{
 */


class BxPaymentGridPending extends BxBaseModPaymentGridOrders
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);

        $this->_sOrdersType = 'pending';
    }

	public function performActionProcess()
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return echoJson(array());

            $aIds = array($iId);
        }

        $iId = (int)$aIds[0];

    	$sAction = 'process';
        $sFormObject = $this->_oModule->_oConfig->getObject('form_pendings');
        $sFormDisplay = $this->_oModule->_oConfig->getObject('display_pendings_process');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->_oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;

        $oForm->aInputs['id']['value'] = $iId;
        $oForm->aInputs['seller_id']['value'] = $this->_aQueryAppend['seller_id'];

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$iId = $oForm->getCleanValue('id');

			$this->_oModule->_oDb->updateOrderPending($iId, array(
				'order' => $oForm->getCleanValue('order'),
				'error_code' => 0,
				'error_msg' => 'Manually processed'
			));

            if($this->_oModule->registerPayment($iId))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t($this->_sLangsPrefix . 'err_cannot_perform'));

            return echoJson($aRes);
        }

        $sKey = 'order_' . $this->_sOrdersType . '_process';
        $sId = $this->_oModule->_oConfig->getHtmlIds('pending', $sKey);
    	$sTitle = _t($this->_sLangsPrefix . 'popup_title_ods_' . $sKey);

		$sContent = BxTemplStudioFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('order_pending_process.html', array(
			'form_id' => $oForm->aFormAttrs['id'],
			'form' => $oForm->getCode(true),
			'object' => $this->_sObject,
			'action' => $sAction
		)));

		echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }

	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	if(empty($this->_aQueryAppend['seller_id']))
    		return array();

		$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tt`.`seller_id`=?", $this->_aQueryAppend['seller_id']);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
