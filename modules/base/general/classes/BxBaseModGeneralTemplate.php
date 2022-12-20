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
 * Module representation.
 */
class BxBaseModGeneralTemplate extends BxDolModuleTemplate
{
    protected $MODULE;
    
    public $aMethodsToCallAddJsCss = array('entry', 'unit');

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function addLocationBase()
    {
        parent::addLocationBase();

        $this->addLocation('mod_general', BX_DIRECTORY_PATH_MODULES . 'base' . DIRECTORY_SEPARATOR . 'general' . DIRECTORY_SEPARATOR, BX_DOL_URL_MODULES . 'base/general/');
    }

    public function getJsCode($sType, $aParams = array(), $mixedWrap = true)
    {
        $sMask = "{var} {object} = new {class}({params});";
        $aMaskMarkers = array();
        if(is_array($mixedWrap)) {
            if(!empty($mixedWrap['mask']))
                $sMask = $mixedWrap['mask'];

            if(!empty($mixedWrap['mask_markers']) && is_array($mixedWrap['mask_markers']))
                $aMaskMarkers = $mixedWrap['mask_markers'];
        }

        $sJsClass = $this->_oConfig->getJsClass($sType);
        if(!empty($aParams['js_class'])) {
            $sJsClass = $aParams['js_class'];
            unset($aParams['js_class']);
        }

        $sJsObject = $this->_oConfig->getJsObject($sType);
        if(!empty($aParams['js_object'])) {
            $sJsObject = $aParams['js_object'];
            unset($aParams['js_object']);
        }

        $sBaseUri = $this->_oConfig->getBaseUri();
        $aParams = array_merge([
            'sActionUri' => $sBaseUri,
            'sActionUrl' => BX_DOL_URL_ROOT . $sBaseUri,
            'sObjName' => $sJsObject,
            'aHtmlIds' => [],
            'oRequestParams' => []
        ], $aParams);

        $sContent = false;
        bx_alert('system', 'get_js_code', 0, 0, [
            'mask' => &$sMask,
            'mask_markers' => &$aMaskMarkers,
            'object' => &$sJsObject,
            'class' => &$sJsClass,
            'params' => &$aParams,
            'override_result' => &$sContent,
        ]);

        if($sContent === false)
            $sContent = bx_replace_markers($sMask, array_merge([
                'var' => 'var',
                'object' => $sJsObject, 
                'class' => $sJsClass,
                'params' => json_encode($aParams)
            ], $aMaskMarkers));

        return ($mixedWrap === true || (is_array($mixedWrap) && isset($mixedWrap['wrap']) && $mixedWrap['wrap'] === true)) ? $this->_wrapInTagJsCode($sContent) : $sContent;
    }

    public function getTitleAuto($aData, $iMaxLen = 20, $sEllipsisSign = '...')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if(isset($aData[$CNF['FIELD_TITLE']]))
            return $this->getTitle($aData);

        $sResult = $this->getText($aData);
        if(strlen($sResult) > 0 && $iMaxLen > 0)
            $sResult = strmaxtextlen($sResult, $iMaxLen, $sEllipsisSign);

        return $sResult;
    }

    public function getTitle($aData, $mixedProcessOutput = BX_DATA_TEXT)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        
        if(!isset($aData[$CNF['FIELD_TITLE']]))
            return '';

        $sResult = $aData[$CNF['FIELD_TITLE']];
        if($mixedProcessOutput !== false && !empty($sResult))
            $sResult = bx_process_output($sResult, (int)$mixedProcessOutput);

        return $sResult;
    }

    public function getText($aData, $mixedProcessOutput = BX_DATA_HTML)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        
        if(!isset($aData[$CNF['FIELD_TEXT']]))
            return '';

        $sResult = $aData[$CNF['FIELD_TEXT']];
        if($mixedProcessOutput !== false && !empty($sResult))
            $sResult = bx_process_output($sResult, (int)$mixedProcessOutput);

        return $sResult;
    }

    protected function getSummary($aData, $sTitle = '', $sText = '', $sUrl = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        if(empty($CNF['PARAM_CHARS_SUMMARY']))
            return '';

        // get summary
        $sLinkMore = ' <a title="' . bx_html_attribute(_t('_sys_read_more', $sTitle)) . '" href="' . $sUrl . '"><i class="sys-icon ellipsis-h"></i></a>';
        return strmaxtextlen($sText, (int)getParam($CNF['PARAM_CHARS_SUMMARY']), $sLinkMore);
    }

    public function getProfileLink($mixedProfile)
    {
    	if(!is_array($mixedProfile))
            $mixedProfile = $this->getModule()->getProfileInfo((int)$mixedProfile);

    	return $this->getLink('link', array(
            'href' => $mixedProfile['link'],
            'title' => bx_html_attribute(!empty($mixedProfile['title']) ? $mixedProfile['title'] : $mixedProfile['name']),
            'content' => $mixedProfile['name']
    	));
    }

    public function getLink($sTemplate, $aParams)
    {
    	return $this->parseHtmlByName($sTemplate . '.html', array(
            'href' => $aParams['href'],
            'title' => $aParams['title'],
            'content' => $aParams['content']
        ));
    }
    
    function getContextAddon ($aData, $oProfile)
    {
        $CNF = &$this->getModule()->_oConfig->CNF; 
        $sUrl = 'page.php?i=' . $CNF['URI_ENTRIES_BY_CONTEXT'] . '&profile_id=' . $oProfile->id();
        $bActAsProfile = BxDolService::call($oProfile->getModule(), 'act_as_profile');
        if ($bActAsProfile)
            $sUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . $oProfile->id();
        $sUrl = BxDolPermalinks::getInstance()->permalink($sUrl);
        return _t($CNF['T']['txt_all_entries_in'], $sUrl, $oProfile->getDisplayName(), $this->getModule()->_oDb->getEntriesNumByContext($oProfile->id()));
    }
    
    function getContextDesc ($aData)
    {
        return '';
    }

    public function getTmplVarsText($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aVars = $aData;
        $aVars['entry_title'] = $this->getTitle($aData);
        $aVars['entry_text'] = $this->getText($aData);
		$aVars['badges'] = $this->getModule()->serviceGetBadges($aData[$CNF['FIELD_ID']]);
        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);

            // keywords
            if ($oMetatags->keywordsIsEnabled()) {
                $aFields = array_merge($oMetatags->metaFields($aData, $CNF, $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW']), array('entry_title', 'entry_text'));
                foreach ($aFields as $sField)
                    $aVars[$sField] = $oMetatags->keywordsParse($aData[$CNF['FIELD_ID']], $aVars[$sField]);
            }

            // mentions
            if ($oMetatags->mentionsIsEnabled()) {
                $aFields = array_merge($oMetatags->metaFields($aData, $CNF, $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW'], true), array('entry_text'));
                foreach ($aFields as $sField)
                    $aVars[$sField] = $oMetatags->mentionsParse($aData[$CNF['FIELD_ID']], $aVars[$sField]);
            }
            
            // location
            $aVars['location'] = $oMetatags->locationsIsEnabled() ? $oMetatags->locationsString($aData[$CNF['FIELD_ID']]) : '';
        }

        unset($aVars['recipients']);

        return $aVars;
    }

    public function entryBreadcrumb($aContentInfo, $aTmplVarsItems = array())
    {
    	$CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

    	return $this->parseHtmlByName('breadcrumb.html', array(
    		'url_home' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME'])),
    		'icon_home' => $CNF['ICON'],
    		'bx_repeat:items' => $aTmplVarsItems
    	));
    }

    public function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
        $aVars = $this->getTmplVarsText($aData);

        if (empty($aVars['entry_text']))
            return false;
        
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    public function entryLocation ($iContentId)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if (empty($CNF['OBJECT_METATAGS']))
            return '';

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);        

        if (!($sLocationString = $oMetatags->locationsString($iContentId)))
            return '';

        $aVars = array (
            'location' => $sLocationString
        );
        return $this->parseHtmlByName('entry-location.html', $aVars);
    }

	public function entryInfo($aData, $aValues = array())
    {
    	$CNF = $this->_oConfig->CNF;
        $aValuesDefault = array();

        if (isset($aData[$CNF['FIELD_ADDED']]))
            $aValuesDefault[] = array(
                'title' => _t('_sys_txt_field_created'),
                'value' => bx_time_js($aData[$CNF['FIELD_ADDED']]),
            );

        if (isset($aData[$CNF['FIELD_CHANGED']]))
            $aValuesDefault[] = array(
                'title' => _t('_sys_txt_field_updated'),
                'value' => bx_time_js($aData[$CNF['FIELD_CHANGED']]),
            );

        $aValues = array_merge($aValuesDefault, $aValues);

    	return $this->parseHtmlByName('entry-info.html', array(
    		'bx_repeat:info' => $aValues,
    	));
    }
    
    public function getFavoriteList($oProfile, $iStart, $iPerPage, $aParams)
    {
        $CNF = $this->_oConfig->CNF;
        
        $bEmptyMessage = false;
        if(isset($aParams['empty_message'])) {
            $bEmptyMessage = (bool)$aParams['empty_message'];
            unset($aParams['empty_message']);
        }
        
        $oFavorite = BxDolFavorite::getObjectInstance($CNF['OBJECT_FAVORITES'], 0, true);
        $aListsData = $oFavorite->getQueryObject()->getList(array('type' => 'active', 'author_id' => $oProfile->id(), 'need_default' => true, 'start' => $iStart, 'limit' => $iPerPage + 1));
        $iNum = count($aListsData);
        if ($iNum > $iPerPage)
            $aListsData = array_slice($aListsData, 0, $iPerPage);
        
        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_LIST_VIEW']);
        $aListsTmpl = array();
        foreach($aListsData as $iListId => $sName) {
            $aParams['list_id'] = $iListId;
            if ($oPrivacy->check($iListId) || $iListId == 0){
                $aTmp = $this->getModule()->_serviceBrowse ('favorite', array_merge(array('user' => $oProfile->id()), $aParams), BX_DB_PADDING_DEF, $bEmptyMessage, false);
                if ($aTmp && $aTmp['content']){
                    $aListsTmpl[] = array(
                        'title' => $sName, 
                        'content_url' => $this->getModule()->_getFavoriteListUrl($iListId, $oProfile->id()),
                        'content' => $aTmp['content']
                    );
                }
            }
            else{
                $aListsTmpl[] = array(
                    'title' => 'private list', 
                    'content_url' => 'javascript:',
                    'content' => ''
                );
            }
        } 
        
        $oPaginate = new BxTemplPaginate(array(
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}', " . bx_js_string(json_encode($aParams)) . ", 'list_start', 'list_per_page');",
            'num' => $iNum,
            'per_page' => $iPerPage,
            'start' => $iStart,
        ));
        if (count($aListsTmpl) > 0)
            return $this->parseHtmlByName('favorite-lists.html', array('bx_repeat:items' => $aListsTmpl)) . $oPaginate->getSimplePaginate() . $oFavorite->getJsScript();
        
        return false;
    }
    
    public function getFavoritesListInfo($aList, $oProfile)
    {
        $CNF = $this->_oConfig->CNF;

        $iListId = !empty($aList['id']) ? (int)$aList['id'] : 0;

        $oFavorite = BxDolFavorite::getObjectInstance($CNF['OBJECT_FAVORITES'], 0, true);
        $aListInfo = $oFavorite->getQueryObject()->getList(array('type' => 'info', 'list_id' => $iListId, 'author_id' => $oProfile->id()));

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_LIST_VIEW']);
        $sTitle = "";
        if ($aListInfo['allow_view_favorite_list_to'] < 0){
            $oProfileContext = BxDolProfile::getInstance(abs($aListInfo['allow_view_favorite_list_to']));
            $sTitle = $oProfileContext->getDisplayName();
        }
        else{
            if (empty($aListInfo['allow_view_favorite_list_to']))
                $aListInfo['allow_view_favorite_list_to'] = 3;
            $aPrivaciInfo = $oPrivacy->getGroupsBy(array('type'=>'id' , 'id'=> $aListInfo['allow_view_favorite_list_to']));
            $sTitle = _t($aPrivaciInfo['title']);
        }
        
        $aListsTmpl = array();
        
        if (!empty($aListInfo['created'])){
            $aListsTmpl[] = array('title' => _t('_sys_form_favorite_list_title_created'), 'value' => bx_time_js($aListInfo['created']));
        }
        
        $aListsTmpl = array_merge($aListsTmpl, 
            array(
                array('title' => _t('_sys_form_favorite_list_title_updated'), 'value' => bx_time_js($aListInfo['updated'])),
                array('title' => _t('_sys_form_favorite_list_title_count'), 'value' => $aListInfo['count']),
                array('title' => _t('_sys_form_favorite_list_title_visibility'), 'value' => $sTitle)
            )
        );
        
        return $this->parseHtmlByName('favorite-list-info.html', array('bx_repeat:items' => $aListsTmpl, 'author' => $oProfile->getUnit()));
    }
    

    function entryAllActions($sActions)
    {
        if(empty($sActions))
            return '';

        return $this->parseHtmlByName('entry-all-actions.html', array (
            'actions' => $sActions
        ));
    }

    function entryAttachments ($aData, $aParams = array())
    {
        return $this->entryAttachmentsByStorage($this->getModule()->_oConfig->CNF['OBJECT_STORAGE'], $aData, $aParams);
    }

    function entryAttachmentsByStorage ($mixedStorage, $aData, $aParams = array())
    {
        if(!is_array($mixedStorage))
            $mixedStorage = array($mixedStorage);

        $aResult = array();
        foreach($mixedStorage as $sStorage) {
            $aAttachments = $this->getAttachments($sStorage, $aData, $aParams);
            if(empty($aAttachments) || !is_array($aAttachments))
                continue;

            $aResult = array_merge($aResult, $aAttachments);
        }

        if(empty($aResult) || !is_array($aResult))
            return '';

        $this->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flickity/|flickity.css');
    	$this->addJs(array(
            'flickity/flickity.pkgd.min.js',
            'pdfjs/build/pdf.min.js',
        ));

    	return $this->parseHtmlByName('attachments.html', array(
            'bx_repeat:attachments' => $aResult,
            'pdfjs_worker_url' => $this->getJsUrl('pdfjs/build/pdf.worker.min.js'),
        ));
    }
    
    function entryContext ($aData, $iProfileId = false, $sFuncContextDesc = 'getContextDesc', $sTemplateName = 'context.html', $sFuncContextAddon = 'getContextAddon')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $iContextId = $aData[$CNF['FIELD_ALLOW_VIEW_TO']];
        if ($iContextId >= 0)
            return '';
        
        $iProfileId = - $iContextId;

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        if (!$oProfile)
            return '';

        $sName = $oProfile->getDisplayName();
        $sAddon = $sFuncContextAddon ? $this->$sFuncContextAddon($aData, $oProfile) : '';
        $aVars = array (
            'author_url' => $oProfile->getUrl(),
            'author_thumb_url' => $oProfile->getThumb(),
            'author_unit' => $oProfile->getUnit(0, array('template' => 'unit_wo_info')),
            'author_title' => $sName,
            'author_title_attr' => bx_html_attribute($sName),
            'author_desc' => $sFuncContextDesc ? $this->$sFuncContextDesc($aData) : '',
            'bx_if:addon' => array (
                'condition' => (bool)$sAddon,
                'content' => array (
                    'content' => $sAddon,
                ),
            ),
        );
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    protected function getAttachmentsImagesTranscoders ($sStorage = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $oTranscoderView = !empty($CNF['OBJECT_IMAGES_TRANSCODER_PICTURE']) ? BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PICTURE']) : null;

        return array($oTranscoder, $oTranscoderView);
    }

    protected function getAttachmentsVideoTranscoders ($sStorage = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if (isset($CNF['OBJECT_VIDEOS_TRANSCODERS']) && $CNF['OBJECT_VIDEOS_TRANSCODERS'])
            return array (
                'poster' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']),
                'mp4' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']),
                'mp4_hd' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4_hd']),
            );

        return false;
    }
    
    protected function getAttachments ($sStorage, $aData, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $oStorage = BxDolStorage::getObjectInstance($sStorage);

        list($oTranscoder, $oTranscoderPreview) = $this->getAttachmentsImagesTranscoders($sStorage);
        $oTranscoderSound = isset($CNF['OBJECT_SOUNDS_TRANSCODER']) && $CNF['OBJECT_SOUNDS_TRANSCODER'] ? BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_SOUNDS_TRANSCODER']) : null;
        $aTranscodersVideo = $this->getAttachmentsVideoTranscoders($sStorage);

        $aGhostFiles = $oStorage->getGhosts ($this->getModule()->serviceGetContentOwnerProfileId($aData[$CNF['FIELD_ID']]), $aData[$CNF['FIELD_ID']]);
        if (!$aGhostFiles)
            return false;

        $sFilterField = isset($aParams['filter_field']) ? $aParams['filter_field'] : $CNF['FIELD_THUMB'];
		if(!empty($sFilterField) && isset($aData[$sFilterField]))
	        foreach ($aGhostFiles as $k => $a) {
	            // don't show thumbnail in attachments
	            if ($a['id'] == $aData[$sFilterField])
	                unset($aGhostFiles[$k]);
	        }

        if(!$aGhostFiles)
            return false;

        $aAttachmnts = array();
        foreach ($aGhostFiles as $k => $a) {
            $isImage = !empty($CNF['OBJECT_STORAGE_PHOTOS']) && $sStorage == $CNF['OBJECT_STORAGE_PHOTOS'] && $oTranscoder && (0 === strncmp('image/', $a['mime_type'], 6)) && $oTranscoder->isMimeTypeSupported($a['mime_type']); // preview for images, transcoder object for preview must be defined
            $isVideo = !empty($CNF['OBJECT_STORAGE_VIDEOS']) && $sStorage == $CNF['OBJECT_STORAGE_VIDEOS'] && $aTranscodersVideo && (0 === strncmp('video/', $a['mime_type'], 6)) && $aTranscodersVideo['poster']->isMimeTypeSupported($a['mime_type']); // preview for videos, transcoder object for video must be defined
            $isSound = !empty($CNF['OBJECT_STORAGE_SOUNDS']) && $sStorage == $CNF['OBJECT_STORAGE_SOUNDS'] && $oTranscoderSound && (0 === strncmp('audio/', $a['mime_type'], 6)) && $oTranscoderSound->isMimeTypeSupported($a['mime_type']); // preview for sounds, transcoder object for sounds must be defined
            $sUrlOriginal = $oStorage->getFileUrlById($a['id']);
            $sImgPopupId = 'bx-messages-atachment-popup-' . $a['id'];

            // images are displayed with preview and popup upon clicking
            $a['bx_if:image'] = array (
                'condition' => $isImage,
                'content' => array (
                    'url_original' => $isImage && $oTranscoderPreview ? $oTranscoderPreview->getFileUrl($a['id']) : $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'popup_id' => $sImgPopupId,
                    'url_preview' => $isImage ? $oTranscoder->getFileUrl($a['id']) : '',
                    'popup' =>  BxTemplFunctions::getInstance()->transBox($sImgPopupId, '<img src="' . $sUrlOriginal . '" />', true, true),
                ),
            );

            $sVideoUrlHd = '';
            if($isVideo) {
                $sVideoUrl = $oStorage->getFileUrlById($a['id']);
                $aVideoFile = $oStorage->getFile($a['id']);

                if (!empty($aVideoFile['dimensions']) && $aTranscodersVideo['mp4_hd']->isProcessHD($aVideoFile['dimensions']))
                    $sVideoUrlHd = $aTranscodersVideo['mp4_hd']->getFileUrl($a['id']);
            }

            // videos are displayed inline
            $a['bx_if:video'] = array (
                'condition' => $isVideo,
                'content' => array (
                    'video' => $isVideo && $aTranscodersVideo ? BxTemplFunctions::getInstance()->videoPlayer(
                        $aTranscodersVideo['poster']->getFileUrl($a['id']), 
                        $aTranscodersVideo['mp4']->getFileUrl($a['id']), 
                        $sVideoUrlHd,
                        false, ''
                    ) : '',
                ),
            );

            // sounds are displayed inline
            $a['bx_if:sound'] = array (
                'condition' => $isSound,
                'content' => array (
                    'sound' => $isSound && $oTranscoderSound && ($oPlayer = BxDolPlayer::getObjectInstance()) ? $this->parseHtmlByName('attachment_sound.html', array(
                        'file_name' => $a['file_name'],
                        'file_url' => $oTranscoderSound ? $oTranscoderSound->getFileUrl($a['id']) : '',
                        'player' => $oTranscoderSound && $oTranscoderSound->isFileReady($a['id']) ? 
                            $oPlayer->getCodeAudio (BX_PLAYER_STANDARD, array(
                                'mp3' => $oTranscoderSound->getFileUrl($a['id']),
                            )) : _t('_sys_txt_err_sound_not_transcoded_yet'),
                    )) : '',
                ),
            );

            // non-images are displayed as text links to original file
            $a['bx_if:not_image'] = array (
                'condition' => !$isImage && !$isVideo && !$isSound,
                'content' => array (
                    'url_original' => $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'file_name' => bx_process_output($a['file_name']),
                ),
            );

            $aAttachmnts[] = $a;
        }

        return $aAttachmnts;
    }

    public function embedVideo($iFileId)
    {
        $CNF = $this->getModule()->_oConfig->CNF;
        list($oPlayer, $oStorage, $aContentInfo, $a) = $this->_embedChecks('OBJECT_STORAGE_VIDEOS', $iFileId);

        // check if file is really video
        $aTranscodersVideo = $this->getAttachmentsVideoTranscoders();        
        if (!$aTranscodersVideo || (0 !== strncmp('video/', $a['mime_type'], 6)) || !$aTranscodersVideo['poster']->isMimeTypeSupported($a['mime_type'])) {
            $this->displayErrorOccured('', BX_PAGE_EMBED);
            exit;
        }

        // check HD video version
        $sVideoUrlHd = '';
        $sVideoUrl = $oStorage->getFileUrlById($a['id']);
        $aVideoFile = $oStorage->getFile($a['id']);

        if (!empty($aVideoFile['dimensions']) && $aTranscodersVideo['mp4_hd']->isProcessHD($aVideoFile['dimensions']))
            $sVideoUrlHd = $aTranscodersVideo['mp4_hd']->getFileUrl($a['id']);

        // generate player code
        $sCode = $oPlayer->getCodeVideo (BX_PLAYER_EMBED, array(
            'poster' => $aTranscodersVideo['poster']->getFileUrl($a['id']),
            'mp4' => array(
                'sd' => $aTranscodersVideo['mp4']->getFileUrl($a['id']), 
                'hd' => $sVideoUrlHd
            ),
        ));

        // display page
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_EMBED);
        $oTemplate->setPageHeader ($a['file_name']);
        $oTemplate->setPageContent ('page_main_code', $sCode);
        $oTemplate->getPageCode();
        exit;
    }

    public function embedSound($iFileId)
    {
        $CNF = $this->getModule()->_oConfig->CNF;
        list($oPlayer, $oStorage, $aContentInfo, $a) = $this->_embedChecks('OBJECT_STORAGE_SOUNDS', $iFileId);

        // check if file is really audio
        $oTranscoderSound = isset($CNF['OBJECT_SOUNDS_TRANSCODER']) ? BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_SOUNDS_TRANSCODER']) : null;
        if (!$oTranscoderSound || (0 !== strncmp('audio/', $a['mime_type'], 6)) || !$oTranscoderSound->isMimeTypeSupported($a['mime_type'])) {
            $this->displayErrorOccured('', BX_PAGE_EMBED);
            exit;
        }

        if (!$oTranscoderSound->isFileReady($a['id'])) {
            $oTranscoderSound->getFileUrl($a['id']); // queue for encoding
            $this->displayMsg('_sys_txt_err_sound_not_transcoded_yet', true, BX_PAGE_EMBED);
            exit;
        }

        // generate player code
        $sCode = $oPlayer->getCodeAudio (BX_PLAYER_EMBED, array(
            'mp3' => $oTranscoderSound->getFileUrl($a['id']),
        ));

        // display page
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_EMBED);
        $oTemplate->setPageHeader ($a['file_name']);
        $oTemplate->setPageContent ('page_main_code', $sCode);
        $oTemplate->getPageCode();
        exit;
    }

    protected function _embedChecks($sStorageKey, $iFileId)
    {
        // general checks
        $oPlayer = BxDolPlayer::getObjectInstance();
        $CNF = $this->getModule()->_oConfig->CNF;
        $sStorage = isset($CNF[$sStorageKey]) ? $CNF[$sStorageKey] : false;
        if (!$oPlayer || !$sStorage || !($oStorage = BxDolStorage::getObjectInstance($sStorage))) {
            $this->displayErrorOccured('', BX_PAGE_EMBED);
            exit;
        }

        // privacy check
        $aGhost = $oStorage->getGhost($iFileId);
        $aContentInfo = $aGhost && $aGhost['content_id'] ? $this->getModule()->_oDb->getContentInfoById($aGhost['content_id']) : false;
        if (!$aGhost || ($aGhost['content_id'] && !$aContentInfo)) {
            $this->displayPageNotFound('', BX_PAGE_EMBED);
            exit;
        }
            
        if (!$aGhost['content_id']) {
            // if file is not associated with content yet, then only admin and owner can view it
            if (!isAdmin() && CHECK_ACTION_RESULT_ALLOWED !== $this->getModule()->checkAllowedEditAnyEntry() && $aGhost['profile_id'] != bx_get_logged_profile_id()) {
                $this->displayAccessDenied('', BX_PAGE_EMBED);
                exit;
            }
        }
        else {
            // if file is associated with content, then check entry privacy
            if (CHECK_ACTION_RESULT_ALLOWED !== $this->getModule()->checkAllowedView($aContentInfo)) {
                $this->displayAccessDenied('', BX_PAGE_EMBED);
                exit;
            }
        }

        // get file info
        if (!($a = $oStorage->getFile($iFileId))) {
            $this->displayPageNotFound('', BX_PAGE_EMBED);
            exit;
        }

        return array($oPlayer, $oStorage, $aContentInfo, $a);
    }
    
    function _getImageSettings($sSettings)
    {
        $sCoverSettings = '';
        $aCoverData = json_decode($sSettings, true);
        if (!empty($aCoverData)){
            $sCoverSettings = 'background-position: ' . $aCoverData['x'] . '% ' . $aCoverData['y'] . '%';
        }
        return $sCoverSettings;
    }

    function _prepareImage($aData, $sUniqId, $sUploader, $sStorage, $sField, $bAllowTweak)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $oUploader = null;
        $sUploadersButtons = $sUploadersJs = '';

        $aUploaders = $sUploader;
        $sUploadersJs = '';
        $sJsName = '';
        foreach ($aUploaders as $sUploaderObject) {
            $oUploader = BxDolUploader::getObjectInstance($sUploaderObject, $sStorage, $sUniqId, $this);
            $sGhostTemplate = '{file_id}';

            $aParamsJs = array_merge($oUploader->getUploaderJsParams(), 
                [
                    'content_id' => $aData['id'],
                    'storage_private' => '0',
                    'is_init_ghosts' => 0,
                    'is_init_reordering' => 0
                ]
            );
            $sUploadersJs .= $oUploader->getUploaderJs($sGhostTemplate, false, $aParamsJs);
            $sJsName = $oUploader->getNameJsInstanceUploader();
        }

        $aParamsButtons = [
            'content_id' => $aData['id'],
            'storage_private' => '0',
            'btn_class' => '',
            'button_title' => '',
            'attrs' => "class='hidden'"
        ];
        
        $this->addJs(['BxDolUploader.js']);

        $sAddCode = $this->parseHtmlByName('image_tweak.html', [
            'id' => $aData['id'],
            'js_object' => $sJsName,
            'unique_id' => $sUniqId,
            'id' => $aData['id'],
            'allow_tweak' => $bAllowTweak,
            'image_exists' => $aData[$sField] == 0 ? 'bx-image-edit-buttons-no-image' : '',
            'field' => $sField,
            'action_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(),
            'uploader' => $oUploader->getUploaderButton($aParamsButtons),
            'uploader_js' => $sUploadersJs,
        ]); 
        $this->addJsTranslation(['_sys_uploader_image_reposition_info']);        
        return $sAddCode;
    }

    public function addCssJs()
    {
        $this->addCss('main.css');
    }
}

/** @} */
