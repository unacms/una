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

    public function __construct($aInfo, $oTemplate = false)
    {
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

        if (isset($CNF['FIELD_ALLOW_VIEW_TO']) && isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]) && $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) {

            $aSave = array('db' => array('pass' => 'Xss'));
            array_walk($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']], function ($a, $k, $aSave) {
                if (in_array($k, array('info', 'caption', 'value')))
                    $aSave[0][$k] = $a;
            }, array(&$aSave));
            
            $aGroupChooser = $oPrivacy->getGroupChooser($CNF['OBJECT_PRIVACY_VIEW']);
            
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']] = array_merge($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']], $aGroupChooser, $aSave);
        }        
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

        if (isset($CNF['FIELD_LABELS']) && isset($this->aInputs[$CNF['FIELD_LABELS']]) && !empty($aValues['id']) && ($oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])) && $oMetatags->keywordsIsEnabled() && ($aLabels = $oMetatags->keywordsGet($aValues['id']))) {
            $this->aInputs[$CNF['FIELD_LABELS']]['content_id'] = $aValues['id'];
            $this->aInputs[$CNF['FIELD_LABELS']]['meta_object'] = $CNF['OBJECT_METATAGS'];
            $this->aInputs[$CNF['FIELD_LABELS']]['value'] = array_intersect($aLabels, BxDolLabel::getInstance()->getLabels(array('type' => 'values')));
        }

        if (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && isset($CNF['FIELD_AUTHOR']) && isset($aValues[$CNF['FIELD_AUTHOR']])) {
            $this->aInputs[$CNF['FIELD_ANONYMOUS']]['checked'] = $aValues[$CNF['FIELD_AUTHOR']] < 0;
        }

        if (isset($CNF['FIELD_ALLOW_VIEW_TO']) && isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]) && $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) {
            $iContentId = !empty($aValues[$CNF['FIELD_ID']]) ? (int)$aValues[$CNF['FIELD_ID']] : 0;
            $iProfileId = !empty($iContentId) ? (int)$this->getContentOwnerProfileId($iContentId) : bx_get_logged_profile_id();
            $iGroupId = !empty($aValues[$CNF['FIELD_ALLOW_VIEW_TO']]) ? $aValues[$CNF['FIELD_ALLOW_VIEW_TO']] : 0;

            $sKey = $CNF['FIELD_ALLOW_VIEW_TO'];
            if(!isset($this->aInputs[$sKey]['content']))
                $this->aInputs[$sKey]['content'] = '';

            $this->aInputs[$sKey]['content'] .= $oPrivacy->loadGroupCustom($iProfileId, $iContentId, $iGroupId, array(
                'form' => $this->getId()
            ));
        }

        parent::initChecker ($aValues, $aSpecificValues);
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($CNF['FIELD_AUTHOR']) && empty($aValsToAdd[$CNF['FIELD_AUTHOR']]))
            $aValsToAdd[$CNF['FIELD_AUTHOR']] = (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && $this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * bx_get_logged_profile_id ();

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

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        if(isset($CNF['FIELD_AUTHOR']) && isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]))
            $aValsToAdd[$CNF['FIELD_AUTHOR']] = ($this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * abs($aContentInfo[$CNF['FIELD_AUTHOR']]);

        if(isset($CNF['FIELD_CHANGED']) && empty($aValsToAdd[$CNF['FIELD_CHANGED']]) && empty($this->getCleanValue($CNF['FIELD_CHANGED'])))
            $aValsToAdd[$CNF['FIELD_CHANGED']] = time();

        if(CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb($iContentId) && isset($CNF['FIELD_THUMB'])) {
            $aThumb = bx_process_input (bx_get($CNF['FIELD_THUMB']), BX_DATA_INT);
            $aValsToAdd[$CNF['FIELD_THUMB']] = 0;
            if (!empty($aThumb) && is_array($aThumb) && ($iFileThumb = array_pop($aThumb)))
                $aValsToAdd[$CNF['FIELD_THUMB']] = $iFileThumb;
        }

        if(isset($CNF['FIELD_STATUS']) && isset($aContentInfo[$CNF['FIELD_STATUS']]) && $aContentInfo[$CNF['FIELD_STATUS']] == 'failed')
            $aValsToAdd[$CNF['FIELD_STATUS']] = 'active';

        $mixedResult = parent::update($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
        if($mixedResult !== false)
            $this->_processTrackFields($iContentId);

        return $mixedResult;
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

        if (!empty($CNF['OBJECT_SCORES'])) {
            $o = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_COMMENTS'])) {
            $o = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_METATAGS'])) {
            $o = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            if ($o) $o->onDeleteContent($iContentId);
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
}

/** @} */
