<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolCacheFile');

class BxDolParams extends BxDol {
    protected $_oDb;
    protected $_oCache;
    protected $_sCacheFile;
    protected $_aParams;

    /**
     * constructor
     */
    function __construct($oDb) {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_oDb = $oDb;
        $this->_oCache = new BxDolCacheFile(); // feel free to change to another cache system if you are sure that it is available

        $this->_sCacheFile = 'sys_options_' . bx_site_hash('', true) . '.php';

        $this->_aParams = $this->_oCache->getData($this->_sCacheFile);
        if(empty($this->_aParams) && $this->_oDb != null)
            $this->cache();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone() {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance($oDb) {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolParams($oDb);

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function isInCache($sKey) {
        return isset($this->_aParams[$sKey]);
    }

    function exists($sKey, $bFromCache = true) {
    	if($bFromCache && $this->isInCache($sKey))
    	   return true;

        $sQuery = $this->_oDb->prepare("SELECT `name` FROM `sys_options` WHERE `name`=? LIMIT 1", $sKey);
        return $this->_oDb->getOne($sQuery) == $sKey;
    }

    function add($sName, $sValue, $iKateg, $sDesc, $sType) {
        //--- Update Database ---//
        $sQuery = $this->_oDb->prepare("INSERT INTO `sys_options` SET `category_id`=?, `name`=?, `caption`=?, `value`=?, `type`=?", $iKateg, $sName, $sDesc, $sValue, $sType);
        $this->_oDb->query($sQuery);

        //--- Update Cache ---//
        $this->cache();
    }

    function get($sKey, $bFromCache = true) {
        if (!$sKey)
            return false;
        if ($bFromCache && $this->isInCache($sKey)) {
            return $this->_aParams[$sKey];
        } else {
            $sQuery = $this->_oDb->prepare("SELECT `value` FROM `sys_options` WHERE `name`=? LIMIT 1", $sKey);
            return $this->_oDb->getOne($sQuery);
        }
    }

    function set($sKey, $mixedValue) {
        //--- Update Database ---//
        $sQuery = $this->_oDb->prepare("UPDATE `sys_options` SET `value`=? WHERE `name`=? LIMIT 1", $mixedValue, $sKey);
        $this->_oDb->query($sQuery);

        //--- Update Cache ---//
        $this->cache();
    }

    function cache() {
        $this->_aParams = $this->_oDb->getPairs("SELECT `name`, `value` FROM `sys_options`", "name", "value");
        if (empty($this->_aParams)) {
            $this->_aParams = array ();
            return false;
        }

        return $this->_oCache->setData($this->_sCacheFile, $this->_aParams);
    }

    function clearCache() {
        $this->_oCache->delData($this->_sCacheFile);
    }
}

/** @} */
