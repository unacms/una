<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 *
 * @{
 */

class BxMarketConfig extends BxBaseModTextConfig
{
	protected $_aCurrency;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'edit-product' => 'checkAllowedEdit',
            'delete-product' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'file-text col-red3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'products',
        	'TABLE_PHOTOS2ENTRIES' => $aModule['db_prefix'] . 'photos2products',
        	'TABLE_FILES2ENTRIES' => $aModule['db_prefix'] . 'files2products',
        	'TABLE_FILES' => $aModule['db_prefix'] . 'files',
        	'TABLE_DOWNLOADS' => $aModule['db_prefix'] . 'downloads_track',
        	'TABLE_LICENSES' => $aModule['db_prefix'] . 'licenses',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
        	'FIELD_NAME' => 'name',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'product-text',
        	'FIELD_CATEGORY' => 'cat',
        	'FIELD_HEADER_BEG_SINGLE' => 'header_beg_single',
        	'FIELD_PRICE_SINGLE' => 'price_single',
        	'FIELD_HEADER_END_SINGLE' => 'header_end_single',
        	'FIELD_HEADER_BEG_RECURRING' => 'header_beg_recurring',
        	'FIELD_PRICE_RECURRING' => 'price_recurring',
        	'FIELD_DURATION_RECURRING' => 'duration_recurring',
        	'FIELD_HEADER_END_RECURRING' => 'header_end_recurring',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
			'FIELD_ALLOW_PURCHASE_TO' => 'allow_purchase_to',
			'FIELD_ALLOW_COMMENT_TO' => 'allow_comment_to',
			'FIELD_ALLOW_VOTE_TO' => 'allow_vote_to',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
        	'FIELD_COVER' => 'cover',
        	'FIELD_FILE' => 'files',
        	'FIELD_PACKAGE' => 'package',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-product',
            'URI_AUTHOR_ENTRIES' => 'products-author',
            'URI_ADD_ENTRY' => 'create-product',
        	'URI_EDIT_ENTRY' => 'edit-product',
        	'URI_MANAGE_COMMON' => 'products-manage',

            'URL_HOME' => 'page.php?i=products-home',
            'URL_POPULAR' => 'page.php?i=products-popular',
            'URL_UPDATED' => 'page.php?i=products-updated',
        	'URL_MANAGE_COMMON' => 'page.php?i=products-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=products-administration',
        	'URL_VIEW_ENTRY' => 'page.php?i=view-product&id=',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_market_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_market_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_market_rss_num',

            // objects            
            'OBJECT_STORAGE' => 'bx_market_photos',
        	'OBJECT_STORAGE_FILES' => 'bx_market_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_market_preview',
        	'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_market_cover',
        	'OBJECT_IMAGES_TRANSCODER_SCREENSHOT' => 'bx_market_screenshot',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_market_gallery',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
        	'OBJECT_REPORTS' => 'bx_market',
            'OBJECT_VIEWS' => 'bx_market',
            'OBJECT_VOTES' => 'bx_market',
            'OBJECT_METATAGS' => 'bx_market',
            'OBJECT_COMMENTS' => 'bx_market',
            'OBJECT_PRIVACY_VIEW' => 'bx_market_allow_view_to',
			'OBJECT_PRIVACY_PURCHASE' => 'bx_market_allow_purchase_to',
			'OBJECT_PRIVACY_COMMENT' => 'bx_market_allow_comment_to',
			'OBJECT_PRIVACY_VOTE' => 'bx_market_allow_vote_to',
            'OBJECT_FORM_ENTRY' => 'bx_market',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_market_entry_view',
        	'OBJECT_FORM_ENTRY_DISPLAY_VIEW_FULL' => 'bx_market_entry_view_full',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_market_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_market_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_market_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_market_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_market_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_market_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_market_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'products-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_market_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_market_administration',
        	'OBJECT_GRID_COMMON' => 'bx_market_common',
        	'OBJECT_GRID_LICENSES' => 'bx_market_licenses',
            'OBJECT_UPLOADERS' => array('bx_market_simple', 'bx_market_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_market_my' => array (
                    'create-product' => 'checkAllowedAdd',
                ),
                'bx_market_view' => $aMenuItems2Methods,
            ),

            // global settings
			'OPTION_ENABLE_RECURRING' => 'bx_market_enable_recurring',
            'OPTION_RECURRING_RESERVE' => 'bx_market_recurring_reserve',

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_market_txt_sample_single',
            	'txt_sample_comment_single' => '_bx_market_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_market_txt_sample_vote_single',
            	'grid_action_err_delete' => '_bx_market_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_market_grid_txt_account_manager',
				'filter_item_active' => '_bx_market_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_market_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_market_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_market_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_market_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_market_txt_all_entries_by',
            ),
        );

        $this->_aJsClasses = array(
        	'entry' => 'BxMarketEntry',
        	'manage_tools' => 'BxMarketManageTools',
        	'licenses' => 'BxMarketLicenses'
        );

        $this->_aJsObjects = array(
        	'entry' => 'oBxMarketEntry',
        	'manage_tools' => 'oBxMarketManageTools',
        	'licenses' => 'oBxMarketLicenses'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	'licenses' => $this->CNF['OBJECT_GRID_LICENSES'],
        );

        $oPayments = BxDolPayments::getInstance();
        $this->_aCurrency = array(
        	'code' => $oPayments->getOption('default_currency_code'),
        	'sign' => $oPayments->getOption('default_currency_sign')
        );
    }

    public function getCurrency()
    {
    	return $this->_aCurrency;
    }
}

/** @} */
