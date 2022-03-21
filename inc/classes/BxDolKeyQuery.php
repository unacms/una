<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
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

    public function insert ($sKey, $sData, $iExpire, $sSalt = '')
    {
        $sQuery = $this->prepare("INSERT INTO `sys_keys` SET `key` = ?, `data` = ?, `expire` = ?, `salt` = ?", $sKey, $sData, time() + $iExpire, $sSalt);
        return $this->query($sQuery);
    }

    public function remove ($sKey)
    {
        $sQuery = $this->prepare("DELETE FROM `sys_keys` WHERE `key` = ?", $sKey);
        return $this->query($sQuery);
    }

    public function get ($sKey, $sSalt = '')
    {
        $sWhere = '';
        $aBind = ['key' => $sKey];
        if ($sSalt) {
            $sWhere .= " AND `salt` = :salt ";
            $aBind['salt'] = $sSalt;
        }
        return $this->getOne("SELECT `key` FROM `sys_keys` WHERE `key` = :key" . $sWhere, $aBind);
    }

    public function getData ($sKey, $sSalt = '')
    {
        $sWhere = '';
        $aBind = ['key' => $sKey];
        if ($sSalt) {
            $sWhere .= " AND `salt` = :salt ";
            $aBind['salt'] = $sSalt;
        }
        return $this->getOne("SELECT `data` FROM `sys_keys` WHERE `key` = :key" . $sWhere, $aBind);
    }

    public function prune ()
    {
        $sQuery = $this->prepare("DELETE FROM `sys_keys` WHERE `expire` < ?", time());
        return $this->query($sQuery);
    }
}

/** @} */
