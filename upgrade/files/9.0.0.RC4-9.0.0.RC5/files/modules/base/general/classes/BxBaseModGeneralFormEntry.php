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
    protected $MODULE;

    protected $_oModule;

    protected $_aMetatagsFieldsWithKeywords = array();
    protected $_oMetatagsObject = null;
    protected $_oMetatagsContentId = 0;

    protected $_sGhostTemplate = 'form_ghost_template.html';

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if (isset($CNF['FIELD_ADDED']) && isset($this->aInputs[$CNF['FIELD_ADDED']])) {
            $this->aInputs[$CNF['FIELD_ADDED']]['date_filter'] = BX_DATA_INT;
            $this->aInputs[$CNF['FIELD_ADDED']]['date_format'] = BX_FORMAT_DATE;
        }

        if (isset($CNF['FIELD_CHANGED']) && isset($this->aInputs[$CNF['FIELD_CHANGED']])) {
            $this->aInputs[$CNF['FIELD_CHANGED']]['date_filter'] = BX_DATA_INT;
            $this->aInputs[$CNF['FIELD_CHANGED']]['date_format'] = BX_FORMAT_DATE;
        }

        if (isset($this->aInputs[$CNF['FIELD_TEXT']]) && isset($CNF['FIELD_TEXT_ID'])) {
            $this->aInputs[$CNF['FIELD_TEXT']]['attrs'] = array_merge (
                array ('id' => $CNF['FIELD_TEXT_ID']),
                is_array($this->aInputs[$CNF['FIELD_TEXT']]['attrs']) ? $this->aInputs[$CNF['FIELD_TEXT']]['attrs'] : array ()
            );
        }
        
        if (isset($CNF['FIELD_LOCATION_PREFIX']) && isset($this->aInputs[$CNF['FIELD_LOCATION_PREFIX']])) {
            $this->aInputs[$CNF['FIELD_LOCATION_PREFIX']]['manual_input'] = true;
        }

        if (isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_object'] = $CNF['OBJECT_STORAGE'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_PHOTO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_PHOTO']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = '';
        }
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
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
        
        parent::initChecker ($aValues, $aSpecificValues);
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($CNF['FIELD_AUTHOR']) && empty($aValsToAdd[$CNF['FIELD_AUTHOR']]))
            $aValsToAdd[$CNF['FIELD_AUTHOR']] = bx_get_logged_profile_id ();

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
        
        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($CNF['FIELD_CHANGED']) && empty($aValsToAdd[$CNF['FIELD_CHANGED']]) && empty($this->getCleanValue($CNF['FIELD_CHANGED'])))
            $aValsToAdd[$CNF['FIELD_CHANGED']] = time();

        if (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb($iContentId) && isset($CNF['FIELD_THUMB']) && isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $aThumb = bx_process_input (bx_get($CNF['FIELD_THUMB']), BX_DATA_INT);
            $aValsToAdd[$CNF['FIELD_THUMB']] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[$CNF['FIELD_THUMB']] = $iFileThumb;
        }
        
        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
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
			'content_id' => $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'],
			'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : '',
			'thumb_id' => isset($CNF['FIELD_THUMB']) && isset($aContentInfo[$CNF['FIELD_THUMB']]) ? $aContentInfo[$CNF['FIELD_THUMB']] : 0,
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

        if (!empty($CNF['OBJECT_STORAGE'])) {
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
            if ($oStorage)
                $oStorage->queueFilesForDeletionFromGhosts($aContentInfo[$CNF['FIELD_AUTHOR']], $iContentId);
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

        if (!empty($CNF['OBJECT_COMMENTS'])) {
            $o = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            $oMetatags->onDeleteContent($iContentId);
        }

        // delete db record

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
        if (!isset($this->aParams['view_mode']) || !$this->aParams['view_mode']) {
            if (self::$_isCssJsAdded)
                return;
            $this->_oModule->_oTemplate->addCss('forms.css');
            $this->_oModule->_oTemplate->addJs('modules/base/general/js/|forms.js');
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
}

/** @} */
