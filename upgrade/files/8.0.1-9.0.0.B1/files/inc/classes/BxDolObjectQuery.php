<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolObject
 */
class BxDolObjectQuery extends BxDolDb
{
    protected $_oModule;

    protected $_sTable;
    protected $_sTableTrack;
    protected $_sTriggerTable;
    protected $_sTriggerFieldId;
    protected $_sTriggerFieldAuthor;
    protected $_sTriggerFieldCount;

    protected $_sMethodGetEntry;

    public function __construct(&$oModule)
    {
        parent::__construct();

        $this->_oModule = $oModule;

        $aSystem = $this->_oModule->getSystemInfo();
        $this->_sTable = isset($aSystem['table_main']) ? $aSystem['table_main'] : '';
        $this->_sTableTrack = isset($aSystem['table_track']) ? $aSystem['table_track'] : '';
        $this->_sTriggerTable = isset($aSystem['trigger_table']) ? $aSystem['trigger_table'] : '';
        $this->_sTriggerFieldId = isset($aSystem['trigger_field_id']) ? $aSystem['trigger_field_id'] : '';
        $this->_sTriggerFieldAuthor = isset($aSystem['trigger_field_author']) ? $aSystem['trigger_field_author'] : '';
        $this->_sTriggerFieldCount = isset($aSystem['trigger_field_count']) ? $aSystem['trigger_field_count'] : '';
    }

    public function getSqlParts($sMainTable, $sMainField)
    {
        if(empty($sMainTable) || empty($sMainField))
            return array();

        return array (
            'fields' => ", `{$this->_sTable}`.`count` as `count` ",
            'join' => " LEFT JOIN `{$this->_sTable}` ON (`{$this->_sTable}`.`object_id` = `{$sMainTable}`.`{$sMainField}`) ",
        );
    }

	public function isPerformed($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `object_id` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        return (int)$this->getOne($sQuery) != 0;
    }

    public function getPerformedBy($iObjectId)
    {
        $sQuery = $this->prepare("SELECT `author_id` FROM `{$this->_sTableTrack}` WHERE `object_id`=?", $iObjectId);
        return $this->getColumn($sQuery);
    }

    public function deleteObjectEntries($iObjectId)
    {
    	if(!empty($this->_sTable)) {
	        $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `object_id` = ?", $iObjectId);
	        if($this->query($sQuery))
	        	$this->query("OPTIMIZE TABLE {$this->_sTable}");
    	}

    	if(!empty($this->_sTableTrack)) {
	        $sQuery = $this->prepare("DELETE FROM {$this->_sTableTrack} WHERE `object_id` = ?", $iObjectId);
	        if($this->query($sQuery))
	        	$this->query ("OPTIMIZE TABLE {$this->_sTableTrack}");
    	}
    }

	public function getObjectAuthorId($iId)
    {
        $sQuery = $this->prepare("SELECT `{$this->_sTriggerFieldAuthor}` FROM `{$this->_sTriggerTable}` WHERE `{$this->_sTriggerFieldId}` = ? LIMIT 1", $iId);
        return $this->getOne($sQuery);
    }

    public function updateTriggerTable($iObjectId)
    {
    	if(empty($this->_sMethodGetEntry))
    		return false;

        $aEntry = $this->{$this->_sMethodGetEntry}($iObjectId);
        if(empty($aEntry) || !is_array($aEntry))
            return false;

        $sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldCount}` = ? WHERE `{$this->_sTriggerFieldId}` = ?", $aEntry['count'], $iObjectId);
        return (int)$this->query($sQuery) > 0;
    }
}

/** @} */
