<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxVideosFormEntry extends BxBaseModTextFormEntry
{
    protected $_sGhostTemplateVideo = 'form_ghost_template_video.html';

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_videos';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_VIDEOS']])) {
            $this->aInputs[$CNF['FIELD_VIDEOS']]['storage_object'] = $CNF['OBJECT_STORAGE_VIDEOS'];
            $this->aInputs[$CNF['FIELD_VIDEOS']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_VIDEOS']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_VIDEOS']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_VIDEOS']]['upload_buttons_titles'] = _t('_bx_videos_form_entry_input_videos_upload');
            $this->aInputs[$CNF['FIELD_VIDEOS']]['images_transcoder'] = $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster_preview'];
            $this->aInputs[$CNF['FIELD_VIDEOS']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEOS']]['multiple'] = false;
            $this->aInputs[$CNF['FIELD_VIDEOS']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEOS']]['ghost_template'] = '';
        }
    }
    
    public function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_VIDEOS']])) {

            $aContentInfo = false;
            if ($aValues && !empty($aValues['id'])) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($aValues['id']);
                $this->aInputs[$CNF['FIELD_VIDEOS']]['content_id'] = $aValues['id'];
            }

            $this->aInputs[$CNF['FIELD_VIDEOS']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplateVideo, $this->_getVideoGhostTmplVars($aContentInfo));
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aValsToAdd[$CNF['FIELD_VIDEO']] = isset($_POST[$CNF['FIELD_VIDEO']]) ? bx_process_input($_POST[$CNF['FIELD_VIDEO']], BX_DATA_INT) : 0;

        if($this->_oModule->checkAllowedSetThumb() === CHECK_ACTION_RESULT_ALLOWED && isset($CNF['FIELD_POSTER'])) {
            $aPoster = isset($_POST[$CNF['FIELD_POSTER']]) ? bx_process_input ($_POST[$CNF['FIELD_POSTER']], BX_DATA_INT) : false;

            $aValsToAdd[$CNF['FIELD_POSTER']] = 0;
            if(!empty($aPoster) && is_array($aPoster) && ($iFilePoster = array_pop($aPoster)))
                $aValsToAdd[$CNF['FIELD_POSTER']] = $iFilePoster;
        }

        $iContentId = parent::insert ($aValsToAdd, $isIgnore);
        if(!empty($iContentId))
            $this->processFiles($CNF['FIELD_VIDEOS'], $iContentId, true);

        return $iContentId;
    }

    public function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aValsToAdd[$CNF['FIELD_VIDEO']] = isset($_POST[$CNF['FIELD_VIDEO']]) ? bx_process_input($_POST[$CNF['FIELD_VIDEO']], BX_DATA_INT) : 0;

        if($this->_oModule->checkAllowedSetThumb($iContentId) === CHECK_ACTION_RESULT_ALLOWED && isset($CNF['FIELD_POSTER']) && isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $aPoster = bx_process_input (bx_get($CNF['FIELD_POSTER']), BX_DATA_INT);

            $aValsToAdd[$CNF['FIELD_POSTER']] = 0;
            if(!empty($aPoster) && is_array($aPoster) && ($iFilePoster = array_pop($aPoster)))
                $aValsToAdd[$CNF['FIELD_POSTER']] = $iFilePoster;
        }

        $iRet = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        $this->processFiles($CNF['FIELD_VIDEOS'], $iContentId, false);

        return $iRet;
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

    	$bResult = parent::delete($iContentId, $aContentInfo);
        if(!$bResult)
			return $bResult;

        // delete associated files
        if (!empty($CNF['OBJECT_STORAGE_VIDEOS'])) {
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_VIDEOS']);
            if($oStorage)
                $oStorage->queueFilesForDeletionFromGhosts($aContentInfo[$CNF['FIELD_AUTHOR']], $iContentId);
        }

		return $bResult;
    }

    protected function genCustomViewRowValueDuration(&$aInput)
    {
        return _t_format_duration($aInput['value']);
    }

    protected function _getPhotoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = parent::_getPhotoGhostTmplVars($aContentInfo);
        $aResult = array_merge($aResult, array(
            'poster_id' => isset($CNF['FIELD_POSTER']) && isset($aContentInfo[$CNF['FIELD_POSTER']]) ? $aContentInfo[$CNF['FIELD_POSTER']] : 0,
        	'bx_if:set_poster' => array (
    			'condition' => CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetThumb($this->aInputs[$CNF['FIELD_PHOTO']]['content_id']),
    			'content' => array (
    				'name_poster' => isset($CNF['FIELD_POSTER']) ? $CNF['FIELD_POSTER'] : '',
    				'txt_pict_use_as_poster' => _t(!empty($CNF['T']['txt_pict_use_as_poster']) ? $CNF['T']['txt_pict_use_as_poster'] : '_sys_txt_form_entry_input_picture_use_as_thumb')
    			),
    		)
        ));

    	return $aResult;
    }

    protected function _getVideoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
			'name' => $this->aInputs[$CNF['FIELD_VIDEOS']]['name'],
    		'name_thumb' => isset($CNF['FIELD_VIDEO']) ? $CNF['FIELD_VIDEO'] : '',
			'content_id' => $this->aInputs[$CNF['FIELD_VIDEOS']]['content_id'],
		);
    }
}

/** @} */
