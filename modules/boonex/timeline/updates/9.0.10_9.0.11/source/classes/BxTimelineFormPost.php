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

        if(isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && isset($aValues[$CNF['FIELD_OBJECT_ID']]))
            $this->aInputs[$CNF['FIELD_ANONYMOUS']]['checked'] = $aValues[$CNF['FIELD_OBJECT_ID']] < 0;

        parent::initChecker ($aValues, $aSpecificValues);
    }

    public function insert($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aValsToAdd[$CNF['FIELD_OBJECT_ID']] *= isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && $this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1;

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']])) {
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

            $aValsToAdd[$CNF['FIELD_OBJECT_ID']] = ($this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * abs($aContentInfo[$CNF['FIELD_OBJECT_ID']]);
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

        if($this->_bPublicMode || $this->_bProfileMode)
            foreach($aInput['values'] as $iKey => $aValue) {
                    //--- Show 'Public' privacy group only in Public post form. 
                    if($this->_bPublicMode && isset($aValue['key']) && $aValue['key'] == BX_DOL_PG_ALL)
                            continue;

                    //--- Show a default privacy groups in Profile (for Owner) post form.
                    if($this->_bProfileMode && $iOwnerId == $iUserId && isset($aValue['key']) && (int)$aValue['key'] >= 0)
                            continue;

                    unset($aInput['values'][$iKey]);
            }

        return $aInput;
    }
}

/** @} */
