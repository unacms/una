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

    public function addLocationBase()
    {
        parent::addLocationBase();

        $this->addLocation('mod_profile', BX_DIRECTORY_PATH_MODULES . 'base' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR, BX_DOL_URL_MODULES . 'base/profile/');
    }

    /**
     * Get profile unit
     */
    function unit ($aData, $isCheckPrivateContent = true, $mixedTemplate = false, $aParams = array())
    {
        list($sTemplate) = is_array($mixedTemplate) ? $mixedTemplate : array($mixedTemplate);

        if(!empty($aParams['template_name']))
            $sTemplate = $aParams['template_name'];
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

        $aVars = $this->unitVars($aData, $isCheckPrivateContent, $mixedTemplate, $aParams);
        
        $aExtras = array(
            'module' => $oModule->getName(),
            'data' => $aData,
            'check_private_content' => $isCheckPrivateContent,
            'template' => $mixedTemplate,
            'params' => $aParams,
            'tmpl_name' => &$sTemplate,
            'tmpl_vars' => &$aVars
        );
        bx_alert('profile', 'unit', 0, 0, $aExtras);
        bx_alert($aExtras['module'], 'unit', 0, 0, $aExtras);
 
        return $this->parseHtmlByName($sTemplate, $aVars);
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $mixedTemplate = false, $aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        list($sTemplate, $sTemplateSize, $aTemplateVars) = is_array($mixedTemplate) ? $mixedTemplate : array($mixedTemplate, false, array());

        if(!empty($aParams['template_name']))
            $sTemplate = $aParams['template_name'];
        if(empty($sTemplate))
            $sTemplate = $this->_sUnitDefault;

        if(!empty($aParams['template_size']))
            $sTemplateSize = $aParams['template_size'];
        if(empty($sTemplateSize))
            $sTemplateSize = $this->_getUnitSize($aData, $sTemplate);

        if(!empty($aParams['template_vars']) && is_array($aParams['template_vars']))
            $aTemplateVars = $aParams['template_vars'];
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
        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId));

        // get profile's title
        $sTitle = $oModule->serviceProfileName($iContentId);

        $sText = $sSummary = '';
        if(!empty($CNF['FIELD_TEXT']) && !empty($aData[$CNF['FIELD_TEXT']])) {
            $sText = $this->getText($aData);
            $sSummary = $this->getSummary($aData, $sTitle, $sText, $sUrl);
        }
        
        $sCoverUrl = $bPublicCover ? $this->urlCoverUnit($aData, false) : '';
        $bCoverUrl = !empty($sCoverUrl);

        if(empty($sCoverUrl) && ($iCoverId = (int)getParam('sys_unit_cover_profile')) != 0)
            $sCoverUrl = BxDolTranscoder::getObjectInstance(BX_DOL_TRANSCODER_OBJ_COVER_UNIT_PROFILE)->getFileUrlById($iCoverId);
        if(empty($sCoverUrl))
            $sCoverUrl = $this->getImageUrl('cover.svg');
        
        $sThumbUrl = $bPublicThumb ? $this->_getUnitThumbUrl($sTemplateSize, $aData, false) : '';
        $bThumbUrl = !empty($sThumbUrl);

        if(substr($sTemplate, 0, 13) == 'unit_wo_cover' && !$bThumbUrl && $bCoverUrl) {
            $bThumbUrl = true;
            $sThumbUrl = $sCoverUrl;
        }

        $aTmplVarsThumbnail = array(
            'class_size' => '',
            'bx_if:show_thumb_image' => array(
                'condition' => $bThumbUrl,
                'content' => array(
                    'class_size' => '',
                    'size' => $sTemplateSize,
                    'thumb_url' => $sThumbUrl
                )
            ),
            'bx_if:show_thumb_letter' => array(
                'condition' => !$bThumbUrl,
                'content' => array(
                    'class_size' => '',
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

        $aTmplVarsMeta = array();
        if(substr($sTemplate, 0, 8) != 'unit_wo_')
            $aTmplVarsMeta = $this->getSnippetMenuVars ($iProfile, $bPublic);
        
        $sCoverData = isset($aData['cover_data']) ? $aData['cover_data'] : '';

        return array_merge(array (
            'class' => $this->_getUnitClass($aData, $sTemplate) . (!$bCoverUrl ? ' bx-cover-empty' : ''),
            'id' => $iContentId,
            'public' => $bPublic,
            'bx_if:show_thumbnail' => array(
                'condition' => $bThumbUrl || $this->_bLetterAvatar,
                'content' => $aTmplVarsThumbnail
            ),
            'cover_url' => $sCoverUrl,
            'cover_settings' => $this->_getImageSettings($sCoverData),
            'bx_if:show_cover' => array(
                'condition' => $bCoverUrl,
                'content' => array(
                    'cover_url' => $sCoverUrl,
                    'cover_settins' => $this->_getImageSettings($sCoverData),
                    'title' => $sTitle,
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

    function getBlockCover($aData, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);
        if(!$oProfile)
            return '';

        $aTmplVars = $this->prepareCover($aData, array_merge($aParams, [
            'use_as_block' => true
        ]));
    
        $this->addCss(array('cover.css'));
        
        return $this->parseHtmlByName('cover_block.html', $aTmplVars);
    }
    
    function prepareCover($aData, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;
        $oModule = $this->getModule();
        $sClass = isset($aParams['class']) ? $aParams['class'] : '';
        $sShowData = isset($aParams['show_data']) ? $aParams['show_data'] : '';
        $bShowCover = !isset($aParams['show_cover']) || $aParams['show_cover'] === true;
        $bShowAvatar = !isset($aParams['show_avatar']) || $aParams['show_avatar'] === true;
        $sAddCode = "";
        

        $bUseAsAuthor = isset($aParams['use_as_author']) && $aParams['use_as_author'] === true;
        $bUseAsBlock = $bUseAsAuthor || (isset($aParams['use_as_block']) && $aParams['use_as_block'] === true);

        if($bUseAsAuthor)
            $sClass .= ' bx-base-author';
        else
            BxDolTemplate::getInstance()->addInjection('injection_main_class', 'text', 'bx-base-profile-view');

        $bProfileViewAllowed = $oModule->checkAllowedView($aData) === CHECK_ACTION_RESULT_ALLOWED;

        if($oModule->checkAllowedViewProfileImage($aData) !== CHECK_ACTION_RESULT_ALLOWED)
            $aData[$CNF['FIELD_PICTURE']] = 0;

        if($oModule->checkAllowedViewCoverImage($aData) !== CHECK_ACTION_RESULT_ALLOWED)
            $aData[$CNF['FIELD_COVER']] = 0;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);
        $iProfile = $oProfile->id();

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]));
        $sTitle = bx_process_output($oProfile->getDisplayName());

        //--- Process Cover
        $bTmplVarsShowCover = $bShowCover;
        $aTmplVarsShowCover = [];

        if($bTmplVarsShowCover) {
            $sCoverPopupId = $this->MODULE . '-popup-cover';
            $bIsAllowEditCover = $oModule->checkAllowedChangeCover($aData) === CHECK_ACTION_RESULT_ALLOWED;

            $oPage = false;
            if(isset($aParams['page']) && is_a($aParams['page'], 'BxDolPage'))
                $oPage = $aParams['page'];

            $bUrlCover = false;
            $sUrlCover = $this->urlCover($aData, false);
            if(!$sUrlCover && $oPage !== false && $oPage->isPageCover()) {
                $aCover = $oPage->getPageCoverImage();
                
                if(!empty($aCover))
                    $sUrlCover = BxDolCover::getCoverImageUrl($aCover);
            }

            if(!empty($sUrlCover)) {
                if(!$bUseAsAuthor)
                    BxDolTemplate::getInstance()->addPageMetaImage($sUrlCover);

                $bUrlCover = true;
            }
            else
                $sUrlCover = $this->getImageUrl('cover.svg');

            $sAddClassCover = "";
            if(isset($CNF['FIELD_COVER']) && isset($CNF['OBJECT_UPLOADERS_COVER']) && isset($CNF['OBJECT_STORAGE_COVER']) && isset($CNF['OBJECT_IMAGES_TRANSCODER_COVER'])){
                bx_alert('system', 'image_editor', 0, 0, array(
                   'module' => $oModule->getName(),
                   'image_type' => 'cover',
                   'is_allow_edit' => $bIsAllowEditCover,
                   'image_url' => $aData[$CNF['FIELD_COVER']] ? $sUrlCover : '',
                   'content_id' => $aData[$CNF['FIELD_ID']],
                   'uploader' => $CNF['OBJECT_UPLOADERS_COVER'][0],
                   'storage' => $CNF['OBJECT_STORAGE_COVER'],
                   'transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_COVER'],
                   'field' => $CNF['FIELD_COVER'],
                   'is_background' => true,
                   'add_class' => &$sAddClassCover,
                   'add_code' => &$sAddCode
                ));
            }
            
            $sCoverTweak = '';
            $sUniqIdCover = genRndPwd (8, false);
            if ($bIsAllowEditCover && empty($sAddCode)){
                $sCoverTweak = $this->_prepareImage($aData, $sUniqIdCover, $CNF['OBJECT_UPLOADERS_COVER'], $CNF['OBJECT_STORAGE_COVER'], $CNF['FIELD_COVER'], true);
            }
            
            $aTmplVarsShowCover = [
                'cover_popup_id' => $sCoverPopupId,
                'cover_url' => $sUrlCover,
                'unique_id' => $sUniqIdCover,
                'cover_settins' => isset($CNF['FIELD_COVER_POSITION']) ? $this->_getImageSettings($aData[$CNF['FIELD_COVER_POSITION']]) : '',
                'add_class' => $sAddClassCover,
                'img_class' => $sAddClassCover != '' ? 'bx-media-editable-src' : '',
            ];
        }
        else
            $sClass .= ' bx-no-cover';

        //--- Process Avatar
        $sUrlAvatar = $this->urlAvatarBig($aData, false);
        $bUrlAvatar = !empty($sUrlAvatar);

        $bTmplVarsShowAvatar = $bShowAvatar && ($bUrlAvatar || $this->_bLetterAvatar);
        $aTmplVarsShowAvatar = [];
        $sPicturePopup = '';

        if($bTmplVarsShowAvatar) {
            $sPicturePopupId = $this->MODULE . '-popup-picture';
            $bIsAllowEditPicture = $oModule->checkAllowedEdit($aData) === CHECK_ACTION_RESULT_ALLOWED;

            $sUrlPicture = $this->urlPicture ($aData);
            $sUrlPictureChange = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]));

            $sAddClassPicture = "";
            if(isset($CNF['FIELD_PICTURE']) && isset($CNF['OBJECT_UPLOADERS_PICTURE']) && isset($CNF['OBJECT_STORAGE']) && isset($CNF['OBJECT_IMAGES_TRANSCODER_THUMB'])){
                bx_alert('system', 'image_editor', 0, 0, array(
                   'module' => $oModule->getName(),
                   'image_type' => 'avatar',
                   'is_allow_edit' => $bIsAllowEditPicture,
                   'image_url' =>  $aData[$CNF['FIELD_PICTURE']] ? $sUrlPicture : '',
                   'content_id' => $aData[$CNF['FIELD_ID']],
                   'uploader' => $CNF['OBJECT_UPLOADERS_PICTURE'][0],
                   'storage' => $CNF['OBJECT_STORAGE'],
                   'transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_AVATAR_BIG'],
                   'field' => $CNF['FIELD_PICTURE'],
                   'is_background' => false,
                   'add_class' => &$sAddClassPicture,
                   'add_code' => &$sAddCode
                )); 
            }
            
            $sPictureTweak = '';
            $sUniqIdPicture = genRndPwd (8, false);
            if ($bIsAllowEditPicture && empty($sAddCode)){
                $sPictureTweak = $this->_prepareImage($aData, $sUniqIdPicture, $CNF['OBJECT_UPLOADERS_PICTURE'], $CNF['OBJECT_STORAGE'], $CNF['FIELD_PICTURE'], false);
            }

            $aTmplVarsShowAvatar = array(
                'add_class' => $sAddClassPicture,
                'letter' => mb_strtoupper(mb_substr($sTitle, 0, 1)),
                'img_class' => $sAddClassPicture != '' ? 'bx-media-editable-src' : '',
                'ava_url' => $sUrlAvatar,
                'color' => implode(', ', BxDolTemplate::getColorCode($iProfile, 1.0)),
                
                'bx_if:show_ava_image' => array(
                    'condition' => $bUrlAvatar,
                    'content' => []
                ),
                'bx_if:show_ava_letter' => array(
                    'condition' => !$bUrlAvatar,
                    'content' => []
                ),
                'bx_if:show_online' => array(
                    'condition' => $oProfile->isOnline(),
                    'content' => []
                ),
                'bx_if:is_avatar' => array(
                    'condition' => $bUrlAvatar,
                    'content' => [
                        'picture_popup_id' => $sPicturePopupId,
                        'picture_url' => $sUrlPicture,
                    ]
                ),
                'picture_avatar_url' => $bUrlAvatar ? $sUrlAvatar : $this->getImageUrl('no-picture-preview.png'),
                'unique_id' => $sUniqIdPicture,
                'picture_tweak' => $sPictureTweak, 
                'cover_settins' => isset($CNF['FIELD_PICTURE_POSITION']) ? $this->_getImageSettings($aData[$CNF['FIELD_PICTURE_POSITION']]) : '',
                'picture_href' => !$aData[$CNF['FIELD_PICTURE']] && CHECK_ACTION_RESULT_ALLOWED === $oModule->checkAllowedEdit($aData) ? $sUrlPictureChange : 'javascript:void(0);',
            );
            
            if($bProfileViewAllowed && $aData[$CNF['FIELD_PICTURE']]) {
                $sPicturePopup = BxTemplFunctions::getInstance()->transBox($sPicturePopupId, $this->parseHtmlByName('image_popup.html', array (
                    'image_url' => $sUrlPicture,
                    'bx_if:owner' => array (
                        'condition' => false,
                        'content' => array (
                            'change_image_url' => $sUrlPictureChange,
                        ),
                    ),
                )), true, true);
            }
        }

        //--- Process Actions menu
        $sActionsMenu = '';
        if(!isset($aParams['show_menu_actions']) || $aParams['show_menu_actions'] === true) {
            $sActionsMenu = $oModule->serviceEntityAllActions([$aData[$CNF['FIELD_ID']], $aData]);
            if(!$sActionsMenu && ($oMenu = BxTemplMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY'], $this)) !== false)
                $sActionsMenu = $oMenu->getCode();
        }

        //--- Process Meta menu
        $sMetaMenu = '';
        if((!isset($aParams['show_menu_meta']) || $aParams['show_menu_meta'] === true) && !empty($CNF['OBJECT_MENU_VIEW_ENTRY_META'])) {
            $oMetaMenu = BxTemplMenu::getObjectInstance($CNF['OBJECT_MENU_VIEW_ENTRY_META']);
            if($oMetaMenu !== false) {
                $oMetaMenu->setContentId($aData[$CNF['FIELD_ID']]);
                $oMetaMenu->setContentPublic($this->isProfilePublic($aData));
                $sMetaMenu = $oMetaMenu->getCode();
            }
        }

        $aTmplVars = [
            'module' => $this->_oConfig->getName(),
            'class' => trim($sClass),
            'id' => $aData[$CNF['FIELD_ID']],
            'content_url' => $sUrl,
            
            'title' => $sTitle,
            'bx_if:show_title_as_tag' => [
                'condition' => !$bUseAsAuthor,
                'content' => [
                    'title' => $sTitle
                ]
            ],
            'bx_if:show_title_as_text' => [
                'condition' => $bUseAsAuthor,
                'content' => [
                    'title' => $sTitle
                ]
            ],
            'bx_if:show_avatar' => [
                'condition' => $bTmplVarsShowAvatar,
                'content' => $aTmplVarsShowAvatar
            ],
            'bx_if:show_avatar_placeholder' => [
                'condition' => !$bTmplVarsShowAvatar,
                'content' => []
            ],
            'badges' => $oModule->serviceGetBadges($aData[$CNF['FIELD_ID']]),
            'action_menu' => $sActionsMenu,
            'meta' => $sMetaMenu,
            'show_data' => $sShowData,
            'picture_popup' => $sPicturePopup,
            'additional_code' => $sAddCode,
            'cover_tweak' => $sCoverTweak,
        ];
        
        $bShowClickable = !isset($aParams['show_clickable']) || $aParams['show_clickable'] === true; //--- Is available for UseAsBlock appearance only.
        
        if($bUseAsBlock && $bShowClickable)
            $sClass .= ' bx-clickable';

        if($bUseAsBlock)
            $aTmplVars = array_merge($aTmplVars, [
                'bx_if:show_clickable' => [
                    'condition' => $bShowClickable,
                    'content' => [
                        'content_url' => $sUrl
                    ]
                ],
                'bx_if:show_cover' => [
                    'condition' => $bTmplVarsShowCover,
                    'content' => $aTmplVarsShowCover
                ],
                'bx_if:show_text' => [
                    'condition' => !isset($aParams['show_text']) || $aParams['show_text'] === true,
                    'content' => [
                        'text' => isset($CNF['FIELD_TEXT']) ? strmaxtextlen(strip_tags($aData[$CNF['FIELD_TEXT']]), 150) : ''
                    ]
                ]
            ]);
        else
            $aTmplVars = array_merge($aTmplVars, $aTmplVarsShowCover, [
                'bx_if:show_cover' => [
                    'condition' => $bUrlCover,
                    'content' => []
                ],
                'bx_if:show_cover_placeholder' => [
                    'condition' => !$bUrlCover,
                    'content' => []
                ],
            ]);

        return $aTmplVars;
    }
    /**
     * Get profile cover
     */
    function setCover ($oPage, $aData, $sTemplateName = 'cover.html')
    {
        BxDolCover::getInstance($this)->set($this->prepareCover($aData, ['page' => $oPage]), $sTemplateName);
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

        if(!$bSubstituteNoImage || $sImageUrl)
            return $sImageUrl;

        $iImageId = (int)getParam('sys_unit_cover_profile');
        $oImageTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_COVER_UNIT_PROFILE);
        if($oImageTranscoder && $iImageId != 0)
            $sImageUrl = $oImageTranscoder->getFileUrl($iImageId);

        if(!$sImageUrl)
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

        if(!$bSubstituteNoImage || $sImageUrl)
            return $sImageUrl;

        $sImageUrl = $this->getImageUrl($sNoImage);
        if(!$sImageUrl)
            $sImageUrl = $this->getImageUrl(substr($sNoImage, 0, strrpos($sNoImage, '-')) . '.svg');

        return $sImageUrl;
    }

    protected function _getUnitClass($aData, $sTemplateName = 'unit.html')
    {
        $sResult = '';

        switch($sTemplateName) {
            default:
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
            case 'unit_with_cover_showcase.html':
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
