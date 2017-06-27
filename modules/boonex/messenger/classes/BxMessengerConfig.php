<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Messenger Messenger
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolPrivacy');

class BxMessengerConfig extends BxBaseModTextConfig
{
	function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (

            // module icon
            'ICON' => 'comments-o col-green3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'lots',
			'TABLE_MESSAGES' => $aModule['db_prefix'] . 'jots',
			'TABLE_TYPES' => $aModule['db_prefix'] . 'lots_types',
			'TABLE_USERS_INFO' => $aModule['db_prefix'] . 'users_info',
        	'TABLE_ENTRIES_FULLTEXT' => 'search_title',

            // database fields
            
			// main lot table fields 
			'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'created',
			'FIELD_PARTICIPANTS' => 'participants',			
            'FIELD_TITLE' => 'title',
			'FIELD_URL' => 'url',
			'FIELD_TYPE' => 'type',
			'FIELD_CLASS' => 'class',
			
			// messages/jots table fields
            'FIELD_MESSAGE' => 'message',
			'FIELD_MESSAGE_FK' => 'lot_id',
			'FIELD_MESSAGE_AUTHOR' => 'user_id',
            'FIELD_MESSAGE_ID' => 'id',
			'FIELD_MESSAGE_ADDED' => 'created',
			'FIELD_MESSAGE_NEW_FOR' => 'new_for',

			// lots types table fields
            'FIELD_TYPE_ID' => 'id',
			'FIELD_TYPE_NAME' => 'name',
			'FIELD_TYPE_LINKED' => 'show_link', // means use link in title
			
			// users info fields
            'FIELD_INFO_LOT_ID' => 'lot_id',
			'FIELD_INFO_USER_ID' => 'user_id',
			'FIELD_INFO_PARAMS' => 'params', // means use link in title
			
            // page URIs  			
            'URL_HOME' => BX_DOL_URL_ROOT . 'page/messenger',

            // some params
			'PARAM_FRIENDS_NUM_BY_DEFAULT' => 10,
			'PARAM_PUSH_NOTIFICATIONS_DEFAULT_SYMBOLS_NUM' => 190,
			'PARAM_PRIVATE' => TRUE,
			'PARAM_PUBLIC' => FALSE,
			'PARAM_ICONS_NUMBER' => 3,
			'PARAM_MODULE_TYPES' => array(
											'bx_groups' => 'groups',
											'bx_events' => 'events'
										),
            // objects
            'OBJECT_STORAGE' => '',
            'OBJECT_VIEWS' => 'bx_messenger_lots',
            'OBJECT_FORM_ENTRY' => 'bx_messenger_lots',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_messenger_lots',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_messenger_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_messenger_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => '', // main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_messenger_menu_manage_tools', //manage menu in content administration tools
        	'OBJECT_GRID' => 'bx_messenger',
            'OBJECT_UPLOADERS' => array(), 

			 //options
			 'MAX_SEND_SYMBOLS'	=> (int)getParam($aModule['db_prefix'] . 'max_symbols_number'),
			 'MAX_PREV_JOTS_SYMBOLS' => (int)getParam($aModule['db_prefix'] . 'max_symbols_brief_jot'),
			 'MAX_JOTS_BY_DEFAULT' => (int)getParam($aModule['db_prefix'] . 'max_jot_number_default'),
			 'MAX_JOTS_LOAD_HISTORY' => (int)getParam($aModule['db_prefix'] . 'max_jot_number_in_history'),
			 'IS_PUSH_ENABLED' => getParam($aModule['db_prefix'] . 'is_push_enabled') == 'on',
			 'PUSH_APP_ID' => getParam($aModule['db_prefix'] . 'push_app_id'),
			 'PUSH_REST_API' => getParam($aModule['db_prefix'] . 'push_rest_api'),
			 'PUSH_SAFARI_WEB_ID' => getParam($aModule['db_prefix'] . 'push_safari_id'),
			 'PUSH_SHORT_NAME' => getParam($aModule['db_prefix'] . 'push_short_name'),
			 'SERVER_URL' => getParam($aModule['db_prefix'] . 'server_url'),
			 'CONVERT_SMILES' => getParam($aModule['db_prefix'] . 'typing_smiles') == 'on'			 
        );
       
    }
	
	function getTalkType($sModule = ''){
		return $sModule && isset($this->CNF['PARAM_MODULE_TYPES'][$sModule]) ? $this->CNF['PARAM_MODULE_TYPES'][$sModule] : BX_IM_TYPE_PUBLIC; 
	}	
}

/** @} */
