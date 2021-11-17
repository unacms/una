<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolReport
 */
class BxDolReportQuery extends BxDolObjectQuery
{
    public function __construct(&$oModule)
    {
        parent::__construct($oModule);

        $this->_sMethodGetEntry = 'getReport';
    }

    public function getSqlParts($sMainTable, $sMainField)
    {
    	$aResult = parent::getSqlParts($sMainTable, $sMainField);
        if(empty($aResult))
            return $aResult;

		$aResult['fields'] = ", `{$this->_sTable}`.`count` as `report_count` ";
        return $aResult;
    }
    
    public function getDataById($iId)
    {
        $sQuery = $this->prepare("SELECT * FROM `{$this->_sTableTrack}` WHERE `id` = ?", $iId);
        return $this->getRow($sQuery);
    }

    public function getPerformedBy($iObjectId, $iStart = 0, $iPerPage = 0)
    {
        $sLimitClause = "";
        if(!empty($iPerPage))
            $sLimitClause = $this->prepareAsString(" LIMIT ?, ?", $iStart, $iPerPage);

        $sQuery = $this->prepare("SELECT `author_id`, `type`, `text`, `id`, `date` FROM `{$this->_sTableTrack}` WHERE `object_id`=? ORDER BY `date` DESC" . $sLimitClause, $iObjectId);
        return $this->getAll($sQuery);
    }

    public function getReport($iObjectId)
    {
        $sQuery = $this->prepare("SELECT `count` as `count` FROM {$this->_sTable} WHERE `object_id` = ? LIMIT 1", $iObjectId);

        $aResult = $this->getRow($sQuery);
        if(empty($aResult) || !is_array($aResult))
            $aResult = array('count' => 0);

        return $aResult;
    }
    
    public function getReportsCountByStatus($iStatus)
    {
        $sQuery = $this->prepare("SELECT COUNT(*) FROM {$this->_sTableTrack} WHERE `status` = ?", $iStatus);
        return $this->getOne($sQuery);
    }

    public function putReport($iObjectId, $iAuthorId, $bUndo = false)
    {
        $sQuery = $this->prepare("SELECT `object_id` FROM `{$this->_sTable}` WHERE `object_id` = ? LIMIT 1", $iObjectId);
        $bExists = (int)$this->getOne($sQuery) != 0;

        if(!$bExists && $bUndo)
            return false;

        if(!$bExists)
            $sQuery = $this->prepare("INSERT INTO `{$this->_sTable}` SET `object_id` = ?, `count` = '1'", $iObjectId);
        else
            $sQuery = $this->prepare("UPDATE `{$this->_sTable}` SET `count` = `count` " . ($bUndo ? "-" : "+") . " 1 WHERE `object_id` = ?", $iObjectId);

        if((int)$this->query($sQuery) == 0)
            return false;

        if($bUndo)
            return $this->_deleteTrack($iObjectId, $iAuthorId);

        return true;
    }
    
    public function clearReports($iObjectId)
    {
        $sQuery = $this->prepare("DELETE FROM `{$this->_sTable}` WHERE `object_id` = ?", $iObjectId);
        $this->query($sQuery);
        
        $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `object_id` = ?", $iObjectId);
        $this->query($sQuery);
        
        $sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldCount}` = 0 WHERE `{$this->_sTriggerFieldId}` = ?", $iObjectId);
        $this->query($sQuery);
    }

    protected function _deleteTrack($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `id` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        $iId = (int)$this->getOne($sQuery);

        $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `id` = ? LIMIT 1", $iId);
        if((int)$this->query($sQuery) > 0)
            return $iId;

        return false;
    }
    
    public function changeStatusReport($iId, $iStatus, $iProfileId)
    {
        if ($iStatus != BX_DOL_REPORT_STASUS_IN_PROCESS)
            $sQuery = $this->prepare("UPDATE `{$this->_sTableTrack}` SET `status` = ? WHERE `id` = ?", $iStatus, $iId);
        else
            $sQuery = $this->prepare("UPDATE `{$this->_sTableTrack}` SET `status` = ?, `checked_by` = ? WHERE `id` = ?", $iStatus, $iProfileId, $iId);
        $this->query($sQuery);
    }
    
}

/** @} */
