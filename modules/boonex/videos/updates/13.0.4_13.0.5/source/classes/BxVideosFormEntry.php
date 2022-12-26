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

        if(!empty($CNF['FIELD_POSTER']) && $this->_oModule->checkAllowedSetThumb() === CHECK_ACTION_RESULT_ALLOWED) {
            $aPoster = isset($_POST[$CNF['FIELD_POSTER']]) ? bx_process_input ($_POST[$CNF['FIELD_POSTER']], BX_DATA_INT) : false;

            $aValsToAdd[$CNF['FIELD_POSTER']] = 0;
            if(!empty($aPoster) && is_array($aPoster) && ($iFilePoster = array_pop($aPoster)))
                $aValsToAdd[$CNF['FIELD_POSTER']] = $iFilePoster;
        }

        if (bx_get('video_source') == 'embed') {
            $aEmbedData = $this->_oModule->parseEmbedLink($this->getCleanValue('video_embed'));
            if ($aEmbedData)
                $aValsToAdd['video_embed_data'] = serialize($aEmbedData);

            //if poster is not set then set it implicitly by using the embed poster link
            if (!bx_get($CNF['FIELD_THUMB']) && $aEmbedData && $aEmbedData['thumb']) {
                $oPhotosStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
                $iFile = $oPhotosStorage->storeFileFromUrl($aEmbedData['thumb'], false, bx_get_logged_profile_id());
                bx_set($CNF['FIELD_THUMB'], [$iFile], 'get');
                bx_set($CNF['FIELD_THUMB'], [$iFile], 'post');
                bx_set($CNF['FIELD_PHOTO'], [$iFile], 'get');
                bx_set($CNF['FIELD_PHOTO'], [$iFile], 'post');
            }
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

        if(!empty($CNF['FIELD_POSTER']) && $this->_oModule->checkAllowedSetThumb($iContentId) === CHECK_ACTION_RESULT_ALLOWED && isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $aPoster = bx_process_input (bx_get($CNF['FIELD_POSTER']), BX_DATA_INT);

            $aValsToAdd[$CNF['FIELD_POSTER']] = 0;
            if(!empty($aPoster) && is_array($aPoster) && ($iFilePoster = array_pop($aPoster)))
                $aValsToAdd[$CNF['FIELD_POSTER']] = $iFilePoster;
        }

        if (bx_get('video_source') == 'embed') {
            $aValsToAdd[$CNF['FIELD_STATUS']] = 'active'; //no need to keep awaiting if we've switched to embed
            $aEmbedData = $this->_oModule->parseEmbedLink($this->getCleanValue('video_embed'));
            if ($aEmbedData)
                $aValsToAdd['video_embed_data'] = serialize($aEmbedData);

            //if poster is not set then set it implicitly by using the embed poster link
            if (!bx_get($CNF['FIELD_THUMB']) && $aEmbedData && $aEmbedData['thumb']) {
                $oPhotosStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
                $iFile = $oPhotosStorage->storeFileFromUrl($aEmbedData['thumb'], false, bx_get_logged_profile_id(), $iContentId);
                bx_set($CNF['FIELD_THUMB'], [$iFile], 'get');
                bx_set($CNF['FIELD_THUMB'], [$iFile], 'post');
                bx_set($CNF['FIELD_PHOTO'], [$iFile], 'get');
                bx_set($CNF['FIELD_PHOTO'], [$iFile], 'post');
            }
        } else {
            $aValsToAdd['video_embed_data'] = '';
            $aValsToAdd['video_embed'] = '';
        }

        $iRet = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
        $this->processFiles($CNF['FIELD_VIDEOS'], $iContentId, false);


        return $iRet;
    }

    public function processFiles ($sFieldFile, $iContentId = 0, $isAssociateWithContent = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if ($sFieldFile == $CNF['FIELD_VIDEOS'] && bx_get('video_source') == 'embed') {
            // delete video files in case the embed link has been provided
            if ($iContentId) {
                if (!isset($this->aInputs[$sFieldFile]))
                    return true;

                $mixedFileIds = $this->getCleanValue($sFieldFile);
                if (!$mixedFileIds)
                    return true;

                $oStorage = BxDolStorage::getObjectInstance($this->aInputs[$sFieldFile]['storage_object']);
                if (!$oStorage)
                    return false;

                $iProfileId = $this->getContentOwnerProfileId($iContentId);

                $aGhostFiles = $oStorage->getGhosts($iProfileId, $isAssociateWithContent ? 0 : $iContentId, true, $this->_isAdmin($iContentId));
                if (!$aGhostFiles)
                    return true;

                foreach ($aGhostFiles as $aFile) {
                    if (is_array($mixedFileIds) && !in_array($aFile['id'], $mixedFileIds))
                        continue;
                    $oStorage->deleteFile($aFile['id'], bx_get_logged_profile_id());
                }
            }
        } else {
            return parent::processFiles($sFieldFile, $iContentId, $isAssociateWithContent);
        }
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
        if (!$aInput['value']) return null;
        return _t_format_duration($aInput['value']);
    }

    protected function _getPhotoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = parent::_getPhotoGhostTmplVars($aContentInfo);

		$bPoster = !empty($CNF['FIELD_POSTER']) && $this->_oModule->checkAllowedSetThumb($this->aInputs[$CNF['FIELD_PHOTO']]['content_id']) === CHECK_ACTION_RESULT_ALLOWED;

		$aTmplVarsSetPoster = array();
		$aTmplVarsInitPoster = array();
		if($bPoster) {
			$aTmplVarsSetPoster = array (
				'name_poster' => $CNF['FIELD_POSTER'],
				'txt_pict_use_as_poster' => _t(!empty($CNF['T']['txt_pict_use_as_poster']) ? $CNF['T']['txt_pict_use_as_poster'] : '_sys_txt_form_entry_input_picture_use_as_thumb')
			);
		
			$aTmplVarsInitPoster = array(
				'poster_id' => isset($aContentInfo[$CNF['FIELD_POSTER']]) ? $aContentInfo[$CNF['FIELD_POSTER']] : 0
			);
		}

		$aResult = array_merge($aResult, array(
			'bx_if:set_poster' => array (
				'condition' => $bPoster,
				'content' => $aTmplVarsSetPoster
			),
			'bx_if:init_poster' => array(
				'condition' => $bPoster,
				'content' => $aTmplVarsInitPoster
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

    public function genCustomRowVideoEmbed(&$aInput) {
        $sSource = isset($this->aInputs['video_source']) && $this->aInputs['video_source']['value'] == 'embed' ? 'embed' : 'upload';
        if ($sSource != 'embed') {
            if (!is_array($aInput['tr_attrs']))
                $aInput['tr_attrs'] = [];
            $aInput['tr_attrs']['style'] = 'display:none';
        }
        return $this->genRowStandard($aInput);
    }

    public function genCustomRowVideos(&$aInput) {
        $sSource = isset($this->aInputs['video_source']) && $this->aInputs['video_source']['value'] == 'embed' ? 'embed' : 'upload';
        if ($sSource != 'upload') $aInput['tr_attrs']['style'] = 'display:none';
        return $this->genRowCustom($aInput, 'genInputFiles');
    }

    public function genCustomInputVideoEmbed(&$aInput) {
        $aEmbedInput = [
            'type' => 'text',
            'name' => $aInput['name'],
            'value' => isset($aInput['value']) ? $aInput['value'] : '',
            'attrs' => [
                'id' => 'bx-video-embed-link',
                'onchange' => $this->_oModule->_oConfig->getJsObject('embeds').'.onNewEmbedCode()',
                'onpaste' => $this->_oModule->_oConfig->getJsObject('embeds').'.onNewEmbedCodeTyping(100)', //the reason is that on this event the value has not been update yet
                'onkeydown' => $this->_oModule->_oConfig->getJsObject('embeds').'.onNewEmbedCodeTyping(500)',
            ],
        ];

        $sEmbedCode = '';
        if (isset($aInput['value']) && !empty($aInput['value']) && $aEmbed = $this->_oModule->parseEmbedLink($aInput['value'])) {
            $sEmbedCode = $aEmbed['embed'];
        }

        return $this->_oModule->_oTemplate->parseHtmlByName('form_field_embed.html', [
            'input' => $this->genInputStandard($aEmbedInput),
            'bx_if:noembed_code' => [
                'condition' => empty($sEmbedCode),
                'content' => [],
            ],
            'embed_code' => $sEmbedCode,
        ]);
    }

    public function genInput(&$aInput) {
        $sJsCode = '';

        if ($aInput['name'] == 'video_source' && $aInput['type'] == 'radio_set') {
            if (!is_array($aInput['attrs']))
                $aInput['attrs'] = [];
            $aInput['attrs']['onchange'] = $this->_oModule->_oConfig->getJsObject('embeds').'.changeVideoSource(this);';
            $sJsCode = '';
            if ($this->_bDynamicMode) {
                $sJsCode .= $this->_oModule->_oTemplate->addJs('embeds.js', true);
                $sJsCode .= $this->_oModule->_oTemplate->addCss('embeds.css', true);
            } else {
                $this->_oModule->_oTemplate->addJs('embeds.js', false);
                $this->_oModule->_oTemplate->addCss('embeds.css', false);
            }

            $sJsCode .= $this->_oModule->_oTemplate->getJsCode('embeds');
        }

        return parent::genInput($aInput).$sJsCode;
    }
}

class BxVideosFormCheckerHelper extends BxDolFormCheckerHelper {
    static public function checkUploadVideoAvail ($s)
    {
        $sSource = bx_get('video_source');
        if ($sSource != 'embed') $sSource = 'upload';

        if ($sSource == 'embed') return true; //if we are embedding then skip uploads check

        return parent::checkAvail($s);
    }
    static public function checkEmbedVideoAvail ($s)
    {
        $sSource = bx_get('video_source');
        if ($sSource != 'embed') $sSource = 'upload';

        if ($sSource == 'upload') return true; //if we are uploading then skip embed check

        if (!parent::checkAvail($s)) return false;

        $oModule = BxDolModule::getInstance('bx_videos');
        if (!$oModule->parseEmbedLink($s)) return false;

        return true;
    }
}

/** @} */
