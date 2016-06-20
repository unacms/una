<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

class BxGroupsConfig extends BxBaseModProfileConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'view-group-profile' => 'checkAllowedView',
            'edit-group-profile' => 'checkAllowedEdit',
            'edit-group-cover' => 'checkAllowedChangeCover',
            'delete-group-profile' => 'checkAllowedDelete',
            'profile-fan-add' => 'checkAllowedFanAdd',
            'profile-fan-remove' => 'checkAllowedFanRemove',
            'profile-subscribe-add' => 'checkAllowedSubscribeAdd',
            'profile-subscribe-remove' => 'checkAllowedSubscribeRemove',
            'profile-actions-more' => 'checkAllowedViewMoreMenu',
            'convos-compose' => 'checkAllowedSubscribeAdd',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'group col-red2',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'data',
            'TABLE_ADMINS' => $aModule['db_prefix'] . 'admins',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_NAME' => 'group_name',
            'FIELD_TITLE' => 'group_name',
            'FIELD_TEXT' => 'group_desc',
            'FIELD_PICTURE' => 'picture',
            'FIELD_COVER' => 'cover',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELDS_QUICK_SEARCH' => array('group_name'),
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-group-profile',
            'URI_EDIT_ENTRY' => 'edit-group-profile',
            'URI_EDIT_COVER' => 'edit-group-cover',
            'URI_JOINED_ENTRIES' => 'joined-groups',
        	'URI_MANAGE_COMMON' => 'groups-manage',

            'URL_HOME' => 'page.php?i=groups-home',
        	'URL_MANAGE_COMMON' => 'page.php?i=groups-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=groups-administration',

            'PARAM_NUM_RSS' => 'bx_groups_num_rss',
            'PARAM_NUM_CONNECTIONS_QUICK' => 'bx_groups_num_connections_quick',

            // objects
            'OBJECT_STORAGE' => 'bx_groups_pics',
            'OBJECT_STORAGE_COVER' => 'bx_groups_pics',
            'OBJECT_IMAGES_TRANSCODER_THUMB' => 'bx_groups_thumb',
            'OBJECT_IMAGES_TRANSCODER_ICON' => 'bx_groups_icon',
            'OBJECT_IMAGES_TRANSCODER_AVATAR' => 'bx_groups_avatar',
            'OBJECT_IMAGES_TRANSCODER_PICTURE' => 'bx_groups_picture',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_groups_cover',
            'OBJECT_IMAGES_TRANSCODER_COVER_THUMB' => 'bx_groups_cover_thumb',
            'OBJECT_VIEWS' => 'bx_groups',
            'OBJECT_VOTES' => 'bx_groups',
            'OBJECT_REPORTS' => 'bx_groups',
            'OBJECT_METATAGS' => 'bx_groups',
            'OBJECT_FORM_ENTRY' => 'bx_group',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_group_view',
        	'OBJECT_FORM_ENTRY_DISPLAY_VIEW_FULL' => 'bx_group_view_full', // for "info" tab on view group page
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_group_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_group_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT_COVER' => 'bx_group_edit_cover',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_group_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_groups_view_actions', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE' => 'bx_groups_view_actions_more', // actions menu on view entry page for "more" popup
            'OBJECT_MENU_SUBMENU' => 'bx_groups_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_groups_view_submenu',  // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_COVER' => 'bx_groups_view_submenu_cover',  // view entry submenu displayed in cover
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'groups-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_groups_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_PAGE_VIEW_ENTRY' => 'bx_groups_view_profile',
            'OBJECT_PAGE_VIEW_ENTRY_CLOSED' => 'bx_groups_view_profile_closed',
            'OBJECT_PRIVACY_VIEW' => 'bx_groups_allow_view_to',
            'OBJECT_PRIVACY_VIEW_NOTIFICATION_EVENT' => 'bx_groups_allow_view_notification_to',
            'OBJECT_GRID_ADMINISTRATION' => 'bx_groups_administration',
        	'OBJECT_GRID_COMMON' => 'bx_groups_common',
            'OBJECT_GRID_CONNECTIONS' => 'bx_groups_fans',
            'OBJECT_CONNECTIONS' => 'bx_groups_fans',
            'OBJECT_UPLOADERS_COVER' => array('bx_groups_cover_crop'),
            'OBJECT_UPLOADERS_PICTURE' => array('bx_groups_picture_crop'),

            'EMAIL_INVITATION' => 'bx_groups_invitation',
            'EMAIL_JOIN_REQUEST' => 'bx_groups_join_request',
            'EMAIL_JOIN_CONFIRM' => 'bx_groups_join_confirm',
            'EMAIL_FAN_BECOME_ADMIN' => 'bx_groups_fan_become_admin',
            'EMAIL_ADMIN_BECOME_FAN' => 'bx_groups_admin_become_fan',
            'EMAIL_FAN_REMOVE' => 'bx_groups_fan_remove',
            'EMAIL_JOIN_REJECT' => 'bx_groups_join_reject',

            'TRIGGER_MENU_PROFILE_VIEW_SUBMENU' => 'trigger_group_view_submenu',
            'TRIGGER_MENU_PROFILE_VIEW_ACTIONS' => 'trigger_group_view_actions',
        	'TRIGGER_PAGE_VIEW_ENTRY' => 'trigger_page_group_view_entry',

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_groups_view_actions' => $aMenuItems2Methods,
                'bx_groups_view_actions_more' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'status' => array (
                    'name' => 'bx-groups-status-not-active',
                    'map' => array (
                        BX_PROFILE_STATUS_PENDING => '_bx_groups_txt_account_pending',
                        BX_PROFILE_STATUS_SUSPENDED => '_bx_groups_txt_account_suspended',
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_groups_txt_sample_single',
                'txt_sample_vote_single' => '_bx_groups_txt_sample_vote_single',
                'txt_private_group' => '_bx_groups_txt_private_group',
                'txt_N_fans' => '_bx_groups_txt_N_fans',
                'txt_ntfs_join_request' => '_bx_groups_txt_ntfs_join_request',
                'txt_ntfs_fan_added' => '_bx_groups_txt_ntfs_fan_added',
                'menu_item_title_befriend_sent' => '_bx_groups_menu_item_title_befriend_sent',
                'menu_item_title_unfriend_cancel_request' => '_bx_groups_menu_item_title_unfriend_cancel_request',
                'menu_item_title_befriend_confirm' => '_bx_groups_menu_item_title_befriend_confirm',
                'menu_item_title_unfriend_reject_request' => '_bx_groups_menu_item_title_unfriend_reject_request',
                'menu_item_title_befriend' => '_bx_groups_menu_item_title_befriend',
                'menu_item_title_unfriend' => '_bx_groups_menu_item_title_unfriend',
            	'grid_action_err_delete' => '_bx_groups_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_groups_grid_txt_account_manager',
				'filter_item_active' => '_bx_groups_grid_filter_item_title_adm_active',
            	'filter_item_pending' => '_bx_groups_grid_filter_item_title_adm_pending',
            	'filter_item_suspended' => '_bx_groups_grid_filter_item_title_adm_suspended',
            	'filter_item_select_one_filter1' => '_bx_groups_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_groups_menu_item_title_manage_my',
                'menu_item_manage_all' => '_bx_groups_menu_item_title_manage_all',
                'menu_item_title_become_fan_sent' => '_bx_groups_menu_item_title_become_fan_sent',
                'menu_item_title_leave_group_cancel_request' => '_bx_groups_menu_item_title_leave_group_cancel_request',
                'menu_item_title_become_fan' => '_bx_groups_menu_item_title_become_fan',
                'menu_item_title_leave_group' => '_bx_groups_menu_item_title_leave_group',
                
                'menu_item_title_become_fan_sent' => '_bx_groups_menu_item_title_become_fan_sent',
                'menu_item_title_become_fan' => '_bx_groups_menu_item_title_become_fan',
            ),

        );

        $this->_aJsClasses = array(
        	'manage_tools' => 'BxGroupsManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxGroupsManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }

}

/** @} */
