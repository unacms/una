<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolFavorite
 */
class BxDolFavoriteQuery extends BxDolObjectQuery
{
    public function __construct(&$oModule)
    {
        parent::__construct($oModule);

        $this->_sMethodGetEntry = 'getFavorite';
    }

    public function isPerformed($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        return (int)$this->getOne($sQuery) > 0;
    }

	public function getPerformedBy($iObjectId)
    {
        $sQuery = $this->prepare("SELECT `author_id` FROM `{$this->_sTableTrack}` WHERE `object_id`=? ORDER BY `date` DESC", $iObjectId);
        return $this->getAll($sQuery);
    }

	public function getFavorite($iObjectId)
    {
        $sQuery = $this->prepare("SELECT `count` as `count` FROM {$this->_sTable} WHERE `object_id` = ? LIMIT 1", $iObjectId);

        $aResult = $this->getRow($sQuery);
        if(empty($aResult) || !is_array($aResult))
            $aResult = array('count' => 0);

        return $aResult;
    }

    public function doFavorite($iObjectId, $iAuthorId, $sAuthorIp)
    {
        $iAuthorNip = ip2long($sAuthorIp);

        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        if((int)$this->getOne($sQuery) > 0)
        	return true;

        $sQuery = $this->prepare("INSERT IGNORE INTO `{$this->_sTableTrack}` SET `object_id` = ?, `author_id` = ?, `author_nip` = ?, `date` = ?", $iObjectId, $iAuthorId, $iAuthorNip, time());
        if((int)$this->query($sQuery) == 0)
            return false;

        $sQuery = $this->prepare("SELECT `object_id` FROM `{$this->_sTable}` WHERE `object_id` = ? LIMIT 1", $iObjectId);
        if((int)$this->getOne($sQuery) == 0)
            $sQuery = $this->prepare("INSERT INTO `{$this->_sTable}` SET `object_id` = ?, `count` = '1'", $iObjectId);
        else
            $sQuery = $this->prepare("UPDATE `{$this->_sTable}` SET `count` = `count` + 1 WHERE `object_id` = ?", $iObjectId);

        return (int)$this->query($sQuery) > 0;
    }

	public function undoFavorite($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        if((int)$this->getOne($sQuery) == 0)
        	return true;

        $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
		if((int)$this->query($sQuery) == 0)
		    return false;

        $sQuery = $this->prepare("UPDATE `{$this->_sTable}` SET `count` = `count` - 1 WHERE `object_id` = ?", $iObjectId);
        return (int)$this->query($sQuery) > 0;
    }

    public function updateTriggerTable($iObjectId)
    {
        $sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldCount}` = `{$this->_sTriggerFieldCount}` + 1 WHERE `{$this->_sTriggerFieldId}` = ?", $iObjectId);
        return (int)$this->query($sQuery) > 0;
    }
}

/** @} */
