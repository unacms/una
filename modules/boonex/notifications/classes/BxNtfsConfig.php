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

        	'OBJECT_MENU_SUBMENU' => 'bx_notifications_submenu', // main module submenu
        );
        
		$this->_aPrefixes = array(
        	'style' => 'bx-ntfs',
        	'language' => '_bx_ntfs',
        	'option' => 'bx_notifications_'
        );

        $this->_aHandlerDescriptor = array('module_name' => '', 'module_method' => '', 'module_class' => '');
        $this->_sHandlersMethod = 'get_notifications_data';
        $this->_aHandlersHiddenEmail = array();
        $this->_aHandlersHiddenPush = array();

        $this->_aJsClasses = array(
            'main' => 'BxNtfsMain',
        	'view' => 'BxNtfsView',
        );
        $this->_aJsObjects = array(
        	'main' => 'oBxNtfsMain',
            'view' => 'oBxNtfsView',
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
