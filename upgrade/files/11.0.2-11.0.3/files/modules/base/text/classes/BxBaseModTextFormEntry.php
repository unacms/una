<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxBaseModTextFormEntry extends BxBaseModGeneralFormEntry
{
    protected $_sGhostTemplateVideo = 'form_ghost_template_video.html';
    protected $_sGhostTemplateSound = 'form_ghost_template_sound.html';
    protected $_sGhostTemplateFile = 'form_ghost_template_file.html';

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }

    function getCode($bDynamicMode = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sResult = parent::getCode($bDynamicMode);
        if(!empty($CNF['OBJECT_MENU_ENTRY_ATTACHMENTS']))
            $sResult = $this->_oModule->_oTemplate->parseHtmlByContent($sResult, array(
                'attachments_menu' => BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ENTRY_ATTACHMENTS'])->getCode()
            ));

        if(isset($CNF['PARAM_POLL_ENABLED']) && $CNF['PARAM_POLL_ENABLED'] === true) {
            $sInclude = '';
            $sInclude .= $this->_oModule->_oTemplate->addCss(array('polls.css'), $bDynamicMode);
            $sInclude .= $this->_oModule->_oTemplate->addJs(array('modules/base/text/js/|polls.js', 'polls.js'), $bDynamicMode);

            $sResult .= ($bDynamicMode ? $sInclude : '') . $this->_oModule->_oTemplate->getJsCode('poll');
        }   

        if(isset($CNF['PARAM_MULTICAT_ENABLED']) && $CNF['PARAM_MULTICAT_ENABLED'] === true) {
            $sInclude2 = '';
            $sInclude2 .= $this->_oModule->_oTemplate->addJs(array('categories.js'), $bDynamicMode);
            $sInclude2 .= $this->_oModule->_oTemplate->addCss(array('categories.css'), $bDynamicMode);

            $sResult .= ($bDynamicMode ? $sInclude2 : '') . $this->_oModule->_oTemplate->getJsCode('categories');
        }

    	return $sResult;
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bValues = $aValues && !empty($aValues['id']);
        $aContentInfo = $bValues ? $this->_oModule->_oDb->getContentInfoById($aValues['id']) : false;

        if (isset($CNF['FIELD_VIDEO']) && isset($this->aInputs[$CNF['FIELD_VIDEO']])) {
            if ($bValues)
                $this->aInputs[$CNF['FIELD_VIDEO']]['content_id'] = $aValues['id'];

            $this->aInputs[$CNF['FIELD_VIDEO']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplateVideo, $this->_getVideoGhostTmplVars($aContentInfo));
        }

        if (isset($CNF['FIELD_SOUND']) && isset($this->aInputs[$CNF['FIELD_SOUND']])) {
            if ($bValues)
                $this->aInputs[$CNF['FIELD_SOUND']]['content_id'] = $aValues['id'];

            $this->aInputs[$CNF['FIELD_SOUND']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplateSound, $this->_getSoundGhostTmplVars($aContentInfo));
        }

        if (isset($CNF['FIELD_FILE']) && isset($this->aInputs[$CNF['FIELD_FILE']])) {
            if ($bValues)
                $this->aInputs[$CNF['FIELD_FILE']]['content_id'] = $aValues['id'];

            $this->aInputs[$CNF['FIELD_FILE']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplateFile, $this->_getFileGhostTmplVars($aContentInfo));
        }

        if (isset($CNF['FIELD_POLL']) && isset($this->aInputs[$CNF['FIELD_POLL']])) {
            if ($bValues)
                $this->aInputs[$CNF['FIELD_POLL']]['content_id'] = $aValues['id'];
        }
        
        if (isset($CNF['FIELD_MULTICAT']) && isset($this->aInputs[$CNF['FIELD_MULTICAT']]) && $CNF['PARAM_MULTICAT_ENABLED']) {
            if ($bValues)
                $this->aInputs[$CNF['FIELD_MULTICAT']]['content_id'] = $aValues['id'];
        }

        parent::initChecker ($aValues, $aSpecificValues);
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        $bMulticatEnabled = $this->_isMulticatEnabled();
        
        if ($bMulticatEnabled)
            $this->processMulticatBefore($CNF['FIELD_MULTICAT'], $aValsToAdd);
        
        $iContentId = parent::insert($aValsToAdd, $isIgnore);
        
        if(!empty($iContentId)){
            if ($bMulticatEnabled)
                $this->processMulticatAfter($CNF['FIELD_MULTICAT'], $iContentId);
        }
        return $iContentId;
    }
    
    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        $bMulticatEnabled = $this->_isMulticatEnabled();
        
        if ($bMulticatEnabled)
            $this->processMulticatBefore($CNF['FIELD_MULTICAT'], $aValsToAdd);
        
        $iResult = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
        
        if ($bMulticatEnabled)
            $this->processMulticatAfter($CNF['FIELD_MULTICAT'], $iContentId);
        return $iResult;
    }
    
    protected function genCustomInputAttachments ($aInput)
    {
        return '__attachments_menu__';
    }

    protected function genCustomInputPolls ($aInput)
    {
        return $this->_oModule->_oTemplate->getPollField(!empty($aInput['content_id']) ? (int)$aInput['content_id'] : 0);
    }

    public function processPolls ($sFieldPoll, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!isset($this->aInputs[$sFieldPoll]))
            return true;

        $aPollIds = $this->getCleanValue($sFieldPoll);
        if(empty($aPollIds) || !is_array($aPollIds))
            return true;

        $iProfileId = $this->getContentOwnerProfileId($iContentId);

        $aPollsDbIds = $this->_oModule->_oDb->getPolls(array('type' => 'content_id_ids', 'content_id' => $iContentId));

        //--- Remove deleted
        $this->_oModule->_oDb->deletePollsByIds(array_diff($aPollsDbIds, $aPollIds));

        //--- Add new
        if($iContentId) {
            $aPollsAddIds = array_diff($aPollIds, $aPollsDbIds);
            foreach($aPollsAddIds as $iPollId)
                $this->_oModule->_oDb->updatePolls(array($CNF['FIELD_POLL_CONTENT_ID'] => $iContentId), array($CNF['FIELD_POLL_ID'] => $iPollId, $CNF['FIELD_POLL_CONTENT_ID'] => 0));
        }

        return true;
    }
    
    /**
     * 
     * MultiCategories related methods. 
     * 
     */
    
    protected function processMulticatBefore($sFieldName, &$aValsToAdd){
        if (isset($this->aInputs[$sFieldName])){
            $this->aInputs[$sFieldName]['value'] = array_unique(array_filter($this->aInputs[$sFieldName]['value'], function($sTmp){
               return trim($sTmp);
            }));  
            $aValsToAdd[$sFieldName] = implode(',', $this->aInputs[$sFieldName]['value']);
        }
    }
    
    protected function processMulticatAfter($sFieldName, $iContentId){
        $CNF = &$this->_oModule->_oConfig->CNF;
        $bAutoActivation = (isset($CNF['PARAM_MULTICAT_AUTO_ACTIVATION_FOR_CATEGORIES']) && getParam($CNF['PARAM_MULTICAT_AUTO_ACTIVATION_FOR_CATEGORIES']) == 'on') ? true : false;
		$oCategories = BxDolCategories::getInstance();
        if (isset($this->aInputs[$sFieldName])){
            $oCategories->delete($this->_oModule->getName(), $iContentId);
            foreach($this->aInputs[$sFieldName]['value'] as  $sValue) {
                $oCategories->add($this->_oModule->getName(), bx_get_logged_profile_id(), $sValue, $iContentId, $bAutoActivation);
            }
        }
    }
    
    protected function genCustomViewRowValueMulticat(&$aInput)
    {
		$oCategories = BxDolCategories::getInstance();
        $aValues = $oCategories->getData(array('type' => 'by_module_and_object', 'module' => $this->_oModule->getName(), 'object_id' => (!empty($aInput['content_id']) ? (int)$aInput['content_id'] : 0 )));
        if (count($aValues) > 0){
            $aVars = array('bx_repeat:cats' => array());
            foreach ($aValues as  $sValue) {
                $aVars['bx_repeat:cats'][] = array(
                    'url' => $oCategories->getUrl($this->_oModule->getName(), $sValue),
                    'name' => _t($sValue),
                    'bx_if:more' => array(
                        'condition' => $sValue === end($aValues) ? false : true,
                        'content' => array('1')
                    ),
                );
            }
            
            if (!$aVars['bx_repeat:cats'])
                return '';

            return $this->_oModule->_oTemplate->parseHtmlByName('category_list_inline.html', $aVars);
        }
        return '';
    }
    
    protected function genCustomInputMulticat(&$aInput)
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject('categories');
		$aValuesForSelect = BxDolCategories::getInstance()->getData(array('type' => 'by_module_and_author', 'module' => $this->_oModule->getName(), 'author' => bx_get_logged_profile_id()));
   
        $aSelectedItems = array();
        if (isset($aInput['value']) && is_array($aInput['value']))
		    $aInput['value'] = array_filter($aInput['value']);
        
        if(!empty($aInput['value'])) {
            if (!is_array($aInput['value']))
                $aValues = BxDolCategories::getInstance()->getData(array('type' => 'by_module_and_object', 'module' => $this->_oModule->getName(), 'object_id' => (!empty($aInput['content_id']) ? (int)$aInput['content_id'] : 0 )));
            else
                $aValues = $aInput['value'];
            
            $aValues = array_filter($aValues);
            foreach($aValues as $sValue) {
                if (!array_key_exists($sValue, $aValuesForSelect)){
                    $aValuesForSelect[$sValue] = array('key' => $sValue, 'value' => $sValue);
                }
            }
            foreach($aValues as $sValue) {
                $sInput = $this->genCustomInputMulticatSelect($aInput, $aValuesForSelect, $sValue);
                $aSelectedItems[] = array('js_object' => $sJsObject, 'select_cat' => $sInput);
            }
        }
        else{
            $aSelectedItems = array(
                array('js_object' => $sJsObject, 'select_cat' => $this->genCustomInputMulticatSelect($aInput, $aValuesForSelect))
            );
        }
        return $this->_oModule->_oTemplate->parseHtmlByName('form_categories.html', array(
            'bx_repeat:items' => $aSelectedItems,
            'js_object' => $sJsObject, 
            'select_cat' => $this->genCustomInputMulticatSelect($aInput, $aValuesForSelect, -1),
            'input_cat' => $this->genCustomInputMulticatInput($aInput),
            'js_object' => $sJsObject,
            'btn_add' => $this->genCustomInputMulticatButton($aInput),
            'btn_add_new' => $this->genCustomInputMulticatButtonNew($aInput)
        ));
    }
    
    protected function genCustomInputMulticatSelect($aInput, $aValues, $mixedValue = '')
    {
        foreach($aValues as $sKey => $aValue) {
            $aValues[$sKey]['value'] = _t($aValue['key']);
        }
        $aValues = array_merge(array('1' => array('key' => false, 'value' =>  _t('_sys_please_select'))), $aValues);
        
        $aInput['type'] = 'select';
        $aInput['name'] .= '[]';
        $aInput['value'] = $mixedValue;
        $aInput['values'] = $aValues;
        return $this->genInput($aInput);
    }
    
    protected function genCustomInputMulticatInput($aInput)
    {
        $aInput['type'] = 'text';
        $aInput['name'] .= '[]';
        $aInput['value'] = '';
        return $this->genInput($aInput);
    }
    
    protected function genCustomInputMulticatButton($aInput)
    {
        $sName = $aInput['name'];
        $aInput['type'] = 'button';
        $aInput['name'] .= '_add';
        $aInput['value'] = _t('_sys_txt_categories_button_caption_add');
        $aInput['attrs']['class'] = 'bx-def-margin-right bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('categories') . ".categoryAdd(this, '" . $sName . "');";
        return $this->genInputButton($aInput);
    }
    
    protected function genCustomInputMulticatButtonNew($aInput)
    {
        $sName = $aInput['name'];
        $aInput['type'] = 'button';
        $aInput['name'] .= '_add';
        $aInput['value'] = _t('_sys_txt_categories_button_caption_addnew');
        $aInput['attrs']['class'] = 'bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('categories') . ".categoryAddNew(this, '" . $sName . "');";
        return $this->genInputButton($aInput);
    }

    protected function _getVideoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
            'name' => $this->aInputs[$CNF['FIELD_VIDEO']]['name'],
            'content_id' => (int)$this->aInputs[$CNF['FIELD_VIDEO']]['content_id'],
            'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : '',
            'embed_url' => BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'file_embed_video/',
    	);
    }

    protected function _getSoundGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
            'name' => $this->aInputs[$CNF['FIELD_SOUND']]['name'],
            'content_id' => (int)$this->aInputs[$CNF['FIELD_SOUND']]['content_id'],
            'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : '',
            'embed_url' => BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'file_embed_sound/',
    	);
    }

    protected function _getFileGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
            'name' => $this->aInputs[$CNF['FIELD_FILE']]['name'],
            'content_id' => (int)$this->aInputs[$CNF['FIELD_FILE']]['content_id'],
            'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : ''
    	);
    }
    
    protected function _isMulticatEnabled(){
        $CNF = $this->_oModule->_oConfig->CNF;
        return isset($CNF['PARAM_MULTICAT_ENABLED']) && $CNF['PARAM_MULTICAT_ENABLED'] === true && isset($CNF['FIELD_MULTICAT']);
    }

}

/** @} */
