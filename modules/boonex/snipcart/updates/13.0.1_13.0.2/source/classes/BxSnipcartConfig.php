<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

class BxSnipcartConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'approve' => 'checkAllowedApprove',
            'edit-snipcart-entry' => 'checkAllowedEdit',
            'delete-snipcart-entry' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'shopping-cart col-green2',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'entries',
            'TABLE_SETTINGS' => $aModule['db_prefix'] . 'settings',
            'TABLE_ENTRIES_FULLTEXT' => 'title_text',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'snipcart-entry-text',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_PRICE' => 'price',
            'FIELD_WEIGHT' => 'weight',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_CF' => 'cf',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
            'FIELD_VIEWS' => 'views',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_STATUS' => 'status',
            'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LOCATION' => 'location',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-snipcart-entry',
            'URI_AUTHOR_ENTRIES' => 'snipcart-author',
            'URI_ENTRIES_BY_CONTEXT' => 'snipcart-context',
            'URI_ADD_ENTRY' => 'create-snipcart-entry',
            'URI_EDIT_ENTRY' => 'edit-snipcart-entry',
            'URI_MANAGE_COMMON' => 'snipcart-manage',

            'URL_HOME' => 'page.php?i=snipcart-home',
            'URL_POPULAR' => 'page.php?i=snipcart-popular',
            'URL_UPDATED' => 'page.php?i=snipcart-updated',
            'URL_MANAGE_COMMON' => 'page.php?i=snipcart-manage',
            'URL_MANAGE_ADMINISTRATION' => 'page.php?i=snipcart-administration',
            'URL_SETTINGS' => 'page.php?i=snipcart-settings',

            // some params
            'PARAM_AUTO_APPROVE' => 'bx_snipcart_enable_auto_approve',
            'PARAM_CHARS_SUMMARY' => 'bx_snipcart_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_snipcart_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_snipcart_rss_num',
            'PARAM_SEARCHABLE_FIELDS' => 'bx_snipcart_searchable_fields',

            // objects
            'OBJECT_STORAGE' => 'bx_snipcart_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_snipcart_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_snipcart_gallery',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_snipcart_cover',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
            'OBJECT_REPORTS' => 'bx_snipcart',
            'OBJECT_VIEWS' => 'bx_snipcart',
            'OBJECT_VOTES' => 'bx_snipcart',
            'OBJECT_REACTIONS' => 'bx_snipcart_reactions',
            'OBJECT_SCORES' => 'bx_snipcart',
            'OBJECT_FAVORITES' => 'bx_snipcart',
            'OBJECT_FEATURED' => 'bx_snipcart',
            'OBJECT_METATAGS' => 'bx_snipcart',
            'OBJECT_COMMENTS' => 'bx_snipcart',
            'OBJECT_NOTES' => 'bx_snipcart_notes',
            'OBJECT_CATEGORY' => 'bx_snipcart_cats',
            'OBJECT_PRIVACY_VIEW' => 'bx_snipcart_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_snipcart',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_snipcart_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_snipcart_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_snipcart_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_snipcart_entry_delete',
            'OBJECT_FORM_SETTINGS' => 'bx_snipcart_settings',
            'OBJECT_FORM_SETTINGS_DISPLAY_EDIT' => 'bx_snipcart_settings_edit',
            'OBJECT_FORM_PRELISTS_CURRENCIES' => 'bx_snipcart_currencies',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_snipcart_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL' => 'bx_snipcart_view_actions', // all actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_snipcart_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_snipcart_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_snipcart_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'snipcart-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_SNIPPET_META' => 'bx_snipcart_snippet_meta', // menu for snippet meta info
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_snipcart_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_snipcart_administration',
            'OBJECT_GRID_COMMON' => 'bx_snipcart_common',
            'OBJECT_UPLOADERS' => array('sys_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_snipcart_my' => array (
                    'create-snipcart-entry' => 'checkAllowedAdd',
                ),
                'bx_snipcart_view' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'approving' => array (
                    'name' => 'bx-snipcart-approving',
                    'map' => array (
                        'pending' => array('msg' => '_bx_snipcart_txt_msg_status_pending', 'type' => BX_INFORMER_ALERT),
                        'hidden' => array('msg' => '_bx_snipcart_txt_msg_status_hidden', 'type' => BX_INFORMER_ERROR),
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_snipcart_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_snipcart_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_snipcart_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_snipcart_txt_sample_vote_single',
                'txt_sample_reaction_single' => '_bx_snipcart_txt_sample_reaction_single',
                'txt_sample_score_up_single' => '_bx_snipcart_txt_sample_score_up_single',
                'txt_sample_score_down_single' => '_bx_snipcart_txt_sample_score_down_single',
            	'form_field_author' => '_bx_snipcart_form_entry_input_author',
            	'grid_action_err_delete' => '_bx_snipcart_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_snipcart_grid_txt_account_manager',
                'filter_item_active' => '_bx_snipcart_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_snipcart_grid_filter_item_title_adm_hidden',
                'filter_item_pending' => '_bx_snipcart_grid_filter_item_title_adm_pending',
            	'filter_item_select_one_filter1' => '_bx_snipcart_grid_filter_item_title_adm_select_one_filter1',
                'filter_item_select_one_filter2' => '_bx_snipcart_grid_filter_item_title_adm_select_one_filter2',
            	'menu_item_manage_my' => '_bx_snipcart_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_snipcart_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_snipcart_txt_all_entries_by',
                'txt_all_entries_in' => '_bx_snipcart_txt_all_entries_in',
                'txt_all_entries_by_author' => '_bx_snipcart_page_title_browse_by_author',
                'txt_all_entries_by_context' => '_bx_snipcart_page_title_browse_by_context',
            	'txt_pict_use_as_thumb' => '_bx_snipcart_form_entry_input_picture_use_as_thumb'
            ),
        );

        $this->_aJsClasses = array(
            'manage_tools' => 'BxSnipcartManageTools'
        );

        $this->_aJsObjects = array(
            'manage_tools' => 'oBxSnipcartManageTools'
        );

        $this->_aGridObjects = array(
            'common' => $this->CNF['OBJECT_GRID_COMMON'],
            'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        );
    }

    public function getViewUrl($iContentId)
    {
        return bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId));
    }

    public function getApiKey($aSettings)
    {
        if(empty($aSettings['mode']) || !in_array($aSettings['mode'], array(BX_SNIPCART_MODE_TEST, BX_SNIPCART_MODE_LIVE)))
            return '';

        return $aSettings['api_key_' . $aSettings['mode']]; 
    }

    public function getCurrency($aSettings)
    {
        $sCurrency = !empty($aSettings['currency']) ? $aSettings['currency'] : 'USD';

        $aCurrencies = BxDolForm::getDataItems($this->CNF['OBJECT_FORM_PRELISTS_CURRENCIES']);
        $sCurrencyCode = $aCurrencies[$sCurrency];

        $aCurrencies = BxDolForm::getDataItems($this->CNF['OBJECT_FORM_PRELISTS_CURRENCIES'], false, BX_DATA_VALUES_ADDITIONAL);
        $sCurrencySign = $aCurrencies[$sCurrency];

        return array('code' => $sCurrencyCode, 'sign' => $sCurrencySign);
    }
}

/** @} */
