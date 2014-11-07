<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModProfileConfig');

class BxPersonsConfig extends BxBaseModProfileConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'view-persons-profile' => 'checkAllowedView',
            'edit-persons-profile' => 'checkAllowedEdit',
            'delete-persons-profile' => 'checkAllowedDelete',
            'profile-friend-add' => 'checkAllowedFriendAdd',
            'profile-friend-remove' => 'checkAllowedFriendRemove',
            'profile-subscribe-add' => 'checkAllowedSubscribeAdd',
            'profile-subscribe-remove' => 'checkAllowedSubscribeRemove',
            'profile-actions-more' => 'checkAllowedViewMoreMenu',
            'profile-set-acl-level' => 'checkAllowedSetMembership',
        );

        $this->CNF = array (

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'data',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_NAME' => 'fullname',
            'FIELD_PICTURE' => 'picture',
            'FIELD_PICTURE_PREVIEW' => 'picture_preview',
            'FIELD_COVER' => 'cover',
            'FIELD_COVER_PREVIEW' => 'cover_preview',
            'FIELDS_QUICK_SEARCH' => array('fullname'),

            // page URIs
            'URI_VIEW_ENTRY' => 'view-persons-profile',
            'URI_EDIT_ENTRY' => 'edit-persons-profile',
            'URI_EDIT_COVER' => 'edit-persons-cover',
        	'URI_MANAGE_COMMON' => 'persons-manage',

            'URL_HOME' => 'page.php?i=persons-home',
        	'URL_MANAGE_COMMON' => 'page.php?i=persons-manage',
        	'URL_MANAGE_MODERATION' => 'page.php?i=persons-moderation',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=persons-administration',

            // some params
            'PARAM_AUTOAPPROVAL' => 'bx_persons_autoapproval',
            'PARAM_DEFAULT_ACL_LEVEL' => 'bx_persons_default_acl_level',
            'PARAM_NUM_RSS' => 'bx_persons_num_rss',
            'PARAM_NUM_CONNECTIONS_QUICK' => 'bx_persons_num_connections_quick',

            // objects
            'OBJECT_STORAGE' => 'bx_persons_pictures',
            'OBJECT_STORAGE_COVER' => 'bx_persons_pictures',
            'OBJECT_IMAGES_TRANSCODER_THUMB' => 'bx_persons_thumb',
            'OBJECT_IMAGES_TRANSCODER_ICON' => 'bx_persons_icon',
            'OBJECT_IMAGES_TRANSCODER_AVATAR' => 'bx_persons_avatar',
            'OBJECT_IMAGES_TRANSCODER_PICTURE' => 'bx_persons_picture',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_persons_cover',
            'OBJECT_IMAGES_TRANSCODER_COVER_THUMB' => 'bx_persons_cover_thumb',
            'OBJECT_VIEWS' => 'bx_persons',
            'OBJECT_FORM_ENTRY' => 'bx_person',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_person_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_person_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_person_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT_COVER' => 'bx_person_edit_cover',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_person_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_persons_view_actions', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE' => 'bx_persons_view_actions_more', // actions menu on view entry page for "more" popup
            'OBJECT_MENU_SUBMENU' => 'bx_persons_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_persons_view_submenu',  // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'persons-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_persons_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_persons_administration',
        	'OBJECT_GRID_MODERATION' => 'bx_persons_moderation',
        	'OBJECT_GRID_COMMON' => 'bx_persons_common',

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_persons_view_actions' => $aMenuItems2Methods,
                'bx_persons_view_actions_more' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'status' => array (
                    'name' => 'bx-persons-status-not-active',
                    'map' => array (
                        BX_PROFILE_STATUS_PENDING => '_bx_persons_txt_account_pending',
                        BX_PROFILE_STATUS_SUSPENDED => '_bx_persons_txt_account_suspended',
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_persons_txt_sample_single',
                'menu_item_title_befriend_sent' => '_bx_persons_menu_item_title_befriend_sent',
                'menu_item_title_unfriend_cancel_request' => '_bx_persons_menu_item_title_unfriend_cancel_request',
                'menu_item_title_befriend_confirm' => '_bx_persons_menu_item_title_befriend_confirm',
                'menu_item_title_unfriend_reject_request' => '_bx_persons_menu_item_title_unfriend_reject_request',
                'menu_item_title_befriend' => '_bx_persons_menu_item_title_befriend',
                'menu_item_title_unfriend' => '_bx_persons_menu_item_title_unfriend',
            	'grid_action_err_delete' => '_bx_persons_grid_action_err_delete',
            	'filter_item_active' => '_bx_persons_grid_filter_item_title_adm_active',
            	'filter_item_pending' => '_bx_persons_grid_filter_item_title_adm_pending',
            	'filter_item_suspended' => '_bx_persons_grid_filter_item_title_adm_suspended',
            	'filter_item_select_one_filter1' => '_bx_persons_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_persons_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_persons_menu_item_title_manage_all',
            ),

        );

        $this->_aJsClass = array(
        	'manage_tools' => 'BxPersonsManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxPersonsManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'moderation' => $this->CNF['OBJECT_GRID_MODERATION'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }
}

/** @} */
