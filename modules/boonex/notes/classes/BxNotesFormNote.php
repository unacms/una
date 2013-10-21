<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplFormView');

/**
 * Create/Edit Note Form.
 */
class BxNotesFormNote extends BxTemplFormView {

    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false) {                
        parent::__construct($aInfo, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_notes');

        if (isset($this->aInputs[BxNotesConfig::$FIELD_TEXT])) {            
            $this->aInputs[BxNotesConfig::$FIELD_TEXT]['attrs'] = array_merge (
                array ('id' => BxNotesConfig::$FIELD_TEXT_ID),
                is_array($this->aInputs[BxNotesConfig::$FIELD_TEXT]['attrs']) ? $this->aInputs[BxNotesConfig::$FIELD_TEXT]['attrs'] : array ()
            );
            
        }
        if (isset($this->aInputs[BxNotesConfig::$FIELD_SUMMARY])) {
            $this->aInputs[BxNotesConfig::$FIELD_SUMMARY]['attrs'] = array_merge (
                array ('id' => BxNotesConfig::$FIELD_SUMMARY_ID),
                is_array($this->aInputs[BxNotesConfig::$FIELD_SUMMARY]['attrs']) ? $this->aInputs[BxNotesConfig::$FIELD_SUMMARY]['attrs'] : array ()
            );
            
        }

        if (isset($this->aInputs[BxNotesConfig::$FIELD_PHOTO])) {
            $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['storage_object'] = BxNotesConfig::$OBJECT_STORAGE;
            $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['uploaders'] = array('sys_simple', 'sys_html5');
            $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['images_transcoder'] = BxNotesConfig::$OBJECT_IMAGES_TRANSCODER_PREVIEW;
            $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['multiple'] = true;
            $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['content_id'] = 0;
            //$this->aInputs[BxNotesConfig::$FIELD_PHOTO]['upload_buttons_titles'] = _t('_bx_notes_form_note_input_photo_btn_upload');
            $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['ghost_template'] = '';
        }

        if (isset($this->aInputs[BxNotesConfig::$FIELD_ALLOW_VIEW_TO])) {
            bx_import('BxDolPrivacy');
            $this->aInputs[BxNotesConfig::$FIELD_ALLOW_VIEW_TO] = BxDolPrivacy::getGroupChooser('bx_notes', 'view');
        }
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())  {

        if (isset($this->aInputs[BxNotesConfig::$FIELD_PHOTO])) {

            $aContentInfo = false;
            if ($aValues && !empty($aValues['id'])) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($aValues['id']);
                $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['content_id'] = $aValues['id'];
            }
            
            $aVars = array (
                'name' => $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['name'],
                'content_id' => $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['content_id'],
                'editor_id' => BxNotesConfig::$FIELD_TEXT_ID,
                'summary_id' => BxNotesConfig::$FIELD_SUMMARY_ID,
                'thumb_id' => $aContentInfo[BxNotesConfig::$FIELD_THUMB],
                'bx_if:set_thumb' => array (
                    'condition' => CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->isAllowedSetThumb(),
                    'content' => array (
                        'name_thumb' => BxNotesConfig::$FIELD_THUMB,
                    ),
                ),
            );
            $this->aInputs[BxNotesConfig::$FIELD_PHOTO]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName('form_ghost_template.html', $aVars);
        }
        
        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false) {

        $aValsToAdd[BxNotesConfig::$FIELD_AUTHOR] = bx_get_logged_profile_id ();
        $aValsToAdd[BxNotesConfig::$FIELD_ADDED] = time();
        $aValsToAdd[BxNotesConfig::$FIELD_CHANGED] = time();

        if (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->isAllowedSetThumb()) {
            $aThumb = isset($_POST[BxNotesConfig::$FIELD_THUMB]) ? bx_process_input ($_POST[BxNotesConfig::$FIELD_THUMB], BX_DATA_INT) : false;
            $aValsToAdd[BxNotesConfig::$FIELD_THUMB] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[BxNotesConfig::$FIELD_THUMB] = $iFileThumb;
        }

        if ($iContentId = parent::insert ($aValsToAdd, $isIgnore)) 
            $this->_processFiles ($this->getCleanValue(BxNotesConfig::$FIELD_PHOTO), $iContentId, true);
        return $iContentId;
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null) {

        $aValsToAdd[BxNotesConfig::$FIELD_CHANGED] = time();

        if (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->isAllowedSetThumb()) {
            $aThumb = bx_process_input ($_POST[BxNotesConfig::$FIELD_THUMB], BX_DATA_INT);
            $aValsToAdd[BxNotesConfig::$FIELD_THUMB] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[BxNotesConfig::$FIELD_THUMB] = $iFileThumb;
        }

        if ($iRet = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges)) 
            $this->_processFiles ($this->getCleanValue(BxNotesConfig::$FIELD_PHOTO), $iContentId, false);
        return $iRet;
    }

    function delete ($iContentId) {

        // delete associated files

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance(BxNotesConfig::$OBJECT_STORAGE);
        if (!$oStorage)
            return false;

        $aGhostFiles = $oStorage->getGhosts (bx_get_logged_profile_id(), $iContentId);
        if ($aGhostFiles)
            foreach ($aGhostFiles as $aFile)
                $this->_deleteFile($aFile['id']);

        // delete db record

        return parent::delete($iContentId);
    }

    function _processFiles ($mixedFileIds, $iContentId = 0, $isAssociateWithContent = false) {
        if (!$mixedFileIds)
            return true;

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance(BxNotesConfig::$OBJECT_STORAGE);
        if (!$oStorage)
            return false;

        $iProfileId = bx_get_logged_profile_id();

        $aGhostFiles = $oStorage->getGhosts ($iProfileId, $isAssociateWithContent ? 0 : $iContentId);
        if (!$aGhostFiles)
            return true;

        foreach ($aGhostFiles as $aFile) {
            if ($aFile['private'])
                $oStorage->setFilePrivate ($aFile['id'], 0);
            if ($isAssociateWithContent && $iContentId)
                $oStorage->updateGhostsContentId ($aFile['id'], $iProfileId, $iContentId);
        }

        return true;
    }

    function _deleteFile ($iFileId) {

        if (!$iFileId)
            return true;        

        bx_import('BxDolStorage');
        if (!($oStorage = BxDolStorage::getObjectInstance(BxNotesConfig::$OBJECT_STORAGE)))
            return false;

        if (!$oStorage->getFile($iFileId))
            return true;

        $iProfileId = bx_get_logged_profile_id(); 
        return $oStorage->deleteFile($iFileId, $iProfileId);
    }


    function addCssJs () {

        if (!isset($this->aParams['view_mode']) || !$this->aParams['view_mode']) {
            if (self::$_isCssJsAdded)
                return;
            $this->_oModule->_oTemplate->addJs('forms.js');
            $this->_oModule->_oTemplate->addCss('forms.css');
        }

        return parent::addCssJs ();
    }


}

/** @} */
