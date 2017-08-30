<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOrgsConfig extends BxBaseModProfileConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2MethodsSubmenu = array (
        	'organization-profile-subscriptions' => 'checkAllowedSubscriptionsView'
        );

        $aMenuItems2MethodsActions = array (
            'view-organization-profile' => 'checkAllowedView',
            'edit-organization-profile' => 'checkAllowedEdit',
            'edit-organization-cover' => 'checkAllowedChangeCover',
            'delete-organization-profile' => 'checkAllowedDelete',
            'profile-friend-add' => 'checkAllowedFriendAdd',
            'profile-friend-remove' => 'checkAllowedFriendRemove',
            'profile-subscribe-add' => 'checkAllowedSubscribeAdd',
            'profile-subscribe-remove' => 'checkAllowedSubscribeRemove',
            'profile-actions-more' => 'checkAllowedViewMoreMenu',
            'profile-set-acl-level' => 'checkAllowedSetMembership',
            'convos-compose' => 'checkAllowedCompose',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'briefcase col-red2',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'data',
            'TABLE_ENTRIES_FULLTEXT' => 'search_fields',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_NAME' => 'org_name',
        	'FIELD_TITLE' => 'org_name',
        	'FIELD_TEXT' => 'org_desc',
            'FIELD_PICTURE' => 'picture',
            'FIELD_COVER' => 'cover',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELDS_QUICK_SEARCH' => array('org_name'),
        	'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-organization-profile',
        	'URI_VIEW_FRIENDS' => 'organization-profile-friends',
        	'URI_VIEW_FRIEND_REQUESTS' => 'organization-friend-requests',
        	'URI_VIEW_FAVORITES' => 'organization-profile-favorites',
            'URI_EDIT_ENTRY' => 'edit-organization-profile',
            'URI_EDIT_COVER' => 'edit-organization-cover',
        	'URI_MANAGE_COMMON' => 'organizations-manage',

            'URL_HOME' => 'page.php?i=organizations-home',
        	'URL_CREATE' => 'page.php?i=create-organization-profile',
        	'URL_MANAGE_COMMON' => 'page.php?i=organizations-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=organizations-administration',

            // some params
            'PARAM_AUTOAPPROVAL' => 'bx_organizations_autoapproval',
            'PARAM_DEFAULT_ACL_LEVEL' => 'bx_organizations_default_acl_level',
            'PARAM_NUM_RSS' => 'bx_organizations_num_rss',
            'PARAM_NUM_CONNECTIONS_QUICK' => 'bx_organizations_num_connections_quick',
            'PARAM_SEARCHABLE_FIELDS' => 'bx_organizations_searchable_fields',
        	'PARAM_PUBLIC_SBSN' => 'bx_organizations_public_subscriptions',
            'PARAM_PUBLIC_SBSD' => 'bx_organizations_public_subscribed_me',
        	'PARAM_REDIRECT_AADD' => 'bx_persons_redirect_aadd',
            'PARAM_REDIRECT_AADD_CUSTOM_URL' => 'bx_persons_redirect_aadd_custom_url',

            // objects
            'OBJECT_STORAGE' => 'bx_organizations_pics',
            'OBJECT_STORAGE_COVER' => 'bx_organizations_pics',
            'OBJECT_IMAGES_TRANSCODER_THUMB' => 'bx_organizations_thumb',
            'OBJECT_IMAGES_TRANSCODER_ICON' => 'bx_organizations_icon',
            'OBJECT_IMAGES_TRANSCODER_AVATAR' => 'bx_organizations_avatar',
            'OBJECT_IMAGES_TRANSCODER_PICTURE' => 'bx_organizations_picture',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_organizations_cover',
            'OBJECT_IMAGES_TRANSCODER_COVER_THUMB' => 'bx_organizations_cover_thumb',
        	'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_organizations_gallery',
            'OBJECT_VIEWS' => 'bx_organizations',
        	'OBJECT_FAVORITES' => 'bx_organizations',
        	'OBJECT_FEATURED' => 'bx_organizations',
            'OBJECT_COMMENTS' => 'bx_organizations',
        	'OBJECT_REPORTS' => 'bx_organizations',
            'OBJECT_METATAGS' => 'bx_organizations',
            'OBJECT_FORM_ENTRY' => 'bx_organization',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_organization_view',
        	'OBJECT_FORM_ENTRY_DISPLAY_VIEW_FULL' => 'bx_organization_view_full', // for "info" tab on view profile page
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_organization_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_organization_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT_COVER' => 'bx_organization_edit_cover',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_organization_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_organizations_view_actions', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE' => 'bx_organizations_view_actions_more', // actions menu on view entry page for "more" popup
            'OBJECT_MENU_SUBMENU' => 'bx_organizations_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_organizations_view_submenu',  // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_COVER' => 'bx_organizations_view_submenu_cover',  // view entry submenu displayed in cover
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'organizations-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_organizations_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_PAGE_VIEW_ENTRY' => 'bx_organizations_view_profile',
            'OBJECT_PAGE_VIEW_ENTRY_CLOSED' => 'bx_organizations_view_profile_closed',
            'OBJECT_PRIVACY_VIEW' => 'bx_organizations_allow_view_to',
            'OBJECT_GRID_ADMINISTRATION' => 'bx_organizations_administration',
            'OBJECT_GRID_COMMON' => 'bx_organizations_common',
            'OBJECT_UPLOADERS_COVER' => array('bx_organizations_cover_crop'),
            'OBJECT_UPLOADERS_PICTURE' => array('bx_organizations_picture_crop'),

            'EMAIL_FRIEND_REQUEST' => 'bx_organizations_friend_request',

            'TRIGGER_MENU_PROFILE_VIEW_SUBMENU' => 'trigger_profile_view_submenu',
            'TRIGGER_MENU_PROFILE_VIEW_ACTIONS' => 'trigger_profile_view_actions',
        	'TRIGGER_PAGE_VIEW_ENTRY' => 'trigger_page_profile_view_entry',

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
        		'bx_organizations_view_submenu' => $aMenuItems2MethodsSubmenu,
                'bx_organizations_view_actions' => $aMenuItems2MethodsActions,
                'bx_organizations_view_actions_more' => $aMenuItems2MethodsActions,
            ),

            // informer messages
            'INFORMERS' => array (
                'status' => array (
                    'name' => 'bx-organizations-status-not-active',
                    'map' => array (
                        BX_PROFILE_STATUS_PENDING => '_bx_orgs_txt_account_pending',
                        BX_PROFILE_STATUS_SUSPENDED => '_bx_orgs_txt_account_suspended',
                    ),
                ),
                'status_moderation' => array (
                    'name' => 'bx-organizations-status-not-active-moderation',
                    'map' => array (
                        BX_PROFILE_STATUS_PENDING => '_bx_orgs_txt_account_pending_moderation',
                        BX_PROFILE_STATUS_SUSPENDED => '_bx_orgs_txt_account_suspended_moderation',
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_orgs_txt_sample_single',
            	'txt_sample_comment_single' => '_bx_orgs_txt_sample_comment_single',
                'txt_N_fans' => '_bx_orgs_txt_N_friends',
                'menu_item_title_befriend_sent' => '_bx_orgs_menu_item_title_befriend_sent',
                'menu_item_title_unfriend_cancel_request' => '_bx_orgs_menu_item_title_unfriend_cancel_request',
                'menu_item_title_befriend_confirm' => '_bx_orgs_menu_item_title_befriend_confirm',
                'menu_item_title_unfriend_reject_request' => '_bx_orgs_menu_item_title_unfriend_reject_request',
                'menu_item_title_befriend' => '_bx_orgs_menu_item_title_befriend',
                'menu_item_title_unfriend' => '_bx_orgs_menu_item_title_unfriend',
            	'grid_action_err_delete' => '_bx_orgs_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_orgs_grid_txt_account_manager',
				'filter_item_active' => '_bx_orgs_grid_filter_item_title_adm_active',
            	'filter_item_pending' => '_bx_orgs_grid_filter_item_title_adm_pending',
            	'filter_item_suspended' => '_bx_orgs_grid_filter_item_title_adm_suspended',
            	'filter_item_select_one_filter1' => '_bx_orgs_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_orgs_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_orgs_menu_item_title_manage_all',
            	'txt_browse_favorites' => '_bx_orgs_page_title_browse_favorites',
            	'option_redirect_aadd_profile' => '_bx_persons_option_redirect_aadd_profile',
            	'option_redirect_aadd_last' => '_bx_persons_option_redirect_aadd_last',
            	'option_redirect_aadd_custom' => '_bx_persons_option_redirect_aadd_custom'
            ),

        );

        $this->_aJsClasses = array(
        	'manage_tools' => 'BxOrgsManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxOrgsManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }

}

/** @} */
