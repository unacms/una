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
    protected $_sUnitDefault;    
    protected $_sUnitSizeDefault;

    protected $_sUnitClass;
    protected $_sUnitClassWithCover;
    protected $_sUnitClassWoInfo;
    protected $_sUnitClassWoInfoShowCase;
    protected $_sUnitClassShowCase;

    /*
     * Enable/Disable Letter based thumbnail in "Unit with Cover" only.
     */
    protected $_bLetterAvatar;
    
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->_sUnitDefault = 'unit.html';
        $this->_sUnitSizeDefault = 'thumb';
                
        $this->_sUnitClass = 'bx-base-pofile-unit';
        $this->_sUnitClassWithCover = 'bx-base-pofile-unit-with-cover';
        $this->_sUnitClassWoInfo = 'bx-base-pofile-unit-wo-info';
        $this->_sUnitClassWoInfoShowCase = 'bx-base-pofile-unit-wo-info bx-base-unit-showcase bx-base-pofile-unit-wo-info-showcase';
        $this->_sUnitClassShowCase = 'bx-base-pofile-unit-with-cover bx-base-unit-showcase bx-base-pofile-unit-showcase';

        $this->_bLetterAvatar = true;
    }

    /**
     * Get profile unit
     */
    function unit ($aData, $isCheckPrivateContent = true, $mixedTemplate = false)
    {
        list($sTemplate) = is_array($mixedTemplate) ? $mixedTemplate : array($mixedTemplate);
        if(empty($sTemplate))
            $sTemplate = $this->_sUnitDefault;
        
        /**
         * Allow use separate template for private profiles. 
         * These templates will be used if privacy field "Visible to" don't allow to view content.
         * To use them you need to create a template with a postfix "_private" and put it in specified module
         * Example: "unit_private.html" for "unit.html" or "unit_wo_links_private.html" for "unit_wo_links.html"
         */ 
        $oModule = $this->getModule();
        if ($oModule->serviceCheckAllowedViewForProfile($aData) !== 0){
            $sTemplatePrivate = str_replace('.html', '_private.html' , $sTemplate);
            if($this->parseHtmlByName($sTemplatePrivate, array())){
                $sTemplate = $sTemplatePrivate;
            }
        }
        
        $aVars = $this->unitVars($aData, $isCheckPrivateContent, $mixedTemplate);
 
        return $this->parseHtmlByName($sTemplate, $aVars);
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $mixedTemplate = false)
    {
        $CNF = &$this->_oConfig->CNF;

        list($sTemplate, $sTemplateSize, $aTemplateVars) = is_array($mixedTemplate) ? $mixedTemplate : array($mixedTemplate, false, array());

        if(empty($sTemplate))
            $sTemplate = $this->_sUnitDefault;
        if(empty($sTemplateSize))
            $sTemplateSize = $this->_getUnitSize($aData, $sTemplate);
        if(empty($aTemplateVars) || !is_array($aTemplateVars))
            $aTemplateVars = array();

        $oModule = $this->getModule();
        $iContentId = (int)$aData[$CNF['FIELD_ID']];

        $bPublic = $isCheckPrivateContent && !empty($CNF['OBJECT_PRIVACY_VIEW']) ? $this->isProfilePublic($aData) : true;

        $bPublicThumb = true;
        if($isCheckPrivateContent && $oModule->checkAllowedViewProfileImage($aData) !== CHECK_ACTION_RESULT_ALLOWED)
            $bPublicThumb = false;

        $bPublicCover = true;
        if($isCheckPrivateContent && $oModule->checkAllowedViewCoverImage($aData) !== CHECK_ACTION_RESULT_ALLOWED)
            $bPublicCover = false;

        $oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->MODULE);
        $iProfile = $oProfile->id();

        // get profile's url
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId);

        // get profile's title
        $sTitle = bx_process_output($aData[$CNF['FIELD_NAME']]);

        $sText = $sSummary = '';
        if(!empty($CNF['FIELD_TEXT']) && !empty($aData[$CNF['FIELD_TEXT']])) {
            $sText = $this->getText($aData);
            $sSummary = $this->getSummary($aData, $sTitle, $sText, $sUrl);
        }

        $sThumbUrl = $bPublicThumb ? $this->_getUnitThumbUrl($sTemplateSize, $aData, false) : '';
        $bThumbUrl = !empty($sThumbUrl);
		
        $aTmplVarsThumbnail = array(
            'bx_if:show_thumb_image' => array(
                'condition' => $bThumbUrl,
                'content' => array(
                    'size' => $sTemplateSize,
                    'thumb_url' => $sThumbUrl
                )
            ),
            'bx_if:show_thumb_letter' => array(
                'condition' => !$bThumbUrl,
                'content' => array(
                    'size' => $sTemplateSize,
                    'color' => implode(', ', BxDolTemplate::getColorCode($iProfile, 1.0)),
                    'letter' => mb_strtoupper(mb_substr($sTitle, 0, 1))
                )
            ),
            'bx_if:show_online' => array(
                'condition' => $oProfile->isOnline(),
                'content' => array()
            ),
            'size' => $sTemplateSize,
            'badges' => $oModule->serviceGetBadges($iContentId, false, true),
            'thumb_url' => $bThumbUrl ? $sThumbUrl : $this->getImageUrl('no-picture-thumb.png'),
        );

        $sCoverUrl = $bPublicCover ? $this->urlCoverUnit($aData, false) : '';
        $bCoverUrl = !empty($sCoverUrl);

        if(empty($sCoverUrl) && ($iCoverId = (int)getParam('sys_unit_cover_profile')) != 0)
            $sCoverUrl = BxDolTranscoder::getObjectInstance(BX_DOL_TRANSCODER_OBJ_COVER_UNIT_PROFILE)->getFileUrlById($iCoverId);
        if(empty($sCoverUrl))
            $sCoverUrl = $this->getImageUrl('cover.svg');

        $aTmplVarsMeta = array();
        if(substr($sTemplate, 0, 8) != 'unit_wo_')
            $aTmplVarsMeta = $this->getSnippetMenuVars ($iProfile, $bPublic);

        return array_merge(array (
            'class' => $this->_getUnitClass($aData, $sTemplate),
            'id' => $iContentId,
            'public' => $bPublic,
            'bx_if:show_thumbnail' => array(
                'condition' => $bThumbUrl || $this->_bLetterAvatar,
                'content' => $aTmplVarsThumbnail
            ),
            'cover_url' => $sCoverUrl,
            'bx_if:show_cover' => array(
                'condition' => $bCoverUrl,
                'content' => array(
                    'cover_url' => $sCoverUrl,
                )
            ),
            'content_url' => $bPublic ? $sUrl : 'javascript:void(0);',
            'content_click' => !$bPublic ? 'javascript:bx_alert(' . bx_js_string('"' . _t('_sys_access_denied_to_private_content') . '"') . ');' : '',
            'title' => $sTitle,
            'title_attr' => bx_html_attribute($sTitle),
            'addon' => !empty($aData['addon']) ? $aData['addon'] : '',
            'module_name' => _t($CNF['T']['txt_sample_single']),
            'ts' => $aData[$CNF['FIELD_ADDED']],
            'bx_if:meta' => array(
                'condition' => !empty($aTmplVarsMeta),
                'content' => $aTmplVarsMeta
            ),
            'text' => $sText,
            'summary' => $sSummary,
        ), $aTmplVarsThumbnail, $aTemplateVars);
    }

    function isProfilePublic($aData)
    {
        $CNF = &$this->_oConfig->CNF;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        if ($oPrivacy && !$oPrivacy->check($aData[$CNF['FIELD_ID']]) && !$oPrivacy->isPartiallyVisible($aData[$CNF['FIELD_ALLOW_VIEW_TO']]))
            return false;
        return true;
    }

    function getSnippetMenuVars ($iProfileId, $bPublic = null)
    {
        if (!($oProfile = BxDolProfile::getInstance($iProfileId)))
            return array();
        
        $CNF = &$this->_oConfig->CNF;
        
        if (null === $bPublic) {
            $aData = $this->getModule()->serviceGetContentInfoById($oProfile->getContentId());
            $bPublic = $this->isProfilePublic($aData);
        }
        
        $aTmplVarsMeta = array();
        if (!empty($CNF['OBJECT_MENU_SNIPPET_META'])) {
            $oMenuMeta = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SNIPPET_META'], $this);
            if($oMenuMeta) {
                $oMenuMeta->setContentId($oProfile->getContentId());
                $oMenuMeta->setContentPublic($bPublic);
                $aTmplVarsMeta = array(
                    'meta' => $oMenuMeta->getCode()
                );
            }
        }

        return $aTmplVarsMeta;
    }

    /**
     * Get profile cover
     */
    function setCover ($oPage, $aData, $sTemplateName = 'cover.html')
    {
        $CNF = &$this->_oConfig->CNF;
        $oModule = $this->getModule();

        BxDolTemplate::getInstance()->addInjection('injection_main_class', 'text', 'bx-base-profile-view');

        $bProfileViewAllowed = CHECK_ACTION_RESULT_ALLOWED === $oModule->checkAllowedView($aData);

        if (CHECK_ACTION_RESULT_ALLOWED !== $oModule->checkAllowedViewProfileImage($aData))
            $aData[$CNF['FIELD_PICTURE']] = 0;

        if (CHECK_ACTION_RESULT_ALLOWED !== $oModule->checkAllowedViewCoverImage($aData))
            $aData[$CNF['FIELD_COVER']] = 0;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);
        $iProfile = $oProfile->id();

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);
        $sTitle = bx_process_output($oProfile->getDisplayName());

        $sUrlAvatar = $this->urlAvatarBig($aData, false);
        $bUrlAvatar = !empty($sUrlAvatar);

        $sUrlPicture = $this->urlPicture ($aData);
        $sUrlPictureChange = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        $sUrlCover = $this->urlCover ($aData, false);
        if(!$sUrlCover && $oPage !== false && $oPage->isPageCover()) {
            $aCover = $oPage->getPageCoverImage();
            if(!empty($aCover))
                $sUrlCover = BxDolCover::getCoverImageUrl($aCover);
        }

        if(!$sUrlCover)
            $sUrlCover = $this->getImageUrl('cover.svg');
		else
			BxDolTemplate::getInstance()->addPageMetaImage($sUrlCover);
        
        $sUrlCoverChange = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_COVER'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        $sCoverPopup = '';
        $sCoverPopupId = $this->MODULE . '-popup-cover';
        if($bProfileViewAllowed && $aData[$CNF['FIELD_COVER']]) {
            $sCoverPopup = BxTemplFunctions::getInstance()->transBox($sCoverPopupId, $this->parseHtmlByName('image_popup.html', array (
                'image_url' => $sUrlCover,
                'bx_if:owner' => array (
                    'condition' => CHECK_ACTION_RESULT_ALLOWED === $oModule->checkAllowedChangeCover($aData),
                    'content' => array (
                        'change_image_url' => $sUrlCoverChange,
                    ),
                ),
            )), true, true);
        }

        $bShowAvatar = $bUrlAvatar || $this->_bLetterAvatar;
        $aShowAvatar = array();
        $sPicturePopup = '';
        if($bShowAvatar) {
            $sPicturePopupId = $this->MODULE . '-popup-picture';

            $aShowAvatar = array(
                'bx_if:show_ava_image' => array(
                    'condition' => $bUrlAvatar,
                    'content' => array(
                        'ava_url' => $sUrlAvatar
                    )
                ),
                'bx_if:show_ava_letter' => array(
                    'condition' => !$bUrlAvatar,
                    'content' => array(
                        'color' => implode(', ', BxDolTemplate::getColorCode($iProfile, 1.0)),
                        'letter' => mb_substr($sTitle, 0, 1)
                    )
                ),
                'bx_if:show_online' => array(
                    'condition' => $oProfile->isOnline(),
                    'content' => array()
                ),
                'picture_avatar_url' => $bUrlAvatar ? $sUrlAvatar : $this->getImageUrl('no-picture-preview.png'),
                'picture_popup_id' => $sPicturePopupId,
                'picture_url' => $sUrlPicture,
                'picture_href' => !$aData[$CNF['FIELD_PICTURE']] && CHECK_ACTION_RESULT_ALLOWED === $oModule->checkAllowedEdit($aData) ? $sUrlPictureChange : 'javascript:void(0);',
            );

            if($bProfileViewAllowed && $aData[$CNF['FIELD_PICTURE']]) {
                $sPicturePopup = BxTemplFunctions::getInstance()->transBox($sPicturePopupId, $this->parseHtmlByName('image_popup.html', array (
                    'image_url' => $sUrlPicture,
                    'bx_if:owner' => array (
                        'condition' => CHECK_ACTION_RESULT_ALLOWED === $oModule->checkAllowedEdit($aData),
                        'content' => array (
                            'change_image_url' => $sUrlPictureChange,
                        ),
                    ),
                )), true, true);
            }
        }

        $sActionsMenu = $oModule->serviceEntityAllActions();
        if(!$sActionsMenu && ($oMenu = BxTemplMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY'])) !== false)
            $sActionsMenu = $oMenu->getCode();

        // generate html
        $aVars = array (
            'module' => $this->_oConfig->getName(),

            'id' => $aData[$CNF['FIELD_ID']],
            'content_url' => $sUrl,
            'title' => $sTitle,
            //'menu' => BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY_COVER'])->getCode(), // TODO: check if menu is used somewhere

            'action_menu' => $sActionsMenu,

            'bx_if:show_avatar' => array(
                'condition' => $bShowAvatar,
                'content' => $aShowAvatar
            ),
            'picture_popup' => $sPicturePopup,

            'cover_popup' => $sCoverPopup,
            'cover_popup_id' => $sCoverPopupId,
            'cover_url' => $sUrlCover,
            'cover_href' => !$aData[$CNF['FIELD_COVER']] && CHECK_ACTION_RESULT_ALLOWED === $oModule->checkAllowedChangeCover($aData) ? $sUrlCoverChange : 'javascript:void(0);',
            'badges' => $oModule->serviceGetBadges($aData[$CNF['FIELD_ID']]),
        );

        BxDolCover::getInstance($this)->set($aVars, $sTemplateName);
    }

    /**
     * Get profile picture thumb url
     * @deprecated since 11.0.0 use BxBaseModProfileTemplate::urlAvatar instead.
     */
    function avatar ($aData, $bSubstituteNoImage = true)
    {
        return $this->urlAvatar($aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture thumb url
     * @deprecated since 11.0.0 use BxBaseModProfileTemplate::urlThumb instead.
     */
    function thumb ($aData, $bSubstituteNoImage = true)
    {
        return $this->urlThumb($aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture thumb url
     * @deprecated since 11.0.0 use BxBaseModProfileTemplate::urlIcon instead.
     */
    function icon ($aData, $bSubstituteNoImage = true)
    {
        return $this->urlIcon($aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function urlIcon ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_PICTURE'], $CNF['OBJECT_IMAGES_TRANSCODER_ICON'], 'no-picture-icon.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile thumb url
     */
    function urlThumb ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_PICTURE'], $CNF['OBJECT_IMAGES_TRANSCODER_THUMB'], 'no-picture-thumb.png', $aData, $bSubstituteNoImage);
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
     * Get profile avatar big url
     */
    function urlAvatarBig ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_image ($CNF['FIELD_PICTURE'], $CNF['OBJECT_IMAGES_TRANSCODER_AVATAR_BIG'], 'no-picture-preview.png', $aData, $bSubstituteNoImage);
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
    function urlCover ($aData, $bSubstituteNoImage = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $sImageUrl = $this->_image($CNF['FIELD_COVER'], $CNF['OBJECT_IMAGES_TRANSCODER_COVER'], '', $aData, $bSubstituteNoImage);

        return $sImageUrl;
    }

    /**
     * Get profile cover image url for browse unit
     */
    function urlCoverUnit ($aData, $bSubstituteNoImage = true)
    {
        $CNF = &$this->_oConfig->CNF;
        $sImageUrl = $this->_image ($CNF['FIELD_COVER'], $CNF['OBJECT_IMAGES_TRANSCODER_GALLERY'], '', $aData, false);
        if($sImageUrl === false) {
        	$iImageId = (int)getParam('sys_unit_cover_profile');
        	$oImageTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_COVER_UNIT_PROFILE);
        	if($oImageTranscoder && $iImageId != 0)
        		$sImageUrl = $oImageTranscoder->getFileUrl($iImageId);
        }

        if($bSubstituteNoImage && !$sImageUrl)
        	$sImageUrl = $this->getImageUrl('cover.svg');

		return $sImageUrl;
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
                
            case 'unit_with_cover_showcase.html':
                $sResult = $this->_sUnitClassShowCase;
                break;

            case 'unit_wo_info_showcase.html':
                $sResult = $this->_sUnitClassWoInfoShowCase;
                break;
        }

        return $sResult;
    }

    protected function _getUnitSize($aData, $sTemplateName = 'unit.html')
    {
        $sResult = '';

        switch($sTemplateName) {
            case 'unit_with_cover.html':
                $sResult = 'ava';
                break;

            default:
                $sResult = $this->_sUnitSizeDefault;
                break;
        }

        return $sResult;
    }

    protected function _getUnitThumbUrl($sSize, $aData, $bSubstituteNoImage = true)
    {
        $sMethod = 'url' . bx_gen_method_name(str_replace('ava', 'avatar', $sSize), array('_', '-'));

        if(method_exists($this, $sMethod))
            return $this->$sMethod($aData, $bSubstituteNoImage);
        else
            return $this->_getUnitThumbUrl($this->_sUnitSizeDefault, $aData, $bSubstituteNoImage);
    }
}

/** @} */
