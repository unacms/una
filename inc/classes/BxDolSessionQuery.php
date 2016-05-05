<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolSession
 */
class BxDolSessionQuery extends BxDolDb
{
    protected $sTable;

    function __construct()
    {
        parent::__construct();

        $this->sTable = 'sys_sessions';
    }
    function getTableName()
    {
        return $this->sTable;
    }
    function exists($sId)
    {
        $sSql = $this->prepare("SELECT `id`, `user_id`, `data` FROM `" . $this->sTable . "` WHERE `id`=? LIMIT 1", $sId);
        $aSession = $this->getRow($sSql);
        return !empty($aSession) ? $aSession : false;
    }
    function save($sId, $aSet)
    {
        $sSetClause = "`id`=:id";
        foreach($aSet as $sKey => $sValue)
            $sSetClause .= ", `" . $sKey . "`=:" . $sKey;
        $sSetClause .= ", `date`=UNIX_TIMESTAMP()";

        $sSql = $this->prepare("REPLACE INTO `" . $this->sTable . "` SET " . $sSetClause);
        return (int)$this->query($sSql, array_merge(array('id' => $sId), $aSet)) > 0;
    }
    function delete($sId)
    {
        $sSql = $this->prepare("DELETE FROM `" . $this->sTable . "` WHERE `id`=? LIMIT 1", $sId);
        return (int)$this->query($sSql) > 0;
    }
    function deleteExpired()
    {
        $sSql = $this->prepare("DELETE FROM `" . $this->sTable . "` WHERE `date` < (UNIX_TIMESTAMP() - ?)", BX_DOL_SESSION_LIFETIME);
        $iRet = (int)$this->query($sSql);
        $this->query("OPTIMIZE TABLE `" . $this->sTable . "`");
        return $iRet;
    }
}

/** @} */
