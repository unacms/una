<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxBaseModGroupsGridPrices.php');

class BxBaseModGroupsGridPricesManage extends BxBaseModGroupsGridPrices
{
    protected $_iRoleId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_iRoleId = 0;
        if(($iRoleId = (int)bx_get('role_id')) !== false)
            $this->setRoleId($iRoleId);
    }

    public function setRoleId($iRoleId)
    {
        $this->_iRoleId = (int)$iRoleId;
        $this->_aQueryAppend['role_id'] = $this->_iRoleId;
    }

    public function getCode($isDisplayHeader = true)
    {
        $sResult = parent::getCode($isDisplayHeader);
        if(empty($sResult))
            return $sResult;

        $sJsCode = '';
        if($isDisplayHeader) {
            $this->_oModule->_oTemplate->addJs([
                'modules/base/groups/js/|prices.js',
                'prices.js'
            ]);

            $sJsCode = $this->_oModule->_oTemplate->getJsCode('prices', array(
                'sObjNameGrid' => $this->_sObject, 
                'aHtmlIds' => $this->_oModule->_oConfig->getHtmlIds()
            ));
        }

        return $sJsCode . $sResult;
    }

    public function performActionAdd()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$sAction = 'add';

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aGroupContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $sFilter = bx_get('filter');
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_iRoleId, $sFilter) = explode($this->_sParamsDivider, $sFilter);

        if(!is_numeric($this->_iRoleId) && ($iRoleId = bx_get('role_id')) !== false)
            $this->_iRoleId = (int)$iRoleId;

        $sForm = $CNF['OBJECT_FORM_PRICE_DISPLAY_ADD'];
    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_ADD']);
    	$oForm->setId($sForm);
        $oForm->setName($sForm);
        $oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', array('o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iGroupProfileId, 'role_id' => $this->_iRoleId)));
        $oForm->setRoleId($this->_iRoleId);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iPeriod = $oForm->getCleanValue('period');
            $sPeriodUnit = $oForm->getCleanValue('period_unit');

            if(!empty($iPeriod) && empty($sPeriodUnit)) 
                return echoJson(array('msg' => _t($CNF['T']['err_period_unit'])));

            $aPrice = $this->_oModule->_oDb->getPrices(array('type' => 'by_prpp', 'profile_id' => $this->_iGroupProfileId, 'role_id' => $this->_iRoleId, 'period' => $iPeriod, 'period_unit' => $sPeriodUnit));
            if(!empty($aPrice) && is_array($aPrice))
                return echoJson(array('msg' => _t($CNF['T']['err_price_duplicate'])));

            $iId = (int)$oForm->insert(array('profile_id' => $this->_iGroupProfileId, 'order' => $this->_oModule->_oDb->getPriceOrderMax($this->_iRoleId) + 1));
            if($iId != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t($CNF['T']['err_cannot_perform']));

            echoJson($aRes);
            return;
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t($CNF['T']['popup_title_price_add']), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', array(
            'form_id' => $oForm->getId(),
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

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aGroupContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $aIds = $this->_getIds();
        if($aIds === false)
            return echoJson(array());

        $aItem = $this->_oModule->_oDb->getPrices(array('type' => 'by_id', 'value' => array_shift($aIds)));
        if(!is_array($aItem) || empty($aItem))
            return echoJson(array());

        $sForm = $CNF['OBJECT_FORM_PRICE_DISPLAY_EDIT'];
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_EDIT']);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
    	$oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iGroupProfileId, 'role_id' => $this->_iRoleId]));

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aItem['id']);
            else
                $aRes = array('msg' => _t($CNF['T']['err_cannot_perform']));

            return echoJson($aRes);
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t($CNF['T']['popup_title_price_edit']), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', array(
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        $aRes = array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false)));

        return echoJson($aRes);
    }

    public function performActionDelete()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds))
            return echoJson(array());

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
            if($this->_oModule->_oDb->deletePrices(array('id' => $iId))) {
                $aIdsAffected[] = $iId;
                $iAffected++;
            }

        return echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['err_cannot_perform'])));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oModule->_oTemplate->addJs([
            'jquery.form.min.js', 
            'modules/base/groups/js/|prices.js', 
            'prices.js'
        ]);

        $this->_oModule->_oTemplate->addCss([
            'prices.css'
        ]);
    }

    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sContent = '';
        $oForm = new BxTemplFormView(array());

        $aInputRoles = array(
            'type' => 'select',
            'name' => 'role',
            'attrs' => array(
                'id' => 'bx-grid-level-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->_oModule->_oConfig->getJsObject('prices') . '.onChangeRole()'
            ),
            'value' => $this->_iRoleId,
            'values' => $this->_aRoles
        );
        $sContent .=  $oForm->genRow($aInputRoles);

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
            ),
        );
        $sContent .= $oForm->genRow($aInputSearch);

        return $sContent;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false) {
            list($iRoleId, $sFilter) = explode($this->_sParamsDivider, $sFilter);
            if(!is_numeric($iRoleId))
                $iRoleId = 0;

            $this->setRoleId($iRoleId);
        }

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString("AND `profile_id`=? AND `role_id`=? ", $this->_iGroupProfileId, $this->_iRoleId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);;
    }

    protected function _isVisibleGrid ($a)
    {
        return $this->_oModule->checkAllowedManageAdmins($this->_aGroupContentInfo) == CHECK_ACTION_RESULT_ALLOWED;
    }
}

/** @} */
