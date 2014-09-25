<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralFormEntry');

/**
 * Create/Edit entry form
 */
class BxBaseModTextFormEntry extends BxBaseModGeneralFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_TEXT']])) {
            $this->aInputs[$CNF['FIELD_TEXT']]['attrs'] = array_merge (
                array ('id' => $CNF['FIELD_TEXT_ID']),
                is_array($this->aInputs[$CNF['FIELD_TEXT']]['attrs']) ? $this->aInputs[$CNF['FIELD_TEXT']]['attrs'] : array ()
            );
        }

        if (isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_object'] = $CNF['OBJECT_STORAGE'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['uploaders'] = array('sys_simple', 'sys_html5');
            $this->aInputs[$CNF['FIELD_PHOTO']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = '';
        }

        if (isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']])) {
            bx_import('BxDolPrivacy');
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']] = BxDolPrivacy::getGroupChooser($CNF['OBJECT_PRIVACY_VIEW']);
        }
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_PHOTO']])) {

            $aContentInfo = false;
            if ($aValues && !empty($aValues['id'])) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($aValues['id']);
                $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = $aValues['id'];
            }

            $aVars = array (
                'name' => $this->aInputs[$CNF['FIELD_PHOTO']]['name'],
                'content_id' => $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'],
                'editor_id' => $CNF['FIELD_TEXT_ID'],
                'thumb_id' => isset($aContentInfo[$CNF['FIELD_THUMB']]) ? $aContentInfo[$CNF['FIELD_THUMB']] : 0,
                'bx_if:set_thumb' => array (
                    'condition' => CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb(),
                    'content' => array (
                        'name_thumb' => $CNF['FIELD_THUMB'],
                    ),
                ),
            );
            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName('form_ghost_template.html', $aVars);
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb()) {
            $aThumb = isset($_POST[$CNF['FIELD_THUMB']]) ? bx_process_input ($_POST[$CNF['FIELD_THUMB']], BX_DATA_INT) : false;
            $aValsToAdd[$CNF['FIELD_THUMB']] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[$CNF['FIELD_THUMB']] = $iFileThumb;
        }

        if ($iContentId = parent::insert ($aValsToAdd, $isIgnore))
            $this->_processFiles ($this->getCleanValue($CNF['FIELD_PHOTO']), $iContentId, true);
        return $iContentId;
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb()) {
            $aThumb = bx_process_input (bx_get($CNF['FIELD_THUMB']), BX_DATA_INT);
            $aValsToAdd[$CNF['FIELD_THUMB']] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[$CNF['FIELD_THUMB']] = $iFileThumb;
        }

        if ($iRet = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges))
            $this->_processFiles ($this->getCleanValue($CNF['FIELD_PHOTO']), $iContentId, true);
        return $iRet;
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // delete associated files

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if ($oStorage)
            $oStorage->queueFilesForDeletionFromGhosts($aContentInfo[$CNF['FIELD_AUTHOR']], $iContentId);

        // delete associated objects data

        bx_import('BxDolView');
        $o = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $iContentId);
        if ($o) $o->onObjectDelete();

        bx_import('BxDolVote');
        $o = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $iContentId);
        if ($o) $o->onObjectDelete();

        bx_import('BxDolCmts');
        $o = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
        if ($o) $o->onObjectDelete();

        // delete db record

        return parent::delete($iContentId);
    }

    function _processFiles ($mixedFileIds, $iContentId = 0, $isAssociateWithContent = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!$mixedFileIds)
            return true;

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
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

    function _deleteFile ($iFileId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!$iFileId)
            return true;

        bx_import('BxDolStorage');
        if (!($oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE'])))
            return false;

        if (!$oStorage->getFile($iFileId))
            return true;

        $iProfileId = bx_get_logged_profile_id();
        return $oStorage->deleteFile($iFileId, $iProfileId);
    }

    function addCssJs ()
    {
        if (!isset($this->aParams['view_mode']) || !$this->aParams['view_mode']) {
            if (self::$_isCssJsAdded)
                return;
            $this->_oModule->_oTemplate->addJs('modules/base/text/js/|forms.js');
        }

        return parent::addCssJs ();
    }

}

/** @} */
