<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEventsConfig extends BxBaseModProfileConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'view-event-profile' => 'checkAllowedView',
            'edit-event-profile' => 'checkAllowedEdit',
            'edit-event-cover' => 'checkAllowedChangeCover',
            'invite-to-event' => 'checkAllowedInvite',
            'delete-event-profile' => 'checkAllowedDelete',
            'profile-fan-add' => 'checkAllowedFanAdd',
            'profile-fan-remove' => 'checkAllowedFanRemove',
            'profile-subscribe-add' => 'checkAllowedSubscribeAdd',
            'profile-subscribe-remove' => 'checkAllowedSubscribeRemove',
            'profile-actions-more' => 'checkAllowedViewMoreMenu',
            'convos-compose' => 'checkAllowedCompose',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'calendar col-red2',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'data',
            'TABLE_ENTRIES_FULLTEXT' => 'search_fields',
            'TABLE_ADMINS' => $aModule['db_prefix'] . 'admins',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_NAME' => 'event_name',
            'FIELD_TITLE' => 'event_name',
            'FIELD_TEXT' => 'event_desc',
            'FIELD_PICTURE' => 'picture',
            'FIELD_COVER' => 'cover',
            'FIELD_TIMEZONE' => 'timezone',
            'FIELD_JOIN_CONFIRMATION' => 'join_confirmation',
        	'FIELD_REMINDER' => 'reminder',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELDS_QUICK_SEARCH' => array('event_name'),
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-event-profile',
            'URI_EDIT_ENTRY' => 'edit-event-profile',
            'URI_EDIT_COVER' => 'edit-event-cover',
            'URI_JOINED_ENTRIES' => 'joined-events',
        	'URI_MANAGE_COMMON' => 'events-manage',

            'URL_HOME' => 'page.php?i=events-home',
        	'URL_MANAGE_COMMON' => 'page.php?i=events-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=events-administration',

            'PARAM_NUM_RSS' => 'bx_events_num_rss',
            'PARAM_NUM_CONNECTIONS_QUICK' => 'bx_events_num_connections_quick',
            
            'PARAM_SEARCHABLE_FIELDS' => 'bx_events_searchable_fields',

            // objects
            'OBJECT_STORAGE' => 'bx_events_pics',
            'OBJECT_STORAGE_COVER' => 'bx_events_pics',
            'OBJECT_IMAGES_TRANSCODER_THUMB' => 'bx_events_thumb',
            'OBJECT_IMAGES_TRANSCODER_ICON' => 'bx_events_icon',
            'OBJECT_IMAGES_TRANSCODER_AVATAR' => 'bx_events_avatar',
            'OBJECT_IMAGES_TRANSCODER_PICTURE' => 'bx_events_picture',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_events_cover',
            'OBJECT_IMAGES_TRANSCODER_COVER_THUMB' => 'bx_events_cover_thumb',
        	'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_events_gallery',
            'OBJECT_VIEWS' => 'bx_events',
            'OBJECT_VOTES' => 'bx_events',
        	'OBJECT_FAVORITES' => 'bx_events',
        	'OBJECT_FEATURED' => 'bx_events',
        	'OBJECT_COMMENTS' => 'bx_events',
            'OBJECT_REPORTS' => 'bx_events',
            'OBJECT_METATAGS' => 'bx_events',
            'OBJECT_FORM_ENTRY' => 'bx_event',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_event_view',
        	'OBJECT_FORM_ENTRY_DISPLAY_VIEW_FULL' => 'bx_event_view_full', // for "info" tab on view group page
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_event_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_event_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT_COVER' => 'bx_event_edit_cover',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_event_delete',
            'OBJECT_FORM_ENTRY_DISPLAY_INVITE' => 'bx_event_invite',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_events_view_actions', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE' => 'bx_events_view_actions_more', // actions menu on view entry page for "more" popup
            'OBJECT_MENU_SUBMENU' => 'bx_events_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_events_view_submenu',  // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_COVER' => 'bx_events_view_submenu_cover',  // view entry submenu displayed in cover
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'events-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_events_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_PAGE_VIEW_ENTRY' => 'bx_events_view_profile',
            'OBJECT_PAGE_VIEW_ENTRY_CLOSED' => 'bx_events_view_profile_closed',
            'OBJECT_PRIVACY_VIEW' => 'bx_events_allow_view_to',
            'OBJECT_PRIVACY_VIEW_NOTIFICATION_EVENT' => 'bx_events_allow_view_notification_to',
            'OBJECT_GRID_ADMINISTRATION' => 'bx_events_administration',
        	'OBJECT_GRID_COMMON' => 'bx_events_common',
            'OBJECT_GRID_CONNECTIONS' => 'bx_events_fans',
            'OBJECT_CONNECTIONS' => 'bx_events_fans',
            'OBJECT_UPLOADERS_COVER' => array('bx_events_cover_crop'),
            'OBJECT_UPLOADERS_PICTURE' => array('bx_events_picture_crop'),

            'EMAIL_INVITATION' => 'bx_events_invitation',
            'EMAIL_JOIN_REQUEST' => 'bx_events_join_request',
            'EMAIL_JOIN_CONFIRM' => 'bx_events_join_confirm',
            'EMAIL_FAN_BECOME_ADMIN' => 'bx_events_fan_become_admin',
            'EMAIL_ADMIN_BECOME_FAN' => 'bx_events_admin_become_fan',
            'EMAIL_FAN_REMOVE' => 'bx_events_fan_remove',
            'EMAIL_JOIN_REJECT' => 'bx_events_join_reject',
            'EMAIL_REMINDER' => 'bx_events_reminder',

            'TRIGGER_MENU_PROFILE_VIEW_SUBMENU' => 'trigger_group_view_submenu',
            'TRIGGER_MENU_PROFILE_VIEW_ACTIONS' => 'trigger_group_view_actions',
        	'TRIGGER_PAGE_VIEW_ENTRY' => 'trigger_page_group_view_entry',

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_events_view_actions' => $aMenuItems2Methods,
                'bx_events_view_actions_more' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'status' => array (
                    'name' => 'bx-events-status-not-active',
                    'map' => array (
                        BX_PROFILE_STATUS_PENDING => '_bx_events_txt_account_pending',
                        BX_PROFILE_STATUS_SUSPENDED => '_bx_events_txt_account_suspended',
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_events_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_events_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_events_txt_sample_comment_single',
                'txt_sample_vote_single' => '_bx_events_txt_sample_vote_single',
                'txt_private_group' => '_bx_events_txt_private_group',
                'txt_N_fans' => '_bx_events_txt_N_fans',
                'txt_ntfs_join_request' => '_bx_events_txt_ntfs_join_request',
                'txt_ntfs_fan_added' => '_bx_events_txt_ntfs_fan_added',
            	'form_field_author' => '_bx_events_form_profile_input_author',
                'menu_item_title_befriend_sent' => '_bx_events_menu_item_title_befriend_sent',
                'menu_item_title_unfriend_cancel_request' => '_bx_events_menu_item_title_unfriend_cancel_request',
                'menu_item_title_befriend_confirm' => '_bx_events_menu_item_title_befriend_confirm',
                'menu_item_title_unfriend_reject_request' => '_bx_events_menu_item_title_unfriend_reject_request',
                'menu_item_title_befriend' => '_bx_events_menu_item_title_befriend',
                'menu_item_title_unfriend' => '_bx_events_menu_item_title_unfriend',
            	'grid_action_err_delete' => '_bx_events_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_events_grid_txt_account_manager',
				'filter_item_active' => '_bx_events_grid_filter_item_title_adm_active',
            	'filter_item_pending' => '_bx_events_grid_filter_item_title_adm_pending',
            	'filter_item_suspended' => '_bx_events_grid_filter_item_title_adm_suspended',
            	'filter_item_select_one_filter1' => '_bx_events_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_events_menu_item_title_manage_my',
                'menu_item_manage_all' => '_bx_events_menu_item_title_manage_all',
                'menu_item_title_become_fan_sent' => '_bx_events_menu_item_title_become_fan_sent',
                'menu_item_title_leave_group_cancel_request' => '_bx_events_menu_item_title_leave_group_cancel_request',
                'menu_item_title_become_fan' => '_bx_events_menu_item_title_become_fan',
                'menu_item_title_leave_group' => '_bx_events_menu_item_title_leave_group',
                
                'menu_item_title_become_fan_sent' => '_bx_events_menu_item_title_become_fan_sent',
                'menu_item_title_become_fan' => '_bx_events_menu_item_title_become_fan',
            	'txt_all_entries_by_author' => '_bx_events_page_title_browse_by_author'
            ),

        );

        $this->_aJsClasses = array(
        	'manage_tools' => 'BxEventsManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxEventsManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }

}

/** @} */
