<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPollsConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'approve' => 'checkAllowedApprove',
            'edit-poll' => 'checkAllowedEdit',
            'delete-poll' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'tasks col-green1',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'entries',
            'TABLE_SUBENTRIES' => $aModule['db_prefix'] . 'subentries',
            'TABLE_VOTES_SUBENTRIES_TRACK' => $aModule['db_prefix'] . 'votes_subentries_track',
            'TABLE_ENTRIES_FULLTEXT' => 'title_text',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => '',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'poll-text',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_SUBENTRIES' => 'subentries',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_CF' => 'cf',
            'FIELD_ANONYMOUS_VOTING' => 'anonymous',
            'FIELD_HIDDEN_RESULTS' => 'hidden_results',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
            'FIELD_VIEWS' => 'views',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_STATUS' => 'status',
            'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LOCATION' => 'location',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELD_LABELS' => 'labels',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // some params
            'PARAM_PER_PAGE_FOR_FAVORITES_LISTS' => 'bx_polls_per_page_for_favorites_lists',
            
            // page URIs
            'URI_VIEW_ENTRY' => 'view-poll',
            'URI_AUTHOR_ENTRIES' => 'polls-author',
            'URI_ENTRIES_BY_CONTEXT' => 'polls-context',
            'URI_ADD_ENTRY' => 'create-poll',
            'URI_EDIT_ENTRY' => 'edit-poll',
            'URI_MANAGE_COMMON' => 'polls-manage',
            'URI_FAVORITES_LIST' => 'polls-favorites',

            'URL_HOME' => 'page.php?i=polls-home',
            'URL_POPULAR' => 'page.php?i=polls-popular',
            'URL_TOP' => 'page.php?i=polls-top',
            'URL_UPDATED' => 'page.php?i=polls-updated',
            'URL_MANAGE_COMMON' => 'page.php?i=polls-manage',
            'URL_MANAGE_ADMINISTRATION' => 'page.php?i=polls-administration',

            // some params
            'PARAM_AUTO_APPROVE' => 'bx_polls_enable_auto_approve',
            'PARAM_CHARS_TITLE' => 'bx_polls_title_chars',
            'PARAM_CHARS_SUMMARY' => '',
            'PARAM_CHARS_SUMMARY_PLAIN' => '',
            'PARAM_NUM_RSS' => 'bx_polls_rss_num',
            'PARAM_SEARCHABLE_FIELDS' => 'bx_polls_searchable_fields',

            // objects
            'OBJECT_STORAGE' => 'bx_polls_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_polls_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_polls_gallery',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_polls_cover',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
            'OBJECT_REPORTS' => 'bx_polls',
            'OBJECT_VIEWS' => 'bx_polls',
            'OBJECT_VOTES' => 'bx_polls',
            'OBJECT_VOTES_SUBENTRIES' => 'bx_polls_subentries',
            'OBJECT_REACTIONS' => 'bx_polls_reactions',
            'OBJECT_SCORES' => 'bx_polls',
            'OBJECT_FAVORITES' => 'bx_polls',
            'OBJECT_FEATURED' => 'bx_polls',
            'OBJECT_METATAGS' => 'bx_polls',
            'OBJECT_COMMENTS' => 'bx_polls',
            'OBJECT_NOTES' => 'bx_polls_notes',
            'OBJECT_CATEGORY' => 'bx_polls_cats',
            'OBJECT_PRIVACY_VIEW' => 'bx_polls_allow_view_to',
            'OBJECT_PRIVACY_LIST_VIEW' => 'bx_polls_allow_view_favorite_list',
            'OBJECT_FORM_ENTRY' => 'bx_polls',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_polls_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_polls_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_polls_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_polls_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_polls_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL' => 'bx_polls_view_actions', // all actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_polls_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_polls_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_polls_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'polls-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_SNIPPET_META' => 'bx_polls_snippet_meta', // menu for snippet meta info
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_polls_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_polls_administration',
            'OBJECT_GRID_COMMON' => 'bx_polls_common',
            'OBJECT_UPLOADERS' => array('sys_html5'),

            // styles
            'STYLES_POLLS_EMBED_CLASS' => 'body.bx-page-iframe.bx-def-color-bg-page',
            'STYLES_POLLS_EMBED_CONTENT' => array(
                'background-color' => 'transparent'
            ),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_polls_my' => array (
                    'create-poll' => 'checkAllowedAdd',
                ),
                'bx_polls_view' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'approving' => array (
                    'name' => 'bx-polls-approving',
                    'map' => array (
                        'pending' => array('msg' => '_bx_polls_txt_msg_status_pending', 'type' => BX_INFORMER_ALERT),
                        'hidden' => array('msg' => '_bx_polls_txt_msg_status_hidden', 'type' => BX_INFORMER_ERROR),
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_polls_txt_sample_single',
                'txt_sample_single_with_article' => '_bx_polls_txt_sample_single_with_article',
                'txt_sample_comment_single' => '_bx_polls_txt_sample_comment_single',
                'txt_sample_vote_single' => '_bx_polls_txt_sample_vote_single',
                'txt_sample_reaction_single' => '_bx_polls_txt_sample_reaction_single',
                'txt_sample_score_up_single' => '_bx_polls_txt_sample_score_up_single',
                'txt_sample_score_down_single' => '_bx_polls_txt_sample_score_down_single',
                'form_field_author' => '_bx_polls_form_entry_input_author',
                'grid_action_err_delete' => '_bx_polls_grid_action_err_delete',
                'grid_txt_account_manager' => '_bx_polls_grid_txt_account_manager',
                'filter_item_active' => '_bx_polls_grid_filter_item_title_adm_active',
                'filter_item_hidden' => '_bx_polls_grid_filter_item_title_adm_hidden',
                'filter_item_pending' => '_bx_polls_grid_filter_item_title_adm_pending',
                'filter_item_select_one_filter1' => '_bx_polls_grid_filter_item_title_adm_select_one_filter1',
                'filter_item_select_one_filter2' => '_bx_polls_grid_filter_item_title_adm_select_one_filter2',
                'menu_item_manage_my' => '_bx_polls_menu_item_title_manage_my',
                'menu_item_manage_all' => '_bx_polls_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_polls_txt_all_entries_by',
                'txt_all_entries_in' => '_bx_polls_txt_all_entries_in',
                'txt_all_entries_by_author' => '_bx_polls_page_title_browse_by_author',
                'txt_all_entries_by_context' => '_bx_polls_page_title_browse_by_context',
                'txt_pict_use_as_thumb' => '_bx_polls_form_entry_input_picture_use_as_thumb'
            ),
        );

        $this->_aJsClasses = array(
            'form' => 'BxPollsForm',
            'entry' => 'BxPollsEntry',
            'manage_tools' => 'BxPollsManageTools'
        );

        $this->_aJsObjects = array(
            'form' => 'oBxPollsForm',
            'entry' => 'oBxPollsEntry',
            'manage_tools' => 'oBxPollsManageTools'
        );

        $this->_aGridObjects = array(
            'common' => $this->CNF['OBJECT_GRID_COMMON'],
            'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
            'block_menu' => $sHtmlPrefix . '-block-menu',
            'block_link_subentries' => $sHtmlPrefix . '-block-subentries-',
            'block_link_results' => $sHtmlPrefix . '-block-results-',
            'snippet_link_subentries' => $sHtmlPrefix . '-snippet-subentries-',
            'snippet_link_results' => $sHtmlPrefix . '-snippet-results-',
            'content' => $sHtmlPrefix . '-content-',
            'embed' => $sHtmlPrefix . '-content-',
        );
    }

    public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }

    public function getTitle($aData)
    {
        return BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($aData[$this->CNF['FIELD_TEXT']]), (int)getParam($this->CNF['PARAM_CHARS_TITLE']));
    }
    
    public function getSalt()
    {
        return time() . rand(0, PHP_INT_MAX);
    }
}

/** @} */
