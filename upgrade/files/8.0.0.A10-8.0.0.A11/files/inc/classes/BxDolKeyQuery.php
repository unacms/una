<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Database queries for BxDolKey object.
 * @see BxDolKey
 */
class BxDolKeyQuery extends BxDolDb
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert ($sKey, $sData, $iExpire)
    {
        $sQuery = $this->prepare("INSERT INTO `sys_keys` SET `key` = ?, `data` = ?, `expire` = ?", $sKey, $sData, time() + $iExpire);
        return $this->query($sQuery);
    }

    public function remove ($sKey)
    {
        $sQuery = $this->prepare("DELETE FROM `sys_keys` WHERE `key` = ?", $sKey);
        return $this->query($sQuery);
    }

    public function get ($sKey)
    {
        $sQuery = $this->prepare("SELECT `key` FROM `sys_keys` WHERE `key` = ?", $sKey);
        return $this->getOne($sQuery);
    }

    public function getData ($sKey)
    {
        $sQuery = $this->prepare("SELECT `data` FROM `sys_keys` WHERE `key` = ?", $sKey);
        return $this->getOne($sQuery);
    }

    public function prune ()
    {
        $sQuery = $this->prepare("DELETE FROM `sys_keys` WHERE `expire` < ?", time());
        return $this->query($sQuery);
    }
}

/** @} */
