<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ACL ACL
 * @ingroup     TridentModules
 * 
 * @{
 */


class BxAclGridAdministration extends BxTemplGrid
{
	protected $MODULE;
	protected $_oModule;

	protected $_sParamsDivider = '#-#';
	protected $_iLevelId = 0;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct ($aOptions, $oTemplate);

        $iLevel = (int)bx_get('level');
        if($iLevel > 0) {
            $this->_iLevelId = $iLevel;
            $this->_aQueryAppend['level'] = $this->_iLevelId;
        }
    }

	public function performActionAdd()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$sAction = 'add';

        $sFilter = bx_get('filter');
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_iLevelId, $sFilter) = explode($this->_sParamsDivider, $sFilter);

        if((int)$this->_iLevelId == 0)
            $this->_iLevelId = (int)bx_get('level_id');

    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_ADD']);
    	$oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&level=' . $this->_iLevelId;
    	$oForm->aInputs['level_id']['value'] = $this->_iLevelId;

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$iLevel = $oForm->getCleanValue('level_id');
        	$iPeriod = $oForm->getCleanValue('period');
        	$sPeriodUnit = $oForm->getCleanValue('period_unit');

            $aPrice = $this->_oModule->_oDb->getPrices(array('type' => 'by_level_id_duration', 'level_id' => $iLevel, 'period' => $iPeriod, 'period_unit' => $sPeriodUnit));
            if(!empty($aPrice) && is_array($aPrice)) {
                echoJson(array('msg' => _t('_bx_acl_err_price_duplicate')));
                return;
            }

            $aLevel = $this->_oModule->_oDb->getLevels(array('type' => 'by_id', 'value' => $iLevel));
            $sName = uriGenerate(strtolower(_t($aLevel['name'])) . ' ' . $iPeriod . ' ' . $sPeriodUnit, $CNF['TABLE_PRICES'], 'name');

            $iId = (int)$oForm->insert(array('name' => $sName, 'order' => $this->_oModule->_oDb->getPriceOrderMax($this->_iLevelId) + 1));
            if($iId != 0) {
            	//TODO: May be we don't need to have this 'Purchasable' flag at all or at least we shouldn't update it from here.
                $this->_oModule->_oDb->updateLevels(array('Purchasable' => 'yes'), array('ID' => $this->_iLevelId));
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            } 
            else
                $aRes = array('msg' => _t('_bx_acl_err_cannot_perform'));

            echoJson($aRes);
            return;
        }

		bx_import('BxTemplStudioFunctions');
		$sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t('_bx_acl_popup_title_price_add'), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', array(
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

        $sAction = 'edit';

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) {
	            echoJson(array());
	            exit;
	        }

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $aItem = $this->_oModule->_oDb->getPrices(array('type' => 'by_id', 'value' => $iId));
        if(!is_array($aItem) || empty($aItem)) {
        	echoJson(array());
			exit;
        }

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_EDIT']);
    	$oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&level=' . $this->_iLevelId;

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aItem['id']);
            else
                $aRes = array('msg' => _t('_bx_acl_err_cannot_perform'));

            echoJson($aRes);
            return;
        }

		bx_import('BxTemplStudioFunctions');
		$sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t('_bx_acl_popup_title_price_edit'), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', array(
			'form_id' => $oForm->aFormAttrs['id'],
			'form' => $oForm->getCode(true),
			'object' => $this->_sObject,
			'action' => $sAction
		)));

		$aRes = array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false)));

		echoJson($aRes);
    }

	public function performActionDelete()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            return;
        }

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
			if($this->_oModule->_oDb->deletePrices(array('id' => $iId))) {
				$aIdsAffected[] = $iId;
        		$iAffected++;
			}

		echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_bx_acl_err_cannot_perform')));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addStudioJs(array('jquery.form.min.js', 'administration.js'));
        $this->_oModule->_oTemplate->addStudioCss(array('administration.css'));

        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    protected function _getCellPeriodUnit($mixedValue, $sKey, $aField, $aRow)
    {
    	$mixedValue = _t('_bx_acl_pre_values_' . $mixedValue);
    	return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellPrice($mixedValue, $sKey, $aField, $aRow)
    {
    	$aCurrency = $this->_oModule->_oConfig->getCurrency();

        return parent::_getCellDefault($aCurrency['sign'] . $mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getActionAdd ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(empty($this->_iLevelId))
            $isDisabled = true;

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

	protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sContent = '';
        $oForm = new BxTemplFormView(array());

        $aInputLevels = array(
            'type' => 'select',
            'name' => 'level',
            'attrs' => array(
                'id' => 'bx-grid-level-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->_oModule->_oConfig->getJsObject('administration') . '.onChangeLevel()'
            ),
            'value' => $this->_iLevelId,
            'values' => array(0 => _t('_bx_acl_txt_select_level'))
        );

        $aLevels = $this->_oModule->_oDb->getLevels(array('type' => 'for_selector'));
        foreach($aLevels as $iId => $sTitle)
        	$aInputLevels['values'][$iId] = _t($sTitle);

        $sContent .=  $oForm->genRow($aInputLevels);

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
            ),
            'tr_attrs' => array(
                'style' => 'display:none;'
            )
        );
        $sContent .= $oForm->genRow($aInputSearch);

        return $sContent;
    }

	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->_iLevelId) && strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_iLevelId, $sFilter) = explode($this->_sParamsDivider, $sFilter);

		if(empty($this->_iLevelId))
            return array();

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString("AND `level_id`=? ", $this->_iLevelId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);;
    }
}

/** @} */
