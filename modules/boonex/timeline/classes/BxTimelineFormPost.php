<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxTimelineFormPost extends BxBaseModGeneralFormEntry
{
    protected $_bAccountMode;
    protected $_bPublicMode;
    protected $_bProfileMode;

    protected $_bVisibilityAutoselect;

    protected $_aUploadersInfo;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_timeline';

        parent::__construct($aInfo, $oTemplate);

        $this->_sGhostTemplate = 'uploader_nfw.html';

        $this->_bAccountMode = isset($this->aParams['display']) && $this->aParams['display'] == $this->_oModule->_oConfig->getObject('form_display_post_add');
        $this->_bPublicMode = isset($this->aParams['display']) && $this->aParams['display'] == $this->_oModule->_oConfig->getObject('form_display_post_add_public');
        $this->_bProfileMode = isset($this->aParams['display']) && $this->aParams['display'] == $this->_oModule->_oConfig->getObject('form_display_post_add_profile');

        $this->_bVisibilityAutoselect = true;

        $this->_aUploadersInfo = array();
    }

    public function getUploadersInfo($sField = '')
    {
        if(empty($sField))
            return $this->_aUploadersInfo;

        $aUploaders = !empty($this->aInputs[$sField]['value']) ? unserialize($this->aInputs[$sField]['value']) : $this->_oModule->_oConfig->getUploaders($sField);

        return $this->_aUploadersInfo[array_shift($aUploaders)];
    }

    public function initChecker($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        $iUserId = $this->_oModule->getUserId();
        $bValueId = $aValues && !empty($aValues['id']);
        $iValueId = $bValueId ? (int)$aValues['id'] : 0;

        if(isset($this->aInputs[$CNF['FIELD_ATTACHMENTS']]) && ($oMenu = $this->_oModule->getAttachmentsMenuObject()) !== false) {
            $oMenu->setEventById($iValueId);
            $oMenu->setUploadersInfo($this->_aUploadersInfo);

            $this->aInputs['attachments']['content'] = $oMenu->getCode() . $this->_oModule->_oTemplate->parseHtmlByName('uploader_progress.html', []);;
        }

        if(isset($this->aInputs[$CNF['FIELD_LINK']]))
            $this->aInputs[$CNF['FIELD_LINK']]['content'] = $this->_oModule->_oTemplate->getAttachLinkField($iUserId, $iValueId);

        foreach(['FIELD_VIDEO', 'FIELD_FILE'] as $sSetting){
            if(!isset($CNF[$sSetting]) || !isset($this->aInputs[$CNF[$sSetting]])) 
                continue;

            $aContentInfo = false;
            if($bValueId) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($iValueId);
                $this->aInputs[$CNF[$sSetting]]['content_id'] = $iValueId;
            }

            $this->aInputs[$CNF[$sSetting]]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplate, $this->_getGhostTmplVars($CNF[$sSetting], $aContentInfo));
        }

        if($this->aParams['display'] == $this->_oModule->_oConfig->getObject('form_display_post_edit') && isset($CNF['FIELD_PUBLISHED']) && isset($this->aInputs[$CNF['FIELD_PUBLISHED']]))
            if(isset($aValues[$CNF['FIELD_STATUS']]) && in_array($aValues[$CNF['FIELD_STATUS']], array(BX_TIMELINE_STATUS_ACTIVE, BX_TIMELINE_STATUS_HIDDEN)))
                unset($this->aInputs[$CNF['FIELD_PUBLISHED']]);

        if(isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && isset($aValues[$CNF['FIELD_OBJECT_ID']]))
            $this->aInputs[$CNF['FIELD_ANONYMOUS']]['checked'] = $aValues[$CNF['FIELD_OBJECT_ID']] < 0;

        parent::initChecker ($aValues, $aSpecificValues);
    }

    public function insert($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aValsToAdd[$CNF['FIELD_SYSTEM']] = 0;
        $aValsToAdd[$CNF['FIELD_OBJECT_ID']] *= isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && $this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1;

        if(isset($CNF['FIELD_ADDED']) && empty($aValsToAdd[$CNF['FIELD_ADDED']])) {
            $iAdded = 0;
            if(isset($this->aInputs[$CNF['FIELD_ADDED']]))
                $iAdded = $this->getCleanValue($CNF['FIELD_ADDED']);
            
            if(empty($iAdded))
                 $iAdded = time();

            $aValsToAdd[$CNF['FIELD_ADDED']] = $iAdded;
            if(isset($CNF['FIELD_REACTED']))
                $aValsToAdd[$CNF['FIELD_REACTED']] = $iAdded;
        }

        if(empty($aValsToAdd[$CNF['FIELD_PUBLISHED']])) {
            $iPublished = 0;
            if(isset($this->aInputs[$CNF['FIELD_PUBLISHED']]))
                $iPublished = $this->getCleanValue($CNF['FIELD_PUBLISHED']);
                
             if(empty($iPublished))
                 $iPublished = time();

             $aValsToAdd[$CNF['FIELD_PUBLISHED']] = $iPublished;
        }

        $aValsToAdd[$CNF['FIELD_STATUS']] = $aValsToAdd[$CNF['FIELD_PUBLISHED']] > $aValsToAdd[$CNF['FIELD_ADDED']] ? BX_TIMELINE_STATUS_AWAITING : BX_TIMELINE_STATUS_ACTIVE;

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']])) {
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

            $aValsToAdd[$CNF['FIELD_OBJECT_ID']] = ($this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * abs($aContentInfo[$CNF['FIELD_OBJECT_ID']]);
        }

        if(empty($aValsToAdd[$CNF['FIELD_PUBLISHED']]) && isset($this->aInputs[$CNF['FIELD_PUBLISHED']])) {
            $iPublished = $this->getCleanValue($CNF['FIELD_PUBLISHED']);
            if(empty($iPublished))
                $iPublished = time();

            $aValsToAdd[$CNF['FIELD_PUBLISHED']] = $iPublished;
        }

        return parent::update($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    public function addHtmlEditor($iViewMode, &$aInput, $sUniq)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oEditor = BxDolEditor::getObjectInstance(false, $this->oTemplate);
        if (!$oEditor)
            return false;

        if(in_array($this->aParams['display'], array($this->_oModule->_oConfig->getObject('form_display_post_add'), $this->_oModule->_oConfig->getObject('form_display_post_add_public'))))
            $oEditor->setCustomConf("
                placeholderText: '" . _t('_bx_timeline_txt_some_text_here') . "',
                initOnClick: false,
            ");

        if(!$this->_oModule->_oConfig->isEditorToolbar())
        	$oEditor->setCustomToolbarButtons('');

        $this->_sCodeAdd .= $oEditor->attachEditor ('#' . $this->aFormAttrs['id'] . ' [name=' . $aInput['name'] . ']', $iViewMode, $this->_bDynamicMode, ['form_id' => $this->aFormAttrs['id'], 'element_name' => $aInput['name'], 'query_params' => $this->getHtmlEditorQueryParams($aInput), 'uniq' => $sUniq]);

        return true;
    }

    public function init()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iUserId = $this->_oModule->getUserId();
        $iOwnerId = $this->_oModule->getOwnerId();

        $this->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'post/';

        $this->aInputs['owner_id']['value'] = $iOwnerId;

        if(isset($this->aInputs['text'])) {
            if(empty($this->aInputs['text']['attrs']) || !is_array($this->aInputs['text']['attrs']))
                $this->aInputs['text']['attrs'] = array();

            $this->aInputs['text']['attrs']['id'] = $this->_oModule->_oConfig->getHtmlIds('post', 'textarea') . time();
        }

        $aPrivacyFields = $this->_getPrivacyFields();
        foreach($aPrivacyFields as $sField => $sPrivacyObject)
            $this->_initPrivacyField($sField, $sPrivacyObject);

        if(isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $sStorage = $this->_oModule->_oConfig->getObject('storage_photos');
            $sUploadersId = genRndPwd(8, false);
            $aUploaders = !empty($this->aInputs[$CNF['FIELD_PHOTO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_PHOTO']]['value']) : $this->_oModule->_oConfig->getUploaders($CNF['FIELD_PHOTO']);

            foreach($aUploaders as $sUploader)
                $this->_aUploadersInfo[$sUploader] = array(
                    'id' => $sUploadersId, 
                    'name' => $sUploader,
                    'js_object' => BxDolUploader::getObjectInstance($sUploader, $sStorage, $sUploadersId)->getNameJsInstanceUploader()
                );

            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_object'] = $sStorage;
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['images_transcoder'] = $this->_oModule->_oConfig->getObject('transcoder_photos_preview');
            $this->aInputs[$CNF['FIELD_PHOTO']]['uploaders_id'] = $sUploadersId;
            $this->aInputs[$CNF['FIELD_PHOTO']]['uploaders'] = $aUploaders;
            $this->aInputs[$CNF['FIELD_PHOTO']]['upload_buttons_titles'] = array('Simple' => 'camera');
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = '';
        }

        if(isset($this->aInputs[$CNF['FIELD_VIDEO']])) {
            $sStorage = $this->_oModule->_oConfig->getObject('storage_videos');
            $sUploadersId = genRndPwd(8, false);
            $aUploaders = !empty($this->aInputs[$CNF['FIELD_VIDEO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_VIDEO']]['value']) : $this->_oModule->_oConfig->getUploaders($CNF['FIELD_VIDEO']);

            foreach($aUploaders as $sUploader)
                $this->_aUploadersInfo[$sUploader] = array(
                    'id' => $sUploadersId,
                    'name' => $sUploader,
                    'js_object' => BxDolUploader::getObjectInstance($sUploader, $sStorage, $sUploadersId)->getNameJsInstanceUploader()
                );

            $this->aInputs[$CNF['FIELD_VIDEO']]['storage_object'] = $sStorage;
            $this->aInputs[$CNF['FIELD_VIDEO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEO']]['images_transcoder'] = $this->_oModule->_oConfig->getObject('transcoder_videos_preview');
            $this->aInputs[$CNF['FIELD_VIDEO']]['uploaders_id'] = $sUploadersId;
            $this->aInputs[$CNF['FIELD_VIDEO']]['uploaders'] = $aUploaders;
            $this->aInputs[$CNF['FIELD_VIDEO']]['upload_buttons_titles'] = array('Simple' => 'video');
            $this->aInputs[$CNF['FIELD_VIDEO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_VIDEO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEO']]['ghost_template'] = '';
        }
        
        if(isset($this->aInputs[$CNF['FIELD_FILE']])) {
            $sStorage = $this->_oModule->_oConfig->getObject('storage_files');
            $sUploadersId = genRndPwd(8, false);
            $aUploaders = !empty($this->aInputs[$CNF['FIELD_FILE']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_FILE']]['value']) : $this->_oModule->_oConfig->getUploaders($CNF['FIELD_FILE']);
          
            foreach($aUploaders as $sUploader){
                $this->_aUploadersInfo[$sUploader] = array(
                    'id' => $sUploadersId,
                    'name' => $sUploader,
                    'js_object' => BxDolUploader::getObjectInstance($sUploader, $sStorage, $sUploadersId)->getNameJsInstanceUploader()
                );
            }
            
            $this->aInputs[$CNF['FIELD_FILE']]['storage_object'] = $sStorage;
            $this->aInputs[$CNF['FIELD_FILE']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['images_transcoder'] = '';
            $this->aInputs[$CNF['FIELD_FILE']]['uploaders_id'] = $sUploadersId;
            $this->aInputs[$CNF['FIELD_FILE']]['uploaders'] = $aUploaders;
            $this->aInputs[$CNF['FIELD_FILE']]['upload_buttons_titles'] = array('Simple' => 'file');
            $this->aInputs[$CNF['FIELD_FILE']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_FILE']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['ghost_template'] = '';
        }

        if (isset($this->_oModule->_oConfig->CNF['FIELD_LOCATION_PREFIX']) && isset($this->aInputs[$this->_oModule->_oConfig->CNF['FIELD_LOCATION_PREFIX']]))
            $this->aInputs[$this->_oModule->_oConfig->CNF['FIELD_LOCATION_PREFIX']]['manual_input'] = false;
    }

    public function setVisibilityAutoselect($bVisibilityAutoselect)
    {
        $this->_bVisibilityAutoselect = $bVisibilityAutoselect;
    }

    protected function genCustomRowObjectCf(&$aInput)
    {
        return parent::genCustomRowCf($aInput);
    }

    protected function _getPrivacyFields($aKeysF2O = array())
    {
        if(empty($aKeysF2O))
            $aKeysF2O = array(
                'FIELD_OBJECT_PRIVACY_VIEW' => 'OBJECT_PRIVACY_VIEW'
            );

        return parent::_getPrivacyFields($aKeysF2O);
    }

    /**
     * The method inherited from BxBaseModGeneralFormEntry class is disabled
     * because own variant (method BxTimelineFormPost::_initPrivacyField) is
     * used instead.
     */
    protected function _preparePrivacyField($sField, $sPrivacyObject)
    {
        return;
    }
    
    
    protected function _initPrivacyField($sField, $sPrivacyObject)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

    	$iUserId = $this->_oModule->getUserId();
        $iOwnerId = $this->_oModule->getOwnerId();

        $this->aInputs[$sField] = array_merge($this->aInputs[$sField], BxDolPrivacy::getGroupChooser($sPrivacyObject, 0, array(
            'object' => $this->aParams['object'],
            'display' => $this->aParams['display'],
            'title' => _t($CNF['T']['form_input_title_' . $sField]),
            'dynamic_mode' => $this->_bDynamicMode
        )));

        //--- Show field as is when 'Autoselect' is disabled.
        if(!$this->_bVisibilityAutoselect)
            return;

        //--- Show default privacy groups and followed contexts on Account (Profile + Connections) post form.
        if($this->_bAccountMode)
            return;

        //--- Preselect Context and hide privacy selector when posting in some context (profile, group, space, etc).
        if($this->_bProfileMode && $iOwnerId != $iUserId) {
            $this->aInputs[$sField]['type'] = 'hidden';
	    $this->aInputs[$sField]['value'] = -$iOwnerId;

            return;
        }

        $bProfileModeOwner = $this->_bProfileMode && $iOwnerId == $iUserId;
        if($this->_bPublicMode || $bProfileModeOwner) {
            $iGc = 0;
            $iKeyGh = false;
            foreach($this->aInputs[$sField]['values'] as $iKey => $aValue) {
                if(isset($aValue['type']) && in_array($aValue['type'], array('group_header', 'group_end'))) {
                    if($iKeyGh !== false && $iGc == 0) {
                        unset($this->aInputs[$sField]['values'][$iKeyGh]);
                        $iKeyGh = false;

                        if($aValue['type'] == 'group_end')
                            unset($this->aInputs[$sField]['values'][$iKey]);
                    }

                    if($aValue['type'] == 'group_header') {
                        $iGc = 0;
                        $iKeyGh = $iKey;
                    }

                    continue;
                }

                //--- Show 'Public' privacy group only in Public post form. 
                if($this->_bPublicMode && $aValue['key'] == BX_DOL_PG_ALL) {
                    $iGc += 1;
                    continue;
                }

                //--- Show a default privacy groups in Profile (for Owner) post form.
                if($bProfileModeOwner && (int)$aValue['key'] >= 0) {
                    $iGc += 1;
                    continue;
                }

                unset($this->aInputs[$sField]['values'][$iKey]);
            }
        }
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

    protected function _getGhostTmplVars($sName, $aContentInfo = array())
    {
    	return array (
            'name' => $this->aInputs[$sName]['name'],
            'content_id' => (int)$this->aInputs[$sName]['content_id'],
        );
    }
}

/** @} */
