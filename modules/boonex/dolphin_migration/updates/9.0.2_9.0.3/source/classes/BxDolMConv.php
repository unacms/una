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
	
class BxDolMConv extends BxDolMData
{	
	public function __construct(&$oMigrationModule, &$oDb)
	{
        parent::__construct($oMigrationModule, $oDb);
		$this -> _sModuleName = 'conversations';
		$this -> _sTableWithTransKey = 'bx_convos_cmts';
    }    
	
	public function getTotalRecords()
	{
		return (int)$this -> _mDb -> getOne("SELECT COUNT(*) FROM  `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` WHERE `Type` = 'letter' AND `Sender` <> `Recipient` ORDER BY `ID`");
	}
	
	public function runMigration()
	{        
		if (!$this -> getTotalRecords())
		{
			  $this -> setResultStatus(_t('_bx_dolphin_migration_no_data_to_transfer'));
	          return BX_MIG_SUCCESSFUL;
		}	
		
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_conversations'));		
			
		$aResult = $this -> _mDb -> getAll("SELECT  * FROM  `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` ORDER BY `ID`");
		
		$iCmts = 0;
		foreach($aResult as $iKey => $aValue)
		{
			$iSenderId = $this -> getProfileId((int)$aValue['Sender']);
			$iRecipientId = $this -> getProfileId((int)$aValue['Recipient']);			
			$iDate = isset($aValue['Date']) ? strtotime($aValue['Date']) : time();				
			if (!$iSenderId || !$iRecipientId || ($iSenderId == $iRecipientId))
				continue;
			
			$iConvId = $this -> isConvExisted($aValue['Subject'], $iSenderId, $iRecipientId);
			if (!$iConvId)
			{
				$sQuery = $this -> _oDb -> prepare( 
							 "
								INSERT INTO
									`bx_convos_conversations`
								SET
									`author`   				= ?,
									`added`      			= ?,
									`changed`   			= ?,
									`views`					= ?,
									`last_reply_timestamp`	= ?,
									`text`					= ?
							 ", 
							$iSenderId, 
							$iDate, 
							$iDate,
							$aValue['New'] ? 0 : 1,
							$iDate,
							$aValue['Subject']
						);			
		
				$this -> _oDb -> query($sQuery);
				
				$iConvId = $this -> _oDb -> lastId();
				if (!$iConvId){
					$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_conv_error', (int)$aValue['ID']));
					return BX_MIG_FAILED;
				}

                $this -> updateConvFolder($iSenderId, stripos($aValue['Trash'], 'sender') !== FALSE ? 4 : 1, $iConvId);
                $this -> updateConvFolder($iRecipientId, stripos($aValue['Trash'], 'recipient') !== FALSE ? 4 : 1, $iConvId);
			}
			
			$this -> createMIdField();
			
			if (!($iMessId = $this -> isItemExisted($aValue['ID'], 'cmt_id')))
			{
				$this -> _oDb -> query("INSERT INTO `bx_convos_cmts` (`cmt_id`, `cmt_parent_id`, `cmt_vparent_id`, `cmt_object_id`, `cmt_author_id`, `cmt_level`, `cmt_text`, `cmt_time`, `cmt_replies`, `cmt_rate`, `cmt_rate_count`)
									VALUES	(NULL, 0, 0, :object, :user, 0, :message, :time, 0, 0, 0)", 
									array(
											'object' => $iConvId,
											'user' => $iSenderId, 
											'message' => $aValue['Text'], 
											'time' => $iDate	
										));
				
				$iMessId = $this -> _oDb -> lastId();
				$this -> setMID($iMessId, $aValue['ID'], 'cmt_id');				
			}
										
			$this -> _iTransferred ++;
			
			$this -> _oDb ->  query("UPDATE `bx_convos_conversations`
									SET 
										`comments` = `comments` + 1, 
										`last_reply_profile_id` = :lpi, 
										`last_reply_comment_id` = :lci,
										`last_reply_timestamp`	= :time
									WHERE `id` = :id", 
										array(
												'lpi' => $iSenderId, 
												'lci' => $iMessId, 
												'id' => $iMessId,
												'time' => $iDate
											));			
			
			$this -> updateOwnTables($iConvId, $iSenderId, $aValue['New']);
		}

        //set as finished;
        $this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_conv_finished', $this -> _iTransferred));
        return BX_MIG_SUCCESSFUL;
    }
	private function updateConvFolder($iCollaborator, $iFolder, $iConvId){
	    $iId = $this -> _oDb -> getOne("SELECT `id` FROM `bx_convos_conv2folder` WHERE `collaborator` = :coll AND `folder_id` = :folder AND `conv_id` = :id",
            array('coll' => $iCollaborator, 'folder' => $iFolder, 'id' => $iConvId));
	    if($iId)
	        return $iId;

	    $sQuery = $this -> _oDb -> prepare(
                        "
							INSERT INTO
								`bx_convos_conv2folder`
							SET
								`conv_id`      		= ?,
								`folder_id`   		= ?,
								`collaborator`		= ?
						",
                $iConvId,
                $iFolder,
                $iCollaborator
            );

	    return $this -> _oDb -> query($sQuery);
    }
	protected function isConvExisted($sSubject, $iSenderId, $iRecipientId)
	{
		$sSubject = preg_replace('/^Re.*:\s+(.*)$/i', '\\1', $sSubject);
		
		return (int)$this -> _oDb -> getOne("SELECT `c`.`id`
											 FROM `bx_convos_conversations` as `c`
											 LEFT JOIN `bx_convos_conv2folder` as `f` ON `c`.`id` = `conv_id`
											 WHERE `text` = :text AND ((`f`.`collaborator`=:sender AND `c`.`author` = :recipient) OR (`f`.`collaborator`=:recipient AND `c`.`author` = :sender)) LIMIT 1", array('text' => $sSubject, 'sender' => $iSenderId, 'recipient' => $iRecipientId));
	}	
	/**
	 *  Builds folders for conversations
	 *  
	 *  @param int $iObject conversation id
	 *  @param int $iProfileId profile id
	 *  @param int $iComments message id
	 *  @return res query result
	 */
	protected function updateOwnTables($iObject, $iProfileId, $iComments)
	{
		$sQuery = $this -> _oDb -> prepare( 
			"
				UPDATE
					`bx_convos_conv2folder`
				SET
					`read_comments`	= `read_comments` + ?							
				WHERE `conv_id` = ? AND `collaborator` = ?
			", 
			$iComments ? 1 : 0,
			$iObject, 
			$iProfileId						
		);
		
		return $this -> _oDb -> query($sQuery);	
	}
	
	public function removeContent()
	{
		if (!$this -> _oDb -> isTableExists($this -> _sTableWithTransKey) || !$this -> _oDb -> isFieldExists($this -> _sTableWithTransKey, $this -> _sTransferFieldIdent))
			return false;
		
		$aRecords = $this -> _oDb -> getAll("SELECT *
											FROM `{$this -> _sTableWithTransKey}`										
											WHERE `{$this -> _sTransferFieldIdent}`!=0");
		$iNumber = 0;
		if (!empty($aRecords))
		{
			foreach($aRecords as $iKey => $aValue)
			{			
				$sQuery = $this-> _oDb -> prepare("DELETE FROM `tl`, `tle` USING `bx_convos_conversations` AS `tl` LEFT JOIN `bx_convos_cmts` AS `tle` ON `tl`.`id`=`tle`.`cmt_object_id` WHERE `tle`.`cmt_object_id` = ?", $aValue['cmt_object_id']);
				$this -> _oDb -> query($sQuery);
						
				$this -> _oDb -> query("DELETE FROM `bx_convos_conv2folder` WHERE `conv_id`=:id", array('id' => $aValue['cmt_object_id']));
				$iNumber++;
			}
		}	
		
		parent::removeContent();		
		return $iNumber;
	}	
}

/** @} */
