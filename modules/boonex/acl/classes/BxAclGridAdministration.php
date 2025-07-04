<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxAclGridLevels.php');

class BxAclGridAdministration extends BxAclGridLevels
{
    protected $_sParamsDivider = '#-#';
    protected $_aLevels = [];
    protected $_iLevelId = 0;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_aLevels = $this->_oModule->_oDb->getLevels(['type' => 'for_selector']);

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

    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_ADD']);
        $oForm->setAction(BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&level=' . $this->_iLevelId);
        $oForm->setLevelId($this->_iLevelId);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iLevel = $oForm->getCleanValue('level_id');
            $iPeriod = $oForm->getCleanValue('period');
            $sPeriodUnit = $oForm->getCleanValue('period_unit');

            if(!empty($iPeriod) && empty($sPeriodUnit)) 
                return echoJson(['msg' => _t('_bx_acl_form_price_input_err_period_unit')]);

            $aPriceSimilar = $this->_oModule->_oDb->getPrices([
                'type' => 'by_level_id_duration', 
                'level_id' => $iLevel, 
                'period' => $iPeriod, 
                'period_unit' => $sPeriodUnit
            ]);

            $iId = (int)$oForm->insert(array('added' => time(), 'order' => $this->_oModule->_oDb->getPriceOrderMax($this->_iLevelId) + 1));
            if($iId != 0) {
            	//TODO: May be we don't need to have this 'Purchasable' flag at all or at least we shouldn't update it from here.
                $this->_oModule->_oDb->updateLevels(['Purchasable' => 'yes'], ['ID' => $this->_iLevelId]);

                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
                if(!empty($aPriceSimilar) && is_array($aPriceSimilar))
                    $aRes['msg'] = _t('_bx_acl_err_price_duplicate');
            } 
            else
                $aRes = ['msg' => _t('_bx_acl_err_cannot_perform')];

            echoJson($aRes);
            return;
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t('_bx_acl_popup_title_price_add'), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', [
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false, 'removeOnClose' => true]]]);
    }

    public function performActionEdit()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'edit';

        $aIds = $this->_getIds();
        if($aIds === false)
            return echoJson([]);

        $iId = $aIds[0];

        $aItem = $this->_oModule->_oDb->getPrices(['type' => 'by_id', 'value' => $iId]);
        if(!is_array($aItem) || empty($aItem)) {
            echoJson([]);
            exit;
        }

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_EDIT']);
        $oForm->setAction(BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&level=' . $this->_iLevelId);

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $aItem['id']];
            else
                $aRes = ['msg' => _t('_bx_acl_err_cannot_perform')];

            echoJson($aRes);
            return;
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t('_bx_acl_popup_title_price_edit'), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', [
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false, 'removeOnClose' => true]]]);
    }

    public function performActionDelete()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson([]);
            return;
        }

        $iAffected = 0;
        $aIdsAffected = [];
        foreach($aIds as $iId)
            if($this->_oModule->_oDb->deletePrices(['id' => $iId])) {
                $aIdsAffected[] = $iId;
                $iAffected++;
            }

        echoJson($iAffected ? ['grid' => $this->getCode(false), 'blink' => $aIdsAffected] : ['msg' => _t('_bx_acl_err_cannot_perform')]);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addStudioJs(array('jquery.form.min.js', 'administration.js'));
        $this->_oModule->_oTemplate->addStudioCss(array('administration.css'));

        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    protected function _getCellLevelId($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = _t(isset($this->_aLevels[$mixedValue]) ? $this->_aLevels[$mixedValue] : '_undefined');

    	return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sContent = '';
        $oForm = new BxTemplFormView([]);

        $aInputLevels = [
            'type' => 'select',
            'name' => 'level',
            'attrs' => [
                'id' => 'bx-grid-level-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->_oModule->_oConfig->getJsObject('administration') . '.onChangeLevel()'
            ],
            'value' => $this->_iLevelId,
            'values' => array(0 => _t('_bx_acl_txt_all_level'))
        ];

        foreach($this->_aLevels as $iId => $sTitle)
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

        if(!empty($this->_iLevelId))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString("AND `level_id`=? ", $this->_iLevelId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);;
    }

    protected function _isVisibleGrid ($a)
    {
        return isAdmin();
    }
}

/** @} */
