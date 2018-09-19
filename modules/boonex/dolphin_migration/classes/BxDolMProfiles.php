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

bx_import('BxDolProfile');
bx_import('BxDolTranscoderImage');
bx_import('BxDolStorage');
	
require_once('BxDolMData.php');	
	
class BxDolMProfiles extends BxDolMData
{
    public function __construct(&$oMigrationModule, &$seDb)
    {
		parent::__construct($oMigrationModule, $seDb);
		$this -> _sModuleName = 'profiles';
		$this -> _sTableWithTransKey = 'sys_accounts';
    }

	public function getTotalRecords()
	{
		return (int)$this -> _mDb -> getOne("SELECT COUNT(*) FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "`");			
	}
	
	public function runMigration()
	{
	    if (!$this -> getTotalRecords())
		{
			  $this -> setResultStatus(_t('_bx_dolphin_migration_no_data_to_transfer'));
	          return BX_MIG_SUCCESSFUL;
		}	
		
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_profiles'));
        $sError = $this -> profilesMigration();
        if($sError) {
              $this -> setResultStatus($sError);
              return BX_MIG_FAILED;
        }
	
        $this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_profiles_finished', $this -> _iTransferred));
		return BX_MIG_SUCCESSFUL;
    }
	
	/**
	* Check if profile with the same nickname already exists
	* @param string $sNickName name of the user
	* @return integer account id
     */
	private function isProfileExisted($sNickName, $sEmail){
        $sQuery  = $this -> _oDb -> prepare("SELECT `id` FROM `sys_accounts` WHERE `name` = ? OR `email` = ?", $sNickName, $sEmail);
        return $this -> _oDb -> getOne($sQuery);
	}

	function profilesMigration()
    {
			$this -> createMIdField();

			// get IDs of the latest transferred profile for UNA Accounts and Dolphin Profiles
			$iProfileID = $this -> getLastMIDField();
			$sStart = '';
			if ($iProfileID[$this -> _sTransferFieldIdent])
				$sStart = "WHERE `ID` > {$iProfileID}";
			else 
				$this -> addPreValues('Sex', true, 2); // transfer all sex values more then 2 value

			$aResult = $this -> _mDb -> getAll("SELECT * FROM `"  . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` {$sStart} ORDER BY `ID` ASC");			
			$aSex = $this -> getPreValuesBy('Sex', 'Value', 'Order');

			foreach($aResult as $iKey => $aValue)
			{

				if($aValue['NickName'])
				{
                     $sAction = "INSERT INTO";
                     $sWhere  = "";
                     $iAccountId = $this -> isProfileExisted($aValue['NickName'], $aValue['Email']);
                     if ($iAccountId)
                     {
                         if ($this -> _oConfig -> _bIsOverwrite) {
                             $sAction = "UPDATE";
                             $sWhere = "WHERE `id` = '{$iAccountId}'";
                         }
                         else
                             continue;
				     }

                      $sQuery = $this -> _oDb -> prepare(
                     "
                     	{$sAction}
                     		`sys_accounts`
                     	SET
                     		`name`   			= ?,
                     		`email`      		= ?,
                     		`password`   		= ?,
                     		`salt`		   		= ?,
							`role`				= ?,		
                     		`lang_id`			= ?,
							`added`				= ?,
                     		`changed`	  		= ?,
                     		`logged` 			= ?,
							`email_confirmed`	= ?,
							`receive_updates`	= ?,	
							`receive_news`		= ?
						{$sWhere}	
                     ", 
						$aValue['NickName'], 
						$aValue['Email'], 
						$aValue['Password'],
						$aValue['Salt'],
						$aValue['Role'],
						$aValue['LangID'],
						strtotime($aValue['DateReg']),
						strtotime($aValue['DateLastEdit']),
						strtotime($aValue['DateLastLogin']),
						isset($aValue['Status']) && $aValue['Status'] != 'Unconfirmed' ? 1 : 0,
						$aValue['EmailNotify'],
						$aValue['EmailNotify']
						);
						
					$this -> _oDb -> query($sQuery);										

					$iAccountId = $iAccountId ? $iAccountId : $this -> _oDb -> lastId();
					if (!$iAccountId) 
						continue;
					
					$this -> setMID($iAccountId, $aValue['ID']);

                    if ($this -> _oConfig -> _bUseNickName)
                        $sFullName = $aValue['NickName'];
					else
					    {
                        $sFullName = isset($aValue['FullName']) ? $aValue['FullName'] : "{$aValue['FirstName']} {$aValue['LastName']}";
                        $sFullName = $sFullName ? $sFullName : $aValue['NickName'];
                    }
					
					$sQuery = $this -> _oDb -> prepare( 
	                     "INSERT INTO
	                     		`bx_persons_data`
	                     	SET
	                     		`author`   			= 0,
	                     		`added`      		= ?,
	                     		`changed`   		= ?,
								`picture`			= 0,		
	                     		`cover`				= 0,
								`fullname`			= ?,
								`birthday`			= ?,
								`gender`			= ?,
								`allow_view_to`		= 3,
								`featured`			= ?,
								`views`				= ?,
								`description`		= ?
	                     ",
							strtotime($aValue['DateReg']),
							strtotime($aValue['DateLastEdit']),
							$sFullName,
							isset($aValue['DateOfBirth']) ? $aValue['DateOfBirth'] : 'NULL',
							isset($aValue['Sex']) && $aValue['Sex'] ? $aSex[$aValue['Sex']] : 1,
							//isset($aValue['allow_view_to']) ? $aValue['allow_view_to'] : 3,
							isset($aValue['Featured']) ? (int)$aValue['Featured'] : 0,
							isset($aValue['Views']) ? (int)$aValue['Views'] : 0,
							isset($aValue['DescriptionMe']) ? $aValue['DescriptionMe'] : ''
							);
						
						$this -> _oDb -> query($sQuery);	
						$iContentId = $this -> _oDb -> lastId();
						
						$this -> _oDb -> query("INSERT INTO `sys_profiles` SET `account_id` = {$iAccountId}, `type` = 'system', `content_id` = {$iContentId}, `status` = 'active'");
						$this -> _oDb -> query("INSERT INTO `sys_profiles` SET `account_id` = {$iAccountId}, `type` = 'bx_persons', `content_id` = {$iContentId}, `status` = 'active'");
						$iProfile = $this -> _oDb -> lastId();

						if($iProfile)
							BxDolAccountQuery::getInstance() -> updateCurrentProfile($iAccountId, $iProfile);
						
						$sQuery = $this -> _oDb -> prepare("UPDATE `bx_persons_data` SET `author` = ? WHERE `id` = ?", $iProfile, $iContentId);						
						$this -> _oDb -> query($sQuery);

						$this -> exportAvatar($iContentId, $aValue);                   
						
						$sResult = $this -> exportFriends($aValue['ID']);
						if($sResult)
							return $sResult;                     

						 $this -> _iTransferred++;
                  }
             }
        }

	/**
	* Get Avatar's image  path
	* @param int $iImgId image ID
	* @return string
         */ 	
	private function getProfileImage($aProfileInfo)
	{	   
       $iProfileId = $aProfileInfo['ID'];	   
	   
	   $sAvatarType = $this -> _mDb -> getParam('sys_member_info_thumb');
	   $sImagePath = $this -> _oDb -> getExtraParam('root') . 'modules' . DIRECTORY_SEPARATOR . 'boonex' . DIRECTORY_SEPARATOR;
	   if ($sAvatarType == 'bx_photos_thumb')
		{
			$sAlbumName = $this -> _mDb -> getParam('bx_photos_profile_album_name');
			$aReplacement = array(
				'{nickname}' => $aProfileInfo['NickName'],
				'{fullname}' =>  $aProfileInfo['NickName']
			);
			
			$sAlbumName = str_replace(array_keys($aReplacement), array_values($aReplacement), $sAlbumName);
			$sAlbumName = uriFilter($sAlbumName);
			$aInfo = $this -> _mDb -> getRow("SELECT `bx_photos_main`.*  FROM `bx_photos_main`
					LEFT JOIN `sys_albums_objects` ON `sys_albums_objects`.`id_object`=`bx_photos_main`.`ID` 
					LEFT JOIN `sys_albums` ON `sys_albums`.`ID`=`sys_albums_objects`.`id_album` 
					WHERE `bx_photos_main`.`Status` ='approved' AND `bx_photos_main`.`Owner` =:id AND `sys_albums`.`Status` ='active' AND `sys_albums`.`Type` ='bx_photos' AND `sys_albums`.`Uri` = :uri 
					ORDER BY `obj_order` ASC, `id_object`
					LIMIT 1", array('uri' => $sAlbumName, 'id' => $aProfileInfo['ID']));
			
			if (!empty($aInfo) && isset($aInfo['ID']))
				$sImagePath = $sImagePath . "photos" . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR ."{$aInfo['ID']}.{$aInfo['Ext']}";
		}
		else
		{
			$iId = $this -> _mDb -> getOne("SELECT `id` FROM `bx_avatar_images` WHERE `author_id` = :id LIMIT 1", array('id' => $aProfileInfo['ID']));
			if ($iId)
				$sImagePath = $sImagePath . "avatar" . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR ."{$iId}b.jpg";
		}

	   if (file_exists($sImagePath))
		   return $sImagePath;

	   return false;	 
	}
 
	private function exportFriends($iProfileId)
    {
       $iProfileId = (int) $iProfileId;
       $sQuery = $this -> _mDb -> prepare("SELECT * FROM `sys_friend_list` WHERE `ID` = {$iProfileId} OR `Profile` = {$iProfileId}");
	   $aFriends = $this -> _mDb -> getAll($sQuery);

		foreach($aFriends as $iKey => $aValue)
		{
			$iUserId = $this -> getProfileId($aValue['ID']);
			$iFriendId = $this -> getProfileId($aValue['Profile']);
			if( !$this -> isFriendExists($iUserId, $iFriendId))
			{

				   $sQuery =  $this -> _oDb -> prepare("
						INSERT IGNORE INTO
							`sys_profiles_conn_friends`
						SET
						   `initiator`	= ?,
						   `content`    = ?,
						   `mutual`     = ?,
						   `added`     	= UNIX_TIMESTAMP()
				   ", $iUserId, $iFriendId, (int)($aValue['Check']));

				   $iResult = (int) $this -> _oDb -> query($sQuery);
				   if($iResult <= 0) {
					   return _t('_bx_dolphin_migration_friends_exports_error');
				   }
				}
				
		}	    
	}

	private function exportAvatar($iProfileId, $aProfileInfo)
    {
       $iProfileId = (int) $iProfileId;
       $sAvatarPath = $this -> getProfileImage($aProfileInfo);
       if($sAvatarPath)
		{
			$oStorage = BxDolStorage::getObjectInstance('bx_persons_pictures'); 				
				$iId = $oStorage->storeFileFromPath($sAvatarPath, false, $iProfileId); 
				if ($iId)
				{ 
					$sQuery = $this -> _oDb -> prepare("UPDATE `bx_persons_data` SET `Picture` = ? WHERE `id` = ?", $iId, $iProfileId);
					$this -> _oDb -> query($sQuery);								
				}
		}
    }

	private function isFriendExists($iProfileId, $iFriendId)
    {
            $iProfileId = (int) $iProfileId;
            $iFriendId  = (int) $iFriendId;

            $sQuery =  "
            	SELECT 
            		COUNT(*) 
            	FROM 
            		`sys_profiles_conn_friends` 
            	WHERE
            		(
            			(`initiator` = {$iProfileId} AND `content` = {$iFriendId})
            				OR
            			(`initiator` = {$iFriendId} AND `content` = {$iProfileId})	
            		)
            ";

            return $this -> _oDb -> getOne($sQuery) ? true : false;
	}
	
	public function removeContent()
	{
		if (!$this -> _oDb -> isTableExists($this -> _sTableWithTransKey) || !$this -> _oDb -> isFieldExists($this -> _sTableWithTransKey, $this -> _sTransferFieldIdent))
			return false;
		
		$iNumber = 0;
		$aRecords = $this -> _oDb -> getAll("SELECT * FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sTransferFieldIdent}` !=0");
		if (!empty($aRecords))
		{
			foreach($aRecords as $iKey => $aValue)
			{
				$oAccount = BxDolAccount::getInstance($aValue['id']);
				if ($oAccount)
					$oAccount -> delete(true);			
				
				$iNumber++;
			}
					
			if ($iNumber)
			foreach ($this -> _oConfig -> _aMigrationModules as $sName => $aModule)
			{
				//Create transferring class object
				require_once($aModule['migration_class'] . '.php');			
				$oObject = new $aModule['migration_class']($this -> _oMainModule, $this -> _mDb);			
				$oObject -> removeContent();				
			}
		}	
		
		parent::removeContent();
		return $iNumber;
	}
	/**
	 *  Adds fields list ()if doesn't exist or value to existed fields list 
	 *  
	 *  @param string $sName list name
	 *  @param boolean $bUseOrder use Order field instead of Value as value for profile
	 *  @param int $iStart start value, to show all above it
	 *  @param boolean $bCreate create fields list if doesn't exists
	 *  @return string fields list name
	 */
	private function addPreValues($sName, $bUseOrder = false, $iStart = 0, $bCreate = false)
	{
		$sKeyField = 'Value';
		if ($bUseOrder)
			$sKeyField = 'Order';

		$aPairs = $this -> getPreValuesBy($sName, $sKeyField, 'LKey', $iStart);
		$aValues = array();
		foreach($aPairs as $sKey => $sValue)
		{
			$aTranslations = $this -> getLKeyTranslations($sValue);
			$aValues[$sKey] = !empty($aTranslations) && sizeof($aTranslations) > 1 ? $this -> getLKeyTranslations($sValue) : $aTranslations[$this -> getDefaultLang()];
		}

		return $this -> transferPreValues($sName, $sName, $aValues, true);	
	}
	
}

	
/** @} */
