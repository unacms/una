<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DolphinMigration  Dolphin Migration
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxDolMData.php');
bx_import('BxDolStorage');
	
class BxDolMShoutBox extends BxDolMData
{	
	
	/**
	 *  @var shout box Lot id in Jot Messenger
	 */
	private $_iLotID = 2;
    private $_sMigField = 'shoutbx_id';
	public function __construct(&$oMigrationModule, &$oDb)
	{
        parent::__construct($oMigrationModule, $oDb);
		$this -> _sModuleName = 'shoutbox';
		$this -> _sTableWithTransKey = 'bx_messenger_jots';
    }
	
	public function getTotalRecords()
	{
		return $this -> _mDb -> getOne("SELECT COUNT(*) FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` WHERE `HandlerID` = 0 AND `OwnerID` != 0 ");
	}
		
	public function runMigration() 
	{        
		if (!$this -> getTotalRecords())
		{
			  $this -> setResultStatus(_t('_bx_dolphin_migration_no_data_to_transfer'));
	          return BX_MIG_SUCCESSFUL;
		}	
		
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_shoutbox'));
		
		$this -> createMIdField($this -> _sMigField);
		
		$aMessages = $this -> _mDb -> getAll("SELECT * FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` WHERE `HandlerID` = 0 AND `OwnerID` != 0 ORDER BY `ID`");
		$aParticipantsList = array();
		foreach($aMessages as $iMes => $aMessage)
		{
			$iMessageId = $this -> isItemExisted($aMessage['ID'], 'id', $this -> _sMigField);
			$iProfileId = $this -> getProfileId((int)$aMessage['OwnerID']);
			if (!$iProfileId)
				continue;
			
			if (!$iMessageId && $iProfileId)
			{
				$sQuery = $this -> _oDb -> prepare( 
						"
							INSERT INTO
								`{$this -> _sTableWithTransKey}`
							SET
								`lot_id`   			= {$this -> _iLotID},
								`message`			= ?,	
								`user_id`			= ?,
								`created`			= ?
						",
						$aMessage['Message'],
						$iProfileId,
						strtotime($aMessage['Date'])
					);
			
					$this -> _oDb -> query($sQuery);
					
					$iMessageId = $this -> _oDb -> lastId();
					if (!$iMessageId)
					{
						$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_shoutbox_error', (int)$aMessage['ID']));
						return BX_MIG_FAILED;					
					}					
				
				if (!in_array($iProfileId, $aParticipantsList))
					array_push($aParticipantsList, $iProfileId);
				
				$this -> setMID($iMessageId, $aMessage['ID'], 'id', $this -> _sMigField);
			}	
		
			$this -> _iTransferred++;
		}
		
		if (!empty($aParticipantsList))
			$this -> _oDb ->  query("UPDATE `bx_messenger_lots` 
									SET 
										`participants` = :parts										
								WHERE `id` = :id", array('parts' => implode(',', $aParticipantsList), 'id' => $this -> _iLotID));
        

		// set as finished;
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_shoutbox_finished', $this -> _iTransferred));
        return BX_MIG_SUCCESSFUL;
    }

    public function dropMID($sIdentFieldName = '', $sTableName = '')
    {
        return parent::dropMID($this -> _sMigField);
    }
    
	public function removeContent()
	{
		if (!$this -> _oDb -> isTableExists($this -> _sTableWithTransKey) || !$this -> _oDb -> isFieldExists($this -> _sTableWithTransKey, $this -> _sMigField))
			return false;
		
		$aRecords = $this -> _oDb -> getAll("SELECT * FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sMigField}` !=0 ");
		$iNumber = 0;
		if (!empty($aRecords))
		{
			foreach($aRecords as $iKey => $aValue)
			{
				$this -> _oDb -> query("DELETE FROM `{$this -> _sTableWithTransKey}` WHERE `id`=:id", array('id' => $aValue['id']));
				$iNumber++;
			}
		}

		parent::removeContent();		
		return $iNumber;
	}
	
}

/** @} */
