<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_DOL_SESSION_LIFETIME', 3600);
define('BX_DOL_SESSION_COOKIE', 'memberSession');

class BxDolSession extends BxDolFactory implements iBxDolSingleton
{
    protected $oDb;
    protected $sId;
    protected $iUserId;
    protected $aData;

    protected $bAutoLogout;	//--- Auto logout with broken session

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->oDb = new BxDolSessionQuery();
        $this->sId = '';
        $this->iUserId = 0;
        $this->aData = array();

        $this->bAutoLogout = false; // when changing to 'true', it's better to increase BX_DOL_SESSION_LIFETIME to 1 month or so
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
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolSession();

        if(!$GLOBALS['bxDolClasses'][__CLASS__]->getId())
            $GLOBALS['bxDolClasses'][__CLASS__]->start();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function start()
    {
        if (defined('BX_DOL_CRON_EXECUTE'))
            return true;

        if ($this->exists($this->sId)) {
            if ($this->iUserId == getLoggedId())
                return true;
            $this->destroy(false);
        }

		/**
		 * Force logout a logged in user if his session wasn't found and required to be automatically recreated.
		 * It's needed to avoid problems when different users are logged in at the same time under one account. 
		 */
		if($this->bAutoLogout && isLogged())
			bx_logout();

        $this->sId = genRndPwd(32, true);

        $aUrl = parse_url(BX_DOL_URL_ROOT);
        $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';
        setcookie(BX_DOL_SESSION_COOKIE, $this->sId, time() + BX_DOL_SESSION_LIFETIME, $sPath, '', false, true);

        $this->save();
        return true;
    }

    function destroy($bDeleteCookies = true)
    {
        if ($bDeleteCookies) {
            $aUrl = parse_url(BX_DOL_URL_ROOT);
            $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';
            setcookie(BX_DOL_SESSION_COOKIE, '', time() - 86400, $sPath, '', false, true);
            unset($_COOKIE[BX_DOL_SESSION_COOKIE]);
        }

        $this->oDb->delete($this->sId);

        $this->sId = '';
        $this->iUserId = 0;
        $this->aData = array();
    }

    function exists($sId = '')
    {
        if(empty($sId) && isset($_COOKIE[BX_DOL_SESSION_COOKIE]))
            $sId = bx_process_input($_COOKIE[BX_DOL_SESSION_COOKIE]);

        $mixedSession = $this->oDb->exists($sId);
        if($mixedSession === false) 
        	return false;

		$this->sId = $mixedSession['id'];
		$this->iUserId = (int)$mixedSession['user_id'];
		$this->aData = unserialize($mixedSession['data']);

		$this->oDb->update($this->sId);		//--- update session's time
		return true;
    }

    function getId()
    {
        return $this->sId;
    }

    function setUserId($iUserId)
    {
        $this->iUserId = $iUserId;
        $this->save();
    }

    function setValue($sKey, $mixedValue)
    {
        if(empty($this->sId))
            $this->start();

        $this->aData[$sKey] = $mixedValue;
        $this->save();
    }

    function unsetValue($sKey)
    {
        if(empty($this->sId))
            $this->start();

        unset($this->aData[$sKey]);

        if(!empty($this->aData))
            $this->save();
        else
            $this->destroy();
    }

	function isValue($sKey)
    {
        if(empty($this->sId))
            $this->start();

        return isset($this->aData[$sKey]);
    }

    function getValue($sKey)
    {
        if(empty($this->sId))
            $this->start();

        return isset($this->aData[$sKey]) ? $this->aData[$sKey] : false;
    }

    function getUnsetValue($sKey)
    {
        $mixedValue = $this->getValue($sKey);
        if($mixedValue !== false)
            $this->unsetValue($sKey);

        return $mixedValue;
    }

    public function maintenance()
    {
        return $this->oDb->deleteExpired();
    }

    protected function save()
    {
        if($this->iUserId == 0)
            $this->iUserId = getLoggedId();

        $this->oDb->save($this->sId, array(
            'user_id' => $this->iUserId,
            'data' => serialize($this->aData)
        ));
    }
}

/** @} */
