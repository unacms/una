<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reviews Reviews
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxReviewsFormEntry extends BxBaseModTextFormEntry
{
    protected $_sGhostTemplateCover = 'form_ghost_template_cover.html';
	
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_reviews';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

    	if(isset($CNF['FIELD_COVER']) && isset($this->aInputs[$CNF['FIELD_COVER']])) {
            if($this->_oModule->checkAllowedSetThumb() === CHECK_ACTION_RESULT_ALLOWED) {
                $this->aInputs[$CNF['FIELD_COVER']]['storage_object'] = $CNF['OBJECT_STORAGE'];
                $this->aInputs[$CNF['FIELD_COVER']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_COVER']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_COVER']]['value']) : $CNF['OBJECT_UPLOADERS'];
                $this->aInputs[$CNF['FIELD_COVER']]['upload_buttons_titles'] = array(
                    'Simple' => _t('_bx_reviews_form_entry_input_covers_uploader_simple_title'), 
                    'HTML5' => _t('_bx_reviews_form_entry_input_covers_uploader_html5_title')
                );
                $this->aInputs[$CNF['FIELD_COVER']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW'];
                $this->aInputs[$CNF['FIELD_COVER']]['storage_private'] = 0;
                $this->aInputs[$CNF['FIELD_COVER']]['multiple'] = false;
                $this->aInputs[$CNF['FIELD_COVER']]['content_id'] = 0;
                $this->aInputs[$CNF['FIELD_COVER']]['ghost_template'] = '';
            }
            else
                unset($this->aInputs[$CNF['FIELD_COVER']]);
        }

        if(isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_object'] = $CNF['OBJECT_STORAGE_PHOTOS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_PHOTO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_PHOTO']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS'];
            $this->aInputs[$CNF['FIELD_PHOTO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_PHOTO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_PHOTO']]['ghost_template'] = '';
            $this->aInputs[$CNF['FIELD_PHOTO']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }

        if(isset($this->aInputs[$CNF['FIELD_VIDEO']])) {
            $this->aInputs[$CNF['FIELD_VIDEO']]['storage_object'] = $CNF['OBJECT_STORAGE_VIDEOS'];
            $this->aInputs[$CNF['FIELD_VIDEO']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_VIDEO']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_VIDEO']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_VIDEO']]['images_transcoder'] = $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster_preview'];
            $this->aInputs[$CNF['FIELD_VIDEO']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEO']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_VIDEO']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_VIDEO']]['ghost_template'] = '';
            $this->aInputs[$CNF['FIELD_VIDEO']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }

        if (isset($CNF['FIELD_FILE']) && isset($this->aInputs[$CNF['FIELD_FILE']])) {
            $this->aInputs[$CNF['FIELD_FILE']]['storage_object'] = $CNF['OBJECT_STORAGE_FILES'];
            $this->aInputs[$CNF['FIELD_FILE']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_FILE']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_FILE']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_FILE']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_FILES'];
            $this->aInputs[$CNF['FIELD_FILE']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_FILE']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['ghost_template'] = '';
            $this->aInputs[$CNF['FIELD_FILE']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }

        if(isset($this->aInputs[$CNF['FIELD_POLL']])) {
            $this->aInputs[$CNF['FIELD_POLL']]['tr_attrs'] = array('class'=> 'bx-base-text-attachment-item');
        }

        if ($this->_oModule->_oDb->getParam($CNF['PARAM_CONTEXT_CONTROL_ENABLE'])) {
            unset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]);
        } else {
            unset($this->aInputs[$CNF['FIELD_REVIEWED_PROFILE']]);
        }
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bValues = $aValues && !empty($aValues['id']);
        $aContentInfo = $bValues ? $this->_oModule->_oDb->getContentInfoById($aValues['id']) : false;

        if (isset($CNF['FIELD_COVER']) && isset($this->aInputs[$CNF['FIELD_COVER']])) {
            if($bValues)
                $this->aInputs[$CNF['FIELD_COVER']]['content_id'] = $aValues['id'];

            $this->aInputs[$CNF['FIELD_COVER']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName($this->_sGhostTemplateCover, $this->_getCoverGhostTmplVars($aContentInfo));
        }

        parent::initChecker ($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($CNF['FIELD_ADDED']) && empty($aValsToAdd[$CNF['FIELD_ADDED']])) {
            $iAdded = 0;
            if(isset($this->aInputs[$CNF['FIELD_ADDED']]))
                $iAdded = $this->getCleanValue($CNF['FIELD_ADDED']);
            
            if(empty($iAdded))
                 $iAdded = time();

            $aValsToAdd[$CNF['FIELD_ADDED']] = $iAdded;
        }

        if (($iReviewFor = $this->safeCustomPostToContext()))
            $aValsToAdd[$CNF['FIELD_ALLOW_VIEW_TO']] = $iReviewFor;

        $this->calcAvgVoting($aValsToAdd);

        $aValsToAdd[$CNF['FIELD_STATUS']] = 'active';

        $iContentId = parent::insert ($aValsToAdd, $isIgnore);
        if(!empty($iContentId)){
            $this->processFiles($CNF['FIELD_COVER'], $iContentId, true);
        }
        return $iContentId;
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (($iReviewFor = $this->safeCustomPostToContext()))
            $aValsToAdd[$CNF['FIELD_ALLOW_VIEW_TO']] = $iReviewFor;

        $this->calcAvgVoting($aValsToAdd);
        
        $iResult = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
        $this->processFiles($CNF['FIELD_COVER'], $iContentId, false);   
        return $iResult;
    }

    protected function calcAvgVoting(&$aValsToAdd) {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_VOTING_OPTIONS']]) && !bx_is_empty_array($this->aInputs[$CNF['FIELD_VOTING_OPTIONS']]['value'])) {
            $iCount = 0;
            $iSum = 0;
            foreach ($this->aInputs[$CNF['FIELD_VOTING_OPTIONS']]['value'] as $iRating) {
                if ($iRating) {
                    $iSum += $iRating;
                    $iCount++;
                }
            }
            if (!$iCount) return '';
            $fRate = $iSum/$iCount;

            //need to scale it to 5 stars only
            $iMaxValue = $this->_oModule->_oDb->getParam($CNF['PARAM_MAX_STARS']);
            $fRate /= $iMaxValue / 5;

            $aValsToAdd[$CNF['FIELD_VOTING_AVG']] = $fRate;
        }
    }

    protected function safeCustomPostToContext() {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if ($this->_oModule->_oDb->getParam($CNF['PARAM_CONTEXT_CONTROL_ENABLE'])) {
            $mixedFieldValue = $this->getCleanValue($CNF['FIELD_REVIEWED_PROFILE']);
            $iProfile = is_array($mixedFieldValue) ? intval($mixedFieldValue[0]) : intval($mixedFieldValue);
            if (!$iProfile)
                return BX_DOL_PG_ALL;

            $oProfile = BxDolProfile::getInstance($iProfile);

            $sModules = $this->_oModule->_oDb->getParam($CNF['PARAM_CONTEXT_MODULES_AVAILABLE']);
            $aModulesList = explode(',', $sModules);
            if (!in_array($oProfile->getModule(), $aModulesList))
                return BX_DOL_PG_ALL;

            return -$iProfile;
        } else return false;
    }

    protected function _getCoverGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
            'name' => $this->aInputs[$CNF['FIELD_COVER']]['name'],
            'content_id' => $this->aInputs[$CNF['FIELD_COVER']]['content_id'],
            'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : '',
            'thumb_id' => isset($CNF['FIELD_THUMB']) && isset($aContentInfo[$CNF['FIELD_THUMB']]) ? $aContentInfo[$CNF['FIELD_THUMB']] : 0,
            'name_thumb' => isset($CNF['FIELD_THUMB']) ? $CNF['FIELD_THUMB'] : ''
        );
    }

    protected function _getPhotoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
            'name' => $this->aInputs[$CNF['FIELD_PHOTO']]['name'],
            'content_id' => (int)$this->aInputs[$CNF['FIELD_PHOTO']]['content_id'],
            'editor_id' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : ''
    	);
    }

    protected function genCustomInputVotingOptions ($aInput)
    {
        return $this->_oModule->_oTemplate->getMultiVoting(isset($aInput['value']) ? $aInput['value'] : array(), true);
    }

    protected function genCustomInputReviewedProfile ($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'suggest_profile_for_review/';
        $aInput['custom']['only_once'] = true;
        if (isset($aInput['value']) && !is_array($aInput['value'])) $aInput['value'] = [$aInput['value']];
        return $this->genCustomInputUsernamesSuggestions($aInput);
    }

    protected function genCustomViewRowValueProduct($aInput) {
        if (empty($aInput['value']))
            return null;

        $sProduct = bx_process_output($aInput['value']);
        $sProductUrlEncoded = $aInput['value'];
        $sProductSearchUrl = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->CNF['URI_SEARCH_PRODUCT'];
        $sProductSearchUrl = bx_append_url_params($sProductSearchUrl, ['keyword' => $sProductUrlEncoded]);
        return '<a href="'.$sProductSearchUrl.'">'.$sProduct.'</a>';
    }

    protected function genCustomViewRowValueReviewedProfile($aInput) {
        $oProfile = BxDolProfile::getInstance($aInput['value']);
        if (!$oProfile)
            return null;

        return '<a href="'.$oProfile->getUrl().'">'.$oProfile->getDisplayName().'</a>';
    }
}

class BxReviewsFormEntryCheckerHelper extends BxDolFormCheckerHelper {
    public function passVotingOptions ($s) {
        if (!is_array($s))
            return false;
        return serialize($s);
    }

    public function passOneIntArray ($s) {
        if (is_array($s)) return intval($s[0]);
        else return intval($s);
    }

    public function displayVotingOptions ($s) {
        return unserialize($s);
    }
}

/** @} */
