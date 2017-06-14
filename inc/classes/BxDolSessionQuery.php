<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolSession
 */
class BxDolSessionQuery extends BxDolDb
{
    protected $sTable;

    public function __construct()
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
    	$aSet['id'] = $sId;
        return (int)$this->query("REPLACE INTO `" . $this->sTable . "` SET " . $this->arrayToSQL($aSet) . ", `date`=UNIX_TIMESTAMP()") > 0;
    }
	function update($sId, $aSet = array())
    {
    	$sSet = !empty($aSet) ? $this->arrayToSQL($aSet) . ", " : "";
    	$sSet .= "`date`=UNIX_TIMESTAMP()";

        return (int)$this->query("UPDATE `" . $this->sTable . "` SET " . $sSet . " WHERE `id`=:id", array('id' => $sId)) > 0;
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
        if ($iRet)
            $this->query("OPTIMIZE TABLE `" . $this->sTable . "`");
        return $iRet;
    }
}

/** @} */
