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
	
class BxDolMChat extends BxDolMData 
{
    private $_sMigField = 'c_mig_id';
    public function __construct(&$oMigrationModule, &$oDb)
	{
        parent::__construct($oMigrationModule, $oDb);
		$this -> _sModuleName = 'chat';
		$this -> _sTableWithTransKey = 'bx_messenger_jots';
    }
	
	public function getTotalRecords()
	{
		return $this -> _mDb -> getOne("SELECT COUNT(*) FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "`");
	}

	/**
	 *  Transfer Flash chat rooms
	 *  
	 *  @return array - created Lots ids associated with Dolphin chat rooms
	 *  
	 */
	private function transferRooms()
	{
		$aRooms = $this -> _mDb -> getAll("SELECT * FROM `RayChatRooms`");
		if (empty($aRooms))
			return false;
		$aResult = array();

		foreach($aRooms as $iKey => $aRoom)
		{
			$aUsers = $this -> _mDb -> getPairs("SELECT `Sender` FROM `RayChatHistory` WHERE `Room`=:Room GROUP BY `Sender`", 'Sender', 'Sender', array('Room' => $aRoom['ID']));
			if (empty($aUsers))
				continue;
			
			foreach($aUsers as $iKey => $iId)
				$aRealProfiles[] = $this -> getProfileId($iId);			
			
			$aParams = array('title' => $aRoom['Name'], 'parts' => implode(',', $aRealProfiles));			
			if (!($ilotId = $this -> _oDb -> getOne("SELECT `id` FROM `bx_messenger_lots` WHERE `title` = :title AND `participants` = :parts AND `type` = 2", $aParams)))
			{
				$this -> _oDb -> query("INSERT INTO `bx_messenger_lots` SET `created` = UNIX_TIMESTAMP(), `title` = :title, `participants` = :parts, `type` = 2", $aParams);
				$ilotId = $this -> _oDb -> lastId();
			}
			
			$aResult[$aRoom['ID']] = $ilotId;
		}
		
		
		return $aResult;
	}
	
	public function runMigration()
	{        
		$aLots = $this -> transferRooms();
		if (!$this -> getTotalRecords() || empty($aLots))
		{
			$this -> setResultStatus(_t('_bx_dolphin_migration_no_data_to_transfer'));
	        return BX_MIG_SUCCESSFUL;
		}	
		
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_simple_messanger'));		
	

		$this -> createMIdField($this -> _sMigField);
		$aMessages = $this -> _mDb -> getAll("SELECT * FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` ORDER BY `ID`");
		foreach($aMessages as $iMes => $aMessage)
		{
			$iMessageId = $this -> isItemExisted($aMessage['ID'], 'id', $this -> _sMigField);
			$iSenderId = $this -> getProfileId((int)$aMessage['Sender']);
			if (!$iMessageId && $iSenderId)
			{
				$sQuery = $this -> _oDb -> prepare( 
						"
							INSERT INTO
								`{$this -> _sTableWithTransKey}`
							SET
								`lot_id`   			= ?,
								`message`			= ?,	
								`user_id`			= ?,
								`created`			= ?
						",
						$aLots[$aMessage['Room']],
						$aMessage['Message'],
						$iSenderId,
						$aMessage['When']
					);
			
					$this -> _oDb -> query($sQuery);
					
					$iMessageId = $this -> _oDb -> lastId();
					if (!$iMessageId)
					{
						$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_simple_messanger_error', (int)$aMessage['ID']));
						return BX_MIG_FAILED;					
					}
					
					$this -> setMID($iMessageId, $aMessage['ID'], 'id', $this -> _sMigField);
				
				$this -> _iTransferred++;
			}	
		}

        // set as finished;
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_simple_messanger_finished', $this -> _iTransferred));
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
		
		$aRecords = $this -> _oDb -> getAll("SELECT * FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sMigField}` !=0");
		$aLots = $this -> _oDb -> getPairs("SELECT `lot_id` FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sMigField}` !=0 GROUP BY `lot_id`", 'lot_id', 'lot_id');
		
		$iNumber = 0;		
		if (!empty($aRecords))
		{
			foreach($aRecords as $iKey => $aValue)
			{
				$this -> _oDb -> query("DELETE FROM `{$this -> _sTableWithTransKey}` WHERE `id`=:id", array('id' => $aValue['id']));			
				$iNumber++;
			}
		}

		if (!empty($aLots))
		{
			$sIn = "WHERE `id` IN (" . implode(',', $aLots) . ")";
			$this -> _oDb -> query("DELETE FROM `bx_messenger_lots` {$sIn}");
		}
		
		parent::removeContent();
		return $iNumber;
	}
}

/** @} */
