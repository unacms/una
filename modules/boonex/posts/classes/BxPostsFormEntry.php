<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxPostsFormEntry extends BxBaseModTextFormEntry
{
    protected $_sGhostTemplateCover = 'form_ghost_template_cover.html';
	
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_posts';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

    	if(isset($CNF['FIELD_COVER']) && isset($this->aInputs[$CNF['FIELD_COVER']])) {
            if($this->_oModule->checkAllowedSetThumb() === CHECK_ACTION_RESULT_ALLOWED) {
                $this->aInputs[$CNF['FIELD_COVER']]['storage_object'] = $CNF['OBJECT_STORAGE'];
                $this->aInputs[$CNF['FIELD_COVER']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_COVER']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_COVER']]['value']) : $CNF['OBJECT_UPLOADERS'];
                $this->aInputs[$CNF['FIELD_COVER']]['upload_buttons_titles'] = array(
                    'Simple' => _t('_bx_posts_form_entry_input_covers_uploader_simple_title'), 
                    'HTML5' => _t('_bx_posts_form_entry_input_covers_uploader_html5_title')
                );
                $this->aInputs[$CNF['FIELD_COVER']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW'];
                $this->aInputs[$CNF['FIELD_COVER']]['storage_private'] = 0;
                $this->aInputs[$CNF['FIELD_COVER']]['multiple'] = false;
                $this->aInputs[$CNF['FIELD_COVER']]['content_id'] = 0;
                $this->aInputs[$CNF['FIELD_COVER']]['ghost_template'] = '';
            }
            else
                unset($this->aInputs[$CNF['FIELD_COVER']]);
        }

        if(isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_object'] = $CNF['OBJECT_STORAGE_PHOTOS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_PHOTO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_PHOTO']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = '';
            $this->aInputs[$CNF['FIELD_PHOTO']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }

        if(isset($this->aInputs[$CNF['FIELD_VIDEO']])) {
            $this->aInputs[$CNF['FIELD_VIDEO']]['storage_object'] = $CNF['OBJECT_STORAGE_VIDEOS'];
            $this->aInputs[$CNF['FIELD_VIDEO']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_VIDEO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_VIDEO']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_VIDEO']]['images_transcoder'] = $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster_preview'];
            $this->aInputs[$CNF['FIELD_VIDEO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_VIDEO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEO']]['ghost_template'] = '';
            $this->aInputs[$CNF['FIELD_VIDEO']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }

        if (isset($CNF['FIELD_FILE']) && isset($this->aInputs[$CNF['FIELD_FILE']])) {
            $this->aInputs[$CNF['FIELD_FILE']]['storage_object'] = $CNF['OBJECT_STORAGE_FILES'];
            $this->aInputs[$CNF['FIELD_FILE']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_FILE']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_FILE']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_FILE']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_FILES'];
            $this->aInputs[$CNF['FIELD_FILE']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_FILE']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['ghost_template'] = '';
            $this->aInputs[$CNF['FIELD_FILE']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }

        if(isset($this->aInputs[$CNF['FIELD_POLL']])) {
            $this->aInputs[$CNF['FIELD_POLL']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }
    }

    public function getCode($bDynamicMode = false)
    {
        $sJs = $this->_oModule->_oTemplate->addJs(array('categories.js'), $bDynamicMode);
        $sJs = $this->_oModule->_oTemplate->addCss(array('categories.css'));
        $sCode = '';
        if($bDynamicMode)
        	$sCode .= $sJs;

		$sCode .= $this->_oModule->_oTemplate->getJsCode('categories');
		$sCode .= parent::getCode($bDynamicMode);

        return $sCode;
    }
    
    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bValues = $aValues && !empty($aValues['id']);
        $aContentInfo = $bValues ? $this->_oModule->_oDb->getContentInfoById($aValues['id']) : false;

        if($this->aParams['display'] == $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'] && isset($CNF['FIELD_PUBLISHED']) && isset($this->aInputs[$CNF['FIELD_PUBLISHED']]))
            if($bValues && in_array($aValues[$CNF['FIELD_STATUS']], array('active', 'hidden')))
                unset($this->aInputs[$CNF['FIELD_PUBLISHED']]);

        if (isset($CNF['FIELD_COVER']) && isset($this->aInputs[$CNF['FIELD_COVER']])) {
            if($bValues)
                $this->aInputs[$CNF['FIELD_COVER']]['content_id'] = $aValues['id'];

            $this->aInputs[$CNF['FIELD_COVER']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplateCover, $this->_getCoverGhostTmplVars($aContentInfo));
        }

        parent::initChecker ($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($CNF['FIELD_ADDED']) && empty($aValsToAdd[$CNF['FIELD_ADDED']])) {
            $iAdded = 0;
            if(isset($this->aInputs[$CNF['FIELD_ADDED']]))
                $iAdded = $this->getCleanValue($CNF['FIELD_ADDED']);
            
            if(empty($iAdded))
                 $iAdded = time();

            $aValsToAdd[$CNF['FIELD_ADDED']] = $iAdded;
        }

        if(empty($aValsToAdd[$CNF['FIELD_PUBLISHED']])) {
            $iPublished = 0;
            if(isset($this->aInputs[$CNF['FIELD_PUBLISHED']]))
                $iPublished = $this->getCleanValue($CNF['FIELD_PUBLISHED']);
                
             if(empty($iPublished))
                 $iPublished = time();

             $aValsToAdd[$CNF['FIELD_PUBLISHED']] = $iPublished;
        }

        $aValsToAdd[$CNF['FIELD_STATUS']] = $aValsToAdd[$CNF['FIELD_PUBLISHED']] > $aValsToAdd[$CNF['FIELD_ADDED']] ? 'awaiting' : 'active';

        $this->processMulticatBefore($CNF['FIELD_MULTICAT'], $aValsToAdd);
        $iContentId = parent::insert ($aValsToAdd, $isIgnore);
        if(!empty($iContentId)){
            $this->processFiles($CNF['FIELD_COVER'], $iContentId, true);
            $this->processMulticatAfter($CNF['FIELD_MULTICAT'], $iContentId);
        }

        return $iContentId;
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($aValsToAdd[$CNF['FIELD_PUBLISHED']]) && isset($this->aInputs[$CNF['FIELD_PUBLISHED']])) {
            $iPublished = $this->getCleanValue($CNF['FIELD_PUBLISHED']);
            if(empty($iPublished))
                $iPublished = time();

            $aValsToAdd[$CNF['FIELD_PUBLISHED']] = $iPublished;
        }
        
        $this->processMulticatBefore($CNF['FIELD_MULTICAT'], $aValsToAdd);
        $iResult = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        $this->processFiles($CNF['FIELD_COVER'], $iContentId, false);
        $this->processMulticatAfter($CNF['FIELD_MULTICAT'], $iContentId);

        return $iResult;
    }

    protected function _getCoverGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
            'name' => $this->aInputs[$CNF['FIELD_COVER']]['name'],
            'content_id' => $this->aInputs[$CNF['FIELD_COVER']]['content_id'],
            'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : '',
            'thumb_id' => isset($CNF['FIELD_THUMB']) && isset($aContentInfo[$CNF['FIELD_THUMB']]) ? $aContentInfo[$CNF['FIELD_THUMB']] : 0,
            'name_thumb' => isset($CNF['FIELD_THUMB']) ? $CNF['FIELD_THUMB'] : ''
        );
    }

    protected function _getPhotoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
            'name' => $this->aInputs[$CNF['FIELD_PHOTO']]['name'],
            'content_id' => (int)$this->aInputs[$CNF['FIELD_PHOTO']]['content_id'],
            'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : ''
    	);
    }
    
    protected function processMulticatBefore($sFieldName, &$aValsToAdd){
        if (isset($this->aInputs[$sFieldName])){
            $this->aInputs[$sFieldName]['value'] = array_unique(array_filter($this->aInputs[$sFieldName]['value'], function($sTmp){
               return trim($sTmp);
            }));  
            $aValsToAdd[$sFieldName] = implode(',', $this->aInputs[$sFieldName]['value']);
        }
    }
    
    protected function processMulticatAfter($sFieldName, $iContentId){
        if (isset($this->aInputs[$sFieldName])){
            foreach($this->aInputs[$sFieldName]['value'] as  $sValue) {
                $this->_oModule->_oDb->addCategory($this->_oModule->getName(), bx_get_logged_profile_id(), $sValue);
            }
        }
    }
    
    protected function genCustomViewRowValueMulticat(&$aInput)
    {
        $aCats = explode(',', $aInput['value']);
        if (count($aCats) > 0){
            $aVars = array('bx_repeat:cats' => array());
            foreach ($aCats as $sKey => $sValue) {
                $aVars['bx_repeat:cats'][] = array(
                    'url' => $this->_oModule->getCategoriesMultiUrl($sValue),
                    'name' => $sValue,
                    'bx_if:more' => array(
                        'condition' => $sValue === end($aCats) ? false : true,
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
        $aValuesList = $this->_oModule->_oDb->getCategories($this->_oModule->getName(), bx_get_logged_profile_id());
        if(!empty($aInput['value'])) {
            if (!is_array($aInput['value']))
                $aValues = explode(',', $aInput['value']);
            else
                $aValues = $aInput['value'];
            $aTmplVarsSubentries = array();
            foreach($aValues as $sValue) {
                if (!array_key_exists($sValue, $aValuesList)){
                    $aValuesList[$sValue] = array('key' => $sValue, 'value' => $sValue);
                }
            }
            foreach($aValues as $sValue) {
                $sInput = $this->genCustomInputMulticatSelect($aInput, $aValuesList, $sValue);
                $aTmplVarsSubentries[] = array('js_object' => $sJsObject, 'select_cat' => $sInput);
            }
        }
        else{
            $aTmplVarsSubentries = array(
                array('js_object' => $sJsObject, 'select_cat' => $this->genCustomInputMulticatSelect($aInput, $aValuesList))
            );
        } 
        return $this->_oModule->_oTemplate->parseHtmlByName('form_categories.html', array(
            'bx_repeat:items' => $aTmplVarsSubentries,
            'input_cat' => $this->genCustomInputMulticatInput($aInput),
            'js_object' => $sJsObject,
            'btn_add' => $this->genCustomInputMulticatButton($aInput),
            'btn_add_new' => $this->genCustomInputMulticatButtonNew($aInput)
        ));
    }
    
    protected function genCustomInputMulticatSelect($aInput, $aValues, $mixedValue = '')
    {
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
        $aInput['value'] = _t('_bx_posts_form_entry_input_category_add');
        $aInput['attrs']['class'] = 'bx-def-margin-right bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('categories') . ".categoryAdd(this, '" . $sName . "');";
        return $this->genInputButton($aInput);
    }
    
    protected function genCustomInputMulticatButtonNew($aInput)
    {
        $sName = $aInput['name'];
        $aInput['type'] = 'button';
        $aInput['name'] .= '_add';
        $aInput['value'] = _t('_bx_posts_form_entry_input_category_add_new');
        $aInput['attrs']['class'] = 'bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('categories') . ".categoryAddNew(this, '" . $sName . "');";
        return $this->genInputButton($aInput);
    }
}

/** @} */
