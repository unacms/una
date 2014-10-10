<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModNotificationsTemplate');

class BxTimelineTemplate extends BxBaseModNotificationsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getCssJs()
    {
    	parent::getCssJs();

        $this->addCss(array(
            'jquery-ui/jquery-ui.css',
            'post.css',
            'share.css',
        ));
        $this->addJs(array(
            'jquery-ui/jquery-ui.custom.min.js',
            'jquery.form.min.js',
            'jquery.ba-resize.min.js',
            'masonry.pkgd.min.js',
            'post.js',
            'share.js',
        ));
    }

    public function getPostBlock($iOwnerId)
    {
        $aForm = $this->getModule()->getFormPost();

        return $this->parseHtmlByName('block_post.html', array (
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('post'),
            'js_content' => $this->getJsCode('post', array(
                'owner_id' => $iOwnerId
            )),
            'form' => $aForm['form']
        ));
    }

    public function getViewBlock($aParams)
    {
        list($sContent, $sLoadMore, $sBack) = $this->getPosts($aParams);

        return $this->parseHtmlByName('block_view.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
        	'html_id' => $this->_oConfig->getHtmlIds('view', 'main'),
            'back' => $sBack,
            'content' => $sContent,
            'load_more' =>  $sLoadMore,
        	'view_image_popup' => $this->_getImagePopup(),
            'js_content' => $this->getJsCode('view', array(
                'type' => $aParams['type'],
                'owner_id' => $aParams['owner_id'],
                'start' => $aParams['start'],
                'per_page' => $aParams['per_page'],
                'filter' => $aParams['filter'],
                'modules' => $aParams['modules'],
                'timeline' => $aParams['timeline'],
            )) . $this->getJsCode('share')
        ));
    }

    public function getItemBlock($iId)
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return '';

        $sContent = $this->getJsCode('view');
        $sContent .= $this->getPost($aEvent, array('type' => BX_TIMELINE_TYPE_ITEM));
        $sContent .= $this->_getImagePopup();

        return $sContent;
    }

    public function getPost(&$aEvent, $aBrowseParams = array())
    {
        $aResult = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $this->_getSystemData($aEvent) : $this->_getCommonData($aEvent);
        if(empty($aResult) || empty($aResult['owner_id']) || empty($aResult['content']))
            return '';

        list($sUserName) = $this->getModule()->getUserInfo($aResult['owner_id']);

        if(empty($aEvent['title']) || empty($aEvent['description'])) {
            $sTitle = !empty($aResult['title']) ? $aResult['title'] : '';
            if($sTitle == '') {
                $sSample = !empty($aResult['content']['sample']) ? $aResult['content']['sample'] : '_bx_timeline_txt_sample';
                $sTitle = _t('_bx_timeline_txt_user_added_sample', $sUserName, _t($sSample));
            }

            $sDescription = !empty($aResult['description']) ? $aResult['description'] : '';
            if($sDescription == '' && !empty($aResult['content']['text']))
                $sDescription = $aResult['content']['text'];

            $this->_oDb->updateEvent(array(
                'title' => bx_process_input(strip_tags($sTitle)),
                'description' => bx_process_input(strip_tags($sDescription))
            ), array('id' => $aEvent['id']));
        }

        $aEvent['object_owner_id'] = $aResult['owner_id'];
        $aEvent['content'] = $aResult['content'];
        $aEvent['votes'] = $aResult['votes'];
        $aEvent['comments'] = $aResult['comments'];

        $sType = !empty($aResult['content_type']) ? $aResult['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
        return $this->_getPost($sType, $aEvent, $aBrowseParams);
    }

    public function getPosts($aParams)
    {
        $iStart = $aParams['start'];
        $iPerPage = $aParams['per_page'];

        $aParamsDb = $aParams;

        //--- Check for Previous
        $bPrevious = false;
        if($iStart - 1 >= 0) {
            $aParamsDb['start'] -= 1;
            $aParamsDb['per_page'] += 1;
            $bPrevious = true;
        }

        //--- Check for Next
        $aParamsDb['per_page'] += 1;
        $aEvents = $this->_oDb->getEvents($aParamsDb);

        //--- Check for Previous
        if($bPrevious)
            $aEvent = array_shift($aEvents);

        //--- Check for Next
        $bNext = false;
        if(count($aEvents) > $iPerPage) {
            $aEvent = array_pop($aEvents);
            $bNext = true;
        }

        $iEvents = count($aEvents);
        $sContent = $this->getEmpty($iEvents <= 0);

        foreach($aEvents as $aEvent) {
            $sEvent = $this->getPost($aEvent, $aParams);
            if(empty($sEvent))
                continue;

            $sContent .= $sEvent;
        }

        $iYearSel = (int)$aParams['timeline'];
        $iYearMin = $this->_oDb->getMaxDuration($aParams);

        $sBack = $this->getBack($iYearSel);
        $sLoadMore = $this->getLoadMore($iStart, $iPerPage, $bNext, $iYearSel, $iYearMin, $iEvents > 0);
        return array($sContent, $sLoadMore, $sBack);
    }

    public function getEmpty($bVisible)
    {
        return $this->parseHtmlByName('empty.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'visible' => $bVisible ? 'block' : 'none',
            'content' => MsgBox(_t('_bx_timeline_txt_msg_no_results'))
        ));
    }

    public function getBack($iYearSel)
    {
        if($iYearSel == 0)
            return '';

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        $iYearNow = date('Y', time());
        return $this->parseHtmlByName('back.html', array(
            'style_prefix' => $sStylePrefix,
            'href' => 'javascript:void(0)',
            'title' => _t('_bx_timeline_txt_jump_to_n_year', $iYearNow),
            'bx_repeat:attrs' => array(
                array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.changeTimeline(this, 0)')
            ),
            'content' => _t('_bx_timeline_txt_jump_to_recent')
        ));
    }

    public function getLoadMore($iStart, $iPerPage, $bEnabled, $iYearSel, $iYearMin, $bVisible = true)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        $sYears = '';
        if(!empty($iYearMin)) {
            $iYearMax = date('Y', time()) - 1;
            for($i = $iYearMax; $i >= $iYearMin; $i--)
                $sYears .= ($i != $iYearSel ? $this->parseHtmlByName('bx_a.html', array(
                    'href' => 'javascript:void(0)',
                    'title' => _t('_bx_timeline_txt_jump_to_n_year', $i),
                    'bx_repeat:attrs' => array(
                        array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.changeTimeline(this, ' . $i . ')')
                    ),
                    'content' => $i
                )) : $i) . ', ';

            $sYears = substr($sYears, 0, -2);
        }

        $aTmplVars = array(
            'style_prefix' => $sStylePrefix,
            'visible' => $bEnabled && $bVisible ? 'block' : 'none',
            'bx_if:is_disabled' => array(
                'condition' => !$bEnabled,
                'content' => array()
            ),
            'bx_if:show_on_click' => array(
                'condition' => $bEnabled,
                'content' => array(
                    'on_click' => 'javascript:' . $sJsObject . '.changePage(this, ' . ($iStart + $iPerPage) . ', ' . $iPerPage . ')'
                )
            ),
            'bx_if:show_jump_to' => array(
                'condition' => !empty($sYears),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'years' => $sYears
                )
            )
        );
        return $this->parseHtmlByName('load_more.html', $aTmplVars);
    }

    public function getComments($sSystem, $iId)
    {
        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $oCmts = $oModule->getCmtsObject($sSystem, $iId);
        if($oCmts === false)
            return '';

        $aComments = $oCmts->getCommentsBlock(0, 0, false);
        return $this->parseHtmlByName('comments.html', array(
            'style_prefix' => $sStylePrefix,
            'id' => $iId,
            'content' => $aComments['content']
        ));
    }

    public function getShareElement($iOwnerId, $sType, $sAction, $iObjectId, $aParams = array())
    {
        $aShared = $this->_oDb->getShared($sType, $sAction, $iObjectId);
        if(empty($aShared) || !is_array($aShared))
            return '';

        if($this->getModule()->isAllowedShare($aShared) !== true)
            return '';

        $bShowDoShareAsButtonSmall = isset($aParams['show_do_share_as_button_small']) && $aParams['show_do_share_as_button_small'] == true;
        $bShowDoShareAsButton = !$bShowDoShareAsButtonSmall && isset($aParams['show_do_share_as_button']) && $aParams['show_do_share_as_button'] == true;

        $bShowDoShareIcon = isset($aParams['show_do_share_icon']) && $aParams['show_do_share_icon'] == true;
        $bShowDoShareLabel = isset($aParams['show_do_share_label']) && $aParams['show_do_share_label'] == true;
        $bShowCounter = isset($aParams['show_counter']) && $aParams['show_counter'] === true;

        $sDoShare = '';
        if($bShowDoShareIcon)
            $sDoShare .= $this->parseHtmlByName('bx_icon.html', array('name' => 'share'));

        if($bShowDoShareLabel)
            $sDoShare .= ($sDoShare != '' ? ' ' : '') . _t('_bx_timeline_txt_do_share');

        $sDoShare = $this->parseHtmlByName('bx_a.html', array(
            'href' => 'javascript:void(0)',
            'title' => _t('_bx_timeline_txt_do_share'),
            'bx_repeat:attrs' => array(
                array('key' => 'class', 'value' => ($bShowDoShareAsButton ? 'bx-btn' : '') . ($bShowDoShareAsButtonSmall ? 'bx-btn bx-btn-small' : '')),
                array('key' => 'onclick', 'value' => $this->getShareJsClick($iOwnerId, $sType, $sAction, $iObjectId))
            ),
            'content' => $sDoShare
        ));

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sStylePrefixShare = $sStylePrefix . '-share-';
        return $this->parseHtmlByName('share_element_block.html', array(
            'style_prefix' => $sStylePrefix,
            'html_id' => $this->_oConfig->getHtmlIds('share', 'main') . $aShared['id'],
            'class' => ($bShowDoShareAsButton ? $sStylePrefixShare . 'button' : '') . ($bShowDoShareAsButtonSmall ? $sStylePrefixShare . 'button-small' : ''),
            'count' => $aShared['shares'],
            'do_share' => $sDoShare,
            'bx_if:show_counter' => array(
                'condition' => $bShowCounter,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
        			'bx_if:show_hidden' => array(
        				'condition' => (int)$aShared['shares'] == 0,
        				'content' => array()
        			),
                    'counter' => $this->getShareCounter($aShared)
                )
            ),
            'script' => $this->getShareJsScript()
        ));
    }

    public function getShareCounter($aEvent)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('share');

        return $this->parseHtmlByName('share_counter.html', array(
            'href' => 'javascript:void(0)',
            'title' => _t('_bx_timeline_txt_shared_by'),
            'bx_repeat:attrs' => array(
                array('key' => 'id', 'value' => $this->_oConfig->getHtmlIds('share', 'counter') . $aEvent['id']),
                array('key' => 'class', 'value' => $sStylePrefix . '-counter'),
                array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.toggleByPopup(this, ' . $aEvent['id'] . ')')
            ),
            'content' => !empty($aEvent['shares']) && (int)$aEvent['shares'] > 0 ? $aEvent['shares'] : ''
        ));
    }

    public function getSharedBy($iId)
    {
        $aTmplUsers = array();
        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $aUserIds = $this->_oDb->getSharedBy($iId);
        foreach($aUserIds as $iUserId) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $oModule->getUserInfo($iUserId);
            $aTmplUsers[] = array(
                'style_prefix' => $sStylePrefix,
                'user_unit' => $sUserUnit
            );
        }

        if(empty($aTmplUsers))
            $aTmplUsers = MsgBox(_t('_Empty'));

        return $this->parseHtmlByName('share_by_list.html', array(
            'style_prefix' => $sStylePrefix,
            'bx_repeat:list' => $aTmplUsers
        ));
    }

    public function getShareJsScript()
    {
        $this->addCss(array('share.css'));
        $this->addJs(array('main.js', 'share.js'));

        return $this->getJsCode('share');
    }

    public function getShareJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
        $sJsObject = $this->_oConfig->getJsObject('share');
        $sFormat = "%s.shareItem(this, %d, '%s', '%s', %d);";

        $iOwnerId = !empty($iOwnerId) ? (int)$iOwnerId : $this->getModule()->getUserId(); //--- in whose timeline the content will be shared
        return sprintf($sFormat, $sJsObject, $iOwnerId, $sType, $sAction, (int)$iObjectId);
    }

    public function getAttachLinkForm()
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('post');

        $aForm = $this->getModule()->getFormAttachLink();

        return $this->parseHtmlByName('attach_link_form.html', array(
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
            'form_id' => $aForm['form_id'],
            'form' => $aForm['form'],
        ));
    }

    public function getAttachLinkField($iUserId)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $aLinks = $this->_oDb->getUnusedLinks($iUserId);

        $sLinks = '';
        foreach($aLinks as $aLink)
            $sLinks .= $this->getAttachLinkItem($iUserId, $aLink);

        return $this->parsePageByName('attach_link_form_field.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('post', 'attach_link_form_field'),
            'style_prefix' => $sStylePrefix,
            'links' => $sLinks
        ));
    }

    public function getAttachLinkItem($iUserId, $mixedLink)
    {
        $aLink = is_array($mixedLink) ? $mixedLink : $this->_oDb->getUnusedLinks($iUserId, (int)$mixedLink);
        if(empty($aLink) || !is_array($aLink))
            return '';

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('post');
        $sLinkIdPrefix = $this->_oConfig->getHtmlIds('post', 'attach_link_item');

        return $this->parsePageByName('attach_link_item.html', array(
            'html_id' => $sLinkIdPrefix . $aLink['id'],
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
            'id' => $aLink['id'],
            'url' => $aLink['url'],
            'title' => $aLink['title'],
        ));
    }

    protected function _getPost($sType, $aEvent, $aBrowseParams = array())
    {
        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon) = $oModule->getUserInfo($aEvent['object_owner_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $aTmplVarsMenuItemActions = $this->_getTmplVarsMenuItemActions($aEvent, $aBrowseParams);

        $aTmplVarsTimelineOwner = array();
        if(isset($aBrowseParams['type']) && $aBrowseParams['type'] == BX_BASE_MOD_NTFS_TYPE_CONNECTIONS)
            $aTmplVarsTimelineOwner = $this->_getTmplVarsTimelineOwner($aEvent);

        $bBrowseItem = isset($aBrowseParams['type']) && $aBrowseParams['type'] == BX_TIMELINE_TYPE_ITEM;

        $aTmplVars = array (
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
        	'html_id' => $this->_oConfig->getHtmlIds('view', 'item') . $aEvent['id'],
            'class' => $bBrowseItem ? 'bx-tl-view-sizer' : 'bx-tl-grid-sizer',
            'bx_if:show_menu_item_manage' => array(
                'condition' => $oModule->getManageMenuObject($aEvent)->isVisible(),
                'content' => array(
        			'style_prefix' => $sStylePrefix,
		            'js_object' => $sJsObject,
		        	'id' => $aEvent['id'],
        		)
            ),
            'bx_if:show_icon' => array(
                'condition' => $bAuthorIcon,
                'content' => array(
                    'author_icon' => $sAuthorIcon
                )
            ),
            'bx_if:show_icon_empty' => array(
                'condition' => !$bAuthorIcon,
                'content' => array()
            ),
            'item_owner_url' => $sAuthorUrl,
            'item_owner_name' => $sAuthorName,
            'bx_if:show_timeline_owner' => array(
                'condition' => !empty($aTmplVarsTimelineOwner),
                'content' => $aTmplVarsTimelineOwner
            ),
            'item_view_url' => $this->_oConfig->getItemViewUrl($aEvent),
            'item_date' => bx_time_js($aEvent['date']),
            'content' => is_string($aEvent['content']) ? $aEvent['content'] : $this->_getContent($sType, $aEvent['content'], $aBrowseParams),
            'bx_if:show_menu_item_actions' => array(
                'condition' => !empty($aTmplVarsMenuItemActions),
                'content' => $aTmplVarsMenuItemActions
            ),
            'comments' => $bBrowseItem ? $this->_getComments($aEvent['comments']) : '',
            'votes' => $this->_getVotes($aEvent['votes'])
        );

        return $this->parseHtmlByName('item.html', $aTmplVars);
    }

    protected function _getContent($sType, $aContent, $aBrowseParams = array())
    {
        $sMethod = '_getTmplVarsContent' . ucfirst($sType);
        if(!method_exists($this, $sMethod))
            return '';

		$aTmplVars = $this->$sMethod($aContent, $aBrowseParams);
		return $this->parseHtmlByName('type_' . $sType . '.html', $aTmplVars);
    }

    protected function _getComments($aComments)
    {
        $mixedComments = $this->getModule()->getCommentsData($aComments);
        if($mixedComments === false)
            return '';

        list($sSystem, $iObjectId, $iCount) = $mixedComments;
        return $this->getComments($sSystem, $iObjectId);
    }

    protected function _getVotes($aVotes)
    {
        $oModule = $this->getModule();

        $mixedVotes = $oModule->getVotesData($aVotes);
        if($mixedVotes === false)
            return '';

        list($sSystem, $iObjectId, $iCount) = $mixedVotes;
        $oVote = $oModule->getVoteObject($sSystem, $iObjectId);
        if($oVote === false)
            return '';

        return $oVote->getJsScript();
    }

    protected function _getImagePopup()
    {
        $sViewImagePopupId = $this->_oConfig->getHtmlIds('view', 'photo_popup');
        $sViewImagePopupContent = $this->parseHtmlByName('popup_image.html', array(
    		'image_url' => ''
    	));

    	bx_import('BxTemplFunctions');
    	return BxTemplFunctions::getInstance()->transBox($sViewImagePopupId, $sViewImagePopupContent, true);
    }

    protected function _getTmplVarsMenuItemActions(&$aEvent)
    {
        bx_import('BxDolMenu');
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_actions'));
        $oMenu->setEvent($aEvent);

        $sMenu = $oMenu->getCode();
        if(empty($sMenu))
            return array();

        return array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('view'),
            'menu_item_actions' => $sMenu
        );
    }

    protected function _getTmplVarsTimelineOwner(&$aEvent)
    {
        $oModule = $this->getModule();

        $aTmplVarsTimelineOwner = array();
        if((int)$aEvent['owner_id'] != (int)$aEvent['object_owner_id']) {
            list($sTimelineAuthorName, $sTimelineAuthorUrl) = $oModule->getUserInfo($aEvent['owner_id']);

            $aTmplVarsTimelineOwner = array(
                'owner_url' => $sTimelineAuthorUrl,
                'owner_username' => $sTimelineAuthorName,
            );
        }

        return $aTmplVarsTimelineOwner;
    }

    protected function _getTmplVarsContentPost($aContent, $aBrowseParams = array())
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        //--- Process Text ---//
        $sUrl = isset($aContent['url']) ? bx_html_attribute($aContent['url']) : '';
        $sTitle = $sTitleAttr = '';
        if(isset($aContent['title'])) {
            $sTitle = bx_process_output($aContent['title']);
            $sTitleAttr = bx_html_attribute($aContent['title']);
        }

        if(!empty($sUrl) && !empty($sTitle))
            $sTitle = $this->parseHtmlByName('bx_a.html', array(
                'href' => $sUrl,
                'title' => $sTitleAttr,
                'bx_repeat:attrs' => array(
                    array('key' => 'class', 'value' => $sStylePrefix . '-title')
                ),
                'content' => $sTitle
            ));

        $sText = isset($aContent['text']) ? strip_tags($aContent['text'], '<p><br>') : '';
        $sTextMore = '';

        $iMaxLength = $this->_oConfig->getCharsDisplayMax();
        if(strlen($sText) > $iMaxLength) {
            $iLength = strpos($sText, ' ', $iMaxLength);

            $sTextMore = trim(substr($sText, $iLength));
            $sText = trim(substr($sText, 0, $iLength));
        }

        $sText = $this->_prepareTextForOutput($sText);
        $sTextMore = $this->_prepareTextForOutput($sTextMore);

        //--- Process Links ---//
        $aTmplVarsLinks = array();
        if(!empty($aContent['links']))
            foreach($aContent['links'] as $aLink)
                $aTmplVarsLinks[] = array(
                    'style_prefix' => $sStylePrefix,
                    'url' => $aLink['url'],
                    'title' => $aLink['title'],
                    'bx_if:show_text' => array(
                        'condition' => !empty($aLink['text']),
                        'content' => array(
                            'style_prefix' => $sStylePrefix,
                            'text' => $aLink['text']
                        )
                    )
                );

        //--- Process Photos ---//
        $aTmplVarsImages = array();
        if(!empty($aContent['images'])) {
            $bBrowseItem = isset($aBrowseParams['type']) && $aBrowseParams['type'] == BX_TIMELINE_TYPE_ITEM;

            foreach($aContent['images'] as $aImage) {
                $sImage = '';
                if(!empty($aImage['src']))
                    $sImage = $this->parseHtmlByName('bx_img.html', array(
                        'src' => $bBrowseItem && !empty($aImage['src_orig']) ? $aImage['src_orig'] : $aImage['src'],
                        'bx_repeat:attrs' => array(
                            array('key' => 'class', 'value' => $sStylePrefix . '-item-image')
                        )
                    ));

                if(!empty($sImage) && (isset($aImage['url']) || isset($aImage['onclick']))) {
                    $aAttrs = array();
                    if(isset($aImage['onclick']))
                        $aAttrs[] = array('key' => 'onclick', 'value' => $aImage['onclick']);

                    $sImage = $this->parseHtmlByName('bx_a.html', array(
                        'href' => isset($aImage['url']) ? $aImage['url'] : 'javascript:void(0)',
                        'title' => '',
                        'bx_repeat:attrs' => $aAttrs,
                        'content' => $sImage
                    ));
                }

                $aTmplVarsImages[] = array(
                    'style_prefix' => $sStylePrefix,
                    'image' => $sImage
                );
            }
        }

        return array(
            'style_prefix' => $sStylePrefix,
            'bx_if:show_title' => array(
                'condition' => !empty($sTitle),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'title' => $sTitle,
                )
            ),
            'bx_if:show_content' => array(
                'condition' => !empty($sText),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'item_content' => $sText,
                    'bx_if:show_more' => array(
                        'condition' => !empty($sTextMore),
                        'content' => array(
                            'style_prefix' => $sStylePrefix,
                            'js_object' => $sJsObject,
                            'item_content_more' => $sTextMore
                        )
                    ),
                )
            ),
            'bx_if:show_links' => array(
                'condition' => !empty($aTmplVarsLinks),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_repeat:links' => $aTmplVarsLinks
                )
            ),
            'bx_if:show_images' => array(
                'condition' => !empty($aTmplVarsImages),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_repeat:images' => $aTmplVarsImages
                )
            )
        );
    }

    protected function _getTmplVarsContentShare($aContent, $aBrowseParams = array())
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $sOwnerLink = $this->parseHtmlByName('bx_a.html', array(
            'href' => $aContent['owner_url'],
            'title' => '',
            'bx_repeat:attrs' => array(),
            'content' => $aContent['owner_name']
        ));

        $sSample = _t($aContent['sample']);
        $sSampleLink = empty($aContent['url']) ? $sSample : $this->parseHtmlByName('bx_a.html', array(
            'href' => $aContent['url'],
            'title' => '',
            'bx_repeat:attrs' => array(),
            'content' => $sSample
        ));

        $sTitle = _t('_bx_timeline_txt_shared', $sOwnerLink, $sSampleLink);
        $sText = $this->_getContent($aContent['parse_type'], $aContent);

        return array(
            'bx_if:show_title' => array(
                'condition' => !empty($sTitle),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'title' => $sTitle,
                )
            ),
            'bx_if:show_content' => array(
                'condition' => !empty($sText),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'content' => $sText,
                )
            )
        );
    }

    protected function _getSystemData(&$aEvent)
    {
        $sHandler = $aEvent['type'] . '_' . $aEvent['action'];
        if(!$this->_oConfig->isHandler($sHandler))
            return '';

        $aHandler = $this->_oConfig->getHandlers($sHandler);
        if(!empty($aHandler['module_name']) && !empty($aHandler['module_class']) && !empty($aHandler['module_method'])) {
        	$aEvent['js_mode'] = $this->_oConfig->getJsMode();
            return BxDolService::call($aHandler['module_name'], $aHandler['module_method'], array($aEvent), $aHandler['module_class']);
        }

		$sMethod = 'display' . bx_gen_method_name($aHandler['alert_unit'] . '_' . $aHandler['alert_action']);
		if(!method_exists($this, $sMethod))
        	return '';

		return $this->$sMethod($aEvent);
    }

    protected function _getCommonData(&$aEvent)
    {
        $oModule = $this->getModule();
        $sJsObject = $this->_oConfig->getJsObject('view');
        $sPrefix = $this->_oConfig->getPrefix('common_post');
        $sType = str_replace($sPrefix, '', $aEvent['type']);

        $aResult = array(
            'owner_id' => $aEvent['object_id'],
            'content_type' => $sType,
            'content' => array(
                'sample' => '_bx_timeline_txt_sample',
                'url' => $this->_oConfig->getItemViewUrl($aEvent)
            ), //a string to display or array to parse default template before displaying.
            'votes' => '',
            'comments' => '',
            'title' => '', //may be empty.
            'description' => '' //may be empty.
        );

        switch($sType) {
            case BX_TIMELINE_PARSE_TYPE_POST:
                if(!empty($aEvent['content']))
                    $aResult['content'] = array_merge($aResult['content'], unserialize($aEvent['content']));

                $aLinks = $this->_oDb->getLinks($aEvent['id']);
                if(!empty($aLinks) && is_array($aLinks))
                    foreach($aLinks as $aLink)
                        $aResult['content']['links'][] = array(
                            'url' => $aLink['url'],
                            'title' => $aLink['title'],
                            'text' => $aLink['text']
                        );

                $aPhotos = $this->_oDb->getPhotos($aEvent['id']);
                if(!empty($aPhotos) && is_array($aPhotos)) {
                    bx_import('BxDolStorage');
                    $oStorage = BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage'));

                    bx_import('BxDolImageTranscoder');
                    $oTranscoder = BxDolImageTranscoder::getObjectInstance($this->_oConfig->getObject('transcoder_view'));

                    foreach($aPhotos as $iPhotoId) {
                        $iPhotoIndex = array_search($iPhotoId, $aPhotos);
                        $sPhotoSrcOrig = $oStorage->getFileUrlById($iPhotoId);

                        $aResult['content']['images'][] = array(
                            'src' => $oTranscoder->getImageUrl($iPhotoId),
                            'src_orig' => $sPhotoSrcOrig,
                            'title' => '',
                            'onclick' => $sJsObject . '.showPhoto(this, \'' . $sPhotoSrcOrig . '\')'
                        );
                    }
                }
                break;

            case BX_TIMELINE_PARSE_TYPE_SHARE:
                if(empty($aEvent['content']))
                    return array();

                $aContent = unserialize($aEvent['content']);

                if(!$this->_oConfig->isSystem($aContent['type'] , $aContent['action'])) {
                    $aShared = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $aContent['object_id']));
                    $aShared = $this->_getCommonData($aShared);
                } 
                else
                	$aShared = $this->_getSystemData($aContent);

				if(empty($aShared) || !is_array($aShared))
					return array();

                $aResult['content'] = array_merge($aContent, $aShared['content']);
                $aResult['content']['parse_type'] = !empty($aShared['content_type']) ? $aShared['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
                $aResult['content']['owner_id'] = $aShared['owner_id'];
                list($aResult['content']['owner_name'], $aResult['content']['owner_url']) = $oModule->getUserInfo($aShared['owner_id']);

                list($sUserName) = $oModule->getUserInfo($aEvent['object_id']);
                $sSample = !empty($aResult['content']['sample']) ? $aResult['content']['sample'] : '_bx_timeline_txt_sample';

                $aResult['title'] = _t('_bx_timeline_txt_user_shared_sample', $sUserName, $aResult['content']['owner_name'], _t($sSample));
                $aResult['description'] = '';
                break;
        }

        $sSystem = $this->_oConfig->getObject('vote');
        if($oModule->getVoteObject($sSystem, $aEvent['id']) !== false)
            $aResult['votes'] = array(
                'system' => $sSystem,
                'object_id' => $aEvent['id'],
                'count' => $aEvent['votes']
            );

        $sSystem = $this->_oConfig->getObject('comment');
        if($oModule->getCmtsObject($sSystem, $aEvent['id']) !== false)
            $aResult['comments'] = array(
                'system' => $sSystem,
                'object_id' => $aEvent['id'],
                'count' => $aEvent['comments']
            );

        return $aResult;
    }

    protected function _prepareTextForOutput($s)
    {
        $sHttp = '';
        $sPattern = $this->_oConfig->getPregPattern('url');

        $aMatches = array();
        if(preg_match($sPattern, $s, $aMatches) && empty($aMatches[1]))
            $sHttp = 'http://';

        $s = bx_process_output($s, BX_DATA_TEXT_MULTILINE);
        $s = preg_replace($sPattern, '<a href="' . $sHttp . '$0" target="_blank">$0</a>', $s);

        return $s;
    }
}

/** @} */
