<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolPush extends BxDolFactory implements iBxDolSingleton
{
    protected $_sAppId;
    protected $_sRestApi;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_sAppId = getParam('sys_push_app_id');
        $this->_sRestApi = getParam('sys_push_rest_api');
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolPush();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
    
    public function send($iProfileId, $aMessage, $bAddToQueue = false)
    {
        if(empty($this->_sAppId) || empty($this->_sRestApi))
            return false;

        if($bAddToQueue && BxDolQueuePush::getInstance()->add($iProfileId, $aMessage))
            return true;

		$aFields = array(
			'app_id' => $this->_sAppId,
			'filters' => array(
		        array('field' => 'tag', 'key' => 'user', 'relation' => '=', 'value' => $iProfileId)
            ),
			'contents' => !empty($aMessage['contents']) && is_array($aMessage['contents']) ? $aMessage['contents'] : array(),
			'headings' => !empty($aMessage['headings']) && is_array($aMessage['headings']) ? $aMessage['headings'] : array(),
            'web_url' => !empty($aMessage['url']) ? $aMessage['url'] : '',
            'data' => array('url' => !empty($aMessage['url']) ? $aMessage['url'] : ''),
            'chrome_web_icon' => !empty($aMessage['icon']) ? $aMessage['icon'] : '',
            'ios_badgeType' => 'Increase',
            'ios_badgeCount' => 1,
		);

		$oChannel = curl_init();
		curl_setopt($oChannel, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($oChannel, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8',
			'Authorization: Basic ' . $this->_sRestApi
		));
		curl_setopt($oChannel, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($oChannel, CURLOPT_HEADER, false);
		curl_setopt($oChannel, CURLOPT_POST, true);
		curl_setopt($oChannel, CURLOPT_POSTFIELDS, json_encode($aFields));
		curl_setopt($oChannel, CURLOPT_SSL_VERIFYPEER, false);

		$sResult = curl_exec($oChannel);
		curl_close($oChannel);

		return $sResult;
    }
}

/** @} */
