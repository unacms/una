<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralConfig');

class BxBaseModNotificationsConfig extends BxBaseModGeneralConfig
{
	protected $_oDb;

    protected $_aPrefixes;
    protected $_aObjects;

	protected $_aHandlerDescriptor;
    protected $_sHandlersMethod;
    protected $_aHandlers;
    protected $_aHandlersHidden;

    protected $_aJsClass;
    protected $_aJsObjects;

    protected $_aPerPage;
    protected $_aHtmlIds;
	
    protected $_sAnimationEffect;
    protected $_iAnimationSpeed;

    protected $_iPrivacyViewDefault;

    function __construct($aModule)
    {
        parent::__construct($aModule);

		$this->_aPrefixes = array();
		$this->_aObjects = array(
			'alert' => $this->_sName,
			'conn_subscriptions' => 'sys_profiles_subscriptions'
		);

        $this->_aHandlerDescriptor = array();
        $this->_sHandlersMethod = '';
        $this->_aHandlersHidden = array();
        $this->_aHandlers = array();

		$this->_aJsClass = array();
		$this->_aJsObjects = array();

		$this->_aPerPage = array();
		$this->_aHtmlIds = array();

		$this->_sAnimationEffect = 'fade';
        $this->_iAnimationSpeed = 'slow';

        $this->_iPrivacyViewDefault = BX_DOL_PG_ALL;
    }

	public function init(&$oDb)
    {
        $this->_oDb = &$oDb;
        $sOptionPrefix = $this->getPrefix('option');

        $aHandlers = $this->_oDb->getHandlers();
        foreach($aHandlers as $aHandler) {
            if($aHandler['type'] === BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT && !empty($aHandler['content'])) {
                $aContent = unserialize($aHandler['content']);
                if(is_array($aContent) && !empty($aContent))
                    $aHandler = array_merge($aHandler, $aContent);
            }

           $this->_aHandlers[$aHandler['alert_unit'] . (!empty($aHandler['alert_action']) ? '_' . $aHandler['alert_action'] :'')] = $aHandler;
        }

        $sHideTimeline = getParam($sOptionPrefix . 'events_hide');
        if(!empty($sHideTimeline))
            $this->_aHandlersHidden = explode(',', $sHideTimeline);
    }

    public function getPrefix($sType = '')
    {
    	if(empty($sType))
            return $this->_aPrefixes;

        return $this->_aPrefixes[$sType];
    }

    public function getObject($sType = '')
    {
    	if(empty($sType))
            return $this->_aObjects;

        return $this->_aObjects[$sType];
    }

	public function getHandlerDescriptor()
    {
    	return $this->_aHandlerDescriptor;
    }
    public function getHandlersMethod()
    {
    	return $this->_sHandlersMethod;
    }

    public function isHandler($sKey = '')
    {
        return isset($this->_aHandlers[$sKey]);
    }

    public function getHandlers($sKey = '')
    {
        if($sKey == '')
            return $this->_aHandlers;

        return $this->_aHandlers[$sKey];
    }

    public function getHandlersHidden()
    {
        return $this->_aHandlersHidden;
    }

	public function getJsClass($sType)
    {
        return $this->_aJsClass[$sType];
    }

    public function getJsObject($sType)
    {
        return $this->_aJsObjects[$sType];
    }

	public function getPerPage($sType = 'default')
    {
    	if(empty($sType))
            return $this->_aPerPage;

        return $this->_aPerPage[$sType];
    }

    public function getHtmlIds($sType, $sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds[$sType];

        return $this->_aHtmlIds[$sType][$sKey];
    }

    public function getAnimationEffect()
    {
        return $this->_sAnimationEffect;
    }

    public function getAnimationSpeed()
    {
        return $this->_iAnimationSpeed;
    }

    public function getPrivacyViewDefault()
    {
        return $this->_iPrivacyViewDefault;
    }
}

/** @} */
