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
	
class BxDolMSimpleMessenger extends BxDolMData
{
	public function __construct(&$oMigrationModule, &$oDb)
	{
        parent::__construct($oMigrationModule, $oDb);
		$this -> _sModuleName = 'simple_messenger';
		$this -> _sTableWithTransKey = 'bx_messenger_jots';
    }    
	
	public function getTotalRecords()
	{
		return $this -> _mDb -> getOne("SELECT COUNT(*) FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "`");
	}
	
	private function getLotId($iSenderId, $iRecipientId)
	{
		$iLot = $this -> _oDb -> getOne("SELECT `id` FROM `bx_messenger_lots` WHERE (`participants` = :l1 OR `participants` = :l2) AND `type`=2 AND `author` != 0 LIMIT 1", array('l1' => "{$iSenderId},{$iRecipientId}", 'l2' => "{$iRecipientId},{$iSenderId}"));
		if ((int)$iLot)
			return $iLot;
		
		$this -> _oDb -> query("INSERT INTO `bx_messenger_lots` SET `created` = UNIX_TIMESTAMP(), `author`= :author, `participants` = :parts, `type` = 2", array('parts' => "{$iSenderId},{$iRecipientId}", 'author' => $iSenderId));
		return $this -> _oDb -> lastId();
	}
	
	public function runMigration()
	{        
		if (!$this -> getTotalRecords())
		{
			  $this -> setResultStatus(_t('_bx_dolphin_migration_no_data_to_transfer'));
	          return BX_MIG_SUCCESSFUL;
		}	
		
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_simple_messanger'));		
			
		$this -> createMIdField();
		$aMessages = $this -> _mDb -> getAll("SELECT * FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` ORDER BY `ID`");
		foreach($aMessages as $iMes => $aMessage)
		{
			$iMessageId = $this -> isItemExisted($aMessage['ID']);
			$iSenderId = $this -> getProfileId((int)$aMessage['SenderID']);
			$iRecipientId = $this -> getProfileId((int)$aMessage['RecipientID']);
			if (!$iMessageId && $iSenderId && $iRecipientId)
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
						$this -> getLotId($iSenderId, $iRecipientId),
						$aMessage['Message'],
						$iSenderId,
						strtotime($aMessage['Date'])
					);
			
					$this -> _oDb -> query($sQuery);
					
					$iMessageId = $this -> _oDb -> lastId();
					if (!$iMessageId)
					{
						$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_simple_messanger_error', (int)$aMessage['ID']));
						return BX_MIG_FAILED;					
					}
					
					$this -> setMID($iMessageId, $aMessage['ID']);
			}
			
			$this -> _iTransferred++;
		}

        // set as finished;
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_simple_messanger_finished', $this -> _iTransferred));
        return BX_MIG_SUCCESSFUL;
    }
	
	public function removeContent()
	{
		if (!$this -> _oDb -> isTableExists($this -> _sTableWithTransKey) || !$this -> _oDb -> isFieldExists($this -> _sTableWithTransKey, $this -> _sTransferFieldIdent))
			return false;
		
		$aRecords = $this -> _oDb -> getAll("SELECT * FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sTransferFieldIdent}` !=0");
		$aLots = $this -> _oDb -> getPairs("SELECT `lot_id` FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sTransferFieldIdent}` !=0 GROUP BY `lot_id`", 'lot_id', 'lot_id');
		parent::removeContent();
		
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
		
		return $iNumber;
	}
}

/** @} */
