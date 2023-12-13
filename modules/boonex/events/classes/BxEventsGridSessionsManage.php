<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEventsGridSessionsManage extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sParamsDivider = '#-#';

    protected $_iEventProfileId;
    protected $_iEventContentId;
    protected $_aEventContentInfo;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_events';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);

        $this->_iEventProfileId = 0;
        $this->_iEventContentId = 0;
        $this->_aEventContentInfo = [];
        if(($iEventProfileId = bx_get('profile_id')) !== false)
            $this->setProfileId($iEventProfileId);
    }

    public function setProfileId($iProfileId)
    {
        $this->_iEventProfileId = (int)$iProfileId;
        $this->_aQueryAppend['profile_id'] = $this->_iEventProfileId;

        if(($oEventProfile = BxDolProfile::getInstance($this->_iEventProfileId)) !== false) {
            $this->_iEventContentId = (int)$oEventProfile->getContentId();
            $this->_aEventContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iEventContentId);
        }
    }

    public function performActionAdd()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$sAction = 'add';

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aEventContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $sForm = $CNF['OBJECT_FORM_SESSION_DISPLAY_ADD'];
    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_SESSION'], $CNF['OBJECT_FORM_SESSION_DISPLAY_ADD']);
    	$oForm->setId($sForm);
        $oForm->setName($sForm);
        $oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iEventProfileId]));

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iId = (int)$oForm->insert(['event_id' => $this->_iEventContentId, 'added' => time(), 'order' => $this->_oModule->_oDb->getSessionOrderMax($this->_iEventContentId) + 1]);
            if($iId != 0)
                $aResult = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aResult = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return echoJson($aResult);
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_session'), _t('_bx_events_popup_title_sn_add'), $this->_oModule->_oTemplate->parseHtmlByName('popup_session.html', [
            'form_id' => $oForm->getId(),
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

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aEventContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $aIds = $this->_getIds();
        if($aIds === false)
            return echoJson([]);

        $iItem = array_shift($aIds);
        $aItem = $this->_oModule->_oDb->getSessions(['sample' => 'id', 'id' => $iItem]);
        if(!is_array($aItem) || empty($aItem))
            return echoJson([]);

        $sForm = $CNF['OBJECT_FORM_SESSION_DISPLAY_EDIT'];
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_SESSION'], $CNF['OBJECT_FORM_SESSION_DISPLAY_EDIT']);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
    	$oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iEventProfileId, 'id' => $iItem]));

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aResult = ['grid' => $this->getCode(false), 'blink' => $aItem['id']];
            else
                $aResult = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return echoJson($aResult);
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_session'), _t('_bx_events_popup_title_sn_edit'), $this->_oModule->_oTemplate->parseHtmlByName('popup_session.html', [
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getCellDateStart($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellDate($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellDateEnd($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellDate($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        if(bx_is_api()){
            return ['type' => 'datetime', 'data'=> $mixedValue];    
        }
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE_TIME, true), $sKey, $aField, $aRow);
    }
    
    protected function _getCellData($sKey, $aField, $aRow)
    {
        if($sKey == 'description')
            $aRow[$sKey] = strip_tags($aRow[$sKey]);

        return parent::_getCellData($sKey, $aField, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString("AND `event_id`=? ", $this->_iEventContentId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _isVisibleGrid ($a)
    {
        return $this->_oModule->checkAllowedEdit($this->_aEventContentInfo) == CHECK_ACTION_RESULT_ALLOWED;
    }

    protected function _getIds()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) 
                return false;

            $aIds = [$iId];
        }

        return $aIds;
    }
}

/** @} */
