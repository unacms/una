<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
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
        return array('count' => $this->getObjectCount($iObjectId));
    }

    public function doFavorite($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        if((int)$this->getOne($sQuery) > 0)
        	return true;

        $sQuery = $this->prepare("INSERT IGNORE INTO `{$this->_sTableTrack}` SET `object_id` = ?, `author_id` = ?, `date` = ?", $iObjectId, $iAuthorId, time());
        return (int)$this->query($sQuery) > 0;
    }

	public function undoFavorite($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        if((int)$this->getOne($sQuery) == 0)
        	return true;

        $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        return (int)$this->query($sQuery) > 0;
    }
}

/** @} */
