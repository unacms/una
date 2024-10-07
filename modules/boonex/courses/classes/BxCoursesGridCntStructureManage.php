<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxCoursesGridCntStructureManage extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sPageUrl;
    protected $_iEntryId;
    protected $_iEntryPid;

    protected $_iParentId;
    protected $_aParentInfo;

    protected $_iLevel;
    protected $_iLevelMax;
    protected $_aLevelToNode;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_courses';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_iEntryId = 0;
        if(($iEntryId = bx_get('entry_id')) !== false)
            $this->setEntryId($iEntryId);

        $this->setParentId(($iParentId = bx_get('parent_id')) !== false ? $iParentId : 0);            

        $this->_iLevel = $this->_getNodeLevel();
        $this->_iLevelMax = $this->_oModule->_oConfig->isContentLevelMax();

        $this->_aLevelToNode = [
            1 => _t('_bx_courses_txt_module'),
            2 => _t('_bx_courses_txt_theme'),
            3 => _t('_bx_courses_txt_lesson')
        ];
    }

    public function setEntryId($iEntryId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_iEntryId = (int)$iEntryId;
        $this->_iEntryPid = ($oProfile = BxDolProfile::getInstanceByContentAndType($this->_iEntryId, $this->_sModule)) !== false ? $oProfile->id() : 0;

        $this->_sPageUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_MANAGE_STRUCTURE'], ['profile_id' => $this->_iEntryPid]);

        $this->_aQueryAppend['entry_id'] = $this->_iEntryId;
    }

    public function setParentId($iParentId)
    {
        $this->_iParentId = (int)$iParentId;

        $this->_aParentInfo = [];
        if($this->_iParentId)
            $this->_aParentInfo = $this->_oModule->_oDb->getContentNodes(['sample' => 'id_full', 'id' => $this->_iParentId]);

        $this->_aQueryAppend['parent_id'] = $this->_iParentId;
    }

    public function getCode ($isDisplayHeader = true)
    {
        if($this->_iLevel > $this->_iLevelMax)
            return '';

        return parent::getCode($isDisplayHeader);
    }

    public function performActionAdd()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$sAction = 'add';

        $aEntryInfo = $this->_oModule->_oDb->getContentInfoById($this->_iEntryId);
        if(($mixedResult = $this->_oModule->checkAllowedEdit($aEntryInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $sForm = $CNF['OBJECT_FORM_CNT_NODE_DISPLAY_ADD'];
    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_CNT_NODE'], $CNF['OBJECT_FORM_CNT_NODE_DISPLAY_ADD']);
    	$oForm->setId($sForm);
        $oForm->setName($sForm);
        $oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'entry_id' => $this->_iEntryId, 'parent_id' => $this->_iParentId]));

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {

            $iId = (int)$oForm->insert(['entry_id' => $this->_iEntryId, 'added' => time()]);
            if($iId != 0) {
                $this->_oModule->_oDb->insertContentStructureNode([
                    'entry_id' => $this->_iEntryId,
                    'parent_id' => $this->_iParentId,
                    'node_id' => $iId,
                    'level' => $this->_iLevel,
                    'order' => $this->_oModule->_oDb->getContentStructureOrderMax($this->_iEntryId, $this->_iParentId) + 1
                ]);

                if($this->_iLevel > 1) 
                    $this->_oModule->_oDb->updateContentStructureCounters($this->_iParentId, $this->_iLevel, 1);

                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            }
            else
                $aRes = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return echoJson($aRes);
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_content_node'), $this->_parseNodeName(_t($CNF['T']['popup_title_content_node_add'])), $this->_oModule->_oTemplate->parseHtmlByName('popup_content_node.html', [
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

        $aEntryInfo = $this->_oModule->_oDb->getContentInfoById($this->_iEntryId);
        if(($mixedResult = $this->_oModule->checkAllowedEdit($aEntryInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedResult]);

        $aIds = $this->_getIds();
        if($aIds === false)
            return echoJson([]);

        $aNode = $this->_oModule->_oDb->getContentNodes(['sample' => 'id', 'id' => array_shift($aIds)]);
        if(!is_array($aNode) || empty($aNode))
            return echoJson([]);

        $sForm = $CNF['OBJECT_FORM_CNT_NODE_DISPLAY_EDIT'];
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_CNT_NODE'], $CNF['OBJECT_FORM_CNT_NODE_DISPLAY_EDIT']);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
    	$oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'entry_id' => $this->_iEntryId, 'parent_id' => $this->_iParentId, 'id' => $aNode['id']]));

        $oForm->initChecker($aNode);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aNode['id']) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $aNode['id']];
            else
                $aRes = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return echoJson($aRes);
        }

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_content_node'), $this->_parseNodeName(_t($CNF['T']['popup_title_content_node_edit'])), $this->_oModule->_oTemplate->parseHtmlByName('popup_content_node.html', [
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getCellHeaderCnL2($sKey, $aField)
    {
        if($this->_iLevel >= 2)
            return '';

        return parent::_getCellHeaderDefault($sKey, $aField);
    }
    
    protected function _getCellHeaderCnL3($sKey, $aField)
    {
        if($this->_iLevel >= 3)
            return '';

        return parent::_getCellHeaderDefault($sKey, $aField);
    }
    
    protected function _getCellHeaderCounters($sKey, $aField)
    {
        if($this->_iLevel != $this->_iLevelMax)
            return '';

        return parent::_getCellHeaderDefault($sKey, $aField);
    }

    protected function _getCellTitle($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oModule->_oTemplate->parseHtmlByName('name_link.html', [
            'href' => bx_append_url_params($this->_sPageUrl, ['parent_id' => $aRow['id']]),
            'title' => bx_html_attribute($mixedValue),
            'content' => $mixedValue
        ]);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellCnL2($mixedValue, $sKey, $aField, $aRow)
    {
        if($this->_iLevel >= 2)
            return '';

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellCnL3($mixedValue, $sKey, $aField, $aRow)
    {
        if($this->_iLevel >= 3)
            return '';

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellCounters($mixedValue, $sKey, $aField, $aRow)
    {
        if($this->_iLevel != $this->_iLevelMax)
            return '';

        $sCounters = '';
        if(!empty($mixedValue) && ($aCounters = json_decode(html_entity_decode($mixedValue), true)))
            $sCounters = $this->_oModule->_oTemplate->getCounters($aCounters);

        return parent::_getCellDefault($sCounters, $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        if($this->_bIsApi)
            return ['type' => 'time', 'data' => $mixedValue];

        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionBack($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
        if(empty($this->_aParentInfo))
            return '';

        $sUrl = $this->_sPageUrl;
        if(!empty($this->_aParentInfo['parent_id']))
            $sUrl = bx_append_url_params($sUrl, ['parent_id' => $this->_aParentInfo['parent_id']]);

        $a['attr'] = array_merge($a['attr'], [
            "onclick" => "window.open('" . $sUrl . "','_self');"
    	]);

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionAdd($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
        if($this->_iLevel > $this->_iLevelMax)
            return '';

        $a['title'] = $this->_parseNodeName($a['title']);
    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _delete($mixedId)
    {
        $this->_oModule->_oDb->deleteContentStructureNode(['node_id' => (int)$mixedId]);

        return parent::_delete($mixedId);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(!$this->_iEntryId)
            return [];

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tcn`.`entry_id`=? AND `tcs`.`parent_id`=?", $this->_iEntryId, $this->_iParentId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'active' : 'hidden';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'active' == $mixedState ? true : false;
    }

    protected function _getNodeLevel()
    {
        return (is_array($this->_aParentInfo) && !empty($this->_aParentInfo['level']) ? (int)$this->_aParentInfo['level'] : 0) + 1;
    }
    
    protected function _parseNodeName($s)
    {
        return bx_replace_markers($s, [
            'node' => isset($this->_aLevelToNode[$this->_iLevel]) ? $this->_aLevelToNode[$this->_iLevel]: _t('_undefined')
        ]);
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
