<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Profile based modules representation.
 */
class BxBaseModProfileTemplate extends BxBaseModGeneralTemplate
{
    protected $_sUnitClass;
    protected $_sUnitClassWithCover;
    protected $_sUnitClassWoInfo;
    
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->_sUnitClass = 'bx-base-pofile-unit';
        $this->_sUnitClassWithCover = 'bx-base-pofile-unit-with-cover';
        $this->_sUnitClassWoInfo = 'bx-base-pofile-unit-wo-info';
    }

    /**
     * Get profile unit
     */
    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html')
    {
        $aVars = $this->unitVars ($aData, $isCheckPrivateContent, $sTemplateName);

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html')
    {
        $CNF = &$this->_oConfig->CNF;

        $oModule = $this->getModule();

        $bPublic = true;
        if($isCheckPrivateContent && !empty($CNF['OBJECT_PRIVACY_VIEW'])) {
            $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
            if ($oPrivacy && !$oPrivacy->check($aData[$CNF['FIELD_ID']]) && !$oPrivacy->isPartiallyVisible($aData[$CNF['FIELD_ALLOW_VIEW_TO']]))
                $bPublic = false;
        }

        $bPublicThumb = true;
        if($isCheckPrivateContent && $oModule->checkAllowedViewProfileImage($aData) !== CHECK_ACTION_RESULT_ALLOWED)
            $bPublicThumb = false;

        $bPublicCover = true;
        if($isCheckPrivateContent && $oModule->checkAllowedViewCoverImage($aData) !== CHECK_ACTION_RESULT_ALLOWED)
            $bPublicCover = false;

        //$aVars = parent::unitVars ($aData, $isCheckPrivateContent, $sTemplateName);

        $oProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);
        $iProfile = $oProfile->id();

        // get profile's title
        $sTitle = bx_process_output($aData[$CNF['FIELD_NAME']]);

        // get profile's url
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        // connections object 
        $sConnectionsObject = isset($CNF['OBJECT_CONNECTIONS']) ? $CNF['OBJECT_CONNECTIONS'] : 'sys_profiles_friends';
        $oConn = BxDolConnection::getObjectInstance($sConnectionsObject);
        $aConnectionTitles = $oModule->serviceGetConnectionButtonsTitles($iProfile, $sConnectionsObject);

        $sThumbUrl = $bPublicThumb ? $this->thumb($aData, false) : '';
        $bThumbUrl = !empty($sThumbUrl);

        $sCoverUrl = $bPublicCover ? $this->urlCoverUnit($aData, false) : '';
        if(empty($sCoverUrl) && ($iCoverId = (int)getParam('sys_unit_cover_profile')) != 0)
            $sCoverUrl = BxDolTranscoder::getObjectInstance(BX_DOL_TRANSCODER_OBJ_COVER_UNIT_PROFILE)->getFileUrlById($iCoverId);
        if(empty($sCoverUrl))
            $sCoverUrl = $this->getImageUrl('cover.jpg');

        // generate html
        $aVars = array (
        	'class' => $this->_getUnitClass($aData, $sTemplateName),
            'id' => $aData[$CNF['FIELD_ID']],
            'bx_if:show_thumb_image' => array(
                'condition' => $bThumbUrl,
                'content' => array(
                    'thumb_url' => $sThumbUrl
                )
            ),
            'bx_if:show_thumb_letter' => array(
                'condition' => !$bThumbUrl,
                'content' => array(
                    'color' => implode(', ', BxDolTemplate::getColorCode($iProfile, 0.5)),
                    'letter' => mb_strtoupper(mb_substr($sTitle, 0, 1))
                )
            ),
            'thumb_url' => $bThumbUrl ? $sThumbUrl : $this->getImageUrl('no-picture-thumb.png'),
            'cover_url' => $sCoverUrl,
            'content_url' => $bPublic ? $sUrl : 'javascript:void(0);',
            'content_click' => !$bPublic ? 'javascript:bx_alert(' . bx_js_string('"' . _t('_sys_access_denied_to_private_content') . '"') . ');' : '',
            'title' => $sTitle,
            'module_name' => _t($CNF['T']['txt_sample_single']),
            'ts' => $aData[$CNF['FIELD_ADDED']],
            'bx_if:info' => array(
                'condition' => true,
                'content' => array (
                    'members' => $bPublic ? _t($CNF['T']['txt_N_fans'], $oConn ? $oConn->getConnectedInitiatorsCount($iProfile, true) : 0) : '&nbsp;',
                    'bx_if:btn' => array (
                        'condition' => isLogged() && !empty($aConnectionTitles['add']) && CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedFriendAdd($aData),
                        'content' => array (
                            'id' => $iProfile,
                            'title' => isset($aConnectionTitles['add']) ? $aConnectionTitles['add'] : '',
                            'object' => $sConnectionsObject,
                        ),
                    ),
                ),
            ),
        );

        return $aVars;
    }

    /**
     * Get profile cover
     */
    function setCover ($oPage, $aData, $sTemplateName = 'cover.html')
    {
        $CNF = &$this->_oConfig->CNF;

        if (CHECK_ACTION_RESULT_ALLOWED !== $this->getModule()->checkAllowedViewProfileImage($aData)) {
            $CNF = &$this->_oConfig->CNF;
            $aData[$CNF['FIELD_PICTURE']] = 0;
        }

        if (CHECK_ACTION_RESULT_ALLOWED !== $this->getModule()->checkAllowedViewCoverImage($aData)) {
            $CNF = &$this->_oConfig->CNF;
            $aData[$CNF['FIELD_COVER']] = 0;
        }

        $oProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);
        $iProfile = $oProfile->id();

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);
        $sTitle = bx_process_output($aData[$CNF['FIELD_NAME']]);

        $sUrlPicture = $this->urlPicture ($aData);
        $sUrlPictureChange = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        $sUrlAvatar = $this->urlAvatar($aData, false);
        $bUrlAvatar = !empty($sUrlAvatar);

        $sUrlCover = $this->urlCover ($aData, false);
        if(!$sUrlCover && $oPage !== false && $oPage->isPageCover()) {
            $aCover = $oPage->getPageCoverImage();
			if(!empty($aCover))
				$sUrlCover = BxDolCover::getCoverImageUrl($aCover);
        }

        if(!$sUrlCover)
            $sUrlCover = $this->getImageUrl('cover.jpg');
        
        $sUrlCoverChange = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_COVER'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        $sCoverPopup = '';
        $sCoverPopupId = $this->MODULE . '-popup-cover';
        if ($aData[$CNF['FIELD_COVER']]) {
            $sCoverPopup = BxTemplFunctions::getInstance()->transBox($sCoverPopupId, $this->parseHtmlByName('image_popup.html', array (
                'image_url' => $sUrlCover,
                'bx_if:owner' => array (
                    'condition' => CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedChangeCover($aData),
                    'content' => array (
                        'change_image_url' => $sUrlCoverChange,
                    ),
                ),
            )), true, true);
        }

        $sPicturePopup = '';
        $sPicturePopupId = $this->MODULE . '-popup-picture';
        if ($aData[$CNF['FIELD_PICTURE']]) {
            $sPicturePopup = BxTemplFunctions::getInstance()->transBox($sPicturePopupId, $this->parseHtmlByName('image_popup.html', array (
                'image_url' => $sUrlPicture,
                'bx_if:owner' => array (
                    'condition' => CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedEdit($aData),
                    'content' => array (
                        'change_image_url' => $sUrlPictureChange,
                    ),
                ),
            )), true, true);
        }

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);

        // generate html
        $aVars = array (
            'id' => $aData[$CNF['FIELD_ID']],
            'content_url' => $sUrl,
            'title' => $sTitle,
            //'menu' => BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY_COVER'])->getCode(), // TODO: check if menu is used somewhere

            'action_menu' => $oMenu ? $oMenu->getCode() : '',

        	'bx_if:show_ava_image' => array(
                'condition' => $bUrlAvatar,
                'content' => array(
                    'ava_url' => $sUrlAvatar
                )
            ),
            'bx_if:show_ava_letter' => array(
                'condition' => !$bUrlAvatar,
                'content' => array(
            		'color' => implode(', ', BxDolTemplate::getColorCode($iProfile, 0.5)),
                    'letter' => mb_substr($sTitle, 0, 1)
                )
            ),
            'picture_avatar_url' => $bUrlAvatar ? $sUrlAvatar : $this->getImageUrl('no-picture-preview.png'),
            'picture_popup' => $sPicturePopup,
            'picture_popup_id' => $sPicturePopupId,
            'picture_url' => $sUrlPicture,
            'picture_href' => !$aData[$CNF['FIELD_PICTURE']] && CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedEdit($aData) ? $sUrlPictureChange : 'javascript:void(0);',

            'cover_popup' => $sCoverPopup,
            'cover_popup_id' => $sCoverPopupId,
            'cover_url' => $sUrlCover,
            'cover_href' => !$aData[$CNF['FIELD_COVER']] && CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedChangeCover($aData) ? $sUrlCoverChange : 'javascript:void(0);',
        );

        BxDolCover::getInstance($this)->set($aVars, $sTemplateName);
    }

    /**
     * Get profile picture thumb url
     */
    function thumb ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_PICTURE'], $CNF['OBJECT_IMAGES_TRANSCODER_THUMB'], 'no-picture-thumb.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function icon ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_PICTURE'], $CNF['OBJECT_IMAGES_TRANSCODER_ICON'], 'no-picture-icon.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile avatar url
     */
    function urlAvatar ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_PICTURE'], $CNF['OBJECT_IMAGES_TRANSCODER_AVATAR'], 'no-picture-preview.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture url
     */
    function urlPicture ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_PICTURE'], $CNF['OBJECT_IMAGES_TRANSCODER_PICTURE'], 'no-picture-preview.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile cover image url
     */
    function urlCover ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_COVER'], $CNF['OBJECT_IMAGES_TRANSCODER_COVER'], 'cover.jpg', $aData, $bSubstituteNoImage);
    }

	/**
     * Get profile cover image url for browse unit
     */
    function urlCoverUnit ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_COVER'], $CNF['OBJECT_IMAGES_TRANSCODER_GALLERY'], 'cover.jpg', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function _image ($sField, $sTranscodeObject, $sNoImage, $aData, $bSubstituteNoImage = true)
    {
        $sImageUrl = false;
        if ($aData[$sField]) {
            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscodeObject);
            if ($oImagesTranscoder)
                $sImageUrl = $oImagesTranscoder->getFileUrl($aData[$sField]);
        }
        return $bSubstituteNoImage && !$sImageUrl ? $this->getImageUrl($sNoImage) : $sImageUrl;
    }

    protected function _getUnitClass($aData, $sTemplateName = 'unit.html')
    {
        $sResult = '';

        switch($sTemplateName) {
            case 'unit.html':
                $sResult = $this->_sUnitClass;
                break;

            case 'unit_with_cover.html':
                $sResult = $this->_sUnitClassWithCover;
                break;

            case 'unit_wo_info.html':
            case 'unit_wo_info_links.html':
                $sResult = $this->_sUnitClassWoInfo;
                break;
        }

        return $sResult;
    }
}

/** @} */
