<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Module representation.
 */
class BxBaseModTextTemplate extends BxBaseModGeneralTemplate
{
    protected $_sUnitClassShowCase;
    
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
        
        $this->_sUnitClassShowCase = 'bx-base-unit-showcase bx-base-text-unit-showcase bx-def-margin-sec-bottom';
    }

    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds(),
            'sEditorId' => isset($CNF['FIELD_TEXT_ID']) ? $CNF['FIELD_TEXT_ID'] : ''
        ), $aParams);

        return parent::getJsCode($sType, $aParams, $bWrap);
    }

    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html', $aParams = array())
    {
    	$sResult = $this->checkPrivacy ($aData, $isCheckPrivateContent, $this->getModule(), $sTemplateName);
    	if($sResult)
            return $sResult;
        $aParams['template_name'] = $sTemplateName;
		return $this->parseHtmlByName($sTemplateName, $this->getUnit($aData, $aParams));
    }

    function entryAttachments ($aData, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aStoragesKeys = array('OBJECT_STORAGE_PHOTOS', 'OBJECT_STORAGE_VIDEOS', 'OBJECT_STORAGE_SOUNDS', 'OBJECT_STORAGE_FILES');

        $aStorages = array();
        foreach($aStoragesKeys as $sKey)
            if(!empty($CNF[$sKey]))
                $aStorages[] = $CNF[$sKey];

        if(!empty($aStorages))
            return $this->entryAttachmentsByStorage($aStorages, $aData, array_merge($aParams, array('filter_field' => '')));

        return parent::entryAttachments($aData, $aParams);
    }

    function entryAuthor ($aData, $iProfileId = false, $sFuncAuthorDesc = 'getAuthorDesc', $sTemplateName = 'author.html', $sFuncAuthorAddon = 'getAuthorAddon')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if (!$iProfileId)
            $iProfileId = $aData[$CNF['FIELD_AUTHOR']];
        
        $oProfile = BxDolProfile::getInstanceMagic($iProfileId);
        $sName = $oProfile->getDisplayName();
        $sAddon = $sFuncAuthorAddon && is_a($oProfile, 'BxDolProfile') ? $this->$sFuncAuthorAddon($aData, $oProfile) : '';        

        $aVars = array (
            'author_url' => $oProfile->getUrl(),
            'author_thumb_url' => $oProfile->getThumb(),
            'author_unit' => $oProfile->getUnit(0, array('template' => 'unit_wo_info')),
            'author_title' => $sName,
            'author_title_attr' => bx_html_attribute($sName),
            'author_desc' => $sFuncAuthorDesc ? $this->$sFuncAuthorDesc($aData, $oProfile) : '',
            'author_profile_desc' => $this->getAuthorProfileDesc($aData, $oProfile),
            'bx_if:addon' => array (
                'condition' => (bool)$sAddon,
                'content' => array (
                    'content' => $sAddon,
                ),
            ),
        );
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    public function entryBreadcrumb($aContentInfo, $aTmplVarsItems = array())
    {
        if(!empty($aTmplVarsItems) && is_array($aTmplVarsItems))
            return parent::entryBreadcrumb($aContentInfo, $aTmplVarsItems);

    	$CNF = &$this->getModule()->_oConfig->CNF;

        $aTmplVarsItems = array();
        if(!empty($CNF['OBJECT_CATEGORY']) && !empty($CNF['FIELD_CATEGORY'])) {
            $oCategory = BxDolCategory::getObjectInstance($CNF['OBJECT_CATEGORY']);

            $aTmplVarsItems[] = array(
                'url' => $oCategory->getCategoryUrl($aContentInfo[$CNF['FIELD_CATEGORY']]),
                'title' => $oCategory->getCategoryTitle($aContentInfo[$CNF['FIELD_CATEGORY']])
            );
        }

    	$aTmplVarsItems[] = array(
            'url' => BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]),
            'title' => bx_process_output($aContentInfo[$CNF['FIELD_TITLE']])
        );

    	return parent::entryBreadcrumb($aContentInfo, $aTmplVarsItems);
    }

    public function entryPolls($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        
        $aPolls = $this->_oDb->getPolls(array(
            'type' => 'content_id', 
            'content_id' => $aData[$CNF['FIELD_ID']], 
        ));
        if(empty($aPolls) || !is_array($aPolls))
            return;

        $iProfileId = bx_get_logged_profile_id();

        $sPolls = '';
        foreach($aPolls as $aPoll)
            $sPolls .= $this->getPollItem($aPoll, $iProfileId);

        if(empty($sPolls))
            return '';

        $this->_addCssJs();
        return $this->getJsCode('poll') . $this->parseHtmlByName('poll_items.html', array(
            'polls' => $sPolls
        ));
    }

    public function entryPollAnswers($aPoll, $bDynamic = false)
    {
        $sContent = $this->_getPollAnswers($aPoll, $bDynamic);
        if(empty($sContent))
            return '';

    	return array(
            'content' => $sContent,
            'menu' => $this->_getPollBlockMenu($aPoll, 'answers')
        ); 
    }

    public function entryPollResults($aPoll, $bDynamic = false)
    {
        $sContent = $this->_getPollResults($aPoll, $bDynamic);
        if(empty($sContent))
            return '';

        return array(
            'content' => $sContent,
            'menu' => $this->_getPollBlockMenu($aPoll, 'results')
        );  
    }

    public function getPollForm()
    {
        $aForm = $this->getModule()->getPollForm();

        return $this->parseHtmlByName('poll_form.html', array(
            'js_object' => $this->_oConfig->getJsObject('poll'),
            'form_id' => $aForm['form_id'],
            'form' => $aForm['form'],
        ));
    }
    
    public function getPollField($iContentId = 0, $iProfileId = 0)
    {
        if(empty($iProfileId))
            $iProfileId = bx_get_logged_profile_id();

        $aPolls = array();
        if(!empty($iContentId))
            $aPolls = array_merge($aPolls, $this->_oDb->getPolls(array(
                'type' => 'content_id', 
                'content_id' => $iContentId, 
            )));

        $aPolls = array_merge($aPolls, $this->_oDb->getPolls(array(
            'type' => 'author_id', 
            'author_id' => $iProfileId, 
            'unused' => true
        )));

        $sPolls = '';
        foreach($aPolls as $aPoll)
            $sPolls .= $this->getPollItem($aPoll, $iProfileId, array(
                'manage' => true
            ));

        return $this->parseHtmlByName('poll_form_field.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('add_poll_form_field'),
            'polls' => $sPolls
        ));
    }

    public function getPollItem($mixedPoll, $iProfileId = 0, $aParams = array())
    {
        $oModule = $this->getModule();
        $CNF = &$oModule->_oConfig->CNF;

        $aPoll = is_array($mixedPoll) ? $mixedPoll : $this->_oDb->getPolls(array('type' => 'id', 'id' => (int)$mixedPoll));
        if(empty($aPoll) || !is_array($aPoll))
            return '';

        $sJsObject = $this->_oConfig->getJsObject('poll');

        $bDynamic = isset($aParams['dynamic']) && $aParams['dynamic'] === true;
        $bManage = isset($aParams['manage']) && $aParams['manage'] === true;
        $bSwitchMenu = isset($aParams['switch_menu']) ? (bool)$aParams['switch_menu'] : true;
        $bForceDisplayAnswers = isset($aParams['force_display_answers']) && (bool)$aParams['force_display_answers'] === true;

        $iPollId = (int)$aPoll[$CNF['FIELD_POLL_ID']];
        $sPollView = !$bForceDisplayAnswers && $oModule->isPollPerformed($iPollId, $iProfileId) ? 'results' : 'answers';
        
        $sMethod = '_getPoll' . ucfirst($sPollView);
        if(!method_exists($this, $sMethod))
            return '';

        $mixedMenu = '';
        if($bSwitchMenu)
            $mixedMenu = $this->_getPollBlockMenu($aPoll, $sPollView, array('template' => 'menu_interactive.html'));

        return $this->parseHtmlByName('poll_item.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('poll') . $iPollId,
            'bx_if:show_input_hidden' => array(
                'condition' => $bManage,
                'content' => array(
                    'name' => $CNF['FIELD_POLL'],
                    'id' => $iPollId
                )
            ),
            'action_menu' => !empty($mixedMenu) ? $mixedMenu->getCode() : '',
            'bx_if:show_action_embed' => array(
                'condition' => $bManage,
                'content' => array(
                    'js_object' => $sJsObject,
                    'id' => $iPollId
                )
            ),
            'bx_if:show_action_delete' => array(
                'condition' => $bManage,
                'content' => array(
                    'js_object' => $sJsObject,
                    'id' => $iPollId
                )
            ),
            'text' => bx_process_output($aPoll[$CNF['FIELD_POLL_TEXT']], BX_DATA_TEXT),
            'content' => $this->$sMethod($aPoll, $bDynamic)
        ));
    }

    public function embedPollItem($mixedPoll, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sHeader = '';
        $sContent = $this->getPollItem($mixedPoll, 0, $aParams);
        if(!empty($sContent)) {
            $aPoll = is_array($mixedPoll) ? $mixedPoll : $this->_oDb->getPolls(array('type' => 'id', 'id' => (int)$mixedPoll));

            $sHeader = strmaxtextlen($aPoll[$CNF['FIELD_POLL_TEXT']], 32, '...');
            $sContent = $this->getJsCode('poll') . $sContent;
        }

        $this->_addCssJs();

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addCssStyle($CNF['STYLES_POLLS_EMBED_CLASS'], $CNF['STYLES_POLLS_EMBED_CONTENT']);
        $oTemplate->setPageNameIndex(BX_PAGE_EMBED);
        $oTemplate->setPageHeader($sHeader);
        $oTemplate->setPageContent('page_main_code', $sContent);
        $oTemplate->getPageCode();
        exit;
    }

    public function embedPollItems($mixedContentInfo, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aContentInfo = is_array($mixedContentInfo) ? $mixedContentInfo : $this->_oDb->getContentInfoById((int)$mixedContentInfo);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return;

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];

        $aPolls = $this->_oDb->getPolls(array('type' => 'content_id', 'content_id' => $iContentId));
        if(empty($aPolls) || !is_array($aPolls))
            return;

        $iPolls = 0;
        $sContent = '';
        foreach($aPolls as $aPoll) {
            $sPoll = $this->getPollItem($aPoll, 0, $aParams);
            if(empty($sPoll))
                continue;

            $sContent .= $sPoll;
            $iPolls += 1;
        }

        if(!empty($sContent) && isset($aParams['showcase']) && (bool)$aParams['showcase'] === true) {
            $this->addJs(array('flickity/flickity.pkgd.min.js'));
            $this->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flickity/|flickity.css');

            $sContent = $this->parseHtmlByName('poll_items_showcase.html', array(
                'js_object' => $this->_oConfig->getJsObject('poll'),
                'html_id' => $this->_oConfig->getHtmlIds('polls_showcase') . $iContentId,
                'type' => $iPolls == 1 ? 'single' : 'multiple',
                'polls' => $sContent
            ));
        }

        $sHeader = '';
        if(!empty($sContent)) {
            $sHeader = strmaxtextlen($aContentInfo[$CNF['FIELD_TITLE']], 32, '...');
            $sContent = $this->getJsCode('poll') . $sContent;
        }

        $this->_addCssJs();

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addCssStyle($CNF['STYLES_POLLS_EMBED_CLASS'], $CNF['STYLES_POLLS_EMBED_CONTENT']);
        $oTemplate->setPageNameIndex(BX_PAGE_EMBED);
        $oTemplate->setPageHeader($sHeader);
        $oTemplate->setPageContent('page_main_code', $sContent);
        $oTemplate->getPageCode();
        exit;
    }

    protected function _addCssJs()
    {
        $this->addJs(array(
            'modules/base/text/js/|polls.js',
            'polls.js'
        ));
        $this->addCss(array('polls.css'));
    }

    protected function _getPollAnswers($aPoll, $bDynamic = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        
        $aAnswers = $this->_oDb->getPollAnswers(array('type' => 'poll_id', 'poll_id' => $aPoll[$CNF['FIELD_POLL_ID']]));
        if(empty($aAnswers) || !is_array($aAnswers))
            return '';

        $aTmplVarsAnswers = array();
        foreach($aAnswers as $aAnswer) {
            $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_POLL_ANSWERS'], $aAnswer['id']);

            $aTmplVarsAnswers[] = array(
                'answer' => $oVotes->getElementBlock(array(
                    'dynamic_mode' => $bDynamic
                ))
            );
        }

    	return $this->parseHtmlByName('poll_item_answers.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('poll_content') . $aPoll[$CNF['FIELD_POLL_ID']],
            'bx_repeat:answers' => $aTmplVarsAnswers
        ));
    }
    
    protected function _getPollResults($aPoll, $bDynamic = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aAnswers = $this->_oDb->getPollAnswers(array('type' => 'poll_id', 'poll_id' => $aPoll[$CNF['FIELD_POLL_ID']]));
        if(empty($aAnswers) || !is_array($aAnswers))
            return '';

        $iTotal = 0;
        foreach($aAnswers as $aAnswer)
            $iTotal += $aAnswer['votes'];

        $aTmplVarsAnswers = array();
        foreach($aAnswers as $aAnswer) {
            $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_POLL_ANSWERS'], $aAnswer['id']);
            $aVotesParams = array('show_counter_empty' => true, 'show_counter_in_brackets' => false, 'dynamic_mode' => $bDynamic);

            $fPercent = $iTotal > 0 ? 100 * (float)$aAnswer['votes']/$iTotal : 0;
            $aTmplVarsAnswers[] = array(
                'title' => bx_process_output($aAnswer['title']),
                'width' => (int)round($fPercent) . '%',
                'votes' => $oVotes->getCounter($aVotesParams),
                'percent' => _t($CNF['T']['txt_poll_answer_vote_percent'], $iTotal > 0 ? round($fPercent, 2) : 0)
            );
        }

        return $this->parseHtmlByName('poll_item_results.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('poll_content') . $aPoll[$CNF['FIELD_POLL_ID']],
            'bx_repeat:answers' => $aTmplVarsAnswers,
        ));
    }

    protected function _getPollBlockMenu($aPoll, $sSelected = '', $aParams = array())
    {
        $oModule = $this->getModule();
        $CNF = &$oModule->_oConfig->CNF;

        $sPostfix = '-' . time() . rand(0, PHP_INT_MAX);
        $sJsObject = $this->_oConfig->getJsObject('poll');
        $iPollId = $aPoll[$CNF['FIELD_POLL_ID']];

        $aViews = array(
            'answers' => true, 
            'results' => $CNF['PARAM_POLL_HIDDEN_RESULTS'] === false || $oModule->isPollPerformed($iPollId)
        );

        $aMenu = array();
        foreach($aViews as $sView => $bActive) {
            if(!$bActive) 
                continue;

            $sId = $this->_oConfig->getHtmlIds('poll_view_link_' . $sView) . $iPollId;
            if(!empty($sSelected) && $sSelected == $sView)
                $sSelected = $sId;

            $aMenu[] = array(
                'id' => $sId, 
                'name' => $sId, 
                'class' => '', 
                'link' => 'javascript:void(0)', 
                'onclick' => 'javascript:' . $sJsObject . '.changePollView(this, \'' . $sView . '\', ' . $iPollId . ')', 
                'target' => '_self', 
                'title_attr' => _t($CNF['T']['txt_poll_menu_view_' . $sView]), 
                'title' => $this->parseIcon($CNF['ICON_POLLS_' . strtoupper($sView)])
            );
        }

        if(count($aMenu) <= 1)
            return '';

        $oMenu = new BxTemplMenuInteractive(array(
            'template' => !empty($aParams['template']) ? $aParams['template'] : 'menu_interactive_vertical.html', 
            'menu_id' => $this->_oConfig->getHtmlIds('poll_view_menu') . $sPostfix, 
            'menu_items' => $aMenu
        ));

        if(!empty($sSelected))
            $oMenu->setSelected('', $sSelected);

        return $oMenu;
    }

    public function getTmplVarsText($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aVars = parent::getTmplVarsText($aData);

        $sImage = '';
        $mixedImage = $this->_getHeaderImage($aData);
        if($mixedImage !== false) {
            if(!empty($mixedImage['object']))
                $o = BxDolStorage::getObjectInstance($mixedImage['object']);
            else if(!empty($mixedImage['transcoder']))
                $o = BxDolTranscoder::getObjectInstance($mixedImage['transcoder']);

            if($o)
                $sImage = $o->getFileUrlById($mixedImage['id']);
        }

        $aVars['content_description_before'] = '';
        $aVars['content_description_after'] = '';
        $aVars['bx_if:show_image'] = array(
            'condition' => !empty($sImage),
            'content' => array(
                'entry_image' => $sImage
            )
        );

        if(!empty($CNF['OBJECT_REACTIONS'])) {
            $oReactions = BxDolVote::getObjectInstance($CNF['OBJECT_REACTIONS'], $aData[$CNF['FIELD_ID']]);
            if($oReactions)
                $aVars['content_description_after'] .= $oReactions->getCounter(array(
                    'show_counter' => true
                ));
        }

        return $aVars;
    }

    function getAuthorDesc($aData, $oProfile)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aItem = array(
            'bx_if:text' => array(
                'condition' => false,
                'content' => array(
                    'content' => ''
                )
            ),
            'bx_if:link' => array(
                'condition' => false,
                'content' => array(
                    'link' => '',
                    'content' => ''
                )
            )
        );

        $aTmplVarsItems = array();
        if(!empty($CNF['FIELD_ADDED']) && !empty($aData[$CNF['FIELD_ADDED']]))
            $aTmplVarsItems[] = array_merge($aItem, array('bx_if:text' => array(
                'condition' => true,
                'content' => array(
                    'content' => bx_time_js($aData[$CNF['FIELD_ADDED']], BX_FORMAT_DATE)
                )
            )));

        if(!empty($CNF['URI_AUTHOR_ENTRIES']))
            $aTmplVarsItems[] = array_merge($aItem, array('bx_if:link' => array(
                'condition' => true,
                'content' => array(
                    'link' => BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . $oProfile->id()),
                    'content' => _t($CNF['T']['txt_all_entries_by'], $this->getModule()->_oDb->getEntriesNumByAuthor($oProfile->id()))
                )
            )));

        return $this->parseHtmlByName('author_desc.html', array(
            'bx_repeat:items' => $aTmplVarsItems
        ));
    }

    function getAuthorProfileDesc ($aData, $oProfile)
    {
        $aSnippetMeta = $this->getProfileSnippetMenu($aData);
        if(empty($aSnippetMeta) || !is_array($aSnippetMeta) || !isset($aSnippetMeta['meta']))
            return '';

        return $aSnippetMeta['meta'];
    }

    function getProfileSnippetMenu ($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        if (!($oProfile = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']])))
            return array();

        return bx_srv($oProfile->getModule(), 'get_snippet_menu_vars', array($oProfile->id()));
    }

    function getAuthorAddon ($aData, $oProfile)
    {
        return '';
    }

    protected function checkPrivacy ($aData, $isCheckPrivateContent, $oModule, $sTemplateName = '')
    {
        if ($isCheckPrivateContent && CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $oModule->checkAllowedView($aData))) {
            $aVars = array (
                'summary' => $sMsg,
            );
            return $this->parseHtmlByName($sTemplateName ? str_replace('.html', '_private.html', $sTemplateName) : 'unit_private.html', $aVars);
        }

        return '';
    }

    protected function getUnitThumbAndGallery ($aData)
    {
        $CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

        $sPhotoThumb = '';
        $sPhotoGallery = '';
        if(!empty($CNF['FIELD_THUMB']) && !empty($aData[$CNF['FIELD_THUMB']])) {

            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
            if ($oImagesTranscoder)
                $sPhotoThumb = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_THUMB']]);

            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']);
            if ($oImagesTranscoder)
                $sPhotoGallery = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_THUMB']]);
            else
                $sPhotoGallery = $sPhotoThumb;
        }

        return array($sPhotoThumb, $sPhotoGallery);
    }

    protected function getUnit ($aData, $aParams = array())
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        // get thumb url
        list($sPhotoThumb, $sPhotoGallery) = $this->getUnitThumbAndGallery($aData);
		
		if ($sPhotoGallery == '' && isset($CNF['PARAM_USE_GALERY_AS_COVER']) && getParam($CNF['PARAM_USE_GALERY_AS_COVER']) == 'on'){
			if(!empty($CNF['OBJECT_STORAGE_PHOTOS'])){
				$sStorage = $CNF['OBJECT_STORAGE_PHOTOS'];
				$oStorage = BxDolStorage::getObjectInstance($sStorage); 
				list($oTranscoder, $oTranscoderPreview) = $this->getAttachmentsImagesTranscoders($sStorage);
				$aGhostFiles = $oStorage->getGhosts ($this->getModule()->serviceGetContentOwnerProfileId($aData[$CNF['FIELD_ID']]), $aData[$CNF['FIELD_ID']]);
				if ($aGhostFiles){
					foreach ($aGhostFiles as $k => $a) {
						$sPhotoGallery = $oTranscoder->getFileUrl($a['id']);
						$sPhotoThumb  = $oTranscoderPreview->getFileUrl($a['id']);
						break; 
					}
				}
			}
		}

        // get entry url
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        $oProfile = BxDolProfile::getInstanceMagic($aData[$CNF['FIELD_AUTHOR']]);
        
        $sTitle = $this->getTitle($aData);
        $sText = $this->getText($aData);
        $sSummary = $this->getSummary($aData, $sTitle, $sText, $sUrl);
        $sSummaryPlain = isset($CNF['PARAM_CHARS_SUMMARY_PLAIN']) && $CNF['PARAM_CHARS_SUMMARY_PLAIN'] ? BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($sSummary), (int)getParam($CNF['PARAM_CHARS_SUMMARY_PLAIN'])) : '';

        if(!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            $sText = $oMetatags->metaParse($aData[$CNF['FIELD_ID']], $sText);
        }

        $aTmplVarsMeta = array();
        if(!empty($CNF['OBJECT_MENU_SNIPPET_META'])) {
            $oMenuMeta = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SNIPPET_META'], $this);
            if($oMenuMeta) {
                $oMenuMeta->setContentId($aData[$CNF['FIELD_ID']]);
                $aTmplVarsMeta = array(
                    'meta' => $oMenuMeta->getCode()
                );
            }
        }

        $aTmplVars = array (
            'class' => $this->_getUnitClass($aData,(isset($aParams['template_name']) ? $aParams['template_name'] : '')),
            'id' => $aData[$CNF['FIELD_ID']],
            'content_url' => $sUrl,
            'title' => $sTitle,
            'badges' => $oModule->serviceGetBadges($aData[$CNF['FIELD_ID']], false, true),
            'title_attr' => bx_html_attribute($sTitle),
            'summary' => $sSummary,
            'text' => $sText,
            'author' => $oProfile->getDisplayName(),
            'author_url' => $oProfile->getUrl(),
            'author_icon' => $oProfile->getIcon(),
            'author_thumb' => $oProfile->getThumb(),
            'author_avatar' => $oProfile->getAvatar(),
            'entry_posting_date' => bx_time_js($aData[$CNF['FIELD_ADDED']], BX_FORMAT_DATE),
            'module_name' => _t($CNF['T']['txt_sample_single']),
            'ts' => $aData[$CNF['FIELD_ADDED']],
            'bx_if:meta' => array(
                'condition' => !empty($aTmplVarsMeta),
                'content' => $aTmplVarsMeta
            ),
            'bx_if:thumb' => array (
                'condition' => $sPhotoThumb,
                'content' => array (
                    'title' => $sTitle,
                    'summary_attr' => bx_html_attribute($sSummaryPlain),
                    'content_url' => $sUrl,
                    'thumb_url' => $sPhotoThumb ? $sPhotoThumb : '',
                    'gallery_url' => $sPhotoGallery ? $sPhotoGallery : '',
                    'strecher' => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', 40),
                ),
            ),
            'bx_if:no_thumb' => array (
                'condition' => !$sPhotoThumb,
                'content' => array (
                    'module_icon' => $CNF['ICON'],
                    'content_url' => $sUrl,
                    'summary_plain' => $sSummaryPlain,
                    'strecher' => mb_strlen($sSummaryPlain) > 240 ? '' : str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', round((240 - mb_strlen($sSummaryPlain)) / 6)),
                ),
            ),
        );

        if(isset($aParams['template_vars']) && is_array($aParams['template_vars']))
            $aTmplVars = array_merge($aTmplVars, $aParams['template_vars']);

        // generate html
        return $aTmplVars;
    }

    protected function getAttachmentsImagesTranscoders ($sStorage = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $oTranscoder = null;
        $oTranscoderView = null;

        if(isset($CNF['OBJECT_STORAGE_PHOTOS']) && $CNF['OBJECT_STORAGE_PHOTOS'] == $sStorage) {
            if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS']))
                $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS']);
            if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_PHOTOS']))
                $oTranscoderView = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_PHOTOS']);
        }
        else if(isset($CNF['OBJECT_STORAGE_FILES']) && $CNF['OBJECT_STORAGE_FILES'] == $sStorage) {
            if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_FILES']))
                $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_FILES']);
            if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_FILES']))
                $oTranscoderView = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_FILES']);
        }
        else
            list($oTranscoder, $oTranscoderView) = parent::getAttachmentsImagesTranscoders($sStorage);

        return array($oTranscoder, $oTranscoderView);
    }

    protected function _getUnitClass($aData, $sTemplateName = 'unit.html')
    {
        $sResult = '';

        switch($sTemplateName) {
            case 'unit_showcase.html':
                $sResult = $this->_sUnitClassShowCase;
                break;
        }

        return $sResult;
    }

    protected function _getHeaderImage($aData)
    {
        return $this->getModule()->getEntryImageData($aData);
    }
    
    function mediaExif ($aMediaInfo, $iProfileId = false, $sFuncAuthorDesc = '', $sTemplateName = 'media-exif.html') 
    {

        if (!$aMediaInfo['exif'])
            return '';

        $a = unserialize($aMediaInfo['exif']);

        $s = '';
        if (!empty($a['Make'])) {
            $oModule = BxDolModule::getInstance($this->MODULE);
            $CNF = &$oModule->_oConfig->CNF;
          
            $sCamera = BxDolMetatags::keywordsCameraModel($a);
            if (!empty($CNF['OBJECT_METATAGS_MEDIA_CAMERA'])) {
                $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA_CAMERA']);
                if ($oMetatags->keywordsIsEnabled()) {
                    $sCamera = $oMetatags->keywordsParseOne($aMediaInfo['id'], $sCamera);
                }
            }

            if (!empty($sCamera))
                $s .= $this->parseHtmlByName('media-exif-value.html', array(
                    'key' => _t($CNF['T']['txt_media_exif_camera']), 
                    'val' => $sCamera,
                ));
        }
        
        if (!empty($a['FocalLength']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t($CNF['T']['txt_media_exif_focal_length']),
                'val' => _t($CNF['T']['txt_media_exif_focal_length_value'], $a['FocalLength']),
            ));

        if (!empty($a['COMPUTED']['ApertureFNumber']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t($CNF['T']['txt_media_exif_aperture']),
                'val' => $a['COMPUTED']['ApertureFNumber'],
            ));

        if (!empty($a['ExposureTime']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t($CNF['T']['txt_media_exif_shutter_speed']),
                'val' => _t($CNF['T']['txt_media_exif_shutter_speed_value'], $a['ExposureTime']),
            ));

        if (!empty($a['ISOSpeedRatings']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t($CNF['T']['txt_media_exif_iso']),
                'val' => $a['ISOSpeedRatings'],
            ));

        if (empty($s))
            return '';
        
        return $this->parseHtmlByName($sTemplateName, array('content' => $s));
    }
}

/** @} */
