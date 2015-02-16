<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects
 * @section key Keys
 * @ref BxDolKey
 */

/**
 * Key objects - automatically generate hashed keys.
 *
 * @section example Example of usage
 *
 * Generate new hashed key:
 *
 * @code
 *  $oKeys = BxDolKey::getInstance(); // get object instance
 *  if ($oKeys) // check if object is available for using
 *      echo $oKeys->getNewKey (false, 3600); // get new hashed key, which will be automatically deleted after 1 hour
 * @endcode
 *
 * Check if hashed key exists:
 *
 * @code
 *  $oKeys = BxDolKey::getInstance(); // get object instance
 *  if ($oKeys && $oKeys->isKeyExists ($sKey)) // check key exists
 *      echo 'key exists';
 *  else
 *      echo 'key is invalid';
 * @endcode
 */
class BxDolKey extends BxDol implements iBxDolSingleton
{
    protected $_oQuery;

    /**
     * Constructor
     */
    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_oQuery = new BxDolKeyQuery();
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
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolKey();
        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Get new key.
     * @param $aData - some data to associate with the key
     * @param $iExpire - number of seconds to generated key after, by default - 1 week
     * @return newly generated key string
     */
    public function getNewKey ($aData = false, $iExpire = 604800)
    {
        $sKey = md5(time() . rand() . BX_DOL_SECRET);
        if ($this->_oQuery->insert($sKey, $aData ? serialize($aData) : '', (int)$iExpire));
            return $sKey;
        return false;
    }

    /**
     * Check if provided key exists.
     * @param $sKey - key string
     * @return true if key exists or false if key is missing
     */
    public function isKeyExists ($sKey)
    {
        return $this->_oQuery->get($sKey) ? true : false;
    }

    /**
     * Get key data.
     * @param $sKey - key string
     * @return true if key exists or false if key is missing
     */
    public function getKeyData ($sKey)
    {
        $sData = $this->_oQuery->getData($sKey);
        if ($sData)
            return unserialize($sData);
        return '';
    }

    /**
     * Delete provided key.
     * @param $sKey - key string
     * @return true if key was successfully found and delete if false otherwise
     */
    public function removeKey ($sKey)
    {
        return $this->_oQuery->remove($sKey);
    }

    /**
     * Delete expired keys.
     */
    public function prune ()
    {
        return $this->_oQuery->prune();
    }

}

/** @} */
