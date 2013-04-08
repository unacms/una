<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

function bx_events_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'events') {
        $oMain = BxDolModule::getInstance('bx_events');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}

bx_import('BxDolTwigModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');

define ('BX_EVENTS_PHOTOS_CAT', 'Events');
define ('BX_EVENTS_PHOTOS_TAG', 'events');

define ('BX_EVENTS_VIDEOS_CAT', 'Events');
define ('BX_EVENTS_VIDEOS_TAG', 'events');

define ('BX_EVENTS_SOUNDS_CAT', 'Events');
define ('BX_EVENTS_SOUNDS_TAG', 'events');

define ('BX_EVENTS_FILES_CAT', 'Events');
define ('BX_EVENTS_FILES_TAG', 'events');

define ('BX_EVENTS_MAX_FANS', 1000);

/**
 * Events module
 *
 * This module allow users to post upcoming events,
 * users can rate, comment, discuss it.
 * Event can have photo, video, sound and files.
 *
 *
 *
 * Profile's Wall:
 * 'add event' event are displayed in profile's wall
 *
 *
 *
 * Spy:
 * The following qactivity is displayed for content_activity:
 * add - new event was created
 * change - events was chaned
 * join - somebody joined event
 * rate - somebody rated event
 * commentPost - somebody posted comment in event
 *
 *
 *
 * Memberships/ACL:
 * events view - BX_EVENTS_VIEW
 * events browse - BX_EVENTS_BROWSE
 * events search - BX_EVENTS_SEARCH
 * events add - BX_EVENTS_ADD
 * events comments delete and edit - BX_EVENTS_COMMENTS_DELETE_AND_EDIT
 * events edit any event - BX_EVENTS_EDIT_ANY_EVENT
 * events delete any event - BX_EVENTS_DELETE_ANY_EVENT
 * events mark as featured - BX_EVENTS_MARK_AS_FEATURED
 * events approve - BX_EVENTS_APPROVE
 * events broadcast message - BX_EVENTS_BROADCAST_MESSAGE
 *
 *
 *
 * Service methods:
 *
 * Homepage block with different events
 * @see BxEventsModule::serviceHomepageBlock
 * BxDolService::call('events', 'homepage_block', array());
 *
 * Profile block with user's events
 * @see BxEventsModule::serviceProfileBlock
 * BxDolService::call('events', 'profile_block', array($iProfileId));
 *
 * Event's forum permissions (for internal usage only)
 * @see BxEventsModule::serviceGetForumPermission
 * BxDolService::call('events', 'get_forum_permission', array($iMemberId, $iForumId));
 *
 * Member menu item for events (for internal usage only)
 * @see BxEventsModule::serviceGetMemberMenuItem
 * BxDolService::call('events', 'get_member_menu_item', array());
 *
 *
 *
 * Alerts:
 * Alerts type/unit - 'bx_events'
 * The following alerts are rised
 *
 *  join - user joined an event
 *      $iObjectId - event id
 *      $iSenderId - joined user
 *
 *  add - new event was added
 *      $iObjectId - event id
 *      $iSenderId - creator of an event
 *      $aExtras['Status'] - status of added event
 *
 *  change - event's info was changed
 *      $iObjectId - event id
 *      $iSenderId - editor user id
 *      $aExtras['Status'] - status of changed event
 *
 *  delete - event was deleted
 *      $iObjectId - event id
 *      $iSenderId - deleter user id
 *
 *  mark_as_featured - event was marked/unmarked as featured
 *      $iObjectId - event id
 *      $iSenderId - performer id
 *      $aExtras['Featured'] - 1 - if event was marked as featured and 0 - if event was removed from featured
 *
 */
class BxEventsModule extends BxDolTwigModule {

    var $_iProfileId;
    var $_oPrivacy;

    function BxEventsModule(&$aModule) {

        parent::BxDolTwigModule($aModule);
        $this->_sFilterName = 'bx_events_filter';
        $this->_sPrefix = 'bx_events';

        $GLOBALS['oBxEventsModule'] = &$this;
        bx_import ('Privacy', $aModule);
        $this->_oPrivacy = new BxEventsPrivacy($this);
    }

    function actionHome () {
        parent::_actionHome(_t('_bx_events_main'));
    }

    function actionVideos ($sUri) {
        parent::_actionVideos ($sUri, _t('_bx_events_caption_videos'));
    }

    function actionPhotos ($sUri) {
        parent::_actionPhotos ($sUri, _t('_bx_events_caption_photos'));
    }

    function actionSounds ($sUri) {
        parent::_actionSounds ($sUri, _t('_bx_events_caption_sounds'));
    }

    function actionFiles ($sUri) {
        parent::_actionFiles ($sUri, _t('_bx_events_caption_files'));
    }

    function actionComments ($sUri) {
        parent::_actionComments ($sUri, _t('_bx_events_caption_comments'));
    }

    function actionBrowseParticipants ($sUri) {
        parent::_actionBrowseFans ($sUri, 'isAllowedViewParticipants', 'getFansBrowse', $this->_oDb->getParam('bx_events_perpage_browse_participants'), 'browse_participants/', _t('_bx_events_caption_participants'));
    }

    function actionView ($sUri) {
        parent::_actionView ($sUri, _t('_bx_events_msg_pending_approval'));
    }

    function actionUploadPhotos ($sUri) {
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadPhotos', 'images', array ('images_choice', 'images_upload'), _t('_bx_events_caption_upload_photos'));
    }

    function actionUploadVideos ($sUri) {
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadVideos', 'videos', array ('videos_choice', 'videos_upload'), _t('_bx_events_caption_upload_videos'));
    }

    function actionUploadSounds ($sUri) {
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadSounds', 'sounds', array ('sounds_choice', 'sounds_upload'), _t('_bx_events_caption_upload_sounds'));
    }

    function actionUploadFiles ($sUri) {
        parent::_actionUploadMedia ($sUri, 'isAllowedUploadFiles', 'files', array ('files_choice', 'files_upload'), _t('_bx_events_caption_upload_files'));
    }

    function actionBroadcast ($iEntryId) {
        parent::_actionBroadcast ($iEntryId, _t('_bx_events_caption_broadcast'), _t('_bx_events_msg_broadcast_no_participants'), _t('_bx_events_msg_broadcast_message_sent'));
    }

    function actionInvite ($iEntryId) {
        parent::_actionInvite ($iEntryId, 'bx_events_invitation', $this->_oDb->getParam('bx_events_max_email_invitations'), _t('_bx_events_invitation_sent'), _t('_bx_events_no_users_msg'), _t('_bx_events_caption_invite'));
    }

    function _getInviteParams ($aDataEntry, $aInviter) {
        return array (
                'EventName' => $aDataEntry['Title'],
                'EventLocation' => _t($GLOBALS['aPreValues']['Country'][$aDataEntry['Country']]['LKey']) . (trim($aDataEntry['City']) ? ', '.$aDataEntry['City'] : '') . ', ' . $aDataEntry['Place'],
                'EventStart' => getLocaleDate($aDataEntry['EventStart']),
                'EventUrl' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aDataEntry['EntryUri'],
                'InviterUrl' => $aInviter ? getProfileLink($aInviter['ID']) : 'javascript:void(0);',
                'InviterNickName' => $aInviter ? $aInviter['NickName'] : _t('_bx_events_user_unknown'),
                'InvitationText' => stripslashes(strip_tags($_POST['inviter_text'])),
            );
    }

    function actionCalendar ($iYear = '', $iMonth = '') {
        parent::_actionCalendar ($iYear, $iMonth, _t('_bx_events_calendar'));
    }

    function actionSearch ($sKeyword = '', $sCountry = '') {

        if (!$this->isAllowedSearch()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        if ($sKeyword)
            $_GET['Keyword'] = $sKeyword;
        if ($sCountry)
            $_GET['Country'] = explode(',', $sCountry);

        if (is_array($_GET['Country']) && 1 == count($_GET['Country']) && !$_GET['Country'][0]) {
            unset($_GET['Country']);
            unset($sCountry);
        }

        if ($sCountry || $sKeyword) {
            $_GET['submit_form'] = 1;
        }

        bx_events_import ('FormSearch');
        $oForm = new BxEventsFormSearch ();
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid ()) {

            bx_events_import ('SearchResult');
            $o = new BxEventsSearchResult('search', $oForm->getCleanValue('Keyword'), $oForm->getCleanValue('Country'));

            if ($o->isError) {
                $this->_oTemplate->displayPageNotFound ();
                return;
            }

            if ($s = $o->processing()) {
                echo $s;
            } else {
                $this->_oTemplate->displayNoData ();
                return;
            }

            $this->isAllowedSearch(true); // perform search action

            $this->_oTemplate->addCss ('unit.css');
            $this->_oTemplate->addCss ('main.css');
            $this->_oTemplate->pageCode($o->aCurrent['title'], false, false);
            return;

        }

        echo $oForm->getCode ();
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->pageCode(_t('_bx_events_caption_search'));
    }

    function actionAdd () {
        parent::_actionAdd (_t('_bx_events_caption_add'));
    }

    function actionEdit ($iEntryId) {
        parent::_actionEdit ($iEntryId, _t('_bx_events_caption_edit'));
    }

    function actionDelete ($iEntryId) {
        parent::_actionDelete ($iEntryId, _t('_bx_events_event_was_deleted'));
    }

    function actionMarkFeatured ($iEntryId) {
        parent::_actionMarkFeatured ($iEntryId, _t('_bx_events_msg_added_to_featured'), _t('_bx_events_msg_removed_from_featured'));
    }

    function actionJoin ($iEntryId, $iProfileId) {
        parent::_actionJoin ($iEntryId, $iProfileId, _t('_bx_events_event_joined_already'), _t('_bx_events_event_joined_already_pending'), _t('_bx_events_event_join_success'), _t('_bx_events_event_join_success_pending'), _t('_bx_events_event_leave_success'));
    }

    function actionParticipants ($iEventId) {
        $iEventId = (int)$iEventId;
        if (!($aEvent = $this->_oDb->getEntryByIdAndOwner ($iEventId, 0, true))) {
            echo MsgBox(_t('_Empty'));
            return;
        }

        bx_events_import ('PageView');
        $oPage = new BxEventsPageView ($this, $aEvent);
        $a = $oPage->getBlockCode_Participants();
        echo $a[0];
        exit;
    }

    function actionSharePopup ($iEntryId) {
        parent::_actionSharePopup ($iEntryId, _t('_bx_events_caption_share_event'));
    }

    function actionManageFansPopup ($iEntryId) {
        parent::_actionManageFansPopup ($iEntryId, _t('_bx_events_caption_manage_fans'), 'getFans', 'isAllowedManageFans', 'isAllowedManageAdmins', BX_EVENTS_MAX_FANS);
    }

    function actionTags() {
        parent::_actionTags (_t('_bx_events_tags'));
    }

    function actionCategories() {
        parent::_actionCategories (_t('_bx_events_categories'));
    }

    function actionDownload ($iEntryId, $iMediaId) {

        $aFileInfo = $this->_oDb->getMedia ((int)$iEntryId, (int)$iMediaId, 'files');

        if (!$aFileInfo || !($aDataEntry = $this->_oDb->getEntryByIdAndOwner((int)$iEntryId, 0, true))) {
            $this->_oTemplate->displayPageNotFound ();
            exit;
        }

        if (!$this->isAllowedView ($aDataEntry)) {
            $this->_oTemplate->displayAccessDenied ();
            exit;
        }

        parent::_actionDownload($aFileInfo, 'media_id');
    }

    // ================================== external actions

    /**
     * Homepage block with different events
     * @return html to display on homepage in a block
     */
    function serviceHomepageBlock () {

        if (!$this->_oDb->isAnyPublicContent())
            return '';

        bx_import ('PageMain', $this->_aModule);
        $o = new BxEventsPageMain ($this);
        $o->sUrlStart = BX_DOL_URL_ROOT . '?';

        $sDefaultHomepageTab = $this->_oDb->getParam('bx_events_homepage_default_tab');
        $sBrowseMode = $sDefaultHomepageTab;
        switch ($_GET['bx_events_filter']) {
            case 'featured':
            case 'recent':
            case 'top':
            case 'popular':
            case 'upcoming':
            case $sDefaultHomepageTab:
                $sBrowseMode = $_GET['bx_events_filter'];
                break;
        }

        return $o->ajaxBrowse(
            $sBrowseMode,
            $this->_oDb->getParam('bx_events_perpage_homepage'),
            array(
                _t('_bx_events_tab_upcoming') => array('href' => BX_DOL_URL_ROOT . '?bx_events_filter=upcoming', 'active' => 'upcoming' == $sBrowseMode, 'dynamic' => true),
                _t('_bx_events_tab_featured') => array('href' => BX_DOL_URL_ROOT . '?bx_events_filter=featured', 'active' => 'featured' == $sBrowseMode, 'dynamic' => true),
                _t('_bx_events_tab_recent') => array('href' => BX_DOL_URL_ROOT . '?bx_events_filter=recent', 'active' => 'recent' == $sBrowseMode, 'dynamic' => true),
                _t('_bx_events_tab_top') => array('href' => BX_DOL_URL_ROOT . '?bx_events_filter=top', 'active' => 'top' == $sBrowseMode, 'dynamic' => true),
                _t('_bx_events_tab_popular') => array('href' => BX_DOL_URL_ROOT . '?bx_events_filter=popular', 'active' => 'popular' == $sBrowseMode, 'dynamic' => true),
            )
        );
    }

    /**
     * Profile block with user's events
     * @param $iProfileId profile id
     * @return html to display on homepage in a block
     */
    function serviceProfileBlock ($iProfileId) {
        $iProfileId = (int)$iProfileId;
        $aProfile = getProfileInfo($iProfileId);
        bx_import ('PageMain', $this->_aModule);
        $o = new BxEventsPageMain ($this);
        $o->sUrlStart = getProfileLink($aProfile['ID']) . '?';
        return $o->ajaxBrowse(
            'user',
            $this->_oDb->getParam('bx_events_perpage_profile'),
            array(),
            process_db_input ($aProfile['NickName'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION),
            true,
            false
        );
    }

    /**
     * Profile block with events user joined
     * @param $iProfileId profile id
     * @return html to display on homepage in a block
     */
    function serviceProfileBlockJoined ($iProfileId) {
        $iProfileId = (int)$iProfileId;
        $aProfile = getProfileInfo($iProfileId);
        bx_import ('PageMain', $this->_aModule);
        $o = new BxEventsPageMain ($this);
        $o->sUrlStart = getProfileLink($aProfile['ID']) . '?';
        return $o->ajaxBrowse(
            'joined',
            $this->_oDb->getParam('bx_events_perpage_profile'),
            array(),
            process_db_input ($aProfile['NickName'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION),
            true,
            false
        );
    }

    /**
     * Event's forum permissions
     * @param $iMemberId profile id
     * @param $iForumId forum id
     * @return array with permissions
     */
    function serviceGetForumPermission($iMemberId, $iForumId) {

        $iMemberId = (int)$iMemberId;
        $iForumId = (int)$iForumId;

        $aFalse = array ( // default permissions, for visitors for example
            'admin' => 0,
            'read' => 1,
            'post' => 0,
        );

        if (!($aForum = $this->_oDb->getForumById ($iForumId))) {
            return $aFalse;
        }

        if (!($aDataEntry = $this->_oDb->getEntryByIdAndOwner ($aForum['entry_id'], 0, true))) {
            return $aFalse;
        }

        $aTrue = array (
            'admin' => $aDataEntry['ResponsibleID'] == $iMemberId || $this->isAdmin() ? 1 : 0, // author is admin
            'read' => 1,
            'post' => $this->isAllowedPostInForum ($aDataEntry, $iMemberId) ? 1 : 0,
        );
        return $aTrue;
    }

    /**
     * Member menu item for events
     * @return html to show in member menu
     */
    function serviceGetMemberMenuItem () {
        parent::_serviceGetMemberMenuItem (_t('_bx_events'), _t('_bx_events'), 'events.png');
    }

    function serviceGetWallPost ($aEvent) {
        return parent::_serviceGetWallPost ($aEvent, _t('_bx_event_wall_object'), _t('_bx_event_wall_added_new'));
    }

    function serviceGetSpyPost($sAction, $iObjectId = 0, $iSenderId = 0, $aExtraParams = array()) {
        return parent::_serviceGetSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams, array(
            'add' => '_bx_events_spy_post',
            'change' => '_bx_events_spy_post_change',
            'join' => '_bx_events_spy_join',
            'rate' => '_bx_events_spy_rate',
            'commentPost' => '_bx_events_spy_comment',
        ));
    }

    function serviceGetSubscriptionParams ($sAction, $iEntryId) {

        $a = array (
            'change' => _t('_bx_events_sbs_change'),
            'commentPost' => _t('_bx_events_sbs_comment'),
            'rate' => _t('_bx_events_sbs_rate'),
            'join' => _t('_bx_events_sbs_join'),
        );

        return parent::_serviceGetSubscriptionParams ($sAction, $iEntryId, $a);
    }

    // ================================== admin actions

    function actionGatherLangKeys () {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $a = array ();
        $sDir = BX_DIRECTORY_PATH_MODULES . $GLOBALS['aModule']['path'] . 'classes/';
        if ($h = opendir($sDir)) {
            while (false !== ($f = readdir($h))) {
                if ($f == "." || $f == ".." || substr($f, -4) != '.php')
                    continue;
                $s = file_get_contents ($sDir . $f);
                if (preg_match_all("/_t[\s]*\([\s]*['\"]{1}(.*?)['\"]{1}[\s]*\)/", $s, $m))
                    foreach ($m[1] as $sKey)
                        $a[] = $sKey;
            }
            closedir($h);
        }

        echo '<pre>';
        echo "\$aLangContent = array(\n";
        asort ($a);
        foreach ($a as $sKey)
            if (preg_match('/^_bx_events/', $sKey))
                echo "\t'$sKey' => '" . (_t($sKey) == $sKey ? '' : _t($sKey)) . "',\n";
        echo ');';
        echo '</pre>';
        exit;
    }

    function actionAdministration ($sUrl = '') {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        $aMenu = array(
            'home' => array(
                'title' => _t('_bx_events_pending_approval'),
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/home',
                '_func' => array ('name' => 'actionAdministrationManage', 'params' => array(false)),
            ),
            'admin_entries' => array(
                'title' => _t('_bx_events_administration_admin_events'),
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/admin_entries',
                '_func' => array ('name' => 'actionAdministrationManage', 'params' => array(true)),
            ),
            'create' => array(
                'title' => _t('_bx_events_administration_create_event'),
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/create',
                '_func' => array ('name' => 'actionAdministrationCreateEntry', 'params' => array()),
            ),
            'settings' => array(
                'title' => _t('_bx_events_administration_settings'),
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/settings',
                '_func' => array ('name' => 'actionAdministrationSettings', 'params' => array()),
            ),
        );

        if (empty($aMenu[$sUrl]))
            $sUrl = 'home';

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array (array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);

        echo $this->_oTemplate->adminBlock ($sContent, _t('_bx_events_administration'), $aMenu);
        $this->_oTemplate->addCssAdmin ('admin.css');
        $this->_oTemplate->addCssAdmin ('unit.css');
        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css');
        $this->_oTemplate->addCssAdmin ('forms_adv.css');
        $this->_oTemplate->pageCodeAdmin (_t('_bx_events_administration'));
    }


    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('Events');
    }

    function actionAdministrationManage ($isAdminEntries = false) {
        return parent::_actionAdministrationManage ($isAdminEntries, '_bx_events_admin_delete', '_bx_events_admin_activate');
    }

    // ================================== events

    function onEventJoinRequest ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventJoinRequest ($iEntryId, $iProfileId, $aDataEntry, 'bx_events_join_request', BX_EVENTS_MAX_FANS);
    }

    function onEventJoinReject ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventJoinReject ($iEntryId, $iProfileId, $aDataEntry, 'bx_events_join_reject');
    }

    function onEventFanRemove ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventFanRemove ($iEntryId, $iProfileId, $aDataEntry, 'bx_events_fan_remove');
    }

    function onEventFanBecomeAdmin ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventFanBecomeAdmin ($iEntryId, $iProfileId, $aDataEntry, 'bx_events_fan_become_admin');
    }

    function onEventAdminBecomeFan ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventAdminBecomeFan ($iEntryId, $iProfileId, $aDataEntry, 'bx_events_admin_become_fan');
    }

    function onEventJoinConfirm ($iEntryId, $iProfileId, $aDataEntry) {
        parent::_onEventJoinConfirm ($iEntryId, $iProfileId, $aDataEntry, 'bx_events_join_confirm');
    }

    // ================================== permissions

    function isAllowedView ($aEvent, $isPerformAction = false) {

        // admin and owner always have access
        if ($this->isAdmin() || $aEvent['ResponsibleID'] == $this->_iProfileId)
            return true;

        // check admin acl
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_VIEW, $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
            return false;

        // check user group
        return $this->_oPrivacy->check('view_event', $aEvent['ID'], $this->_iProfileId);
    }

    function isAllowedBrowse ($isPerformAction = false) {
        if ($this->isAdmin())
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_BROWSE, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedSearch ($isPerformAction = false) {
        if ($this->isAdmin())
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_SEARCH, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedAdd ($isPerformAction = false) {
        if ($this->isAdmin())
            return true;
        if (!$GLOBALS['logged']['member'])
            return false;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_ADD, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedEdit ($aEvent, $isPerformAction = false) {
        if ($this->isAdmin() || ($GLOBALS['logged']['member'] && $aEvent['ResponsibleID'] == $this->_iProfileId && isProfileActive($this->_iProfileId)))
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_EDIT_ANY_EVENT, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedMarkAsFeatured ($aEvent, $isPerformAction = false) {
        if ($this->isAdmin())
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_MARK_AS_FEATURED, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedBroadcast ($aDataEntry, $isPerformAction = false) {
        if ($this->isAdmin() || ($GLOBALS['logged']['member'] && $aDataEntry['ResponsibleID'] == $this->_iProfileId && isProfileActive($this->_iProfileId)))
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_BROADCAST_MESSAGE, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedDelete (&$aEvent, $isPerformAction = false) {
        if ($this->isAdmin() || ($GLOBALS['logged']['member'] && $aEvent['ResponsibleID'] == $this->_iProfileId && isProfileActive($this->_iProfileId)))
            return true;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_DELETE_ANY_EVENT, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedJoin (&$aDataEntry) {
        if (!$this->_iProfileId)
            return false;
        if ($aDataEntry['EventStart'] < time())
            return false;
        $isAllowed = $this->_oPrivacy->check('join', $aDataEntry['ID'], $this->_iProfileId);
        return $isAllowed && $this->_isAllowedJoinByMembership ($aDataEntry);
    }

    function _isAllowedJoinByMembership (&$aEvent) {
        if (!$aEvent['EventMembershipFilter']) return true;
        require_once(BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php');
        $aMemebrshipInfo = getMemberMembershipInfo($this->_iProfileId);
        return $aEvent['EventMembershipFilter'] == $aMemebrshipInfo['ID'] && $aMemebrshipInfo['DateStarts'] < time() && $aMemebrshipInfo['DateExpires'] > time() ? true : false;
    }

    function isAllowedSendInvitation (&$aEvent) {
        return ($aEvent['ResponsibleID'] == $this->_iProfileId && ($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin']) && isProfileActive($this->_iProfileId));
    }

    function isAllowedShareEvent (&$aEvent) {
        return true;
    }

    function isAllowedViewParticipants (&$aEvent) {

        if (($aEvent['ResponsibleID'] == $this->_iProfileId && $GLOBALS['logged']['member'] && isProfileActive($this->_iProfileId)) || $this->isAdmin ())
            return true;

        return $this->_oPrivacy->check('view_participants', $aEvent['ID'], $this->_iProfileId);
    }

    function isAllowedComments (&$aEvent) {

        if (($aEvent['ResponsibleID'] == $this->_iProfileId && $GLOBALS['logged']['member'] && isProfileActive($this->_iProfileId)) || $this->isAdmin ())
            return true;

        return $this->_oPrivacy->check('comment', $aEvent['ID'], $this->_iProfileId);
    }

    function isAllowedUploadPhotos(&$aDataEntry) {
        if (!$this->_iProfileId)
            return false;
        if ($this->isAdmin())
            return true;
        if (!$this->isMembershipEnabledForImages())
            return false;
        return $this->_oPrivacy->check('upload_photos', $aDataEntry['ID'], $this->_iProfileId);
    }

    function isAllowedUploadVideos(&$aDataEntry) {
        if (!$this->_iProfileId)
            return false;
        if ($this->isAdmin())
            return true;
        if (!$this->isMembershipEnabledForVideos())
            return false;
        return $this->_oPrivacy->check('upload_videos', $aDataEntry['ID'], $this->_iProfileId);
    }

    function isAllowedUploadSounds(&$aDataEntry) {
        if (!$this->_iProfileId)
            return false;
        if ($this->isAdmin())
            return true;
        if (!$this->isMembershipEnabledForSounds())
            return false;
        return $this->_oPrivacy->check('upload_sounds', $aDataEntry['ID'], $this->_iProfileId);
    }

    function isAllowedUploadFiles(&$aDataEntry) {
        if (!$this->_iProfileId)
            return false;
        if ($this->isAdmin())
            return true;
        if (!$this->isMembershipEnabledForFiles())
            return false;
        return $this->_oPrivacy->check('upload_files', $aDataEntry['ID'], $this->_iProfileId);
    }

    function isAllowedCreatorCommentsDeleteAndEdit (&$aEvent, $isPerformAction = false) {
        if ($this->isAdmin()) return true;
        if (!$GLOBALS['logged']['member'] || $aEvent['ResponsibleID'] != $this->_iProfileId)
            return false;
        $this->_defineActions();
        $aCheck = checkAction($this->_iProfileId, BX_EVENTS_COMMENTS_DELETE_AND_EDIT, $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    function isAllowedRate (&$aEvent) {

        if (($aEvent['ResponsibleID'] == $this->_iProfileId && $GLOBALS['logged']['member'] && isProfileActive($this->_iProfileId)) || $this->isAdmin ())
            return true;

        return $this->_oPrivacy->check('rate', $aEvent['ID'], $this->_iProfileId);
    }

    function isAllowedPostInForum(&$aDataEntry, $iProfileId = -1) {
        if (-1 == $iProfileId)
            $iProfileId = $this->_iProfileId;
        return $this->isAdmin() || ($GLOBALS['logged']['member'] && $aEvent['ResponsibleID'] == $iProfileId && isProfileActive($iProfileId)) || $this->_oPrivacy->check('post_in_forum', $aDataEntry['ID'], $iProfileId);
    }

    function isAllowedManageAdmins($aDataEntry) {
        if (($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin']) && $aDataEntry['ResponsibleID'] == $this->_iProfileId && isProfileActive($this->_iProfileId))
            return true;
        return false;
    }

    function isAllowedManageFans($aDataEntry) {
        return $this->isEntryAdmin($aDataEntry);
    }

    function isFan($aDataEntry, $iProfileId = 0, $isConfirmed = true) {
        if (!$iProfileId)
            $iProfileId = $this->_iProfileId;
        return $this->_oDb->isFan ($aDataEntry['ID'], $iProfileId, $isConfirmed) ? true : false;
    }

    function isEntryAdmin($aDataEntry, $iProfileId = 0) {
        if (!$iProfileId)
            $iProfileId = $this->_iProfileId;
        if (($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin']) && $aDataEntry['ResponsibleID'] == $iProfileId && isProfileActive($iProfileId))
            return true;
        return $this->_oDb->isGroupAdmin ($aDataEntry['ID'], $iProfileId) && isProfileActive($iProfileId);
    }

    function _defineActions () {
        defineMembershipActions(array('events view', 'events browse', 'events search', 'events add', 'events comments delete and edit', 'events edit any event', 'events delete any event', 'events mark as featured', 'events approve', 'events broadcast message'));
    }

    // ================================== other function

    function _browseMy (&$aProfile) {
        parent::_browseMy ($aProfile, _t('_bx_events_block_my_events'));
    }
}

?>
