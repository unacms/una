<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/edit entry form
 */
class BxBaseModGeneralFormEntry extends BxTemplFormView
{
    protected static $_isCssJsGeneralModuleAdded = false;
        
    protected $MODULE;

    protected $_oModule;

    protected $_aMetatagsFieldsWithKeywords = array();
    protected $_oMetatagsObject = null;
    protected $_oMetatagsContentId = 0;

    protected $_sGhostTemplate = 'form_ghost_template.html';
    
    protected $_aTrackFieldsChanges;
    
    protected $_iContentId;
    
    protected $_bAllowChangeUserForAdmins;

    public function __construct($aInfo, $oTemplate = false)
    {
        if (!isset($this->_bAllowChangeUserForAdmins))
            $this->_bAllowChangeUserForAdmins = true;
        
        parent::__construct($aInfo, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $this->_aTrackFieldsChanges = array();

        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if (isset($CNF['FIELD_ADDED']) && isset($this->aInputs[$CNF['FIELD_ADDED']])) {
            $this->aInputs[$CNF['FIELD_ADDED']]['date_filter'] = BX_DATA_INT;
            $this->aInputs[$CNF['FIELD_ADDED']]['date_format'] = BX_FORMAT_DATE;
        }

        if (isset($CNF['FIELD_CHANGED']) && isset($this->aInputs[$CNF['FIELD_CHANGED']])) {
            $this->aInputs[$CNF['FIELD_CHANGED']]['date_filter'] = BX_DATA_INT;
            $this->aInputs[$CNF['FIELD_CHANGED']]['date_format'] = BX_FORMAT_DATE;
        }

        if (isset($CNF['FIELD_TEXT']) && isset($this->aInputs[$CNF['FIELD_TEXT']]) && isset($CNF['FIELD_TEXT_ID'])) {
            $this->aInputs[$CNF['FIELD_TEXT']]['attrs'] = array_merge (
                array ('id' => $CNF['FIELD_TEXT_ID']),
                is_array($this->aInputs[$CNF['FIELD_TEXT']]['attrs']) ? $this->aInputs[$CNF['FIELD_TEXT']]['attrs'] : array ()
            );
        }

        if (isset($CNF['FIELD_LABELS']) && isset($this->aInputs[$CNF['FIELD_LABELS']]) && !empty($CNF['OBJECT_METATAGS']) && ($oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])) && $oMetatags->keywordsIsEnabled()) {
            $this->aInputs[$CNF['FIELD_LABELS']]['meta_object'] = $CNF['OBJECT_METATAGS'];
        }

        if (isset($CNF['FIELD_LOCATION_PREFIX']) && isset($this->aInputs[$CNF['FIELD_LOCATION_PREFIX']])) {
            $this->aInputs[$CNF['FIELD_LOCATION_PREFIX']]['manual_input'] = true;
        }

        // add ability to change author by admins in some apps
        if (!empty($CNF['FIELD_AUTHOR']) && ($this->_oModule->_isModerator() || $this->_oModule->_isAdministrator()) && $this->_isChangeUserForAdmins($this->aParams['display']))
            $this->aInputs = array_merge([
                $CNF['FIELD_AUTHOR'] => [
                    'type' => 'custom',
                    'name' => $CNF['FIELD_AUTHOR'],
                    'db' => ['pass' => 'Xss'],
                    'caption' => _t('_sys_form_input_caption_author')
                ],
            ], $this->aInputs);

        if (isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_object'] = $CNF['OBJECT_STORAGE'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_PHOTO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_PHOTO']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = '';
        }

        $aPrivacyFields = $this->_getPrivacyFields();
        foreach($aPrivacyFields as $sField => $sObject)
            $this->_preparePrivacyField($sField, $sObject);
        
        foreach($this->aInputs as $sKey => $aInput) {
            if ($aInput['type'] == 'nested_form'){
                $this->genGhostTemplateForInputNestedForm ($this->aInputs[$sKey]);
            }
        }
    }

    protected function _isChangeUserForAdmins($sDisplay)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$this->_bAllowChangeUserForAdmins)
            return false;

        if(isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']) && $sDisplay == $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'])
            return true;

        if(isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT']) && $sDisplay == $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'])
            return true;

        return false;
    }

    function getCode($bDynamicMode = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if(!$bDynamicMode && bx_is_dynamic_request())
            $bDynamicMode = true;
       
        $sResult = parent::getCode($bDynamicMode);

        $aPrivacyFields = $this->_getPrivacyFields();
        foreach($aPrivacyFields as $sField => $sObject)
            $this->_addCssJsPrivacyField($sField, $sObject, $bDynamicMode);

        if(isset($CNF['PARAM_MULTICAT_ENABLED']) && $CNF['PARAM_MULTICAT_ENABLED'] === true) {
            $sInclude2 = '';
            $sInclude2 .= $this->_oModule->_oTemplate->addJs(array('BxDolCategories.js'), $bDynamicMode);
            $sInclude2 .= $this->_oModule->_oTemplate->addCss(array('categories.css'), $bDynamicMode);

            $sResult .= ($bDynamicMode ? $sInclude2 : '') . $this->_oModule->_oTemplate->getJsCode('categories');
        }
        
        return $sResult;
    }
    
    /**
     * Nested forms processing
     */
    function genInputNestedForm (&$aInput, $sInfo = '', $sError = '')
    {
        $sUniqId = genRndPwd (8, false);
        $aNestedForms = array();
        if (!$this->isSubmitted()){
            $aGhostTemplateParams = is_object($aInput['ghost_template']) ? $aInput['ghost_template']->aParams : $aInput['ghost_template']['params'];

            $aNestedValues = $this->_oModule->_oDb->getNestedBy(array(
                'type' => 'content_id', 
                'id' => $this->_iContentId, 
                'key_name' => $aGhostTemplateParams['db']['key']
            ), $aGhostTemplateParams['db']['table']);

            foreach($aNestedValues as $aNestedValue) {
                $aNestedValuesRv = array();
                $oForm = $this->getNestedFormObject($aInput);  
                
                foreach($aNestedValue as $aNestedKey => $aNestedItem) {
                    if ($oForm->aParams['db']['key'] == $aNestedKey)
                        $aNestedValuesRv[$aNestedKey . '[]'] = $aNestedItem;
                    else
                        $aNestedValuesRv[$aNestedKey] = $aNestedItem;
                }
                
                $oForm->initChecker($aNestedValuesRv);
                array_push(
				    $aNestedForms,
				    array(
					    'key_value' => $aNestedValuesRv[$oForm->aParams['db']['key'] . '[]'],
                        'key_name' => $aInput['name'],
					    'form_code' => $oForm->genRows(),
                        'js_instance_name' => 'oBxNestedForm_' . $sUniqId,
                        'nested_type' => $aInput['name'],
				    )
			    );
            }
        }
        else{
            $aInput['ghost_templates'] = $aInput['ghost_template'];
            $this->genGhostTemplateForInputNestedForm ($aInput);
            foreach($aInput['ghost_templates'] as $oForm) {
                if (isset($oForm->aInputs)){
                    array_push(
                        $aNestedForms,
                        array(
                            'key_value' => $oForm->aInputs[$aInput['name']]['value'],
                            'key_name' => $aInput['name'],
                            'form_code' => $oForm->genRows(),
                            'js_instance_name' => 'oBxNestedForm_' . $sUniqId,
                            'nested_type' => $aInput['name'],
                        )
                    );
                }
            }
        }

        return $this->oTemplate->parseHtmlByName('form_field_nested_form.html', array(
            'bx_repeat:items' => $aNestedForms,
            'info' => $sInfo,
            'error' => $sError,
            'uniq_id' => $sUniqId,
            'js_instance_name' => 'oBxNestedForm_' . $sUniqId,
            'options' => json_encode(array(
                'uniq_id' => $sUniqId,
                'js_instance_name' => 'oBxNestedForm_' . $sUniqId,
                'template_ghost' => $this->genGhostTemplate($aInput),
                'form_name' => $aInput['value'],
                'action_uri' =>  $this->_oModule->_oConfig->getBaseUri(),
                'nested_type' => $aInput['name'],
            )),
        ));
    }
    
    function genNestedForm (&$aInput)
    {
        $sResult = '';
        $aGhostTemplateParams = is_object($aInput['ghost_template']) ? $aInput['ghost_template']->aParams : $aInput['ghost_template']['params'];

        $aNestedValues = $this->_oModule->_oDb->getNestedBy(array(
            'type' => 'content_id', 
            'id' => $this->_iContentId, 
            'key_name' => $aGhostTemplateParams['db']['key']
        ), $aGhostTemplateParams['db']['table']);

        foreach($aNestedValues as $aNestedValue) {
            $sValue = '';
			$aNestedValuesRv = array();
            $oForm = $this->getNestedFormObject($aInput, true);
            
            foreach($aNestedValue as $aNestedKey => $aNestedItem) {
                if ($oForm->aParams['db']['key'] == $aNestedKey)
                    $aNestedValuesRv[$aNestedKey . '[]'] = $aNestedItem;
                else
                    $aNestedValuesRv[$aNestedKey] = $aNestedItem;
            }
              
            $oForm->initChecker($aNestedValuesRv);
            
            if ($aInput['rateable']){
                $sVote = '';
				$sVoteBtn = '';
                $iId = BxDolFormQuery::getFormField($this->id, $aInput['name'], $this->_iContentId,  $aNestedValuesRv[$oForm->aParams['db']['key'] . '[]']);
                $oVote = BxDolVote::getObjectInstance($aInput['rateable'], $iId, true, BxDolTemplate::getInstance());
                if ($oVote){
					$sVote = $oVote->getCounter(array('show_counter_empty' => true, 'show_counter' => true, 'show_counter_style' => 'simple', 'dynamic_mode' => $this->_bDynamicMode));
					$sVoteBtn = $oVote->getElementInline(array('show_counter_empty' => true, 'show_counter' => false, 'show_counter_style' => 'simple', 'dynamic_mode' => $this->_bDynamicMode));
				}
				$sValue = $this->oTemplate->parseHtmlByName('form_view_rateable_row.html', array(
					'value' =>  $oForm->getCode(),
					'rate' => $sVote,
					'rate_btn' => $sVoteBtn
				));
            }
			else{
				$sValue =  $oForm->getCode();
			}
			$sResult .= $this->oTemplate->parseHtmlByName('form_view_nested_row.html', array(
				'value' =>  $sValue
			));
        }
        if ($sResult != ''){
            return $this->oTemplate->parseHtmlByName('form_view_row.html', array(
                'type' => $aInput['type'], 
                'caption' => isset($aInput['caption']) ? bx_process_output($aInput['caption']) : '',
                'value' => $sResult
            ));
        }
    }
    
    protected function genGhostTemplateForInputNestedForm (&$aInput)
    {
        $oForm = $this->getNestedFormObject($aInput);  
        $aInput['ghost_template'] = array ( 
            'params' => array(
                'nested_form_template' => 'form_field_nested_form_wrapper.html',
                'db' => array(
                    'table' => $oForm->aParams['db']['table'],
                    'key' => $oForm->aParams['db']['key'],
                    'submit_name' =>  $oForm->aParams['db']['key'],
                ),
            ),
            'inputs' => $oForm->aInputs
        );
    }
    
    function getNestedFormObject (&$aInput, $bViewMode = false)
    {
        $oForm = BxDolForm::getObjectInstance($aInput['value'], $aInput['value'] . ($bViewMode ? '_view' : ''));
        foreach($oForm->aInputs as $sKey => $aInput2) {
            $sName = $oForm->aInputs[$sKey]['name'];
            if (strpos($sName,'[]') === false)
                $oForm->aInputs[$sKey]['name'] = $sName . '[]';
        }
        return $oForm;
    }

    /**
     * Add field(s) which will be tracked during content update. 
     */
    public function addTrackFields($mixedFields, $mixedContent)
    {
        if(empty($mixedContent) || empty($mixedFields))
            return;

        if(!is_array($mixedContent)) {
            $mixedContent = $this->_oModule->_oDb->getContentInfoById((int)$mixedContent);
            if(empty($mixedContent) || !is_array($mixedContent))
                return;
        }

        if(!is_array($mixedFields))
            $mixedFields = array($mixedFields);

        foreach($mixedFields as $sField) {
            if(!isset($mixedContent[$sField]))
                continue;

            $this->_aTrackFieldsChanges[$sField] = array(
                'old' => $mixedContent[$sField],
            );
        }
    }

    /**
     * Checks if the field was changed.
     */
    public function isTrackFieldChanged($sField, $bReturnValues = false)
    {
        if(!isset($this->_aTrackFieldsChanges[$sField]) || $this->_aTrackFieldsChanges[$sField] === false)
            return false;

        return $bReturnValues ? $this->_aTrackFieldsChanges[$sField] : true;
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        $bValues = $aValues && !empty($aValues['id']);
        
        $this->_iContentId = isset($aValues['id']) ? $aValues['id'] : false;
        
        if (!empty($CNF['FIELD_LOCATION_PREFIX']) && isset($this->aInputs[$CNF['FIELD_LOCATION_PREFIX']]) && isset($aValues[$CNF['FIELD_ID']]) && !empty($CNF['OBJECT_METATAGS']) && ($oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])) && $oMetatags->locationsIsEnabled())
            $this->aInputs[$CNF['FIELD_LOCATION_PREFIX']]['value'] = $oMetatags->locationsString($aValues[$CNF['FIELD_ID']], false);

        if (isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {

            $aContentInfo = false;
            if ($aValues && !empty($aValues['id'])) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($aValues['id']);
                $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = $aValues['id'];
            }

            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplate, $this->_getPhotoGhostTmplVars($aContentInfo));
        }

        if (isset($CNF['FIELD_LABELS']) && isset($this->aInputs[$CNF['FIELD_LABELS']]) && !empty($CNF['OBJECT_METATAGS']) && ($oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])) && $oMetatags->keywordsIsEnabled() && !empty($aValues['id'])) {
            $this->aInputs[$CNF['FIELD_LABELS']]['content_id'] = $aValues['id'];
            if(($aLabels = $oMetatags->keywordsGet($aValues['id'])))
                $this->aInputs[$CNF['FIELD_LABELS']]['value'] = array_intersect($aLabels, BxDolLabel::getInstance()->getLabels(array('type' => 'values')));
        }

        if (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && isset($CNF['FIELD_AUTHOR']) && isset($aValues[$CNF['FIELD_AUTHOR']])) {
            $this->aInputs[$CNF['FIELD_ANONYMOUS']]['checked'] = $aValues[$CNF['FIELD_AUTHOR']] < 0;
        }
        
        if (isset($CNF['FIELD_MULTICAT']) && isset($this->aInputs[$CNF['FIELD_MULTICAT']]) && $CNF['PARAM_MULTICAT_ENABLED']) {
            if ($bValues)
                $this->aInputs[$CNF['FIELD_MULTICAT']]['content_id'] = $aValues['id'];
        }

        $aPrivacyFields = $this->_getPrivacyFields();
        foreach($aPrivacyFields as $sField => $sObject)
            $this->_preloadPrivacyField($sField, $sObject, $aValues);
        
        if (isset($CNF['FIELD_AUTHOR']) && isset($this->aInputs[$CNF['FIELD_AUTHOR']])){
            if (isset($aValues[$CNF['FIELD_AUTHOR']]) && $aValues[$CNF['FIELD_AUTHOR']])
                $aValues[$CNF['FIELD_AUTHOR']] = $aValues[$CNF['FIELD_AUTHOR']];
            else
                $aValues[$CNF['FIELD_AUTHOR']] = bx_get_logged_profile_id();
        }
        
        parent::initChecker ($aValues, $aSpecificValues);

        foreach($aPrivacyFields as $sField => $sObject)
            $this->_validatePrivacyField($sField, $sObject, $aValues);
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        $iAuthor = -1;
        
        if (isset($CNF['FIELD_AUTHOR'])){
            if (empty($aValsToAdd[$CNF['FIELD_AUTHOR']]) && (!isset($this->aInputs[$CNF['FIELD_AUTHOR']]) || empty($this->getCleanValue($CNF['FIELD_AUTHOR'])))){
                $aValsToAdd[$CNF['FIELD_AUTHOR']] = (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && $this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * bx_get_logged_profile_id ();
                $iAuthor = $aValsToAdd[$CNF['FIELD_AUTHOR']] ;
            }
            else{
                if(isset($this->aInputs[$CNF['FIELD_AUTHOR']]) && empty($this->getCleanValue($CNF['FIELD_AUTHOR']))){
                     $aValsToAdd[$CNF['FIELD_AUTHOR']] = bx_get_logged_profile_id();
                     $iAuthor = $aValsToAdd[$CNF['FIELD_AUTHOR']];
                }
                if(isset($this->aInputs[$CNF['FIELD_AUTHOR']]) && !empty($this->getCleanValue($CNF['FIELD_AUTHOR']))){
                    $iAuthor = $this->getCleanValue($CNF['FIELD_AUTHOR']);
                }
            }
        }
            

        if (isset($CNF['FIELD_ADDED']) && empty($aValsToAdd[$CNF['FIELD_ADDED']]) && empty($this->getCleanValue($CNF['FIELD_ADDED'])))
            $aValsToAdd[$CNF['FIELD_ADDED']] = time();

        if (isset($CNF['FIELD_CHANGED']) && empty($aValsToAdd[$CNF['FIELD_CHANGED']]) && empty($this->getCleanValue($CNF['FIELD_CHANGED'])))
            $aValsToAdd[$CNF['FIELD_CHANGED']] = time();

        if (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb() && isset($CNF['FIELD_THUMB'])) {
            $aThumb = isset($_POST[$CNF['FIELD_THUMB']]) ? bx_process_input ($_POST[$CNF['FIELD_THUMB']], BX_DATA_INT) : false;
            $aValsToAdd[$CNF['FIELD_THUMB']] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[$CNF['FIELD_THUMB']] = $iFileThumb;
        }

        if(!empty($CNF['OBJECT_METATAGS']))
            $this->_processMetas($aValsToAdd);

        if(isset($CNF['FIELD_STATUS']) && !empty($CNF['FIELDS_DELAYED_PROCESSING']) && !empty($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4'])) {
            $oTranscoder = BxDolTranscoder::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);

            $aFields = $CNF['FIELDS_DELAYED_PROCESSING'];
            if(is_string($aFields))
                $aFields = explode(',', $aFields);

            foreach($aFields as $sField) {
                $mixedFieldValues = $this->getCleanValue($sField);
                if(empty($mixedFieldValues)) 
                    continue;

                $oStorage = BxDolStorage::getObjectInstance($this->aInputs[$sField]['storage_object']);
                if(!$oStorage)
                    continue;

                if(!is_array($mixedFieldValues))
                    $mixedFieldValues = array($mixedFieldValues);

                foreach($mixedFieldValues as $mixedFieldValue) {
                    $aInfo = $oStorage->getFile((int)$mixedFieldValue);
                    if(empty($aInfo) || !is_array($aInfo))
                        continue;

                    if(strncmp($aInfo['mime_type'], 'video/', 6) != 0) 
                        continue;

                    if($oTranscoder->isFileReady((int)$mixedFieldValue))
                        continue;

                    $aValsToAdd[$CNF['FIELD_STATUS']] = 'awaiting';
                    break 2;
                }
            }
        }

        if(!$this->_oModule->_isModerator() && !$this->_oModule->_oConfig->isAutoApproveEnabled())
            $aValsToAdd[$CNF['FIELD_STATUS_ADMIN']] = BX_BASE_MOD_GENERAL_STATUS_PENDING;

        $bMulticatEnabled = $this->_isMulticatEnabled();
        if ($bMulticatEnabled)
            $this->processMulticatBefore($CNF['FIELD_MULTICAT'], $aValsToAdd);
        $iContentId = parent::insert ($aValsToAdd, $isIgnore);
        
        if(!empty($iContentId)) {
            foreach($this->aInputs as $aInput) {
                if ($aInput['type'] == 'nested_form') {
                    if (is_array($aInput['ghost_template']) && !isset($aInput['ghost_template']['inputs'])) {
                        foreach ($aInput['ghost_template'] as $oFormNested) {
                            $iNestedContentId = $oFormNested->insert(array('content_id' => $iContentId));

                            if ($aInput['rateable']) {
                                BxDolFormQuery::addFormField($this->id, $aInput['name'], $iContentId, $iAuthor, $this->_oModule->getName(), $iNestedContentId);
                            }
                        }
                    }
                }
            }
            
            if ($bMulticatEnabled)
                $this->processMulticatAfter($CNF['FIELD_MULTICAT'], $iContentId);
        }
        
        
        foreach($this->aInputs as $aInput) {
            if (isset($aInput['rateable']) && $aInput['rateable'] && $aInput['type'] != 'nested_form'){
                BxDolFormQuery::addFormField($this->id, $aInput['name'], $iContentId, $iAuthor, $this->_oModule->getName());
            }
        }
        
        return $iContentId;
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        $iAuthor = -1;
        
        $bFieldAuthor = isset($CNF['FIELD_AUTHOR']);
        if($bFieldAuthor && isset($aContentInfo[$CNF['FIELD_AUTHOR']])){
            $iAuthor = $aContentInfo[$CNF['FIELD_AUTHOR']];
        }

        if($bFieldAuthor) {
            if(isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']])){
                $aValsToAdd[$CNF['FIELD_AUTHOR']] = ($this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * abs($aContentInfo[$CNF['FIELD_AUTHOR']]);
                $iAuthor = $aValsToAdd[$CNF['FIELD_AUTHOR']];
            }
            else {
                if(isset($this->aInputs[$CNF['FIELD_AUTHOR']]) && empty($this->getCleanValue($CNF['FIELD_AUTHOR']))){
                    $aValsToAdd[$CNF['FIELD_AUTHOR']] = bx_get_logged_profile_id();
                    $iAuthor = $aValsToAdd[$CNF['FIELD_AUTHOR']];
                }

                if(isset($this->aInputs[$CNF['FIELD_AUTHOR']]) && !empty($this->getCleanValue($CNF['FIELD_AUTHOR']))){
                    $iAuthor = $this->getCleanValue($CNF['FIELD_AUTHOR']);
                }
            }
        }

        if(isset($CNF['FIELD_CHANGED']) && empty($aValsToAdd[$CNF['FIELD_CHANGED']]) && empty($this->getCleanValue($CNF['FIELD_CHANGED'])))
            $aValsToAdd[$CNF['FIELD_CHANGED']] = time();

        if(CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb($iContentId) && isset($CNF['FIELD_THUMB'])) {
            $aThumb = bx_process_input (bx_get($CNF['FIELD_THUMB']), BX_DATA_INT);
            $aValsToAdd[$CNF['FIELD_THUMB']] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[$CNF['FIELD_THUMB']] = $iFileThumb;
        }

        if(!empty($CNF['OBJECT_METATAGS']))
            $this->_processMetas($aValsToAdd);

        if(isset($CNF['FIELD_STATUS']) && isset($aContentInfo[$CNF['FIELD_STATUS']]) && $aContentInfo[$CNF['FIELD_STATUS']] == 'failed')
            $aValsToAdd[$CNF['FIELD_STATUS']] = 'active';

        $bMulticatEnabled = $this->_isMulticatEnabled();
        if ($bMulticatEnabled)
            $this->processMulticatBefore($CNF['FIELD_MULTICAT'], $aValsToAdd);

        $mixedResult = parent::update($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        foreach($this->aInputs as $aInput) {
            if ($aInput['type'] == 'nested_form'){
                if (is_array($aInput['ghost_template']) && !isset($aInput['ghost_template']['inputs'])) {
                    foreach ($aInput['ghost_template'] as $oFormNested) {
                        $aSpecificValues = $oFormNested->getSpecificValues();
                        $iNestedContentId = $oFormNested->getSubmittedValue($aInput['name'], BX_DOL_FORM_METHOD_SPECIFIC, $aSpecificValues);

                        if (empty($iNestedContentId)){
                            $iNestedContentId = $oFormNested->insert(array('content_id' => $iContentId));
                            if ($aInput['rateable']){
                                BxDolFormQuery::addFormField($this->id, $aInput['name'], $iContentId, $iAuthor, $this->_oModule->getName(), $iNestedContentId);
                            }
                        }
                        else{
                            $oFormNested->update($iNestedContentId, array('content_id' => $iContentId));
                        }
                    }
                }
            }
        }

        if ($bMulticatEnabled)
            $this->processMulticatAfter($CNF['FIELD_MULTICAT'], $iContentId);
        
		foreach($this->aInputs as $aInput) {
            if (isset($aInput['rateable']) && $aInput['rateable'] && $aInput['type'] != 'nested_form'){
                BxDolFormQuery::addFormField($this->id, $aInput['name'], $iContentId, $iAuthor, $this->_oModule->getName());
            }
        }
		
        if($mixedResult !== false)
            $this->_processTrackFields($iContentId);

        return $mixedResult;
    }
    
    function getHtmlEditorQueryParams($aInput)
    {
        $aQueryParams = parent::getHtmlEditorQueryParams($aInput);
        if (isset($this->MODULE)){
            $aQueryParams['m'] = $this->MODULE;
        }
        if (isset($this->_iContentId) && $this->_iContentId){
            $aQueryParams['cid'] = $this->_iContentId;
        }
        $aQueryParams['fi'] = '';
        
        bx_alert('system', 'editor_query_params', 0, 0, array(
            'form' => $this,
            'override_result' => &$aQueryParams
        ));
        
        return $aQueryParams;
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField = '')
    {
        $oStorage->updateGhostsContentId ($iFileId, $iProfileId, $iContentId, $this->_isAdmin($iContentId));
    }

    protected function _getPhotoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
			'name' => $this->aInputs[$CNF['FIELD_PHOTO']]['name'],
			'content_id' => (int)$this->aInputs[$CNF['FIELD_PHOTO']]['content_id'],
			'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : '',
            'thumb_id' => isset($CNF['FIELD_THUMB']) && isset($aContentInfo[$CNF['FIELD_THUMB']]) ? $aContentInfo[$CNF['FIELD_THUMB']] : 0,
            'name_thumb' => isset($CNF['FIELD_THUMB']) ? $CNF['FIELD_THUMB'] : '',
			'bx_if:set_thumb' => array (
				'condition' => CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb($this->aInputs[$CNF['FIELD_PHOTO']]['content_id']),
				'content' => array (
					'name_thumb' => isset($CNF['FIELD_THUMB']) ? $CNF['FIELD_THUMB'] : '',
    				'txt_pict_use_as_thumb' => _t(!empty($CNF['T']['txt_pict_use_as_thumb']) ? $CNF['T']['txt_pict_use_as_thumb'] : '_sys_txt_form_entry_input_picture_use_as_thumb')
				),
			),
		);
    }
    
    function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // delete associated files

        $a = array('OBJECT_STORAGE', 'OBJECT_STORAGE_FILES', 'OBJECT_STORAGE_PHOTOS', 'OBJECT_STORAGE_VIDEOS', 'OBJECT_STORAGE_SOUNDS');
        foreach ($a as $k) {
            if (!empty($CNF[$k])) {
                $oStorage = BxDolStorage::getObjectInstance($CNF[$k]);
                if ($oStorage)
                    $oStorage->queueFilesForDeletionFromGhosts($aContentInfo[$CNF['FIELD_AUTHOR']], $iContentId);
            }
        }

        // delete associated objects data

        if (!empty($CNF['OBJECT_VIEWS'])) {
            $o = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_VOTES'])) {
            $o = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_SCORES'])) {
            $o = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_COMMENTS'])) {
            $o = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_REPORTS'])) {
            $o = BxDolReport::getObjectInstance($CNF['OBJECT_REPORTS'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_METATAGS'])) {
            $o = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            if ($o) $o->onDeleteContent($iContentId);
        }

        // delete SEO links

        BxDolPage::deleteSeoLink ($this->_oModule->getName(), $this->_oModule->getName(), $iContentId);

        // delete db record
        
        BxDolFormQuery::removeFormField($this->id, $iContentId);

        return parent::delete($iContentId);
    }

    protected function _isAdmin ($iContentId = 0)
    {
        return $this->_oModule->_isModerator();
    }

    public function processFiles ($sFieldFile, $iContentId = 0, $isAssociateWithContent = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!isset($this->aInputs[$sFieldFile]))
            return true;

        $mixedFileIds = $this->getCleanValue($sFieldFile);
        if(!$mixedFileIds)
            return true;

        $oStorage = BxDolStorage::getObjectInstance($this->aInputs[$sFieldFile]['storage_object']);
        if (!$oStorage)
            return false;

        $iProfileId = $this->getContentOwnerProfileId($iContentId);

        $aGhostFiles = $oStorage->getGhosts ($iProfileId, $isAssociateWithContent ? 0 : $iContentId, true, $this->_isAdmin($iContentId));
        if (!$aGhostFiles)
            return true;

        foreach ($aGhostFiles as $aFile) {
            if (is_array($mixedFileIds) && !in_array($aFile['id'], $mixedFileIds))
                continue;

            if ($aFile['private'])
                $oStorage->setFilePrivate ($aFile['id'], 1);

            if ($iContentId)
                $this->_associalFileWithContent($oStorage, $aFile['id'], $iProfileId, $iContentId, $sFieldFile);
        }

        return true;
    }

    function _deleteFile ($iFileId, $sStorage = '')
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!$iFileId)
            return true;

		$sStorage = !empty($sStorage) ? $sStorage : $CNF['OBJECT_STORAGE'];
		$oStorage = BxDolStorage::getObjectInstance($sStorage);
        if (!$oStorage)
            return false;

        if (!$oStorage->getFile($iFileId))
            return true;

        $iProfileId = bx_get_logged_profile_id();
        return $oStorage->deleteFile($iFileId, $iProfileId);
    }

    function addCssJs ()
    {
        if ((!isset($this->aParams['view_mode']) || !$this->aParams['view_mode']) && !self::$_isCssJsGeneralModuleAdded) {
            $this->_oModule->_oTemplate->addCss('forms.css');
            $this->_oModule->_oTemplate->addJs('modules/base/general/js/|forms.js');
            self::$_isCssJsGeneralModuleAdded = true;
        }

        return parent::addCssJs ();
    }
    
    function genViewRowValue(&$aInput)
    {
        $s = parent::genViewRowValue($aInput);

        if ($this->_oMetatagsObject && in_array($aInput['name'], $this->_aMetatagsFieldsWithKeywords) && $s)
            $s = $this->_oMetatagsObject->metaParse($this->_oMetatagsContentId, $s);

        return $s;
    }
    
    function genViewRowWrapped(&$aInput)
    {
        $sResult =  parent::genViewRowWrapped($aInput);
        if (!$aInput['rateable']){
            return $sResult;
        }
        
        $sValue = $this->genViewRowValue($aInput);
        if (null === $sValue)
            return '';
        
        // process rateable fields
        $iId = BxDolFormQuery::getFormField($this->id, $aInput['name'], $this->_iContentId);
        $sVote = '';
        $oVote = BxDolVote::getObjectInstance($aInput['rateable'], $iId, true, BxDolTemplate::getInstance());
        if ($oVote){
            $sVote = $oVote->getCounter(array('show_counter_empty' => true, 'show_counter' => true, 'show_counter_style' => 'simple', 'dynamic_mode' => $this->_bDynamicMode));
			$sVoteBtn = $oVote->getElementInline(array('show_counter_empty' => true, 'show_counter' => false, 'show_counter_style' => 'simple', 'dynamic_mode' => $this->_bDynamicMode));
			return $this->oTemplate->parseHtmlByName('form_view_rateable_row.html', array(
				'value' => $sResult,
				'rate' => $sVote,
				'rate_btn' => $sVoteBtn
			));
        }
		return $sResult;
    }

    function setMetatagsKeywordsData($iId, $a, $o)
    {
        $this->_oMetatagsContentId = $iId;
        $this->_aMetatagsFieldsWithKeywords = $a;
        $this->_oMetatagsObject = $o;
    }

    function getContentOwnerProfileId ($iContentId)
    {
        return $this->_oModule->serviceGetContentOwnerProfileId($iContentId);
    }

    protected function _processTrackFields($mixedContent)
    {
        if(empty($mixedContent) || empty($this->_aTrackFieldsChanges))
            return;

        if(!is_array($mixedContent)) {
            $mixedContent = $this->_oModule->_oDb->getContentInfoById((int)$mixedContent);
            if(empty($mixedContent) || !is_array($mixedContent))
                return;
        }

        foreach($this->_aTrackFieldsChanges as $sField => $aValues)
            if($mixedContent[$sField] == $aValues['old'])
                $this->_aTrackFieldsChanges[$sField] = false;
            else
                $this->_aTrackFieldsChanges[$sField]['new'] = $mixedContent[$sField];
    }

    protected function _processMetas(&$aValsToAdd)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sKey = 'FIELD_LABELS';
        if(isset($CNF[$sKey]) && isset($this->aInputs[$CNF[$sKey]]) && empty($aValsToAdd[$CNF[$sKey]])) {
            $aLabels = $this->getCleanValue($CNF[$sKey]);
            if(!empty($aLabels) && is_array($aLabels))
                $aValsToAdd[$CNF[$sKey]] = serialize($aLabels);
            else
                $aValsToAdd[$CNF[$sKey]] = '';
        }

        $sKey1 = 'FIELD_LOCATION';
        $sKey2 = 'FIELD_LOCATION_PREFIX';
        if(isset($CNF[$sKey1]) && isset($CNF[$sKey2]) && isset($this->aInputs[$CNF[$sKey1]]) && empty($aValsToAdd[$CNF[$sKey1]])) {               
            $aLocation = BxDolMetatags::locationsRetrieveFromForm($CNF[$sKey2], $this);
            if(!empty($aLocation) && is_array($aLocation))
                $aValsToAdd[$CNF[$sKey1]] = serialize(BxDolMetatags::locationsParseComponents($aLocation));
        }
    }

    protected function _getPrivacyFields($aKeysF2O = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($CNF['PRIVACY_FIELD_TO_OBJECT']) && is_array($CNF['PRIVACY_FIELD_TO_OBJECT']))
            return $CNF['PRIVACY_FIELD_TO_OBJECT'];

        if(empty($aKeysF2O))
            $aKeysF2O = array('FIELD_ALLOW_VIEW_TO' => 'OBJECT_PRIVACY_VIEW');

        $aResult = array();
        foreach($aKeysF2O as $sKeyField => $sKeyObject) {
            if(!isset($CNF[$sKeyField]) || !isset($this->aInputs[$CNF[$sKeyField]]) || !isset($CNF[$sKeyObject]))
                continue;

            $aResult[$CNF[$sKeyField]] = $CNF[$sKeyObject];
        }

        return $aResult;
    }

    function _addCssJsPrivacyField($sField, $sPrivacyObject, $bDynamicMode = false)
    {
        $oPrivacy = BxDolPrivacy::getObjectInstance($sPrivacyObject);
        if(!$oPrivacy) 
            return;

        $this->aInputs[$sField]['content'] = $oPrivacy->addCssJs($bDynamicMode) . $this->aInputs[$sField]['content'];
    }

    protected function _preparePrivacyField($sField, $sPrivacyObject)
    {
        $oPrivacy = BxDolPrivacy::getObjectInstance($sPrivacyObject);
        if(!$oPrivacy) 
            return;

        $aSave = array('db' => array('pass' => 'Xss'));
        array_walk($this->aInputs[$sField], function ($a, $k, $aSave) {
            if (in_array($k, array('info', 'caption', 'value')))
                $aSave[0][$k] = $a;
        }, array(&$aSave));

        $aGroupChooser = $oPrivacy->getGroupChooser($sPrivacyObject, 0, array(
            'object' => $this->aParams['object'],
            'display' => $this->aParams['display']
        ));

        $this->aInputs[$sField] = array_merge($this->aInputs[$sField], $aGroupChooser, $aSave);
    }

    protected function _preloadPrivacyField($sField, $sPrivacyObject, $aValues)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oPrivacy = BxDolPrivacy::getObjectInstance($sPrivacyObject);
        if(!$oPrivacy) 
            return;

        $iContentId = !empty($aValues[$CNF['FIELD_ID']]) ? (int)$aValues[$CNF['FIELD_ID']] : 0;
        $iProfileId = !empty($iContentId) ? (int)$this->getContentOwnerProfileId($iContentId) : bx_get_logged_profile_id();
        $iGroupId = !empty($aValues[$sField]) ? $aValues[$sField] : 0;

        if(!isset($this->aInputs[$sField]['content']))
            $this->aInputs[$sField]['content'] = '';

        $this->aInputs[$sField]['content'] .= $oPrivacy->initGroupChooser($sPrivacyObject, $iProfileId, array(
            'content_id' => $iContentId,
            'group_id' => $iGroupId,
            'html_ids' => array(
                'form' => $this->getId()
            )
        ));
    }
    
    protected function _validatePrivacyField($sField, $sPrivacyObject, $aValues)
    {
        $mixedValue = $this->aInputs[$sField]['value'];

        $bValue = false;
        foreach($this->aInputs[$sField]['values'] as $aValue)
            if(isset($aValue['key']) && $aValue['key'] == $mixedValue) {
                $bValue = true;
                break;
            }

        if(!$bValue) {
            $sTitle = '';
            if(is_numeric($mixedValue) && (int)$mixedValue < 0 && ($oContext = BxDolProfile::getInstance(abs((int)$mixedValue))) !== false)
                $sTitle = $oContext->getDisplayName();
            else
                $sTitle = _t('_sys_ps_group_title_unknown');

            $this->aInputs[$sField]['values'] = array_merge(array(
                array('key' => $mixedValue, 'value' => $sTitle),
                array('key' => '', 'value' => '----', 'attrs' => array('disabled' => 'disabled'))
            ), $this->aInputs[$sField]['values']);
        }
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
    
    protected function genCustomInputAuthor ($aInput)
    {
        if(empty($aInput['custom']) || !is_array($aInput['custom']))
            $aInput['custom'] = array();
        $aInput['custom']['only_once'] = 1;
        
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . "modules/?r=" . $this->_oModule->_oConfig->getUri() . "/ajax_get_profiles";
        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
    
    protected function _isMulticatEnabled(){
        $CNF = $this->_oModule->_oConfig->CNF;
        return isset($CNF['PARAM_MULTICAT_ENABLED']) && $CNF['PARAM_MULTICAT_ENABLED'] === true && isset($CNF['FIELD_MULTICAT']);
    }
}

/** @} */
