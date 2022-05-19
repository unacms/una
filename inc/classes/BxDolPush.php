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
    
    /**
     * Get tags to send to PUSH server
     * @param $iProfileId - profile ID
     * @return array of tags
     */
    public static function getTags($iProfileId = false)
    {
        if (false === $iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $oProfile = BxDolProfile::getInstance($iProfileId);
        $oAccount = $oProfile ? $oProfile->getAccountObject() : null;
        if (!$oProfile || !$oAccount)
            return false;

        $sEmail = $oAccount->getEmail();
        $a = array (
            'user' => $iProfileId,
            'user_hash' => encryptUserId($iProfileId),
            'real_name' => $oProfile->getDisplayName(),
            'email' => $sEmail,
            'email_hash' => $sEmail ? hash_hmac('sha256', $sEmail, getParam('sys_push_app_id')) : '',
        );

        bx_alert('system', 'push_tags', $iProfileId, $iProfileId, array('tags' => &$a));

        return $a;
    }

    /**
     * @param $a - array to fill with notification counter per module
     * @return total number of notifications
     */
    public static function getNotificationsCount($iProfileId = 0, &$aBubbles = null)
    {    
        $iMemberIdCookie = null;
        $bLoggedMemberGlobals = null;
        if ($iProfileId && $iProfileId != bx_get_logged_profile_id()) {            
            if (!empty($_COOKIE['memberID']))
                $iMemberIdCookie = $_COOKIE['memberID'];
            if (!empty($GLOBALS['logged']['member']))
                $bLoggedMemberGlobals = $GLOBALS['logged']['member'];
            $oProfile = BxDolProfile::getInstance($iProfileId);
            $_COOKIE['memberID'] = $oProfile ? $oProfile->getAccountId() : 0;
            $GLOBALS['logged']['member'] = $oProfile ? true : false;
        }
    
        $aMenusObjects = array('sys_account_notifications', 'sys_toolbar_member');
        foreach ($aMenusObjects as $sMenuObject) {
            if ($iProfileId && $iProfileId != bx_get_logged_profile_id())
                unset($GLOBALS['bxDolClasses']['BxDolMenu!sys_account_notifications']);
            $oMenu = BxDolMenu::getObjectInstance($sMenuObject);
            if ($iProfileId && $iProfileId != bx_get_logged_profile_id())
                unset($GLOBALS['bxDolClasses']['BxDolMenu!sys_account_notifications']);

            $bSave = $oMenu->setDisplayAddons(true);
            $a = $oMenu->getMenuItems();
            $iBubbles = 0;
            foreach ($a as $r) {
                if (!$r['bx_if:addon']['condition'])
                    continue;
                if (null !== $aBubbles)
                    $aBubbles[$r['name']] = $r['bx_if:addon']['content']['addon'];
                $iBubbles += $r['bx_if:addon']['content']['addon'];
            }
        }

        if ($iProfileId && $iProfileId != bx_get_logged_profile_id()) {
            if (null === $iMemberIdCookie)
                unset($_COOKIE['memberID']);
            else
                $_COOKIE['memberID'] = $iMemberIdCookie;

            if (null === $bLoggedMemberGlobals)
                unset($GLOBALS['logged']['member']);
            else
                $GLOBALS['logged']['member'] = $bLoggedMemberGlobals;
        }

        return $iBubbles;
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
		        array('field' => 'tag', 'key' => 'user_hash', 'relation' => '=', 'value' => encryptUserId($iProfileId))
            ),
			'contents' => !empty($aMessage['contents']) && is_array($aMessage['contents']) ? $aMessage['contents'] : array(),
			'headings' => !empty($aMessage['headings']) && is_array($aMessage['headings']) ? $aMessage['headings'] : array(),
            'web_url' => !empty($aMessage['url']) ? $aMessage['url'] : '',
            'data' => array('url' => !empty($aMessage['url']) ? $aMessage['url'] : ''),
            'chrome_web_icon' => !empty($aMessage['icon']) ? $aMessage['icon'] : '',
		);

        if ('on' == getParam('bx_nexus_option_push_notifications_count')) {
            $iBadgeCount = $this->getNotificationsCount($iProfileId);
            $aFields['ios_badgeType'] = 'SetTo';
            $aFields['ios_badgeCount'] = $iBadgeCount;
        }

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
        
        $oResult = @json_decode($sResult, true);
        if(isset($oResult['errors'])){
            foreach($oResult['errors'] as $sError) {  
                bx_log('sys_push', $sError . " Message:" . json_encode($aMessage));
            }
        }

		return $sResult;
    }
}

/** @} */
