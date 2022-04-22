<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModNotificationsConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    protected $_aObjects;

    protected $_aHandlerDescriptor;
    protected $_sHandlersMethod;
    protected $_aHandlers;
    protected $_aHandlersHidden;

    protected $_aPerPage;
    protected $_aHtmlIds;
	
    protected $_sAnimationEffect;
    protected $_iAnimationSpeed;

    protected $_aPrivacyViewDefault;
    protected $_aCfDefault;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aPrefixes = array();
        $this->_aObjects = array(
            'alert' => $this->_sName,

            'privacy_view' => $this->_sName . '_privacy_view',

            'conn_subscriptions' => 'sys_profiles_subscriptions'
        );

        $this->_aHandlerDescriptor = array();
        $this->_sHandlersMethod = '';
        $this->_aHandlersHidden = array();
        $this->_aHandlers = array();

        $this->_aJsClasses = array();
        $this->_aJsObjects = array();

        $this->_aPerPage = array();
        $this->_aHtmlIds = array();

        $this->_sAnimationEffect = 'fade';
        $this->_iAnimationSpeed = 'slow';

        $this->_aPrivacyViewDefault = array(
            'object' => BX_DOL_PG_ALL,
            'event' => BX_DOL_PG_ALL
        );

        $iCfDefault = BxDolContentFilter::getInstance()->getDefaultValue();
        $this->_aCfDefault = array(
            'object' => $iCfDefault,
            'event' => $iCfDefault
        );
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

    public function getHandlerDescriptor()
    {
    	return $this->_aHandlerDescriptor;
    }

    public function getHandlersMethod()
    {
    	return $this->_sHandlersMethod;
    }

    public function getHandlersUnitTitle($sUnit)
    {
        $sKey = '_' . $sUnit;
        if(strcmp($sKey, _t($sKey)) === 0)
            $sKey = $this->getPrefix('language') . '_alert_module_' . $sUnit;

        return $sKey;
    }

    public function getHandlersActionTitle($sUnit, $sAction, $sType = '')
    {
        $sKeyMask = '_%s_alert_action_%s' . (!empty($sType) ? '_%s' : '');

        $sKey = sprintf($sKeyMask, $sUnit, $sAction, $sType);
        if(strcmp($sKey, _t($sKey)) === 0)
            $sKey = sprintf($sKeyMask, trim($this->getPrefix('language'), '_'), $sAction, $sType);

        return $sKey;
    }

    public function getHandler(&$aEvent)
    {
        $sHandler = $aEvent['type'] . '_' . $aEvent['action'];
        if(!$this->isHandler($sHandler))
            return false;

        $aHandler = $this->getHandlers($sHandler);
        if(empty($aHandler['module_name']) || empty($aHandler['module_class']) || empty($aHandler['module_method']))
            return false;

        return $aHandler;
    }

    public function isHandler($sKey = '')
    {
        return isset($this->_aHandlers[$sKey]);
    }

    public function getHandlers($sKey = '')
    {
        if($sKey == '')
            return $this->_aHandlers;

        return isset($this->_aHandlers[$sKey]) ? $this->_aHandlers[$sKey] : '';
    }

    public function getHandlersHidden()
    {
        return $this->_aHandlersHidden;
    }

    public function getPrivacyObject($sKey = '')
    {
    	if(!$this->isHandler($sKey))
    		return false;

    	$aHandler = $this->getHandlers($sKey);
    	if(empty($aHandler) || !is_array($aHandler) || empty($aHandler['privacy']))
    		return false;

		return BxDolPrivacy::getObjectInstance($aHandler['privacy']);
    }

	public function getPerPage($sType = 'default')
    {
    	if(empty($sType))
            return $this->_aPerPage;

        return isset($this->_aPerPage[$sType]) ? $this->_aPerPage[$sType] : '';
    }

    public function getHtmlIds($sType, $sKey = '')
    {
        if(empty($sKey))
            return isset($this->_aHtmlIds[$sType]) ? $this->_aHtmlIds[$sType] : array();

        return isset($this->_aHtmlIds[$sType][$sKey]) ? $this->_aHtmlIds[$sType][$sKey] : '';
    }

    public function getAnimationEffect()
    {
        return $this->_sAnimationEffect;
    }

    public function getAnimationSpeed()
    {
        return $this->_iAnimationSpeed;
    }

    public function getPrivacyViewDefault($sType)
    {
        return isset($this->_aPrivacyViewDefault[$sType]) ? (int)$this->_aPrivacyViewDefault[$sType] : (int)BX_DOL_PG_ALL;
    }

    public function getCfDefault($sType)
    {
        return isset($this->_aCfDefault[$sType]) ? (int)$this->_aCfDefault[$sType] : BxDolContentFilter::getInstance()->getDefaultValue();
    }
}

/** @} */
