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

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_timeline';

        parent::__construct($aInfo, $oTemplate);

        $this->_bAccountMode = isset($this->aParams['display']) && $this->aParams['display'] == $this->_oModule->_oConfig->getObject('form_display_post_add');
        $this->_bPublicMode = isset($this->aParams['display']) && $this->aParams['display'] == $this->_oModule->_oConfig->getObject('form_display_post_add_public');
        $this->_bProfileMode = isset($this->aParams['display']) && $this->aParams['display'] == $this->_oModule->_oConfig->getObject('form_display_post_add_profile');

        $this->_bVisibilityAutoselect = true;
    }

    public function getCode($bDynamicMode = false)
    {
    	$sResult = parent::getCode($bDynamicMode);

    	return $this->_oModule->_oTemplate->parseHtmlByContent($sResult, array(
    		'attachments_menu' => $this->_oModule->getAttachmentsMenuObject()->getCode()
    	));
    }

    function initChecker($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

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

        $aValsToAdd[$CNF['FIELD_OBJECT_ID']] *= isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && $this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1;

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

    public function addHtmlEditor($iViewMode, &$aInput)
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

        $this->_sCodeAdd .= $oEditor->attachEditor ('#' . $this->aFormAttrs['id'] . ' [name='.$aInput['name'].']', $iViewMode, $this->_bDynamicMode);

        return true;
    }

    public function init()
    {
        $iUserId = $this->_oModule->getUserId();
        $iOwnerId = $this->_oModule->getOwnerId();

        $this->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'post/';

        $this->aInputs['owner_id']['value'] = $iOwnerId;

        if(isset($this->aInputs['text'])) {
            if(empty($this->aInputs['text']['attrs']) || !is_array($this->aInputs['text']['attrs']))
                $this->aInputs['text']['attrs'] = array();

            $this->aInputs['text']['attrs']['id'] = $this->_oModule->_oConfig->getHtmlIds('post', 'textarea') . time();
        }

        if(isset($this->aInputs['object_privacy_view']))
            $this->aInputs['object_privacy_view'] = $this->_updateFieldObjectPrivacyView($this->aInputs['object_privacy_view']);

        if(isset($this->aInputs['link']))
            $this->aInputs['link']['content'] = $this->_oModule->_oTemplate->getAttachLinkField($iUserId);

        if(isset($this->aInputs['photo'])) {
            $aFormNested = array(
                'params' =>array(
                    'nested_form_template' => 'uploader_nfw.html'
                ),
                'inputs' => array(),
            );

            $oFormNested = new BxDolFormNested('photo', $aFormNested, 'tlb_do_submit', $this->_oModule->_oTemplate);

            $this->aInputs['photo']['storage_object'] = $this->_oModule->_oConfig->getObject('storage_photos');
            $this->aInputs['photo']['images_transcoder'] = $this->_oModule->_oConfig->getObject('transcoder_photos_preview');
            $this->aInputs['photo']['uploaders'] = !empty($this->aInputs['photo']['value']) ? unserialize($this->aInputs['photo']['value']) : $this->_oModule->_oConfig->getUploaders('photo');
            $this->aInputs['photo']['upload_buttons_titles'] = array('Simple' => 'camera');
            $this->aInputs['photo']['multiple'] = true;
            $this->aInputs['photo']['ghost_template'] = $oFormNested;
        }

        if(isset($this->aInputs['video'])) {
            $aFormNested = array(
                'params' =>array(
                    'nested_form_template' => 'uploader_nfw.html'
                ),
                'inputs' => array(),
            );

            $oFormNested = new BxDolFormNested('video', $aFormNested, 'tlb_do_submit', $this->_oModule->_oTemplate);

            $this->aInputs['video']['storage_object'] = $this->_oModule->_oConfig->getObject('storage_videos');
            $this->aInputs['video']['images_transcoder'] = $this->_oModule->_oConfig->getObject('transcoder_videos_poster');
            $this->aInputs['video']['uploaders'] = !empty($this->aInputs['video']['value']) ? unserialize($this->aInputs['video']['value']) : $this->_oModule->_oConfig->getUploaders('video');
            $this->aInputs['video']['upload_buttons_titles'] = array('Simple' => 'video');
            $this->aInputs['video']['multiple'] = true;
            $this->aInputs['video']['ghost_template'] = $oFormNested;
        }

        if(isset($this->aInputs['attachments']))
            $this->aInputs['attachments']['content'] = '__attachments_menu__';

        if (isset($this->_oModule->_oConfig->CNF['FIELD_LOCATION_PREFIX']) && isset($this->aInputs[$this->_oModule->_oConfig->CNF['FIELD_LOCATION_PREFIX']]))
            $this->aInputs[$this->_oModule->_oConfig->CNF['FIELD_LOCATION_PREFIX']]['manual_input'] = false;
    }

    public function setVisibilityAutoselect($bVisibilityAutoselect)
    {
        $this->_bVisibilityAutoselect = $bVisibilityAutoselect;
    }

    protected function _updateFieldObjectPrivacyView($aInput)
    {
    	$iUserId = $this->_oModule->getUserId();
        $iOwnerId = $this->_oModule->getOwnerId();

        $aInput = array_merge($aInput, BxDolPrivacy::getGroupChooser($this->_oModule->_oConfig->getObject('privacy_view'), 0, array(
            'title' => _t('_bx_timeline_form_post_input_object_privacy_view')
        )));

        //--- Show field as is when 'Autoselect' is disabled.
        if(!$this->_bVisibilityAutoselect)
            return $aInput;

        //--- Show default privacy groups and followed contexts on Account (Profile + Connections) post form.
        if($this->_bAccountMode)
            return $aInput;

        //--- Preselect Context and hide privacy selector when posting in some context (profile, group, space, etc).
        if($this->_bProfileMode && $iOwnerId != $iUserId) {
            $aInput['type'] = 'hidden';
	    $aInput['value'] = -$iOwnerId;

            return $aInput;
        }

        $bProfileModeOwner = $this->_bProfileMode && $iOwnerId == $iUserId;
        if($this->_bPublicMode || $bProfileModeOwner) {
            $iGc = 0;
            $iKeyGh = false;
            foreach($aInput['values'] as $iKey => $aValue) {
                if(isset($aValue['type']) && in_array($aValue['type'], array('group_header', 'group_end'))) {
                    if($iKeyGh !== false && $iGc == 0) {
                        unset($aInput['values'][$iKeyGh]);
                        $iKeyGh = false;

                        if($aValue['type'] == 'group_end')
                            unset($aInput['values'][$iKey]);
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

                unset($aInput['values'][$iKey]);
            }
        }

        return $aInput;
    }
}

/** @} */
