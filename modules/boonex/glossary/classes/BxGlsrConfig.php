<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary 
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGlsrConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'approve' => 'checkAllowedApprove',
            'edit-glossary' => 'checkAllowedEdit',
            'delete-glossary' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'fa-book col-red3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'terms',
            'TABLE_ENTRIES_FULLTEXT' => 'title_text',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'glossary-text',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_CF' => 'cf',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
            'FIELD_VIEWS' => 'views',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_STATUS' => 'status',
            'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LABELS' => 'labels',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // some params
            'PARAM_PER_PAGE_FOR_FAVORITES_LISTS' => 'bx_glossary_per_page_for_favorites_lists',
            
            // page URIs
            'URI_VIEW_ENTRY' => 'view-glossary',
            'URI_AUTHOR_ENTRIES' => 'glossary-author',
            'URI_ENTRIES_BY_CONTEXT' => 'glossary-context',
            'URI_ADD_ENTRY' => 'create-glossary',
            'URI_EDIT_ENTRY' => 'edit-glossary',
            'URI_MANAGE_COMMON' => 'glossary-manage',
            'URI_FAVORITES_LIST' => 'glossary-favorites',

            'URL_HOME' => 'page.php?i=glossary-home',
            'URL_POPULAR' => 'page.php?i=glossary-popular',
            'URL_TOP' => 'page.php?i=glossary-top',
            'URL_UPDATED' => 'page.php?i=glossary-updated',
            'URL_MANAGE_COMMON' => 'page.php?i=glossary-manage',
            'URL_MANAGE_ADMINISTRATION' => 'page.php?i=glossary-administration',

            // some params
            'PARAM_AUTO_APPROVE' => 'bx_glossary_enable_auto_approve',
            'PARAM_CHARS_SUMMARY' => 'bx_glossary_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_glossary_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_glossary_rss_num',
            'PARAM_SEARCHABLE_FIELDS' => 'bx_glossary_searchable_fields',
            'PARAM_PER_PAGE_BROWSE_SHOWCASE' => 'bx_glossary_per_page_browse_showcase',

            // objects
            'OBJECT_STORAGE' => 'bx_glossary_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_glossary_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_glossary_gallery',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_glossary_cover',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
            'OBJECT_REPORTS' => 'bx_glossary',
            'OBJECT_VIEWS' => 'bx_glossary',
            'OBJECT_VOTES' => 'bx_glossary',
            'OBJECT_REACTIONS' => 'bx_glossary_reactions',
            'OBJECT_SCORES' => 'bx_glossary',
            'OBJECT_FAVORITES' => 'bx_glossary',
            'OBJECT_FEATURED' => 'bx_glossary',
            'OBJECT_METATAGS' => 'bx_glossary',
            'OBJECT_COMMENTS' => 'bx_glossary',
            'OBJECT_NOTES' => 'bx_glossary_notes',
            'OBJECT_CATEGORY' => 'bx_glossary_cats',
            'OBJECT_PRIVACY_VIEW' => 'bx_glossary_allow_view_to',
            'OBJECT_PRIVACY_LIST_VIEW' => 'bx_glossary_allow_view_favorite_list',
            'OBJECT_FORM_ENTRY' => 'bx_glossary',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_glossary_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_glossary_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_glossary_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_glossary_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_glossary_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL' => 'bx_glossary_view_actions', // all actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_glossary_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_glossary_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_glossary_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'glossary-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_SNIPPET_META' => 'bx_glossary_snippet_meta', // menu for snippet meta info
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_glossary_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_glossary_administration',
            'OBJECT_GRID_COMMON' => 'bx_glossary_common',
            'OBJECT_UPLOADERS' => array('sys_simple', 'sys_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_glossary_my' => array (
                    'create-glossary' => 'checkAllowedAdd',
                ),
                'bx_glossary_view' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'approving' => array (
                    'name' => 'bx-glossary-approving',
                    'map' => array (
                        'pending' => array('msg' => '_bx_glossary_txt_msg_status_pending', 'type' => BX_INFORMER_ALERT),
                        'hidden' => array('msg' => '_bx_glossary_txt_msg_status_hidden', 'type' => BX_INFORMER_ERROR),
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_glossary_txt_sample_single',
                'txt_sample_single_with_article' => '_bx_glossary_txt_sample_single_with_article',
                'txt_sample_comment_single' => '_bx_glossary_txt_sample_comment_single',
                'txt_sample_vote_single' => '_bx_glossary_txt_sample_vote_single',
                'txt_sample_reaction_single' => '_bx_glossary_txt_sample_reaction_single',
                'txt_sample_score_up_single' => '_bx_glossary_txt_sample_score_up_single',
                'txt_sample_score_down_single' => '_bx_glossary_txt_sample_score_down_single',
                'form_field_author' => '_bx_glossary_form_entry_input_author',
                'grid_action_err_delete' => '_bx_glossary_grid_action_err_delete',
                'grid_txt_account_manager' => '_bx_glossary_grid_txt_account_manager',
                'filter_item_active' => '_bx_glossary_grid_filter_item_title_adm_active',
                'filter_item_hidden' => '_bx_glossary_grid_filter_item_title_adm_hidden',
                'filter_item_pending' => '_bx_glossary_grid_filter_item_title_adm_pending',
                'filter_item_select_one_filter1' => '_bx_glossary_grid_filter_item_title_adm_select_one_filter1',
                'filter_item_select_one_filter2' => '_bx_glossary_grid_filter_item_title_adm_select_one_filter2',
                'menu_item_manage_my' => '_bx_glossary_menu_item_title_manage_my',
                'menu_item_manage_all' => '_bx_glossary_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_glossary_txt_all_entries_by',
                'txt_all_entries_in' => '_bx_glossary_txt_all_entries_in',
                'txt_all_entries_by_author' => '_bx_glossary_page_title_browse_by_author',
                'txt_all_entries_by_context' => '_bx_glossary_page_title_browse_by_context',
                'txt_pict_use_as_thumb' => '_bx_glossary_form_entry_input_picture_use_as_thumb'
            ),
        );

        $this->_aJsClasses = array(
            'manage_tools' => 'BxGlsrManageTools'
        );

        $this->_aJsObjects = array(
            'manage_tools' => 'oBxGlsrManageTools'
        );

        $this->_aGridObjects = array(
            'common' => $this->CNF['OBJECT_GRID_COMMON'],
            'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
            
        );
    }
}

/** @} */
