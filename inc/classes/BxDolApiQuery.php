<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for API interface
 * @see BxDolApiQuery
 */
class BxDolApiQuery extends BxDolDb implements iBxDolSingleton
{
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
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
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolApiQuery();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function getOrigin ($sUrl)
    {
        return $this->getOne("SELECT `url` FROM `sys_api_origins` WHERE `url` = ?", $sUrl);
    }

    public function getKey ($sKey)
    {
        return $this->getOne("SELECT `key` FROM `sys_api_keys` WHERE `key` = ?", $sKey);
    }
}

/** @} */
