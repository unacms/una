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

class BxBaseModGroupsGridQuestionsManage extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sParamsDivider = '#-#';

    protected $_iGroupProfileId;
    protected $_iGroupContentId;
    protected $_aGroupContentInfo;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);

        $this->_iGroupProfileId = 0;
        $this->_iGroupContentId = 0;
        $this->_aGroupContentInfo = [];
        if(($iGroupProfileId = bx_get('profile_id')) !== false)
            $this->setProfileId($iGroupProfileId);
    }

    public function setProfileId($iProfileId)
    {
        $this->_iGroupProfileId = (int)$iProfileId;
        $this->_aQueryAppend['profile_id'] = $this->_iGroupProfileId;

        if(($oGroupProfile = BxDolProfile::getInstance($this->_iGroupProfileId)) !== false) {
            $this->_iGroupContentId = (int)$oGroupProfile->getContentId();
            $this->_aGroupContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iGroupContentId);
        }
    }

    public function performActionAdd()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$sAction = 'add';

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aGroupContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $sForm = $CNF['OBJECT_FORM_QUESTION_DISPLAY_ADD'];
    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_QUESTION'], $CNF['OBJECT_FORM_QUESTION_DISPLAY_ADD']);
    	$oForm->setId($sForm);
        $oForm->setName($sForm);
        $oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iGroupProfileId]));

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iId = (int)$oForm->insert(['content_id' => $this->_iGroupContentId, 'added' => time(), 'order' => $this->_oModule->_oDb->getQuestionOrderMax($this->_iGroupContentId) + 1]);
            if($iId != 0)
                $aResult = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aResult = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return echoJson($aResult);
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_question'), _t($CNF['T']['popup_title_question_add']), $this->_oModule->_oTemplate->parseHtmlByName('popup_qnr_question.html', [
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

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aGroupContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $aIds = $this->_getIds();
        if($aIds === false)
            return echoJson([]);

        $iItem = array_shift($aIds);
        $aItem = $this->_oModule->_oDb->getQuestions(['sample' => 'id', 'id' => $iItem]);
        if(!is_array($aItem) || empty($aItem))
            return echoJson([]);

        $sForm = $CNF['OBJECT_FORM_QUESTION_DISPLAY_EDIT'];
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_QUESTION'], $CNF['OBJECT_FORM_QUESTION_DISPLAY_EDIT']);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
    	$oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iGroupProfileId, 'id' => $iItem]));

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aResult = ['grid' => $this->getCode(false), 'blink' => $aItem['id']];
            else
                $aResult = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return echoJson($aResult);
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_question'), _t($CNF['T']['popup_title_question_edit']), $this->_oModule->_oTemplate->parseHtmlByName('popup_qnr_question.html', [
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString("AND `content_id`=? AND `action`='add' ", $this->_iGroupContentId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _isVisibleGrid ($a)
    {
        return $this->_oModule->checkAllowedEdit($this->_aGroupContentInfo) == CHECK_ACTION_RESULT_ALLOWED;
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
