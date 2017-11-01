<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolPrivacy');

class BxNtfsConfig extends BxBaseModNotificationsConfig
{
    protected $_iPushMaxLen;

    protected $_aHandlersHiddenEmail;
    protected $_aHandlersHiddenPush;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
        	'URL_HOME' => 'page.php?i=notifications-view',

        	'PARAM_PUSH_APP_ID' => 'bx_notifications_push_app_id',
        	'PARAM_PUSH_REST_API' => 'bx_notifications_push_rest_api',
			'PARAM_PUSH_SAFARI_WEB_ID' => 'bx_notifications_push_safari_id',
			'PARAM_PUSH_SHORT_NAME' => 'bx_notifications_push_short_name',

        	'OBJECT_MENU_SUBMENU' => 'bx_notifications_submenu', // main module submenu
        );
        
		$this->_aPrefixes = array(
        	'style' => 'bx-ntfs',
        	'language' => '_bx_ntfs',
        	'option' => 'bx_notifications_'
        );

        $this->_iPushMaxLen = 190;

        $this->_aHandlerDescriptor = array('module_name' => '', 'module_method' => '', 'module_class' => '');
        $this->_sHandlersMethod = 'get_notifications_data';
        $this->_aHandlersHiddenEmail = array();
        $this->_aHandlersHiddenPush = array();

        $this->_aJsClasses = array(
            'main' => 'BxNtfsMain',
        	'view' => 'BxNtfsView',
        	'push' => 'BxNtfsPush',
        );
        $this->_aJsObjects = array(
        	'main' => 'oBxNtfsMain',
            'view' => 'oBxNtfsView',
        	'push' => 'oBxNtfsPush',
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
        	'view' => array(
        		'block' => $sHtmlPrefix,
        		'events' => $sHtmlPrefix . '-events',
        		'event' => $sHtmlPrefix . '-event-'
        	)
        );
    }

    public function init(&$oDb)
    {
    	parent::init($oDb);

    	$sOptionPrefix = $this->getPrefix('option');
    	$this->_aPerPage = array(
    		'default' => (int)getParam($sOptionPrefix . 'events_per_page')
    	);

    	$aSettings = array(
    	    'site' => '',
    	    'email' => 'Email',
    	    'push' => 'Push'
    	);
    	foreach($aSettings as $sSetting => $sVariable) {
    	    $sHideTimeline = getParam($sOptionPrefix . 'events_hide_' . $sSetting);
            if(!empty($sHideTimeline))
                $this->{'_aHandlersHidden' . $sVariable} = explode(',', $sHideTimeline);
    	}
    }

    public function getPushMaxLen()
    {
        return $this->_iPushMaxLen;
    }

    public function getHandlersHidden($sType = '')
    {
        if(!in_array($sType, array('', 'email', 'push')))
            return array();

        return $this->{'_aHandlersHidden' . ucfirst($sType)};
    }

    /**
     * Ancillary functions
     */
    public function getViewUrl()
    {
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=notifications-view');
    }
}

/** @} */
