<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup	Messenger Messenger
 * @ingroup		UnaModules
 *
 * @{
 */

/**
 * Database queries
 */ 
class BxMessengerDb extends BxBaseModTextDb
{
   private $CNF;   
   
   function __construct(&$oConfig)
   {
		parent::__construct($oConfig);		
		$this->CNF = &$oConfig -> CNF;
    }
	
	/**
	* Get all lot by class name 
	*@param string $sClass
	*@return array lot info
	*/
	public function getLotByClass($sClass){
		$sQuery = $this -> prepare("SELECT * FROM `{$this->CNF['TABLE_ENTRIES']}` WHERE `{$this->CNF['FIELD_CLASS']}` = ? LIMIT 1", $sClass);
		return $this -> getRow($sQuery);
	}

	/**
	* Get all lot by page url
	*@param string $sUrl or the page
	*@return array lot info
	*/	
	public function getLotByUrl($sUrl)
	{
		$sQuery = $this -> prepare("SELECT * FROM `{$this->CNF['TABLE_ENTRIES']}` WHERE `{$this->CNF['FIELD_URL']}` = ? LIMIT 1", $sUrl);
		return $this -> getRow($sQuery);
	}

	/**
	* Get all lot by id
	*@param int $iId lot id
	*@return array lot info
	*/
	public function getLotInfoById($iId)
	{
		$sQuery = $this -> prepare("SELECT * FROM `{$this->CNF['TABLE_ENTRIES']}` WHERE `{$this->CNF['FIELD_ID']}` = ? LIMIT 1", (int)$iId);
		return $this -> getRow($sQuery);
	}

	/**
	* Get lot by id or url
	*@param int $iId lot id
	*@param string $sUrl url or url of the page with talk 
	*@param int $iAuthor profile id 
	*@return array lot info
	*/
	function getLotByIdOrUrl($iId, $sUrl, $iAuthorId = 0){
		if ($iId && $iAuthorId && $this -> isParticipant($iId,  $iAuthorId)) 
			return $this -> getLotInfoById($iId);
		
		if ($sUrl) 
			return $this -> getLotByUrl($sUrl);
		
		return array();
	}

	/**
	* Check if is the member author of the lot
	*@param int $iLotId lot id
	*@param int $iAuthor profile id 
	*@return boolean
	*/
	function isAuthor($iLotId, $iAuthorId){
		$sQuery = $this -> prepare("SELECT COUNT(*) FROM `{$this->CNF['TABLE_ENTRIES']}` WHERE `{$this->CNF['FIELD_ID']}` = ? AND `{$this->CNF['FIELD_AUTHOR']}` = ? LIMIT 1", (int)$iLotId, (int)$iAuthorId);
		return $this -> getOne($sQuery) == 1;
	}

	/**
	* Deletes lot by lot Id
	*@param int $iLotId lot id
	*@return int affected rows
	*/
	public function deleteLot($iLotId){
		$iLotId = (int)$iLotId;
		
		$iResult = $this -> query("DELETE FROM `{$this->CNF['TABLE_ENTRIES']}` WHERE `{$this->CNF['FIELD_ID']}` = :id", array('id' => $iLotId));
		$iResult += $this -> query("DELETE FROM `{$this->CNF['TABLE_MESSAGES']}` WHERE `{$this->CNF['FIELD_MESSAGE_FK']}` = :id", array('id' => $iLotId));
		return $iResult;
	}
	
	/**
	* Removes participant from the participants list
	*@param int $iLotId lot id
	*@param int $iParticipant profile id
	*@return int/false 
	*/	
	public function leaveLot($iLotId, $iParticipant){
		return $this -> removeParticipant($iLotId, $iParticipant);
	}
	
	/**
	* Get Lot(Talk) settings for a member
	*@param int $iLotId lot id
	*@param int $iParticipant profile id
	*@param string $sName name the option
	*@return mixed option value
	*/
	private function getParams($iLotId, $iParticipant, $sName){			
		$sQuery = $this -> prepare("SELECT `{$this->CNF['FIELD_INFO_PARAMS']}` FROM `{$this->CNF['TABLE_USERS_INFO']}` 
									WHERE `{$this->CNF['FIELD_INFO_LOT_ID']}` = ? AND `{$this->CNF['FIELD_INFO_USER_ID']}` = ? 
									LIMIT 1", (int)$iLotId, (int)$iParticipant);
		$sInfo = $this -> getOne($sQuery);
		if (empty($sInfo))
				return false;
		
		$aInfo = unserialize($sInfo);
		return isset($aInfo[$sName]) ? $aInfo[$sName] : 0;
	}

	/**
	* Save Lot(Talk) settings for a member
	*@param int $iLotId lot id
	*@param int $iParticipant profile id
	*@param array $aParams options list
	*@return int affected rows
	*/
	private function setParams($iLotId, $iParticipant, $aParams){
		return $this -> query("REPLACE INTO `{$this->CNF['TABLE_USERS_INFO']}` SET `{$this->CNF['FIELD_INFO_PARAMS']}` = :values, `{$this->CNF['FIELD_INFO_LOT_ID']}` = :id, `{$this->CNF['FIELD_INFO_USER_ID']}` = :user", array('user' => $iParticipant, 'id' => $iLotId, 'values' => serialize($aParams)));		
	}

	/**
	* Make the lot mute for a member
	*@param int $iLotId lot id
	*@param int $iParticipant profile id
	*@return int affected rows
	*/
	public function muteLot($iLotId, $iParticipant){
		$iNotification = $this -> getParams($iLotId, $iParticipant, 'notification');
		$aParams = array('notification' => $iNotification === false || (int)$iNotification ? 0 : 1);

		return $this -> setParams($iLotId, $iParticipant, $aParams);
	}

	
	/**
	* Check if the lot is mute for a member
	*@param int $iLotId lot id
	*@param int $iParticipant profile id
	*@return boolean 
	*/
	function isMuted($iLotId, $iParticipant){
		$iMute = $this -> getParams($iLotId, $iParticipant, 'notification');
		return $iMute === false || $iMute ? false : true; 
	}

	/**
	* Save participants list for the lot
	*@param int $iLotId lot id
	*@param array $aParticipants
	*@return int affected rows
	*/
	public function savePariticipantsList($iLotId, $aParticipants){
		$aParticipants = array_map('intval', $aParticipants);
		
		if (empty($aParticipants)) return false;
		$sParticipants = implode(',', $aParticipants);	
		
		return $this -> query("UPDATE `{$this->CNF['TABLE_ENTRIES']}` SET `{$this->CNF['FIELD_PARTICIPANTS']}` = :parts WHERE `{$this->CNF['FIELD_ID']}` = :id", array('parts' => $sParticipants, 'id' => $iLotId));
	}

	/**
	* Save message for participants to database
	*@param array $aData lot settings
	*@param array $aParticipants participants list, if it empty then used default for lot
	*@return int affected rows
	*/
	public function saveMessage($aData, $aParticipants = array()) {
		if (!$aData['message']) return false;

		$aChat = array();
		if ((int)$aData['lot'])
			$aChat = $this -> getLotInfoById($aData['lot']);
		
		if ($aData['type'] != BX_IM_TYPE_PRIVATE && !$this -> isParticipant($aData['lot'], $aData['member_id']))
			$this -> addMemberToParticipantsList($aData['lot'], $aData['member_id']);			
				
		if (empty($aParticipants) && (int)$aData['lot']) 
			$aParticipants = $this -> getParticipantsList($aData['lot']);	
		
		$aChat = !empty($aChat) ? $aChat : $this -> getLotByUrlAndPariticipantsList($aData['url'], $aParticipants, $aData['type']);
		if (empty($aChat)){
			$iLotID = $this -> createNewLot($aData['member_id'], $aData['title'], $aData['type'], $aData['url'], $aParticipants);  
		} else 
			$iLotID = $aChat[$this->CNF['FIELD_ID']];
		
		return $this -> addNewJot($iLotID, $aData['message'], $aData['member_id']);
	}	

	/**
	* Save message for participants to database
	*@param string $sUrl of the page with lot
	*@param array $aParticipants participants list
	*@param int $iType lot type
	*@return array Lot info
	*/
	public function getLotByUrlAndPariticipantsList($sUrl = '', $aParicipants = array(), $iType = BX_IM_TYPE_PRIVATE){
		if ($iType != BX_IM_TYPE_PRIVATE && $sUrl && $aChat = $this -> getLotByUrl($sUrl)) 
			return $aChat;
		
		$aResult = array();
		if (!empty($aParicipants)){
			$sWhere = " AND `{$this->CNF['FIELD_AUTHOR']}` IN (" . $this -> implode_escape($aParicipants) . ")";

			$aLots = $this -> getAll("SELECT * FROM `{$this->CNF['TABLE_ENTRIES']}` WHERE `type` = :type {$sWhere}", array('type' => $iType));

			if (!empty($aLots)){
					
				foreach($aLots as $iKey => $aValue){
					 $aPerticipantsList = $this -> getParticipantsList($aValue[$this->CNF['FIELD_ID']]);
					 if (empty($aPerticipantsList) || count($aPerticipantsList) != count($aParicipants)) continue;			
					 
					 sort($aPerticipantsList);
					 sort($aParicipants);
					 
					 if (array_values($aPerticipantsList) == array_values($aParicipants)){ 
						$aResult = $aValue;
					 }	
				}					
			}
		}

		return $aResult;
	}

	/**
	* Mark message as read in lot history
	*@param int $iJotId message id
	*@param int iProfileId	
	*/
	function readMessage($iJotId, $iProfileId){
		$sNotViewed = $this -> getOne("SELECT `{$this->CNF['FIELD_MESSAGE_NEW_FOR']}` FROM `{$this->CNF['TABLE_MESSAGES']}` WHERE `{$this->CNF['FIELD_MESSAGE_ID']}` = :id", array('id' => $iJotId));
		$sNewList = '';
		if ($sNotViewed){ 
			$aParticipants = explode(',', $sNotViewed);
			$iKey = array_search($iProfileId, $aParticipants);
			if ($iKey !== FALSE){
				unset($aParticipants[$iKey]);
				$sNewList = count($aParticipants) > 0 ? implode(',', $aParticipants) : '';
				$this -> query("UPDATE `{$this->CNF['TABLE_MESSAGES']}` SET `{$this->CNF['FIELD_MESSAGE_NEW_FOR']}` = :part WHERE `{$this->CNF['FIELD_MESSAGE_ID']}` = :id", array('part' => $sNewList, 'id' => $iJotId));
			} 			
		}
	}

	/**
	* Mark all message as read in lot history for member
	*@param int $iLot  lot id
	*@param int iProfileId	
	*/
	public function readAllMessages($iLot, $iProfileId){
		$aAll = $this-> getAll("SELECT * FROM `{$this->CNF['TABLE_MESSAGES']}` WHERE `{$this->CNF['FIELD_MESSAGE_FK']}` = :id AND FIND_IN_SET(:user, `{$this->CNF['FIELD_MESSAGE_NEW_FOR']}`)", array('id' => $iLot, 'user' => $iProfileId));
		foreach($aAll as $iKey => $aValue){
			$sNewList = '';
			$aParticipants = explode(',', $aValue[$this->CNF['FIELD_MESSAGE_NEW_FOR']]);
			$iPos = array_search($iProfileId, $aParticipants);
			if ($iPos !== FALSE){
				unset($aParticipants[$iPos]);
				$sNewList = count($aParticipants) > 0 ? implode(',', $aParticipants) : '';
				$this -> query("UPDATE `{$this->CNF['TABLE_MESSAGES']}` SET `{$this->CNF['FIELD_MESSAGE_NEW_FOR']}` = :part WHERE `{$this->CNF['FIELD_MESSAGE_ID']}` = :id", array('part' => $sNewList, 'id' => $aValue[$this->CNF['FIELD_MESSAGE_ID']]));
			} 			
		}		
	}

	/**
	*Add new new jot to database  
	*@param int $iLotID  lot id
	*@param string $sMessage posted message 
	*@param int iProfile Id	owner of the message
	*@return  int affected rows
	*/
	private function addNewJot($iLotID, $sMessage, $iProfileId)
	{
		$sParticipants = $this -> getParticipantsList($iLotID, false /* as  string list*/, $iProfileId);
		$sQuery = $this->prepare("INSERT INTO `{$this->CNF['TABLE_MESSAGES']}` 
												SET  `{$this->CNF['FIELD_MESSAGE']}` = ?, 
													 `{$this->CNF['FIELD_MESSAGE_FK']}` = ?, 
													 `{$this->CNF['FIELD_MESSAGE_AUTHOR']}` = ?,
													 `{$this->CNF['FIELD_MESSAGE_NEW_FOR']}` = ?,													 
													 `{$this->CNF['FIELD_MESSAGE_ADDED']}` = UNIX_TIMESTAMP()", $sMessage, $iLotID, $iProfileId, $sParticipants);
		
		return $this->query($sQuery) ? $this -> lastId() : false;
	}
		
	/**
	*Create new chat/lot with list of participants
	*@param int iProfileId owner of the lot
	*@param string $sTitle lot title
	*@param int $iType type of the lot
	*@param string $sUrl url of the page
	*@param array $aParticipants list of participants
	*@return  int affected rows
	*/
	private function createNewLot($iProfileId, $sTitle, $iType, $sUrl = '', &$aParticipants = array())
	{
		$mixedParticipants = !empty($aParticipants) ? implode(',', $aParticipants) : $iProfileId;				
		$sQuery = $this->prepare("INSERT INTO `{$this->CNF['TABLE_ENTRIES']}` 
												SET  `{$this->CNF['FIELD_TITLE']}` = ?, 
													 `{$this->CNF['FIELD_TYPE']}` = ?, 
													 `{$this->CNF['FIELD_AUTHOR']}` = ?,
													 `{$this->CNF['FIELD_ADDED']}` = UNIX_TIMESTAMP(),
													 `{$this->CNF['FIELD_PARTICIPANTS']}` = ?,
													 `{$this->CNF['FIELD_URL']}` = ?", $sTitle, $iType, $iProfileId, $mixedParticipants, $sUrl);
		
		return $this->query($sQuery) ? $this -> lastId() : false;
	}
	
	/**
	*Get list of types of member lots
	*@param int iProfileId
	*@param string $sParam keyword to filter lots
	*@return  array found lots types with lots count per each type
	*/
	public function getMemberLotsTypes($iProfileId, $sParam = ''){
		$sWhere = '';
		$aWhere = array();
		
		if ($iProfileId){
			$aSWhere[] = "FIND_IN_SET(:profile, `{$this->CNF['FIELD_PARTICIPANTS']}`)";
			$aWhere = array('profile' => $iProfileId);
		}		
		
		if ($sParam){
			$sParam = "%{$sParam}%";
			$aSWhere[] = " (`{$this->CNF['FIELD_TITLE']}` LIKE :title OR `{$this->CNF['FIELD_URL']}` LIKE :url OR `{$this->CNF['FIELD_TYPE']}` LIKE :type)";
			$aWhere = array_merge($aWhere, array('title' => $sParam, 'url' => $sParam, 'type' => $sParam));
		}	
				
		if (!empty($aWhere))
			$sWhere = "WHERE (" . implode(' AND ', $aSWhere) . ')';
			
		return $this-> getPairs("SELECT `{$this->CNF['FIELD_TYPE']}`, COUNT(*) as `count` 
			FROM `{$this->CNF['TABLE_ENTRIES']}` 
			{$sWhere}
			GROUP BY `{$this->CNF['FIELD_TYPE']}`", $this->CNF['FIELD_TYPE'], 'count', $aWhere);			
	}

	/**
	*Get list of all existed types 
	*@return  array types
	*/
	public function getAllLotsTypes(){
		return $this -> getAll("SELECT * FROM `{$this->CNF['TABLE_TYPES']}` ORDER BY `{$this->CNF['FIELD_TYPE_ID']}` ASC");
	}

	/**
	* Check if this ype of lot exists
	*@param int $iType type of the lot
	*@return boolean 
	*/
	public function isLotType($iType){
		return $this -> getOne("SELECT COUNT(*) FROM `{$this->CNF['TABLE_TYPES']}` WHERE `{$this->CNF['FIELD_TYPE_ID']}` = :type LIMIT 1", array('type' => $iType)) == 1;
	}
	
	/**
	* Get lot's participant list 
	*@param int $iLotId lot id
	*@param boolean $bArray if true, returns result as array, otherwise string with ids separated commas
	*@param int $bExcludeProfile allows to exclude profile from the list, usually it is owner
	*@return string/array
	*/
	public function getParticipantsList($iLotId, $bArray = true, $bExcludeProfile = 0){
		$aParticipants = array();
		$sParticipants = $this -> getOne("SELECT `{$this->CNF['FIELD_PARTICIPANTS']}` FROM `{$this->CNF['TABLE_ENTRIES']}` WHERE `{$this->CNF['FIELD_ID']}` = :value", array('value' => $iLotId));
		
		if (!$sParticipants) 
				return array();
		
		$aParticipants = explode(',', $sParticipants);
		
		if ($bExcludeProfile && ($iId = array_search($bExcludeProfile, $aParticipants)) !== FALSE)			
			unset($aParticipants[$iId]);
		
		return !$bArray ? implode(',', $aParticipants) : $aParticipants;
	}

	/**
	* Check if it is participant of the lot
	*@param int $iLotId lot id
	*@param int $iParticipantId profile id
	*@return boolean 
	*/
	public function isParticipant($iLotId, $iParticipantId){
		$aParticipants = $this -> getParticipantsList($iLotId);
		return array_search($iParticipantId, $aParticipants) !== FALSE;
	}
	
	/**
	* Add profile to lot's participants list
	*@param int $iLotId lot id
	*@param int $iParticipantId profile id
	*@return int affected rows
	*/
	private function addMemberToParticipantsList($iLotId, $iParticipantId){
		$sParticipants = $this -> getParticipantsList($iLotId, false /* as string list */);
		$sParticipants = $sParticipants ? "{$sParticipants},{$iParticipantId}" : $iParticipantId;		
		return $this -> query("UPDATE `{$this->CNF['TABLE_ENTRIES']}` SET `{$this->CNF['FIELD_PARTICIPANTS']}` = :parts WHERE `{$this->CNF['FIELD_ID']}` = :id", array('parts' => $sParticipants, 'id' => $iLotId));
	}	

	/**
	* Remove profile from participants list
	*@param int $iLotId lot id
	*@param int $iParticipantId profile id
	*@return mixed false or affected rows
	*/
	private function removeParticipant($iLotId, $iParticipantId){
		$aParticipants = $this -> getParticipantsList($iLotId);
		$iKey = array_search($iParticipantId, $aParticipants);
		
		if ($iKey !== FALSE){
			unset($aParticipants[$iKey]);
			return $this -> savePariticipantsList($iLotId, $aParticipants);
		}
			
		return false;
	}

	/**
	* Get jots list for specified lot
	*@param int $iLotId lot id
	*@param int $iStart jot id from which start to get jots
	*@param string $sMode just posted or old jots from history 
	*@param int $iLimit number of jots to get
	*@return array of the jots
	*/
	public function getJotsByLotId($iLotId, $iStart = 0, $sMode = 'new', $iLimit = 0){
		$sLimit = '';
		$aSWhere[] = "`{$this->CNF['FIELD_MESSAGE_FK']}` = ? ";
		$aWhere[] = (int)$iLotId;
		
		if ($iStart){ 
			$aSWhere[] = "`{$this->CNF['FIELD_MESSAGE_ID']}` " . ($sMode == 'new' ? '>' : '<') . " ? ";
			$aWhere[] = (int)$iStart;
		}

		if ($iLimit){ 
			$sLimit = "LIMIT ?";
			$aWhere[] = (int)$iLimit;
		}	

		if (!empty($aWhere))
			$sWhere = 'WHERE ' . implode(' AND ', $aSWhere);
							
		$aQuery = array("(
						SELECT * FROM `{$this->CNF['TABLE_MESSAGES']}`
						{$sWhere}	
						ORDER BY `{$this->CNF['FIELD_MESSAGE_ADDED']}` DESC
						{$sLimit}
					) ORDER BY `{$this->CNF['FIELD_MESSAGE_ADDED']}` ASC");
					
		$sQuery = call_user_func_array(array($this, 'prepare'), array_merge($aQuery, $aWhere));
		
		return $this -> getAll($sQuery);
	}

	/**
	* Get lot it by jot id 
	*@param int $iJotId jot id
	*@param boolean bIdOnly return id or array with info
	*@return mixed lot id or lot info in array
	*/
	public function getLotByJotId($iJotId, $bIdOnly = true){
		return $bIdOnly ? $this -> getOne("SELECT `{$this->CNF['FIELD_MESSAGE_FK']}`
						FROM `{$this->CNF['TABLE_MESSAGES']}` 
						WHERE `{$this->CNF['FIELD_MESSAGE_ID']}` = :jot LIMIT 1", array('jot' => $iJotId)) :
			   $this -> getRow("SELECT *FROM `{$this->CNF['TABLE_MESSAGES']}` 
						WHERE `{$this->CNF['FIELD_MESSAGE_ID']}` = :jot LIMIT 1", array('jot' => $iJotId));
			
	}

	/**
	* Get the latest posted jot(message)
	*@param int $iLotId lot id
	*@param int $iProfileId if not specified the just latest jot of any member
	*@return array with jot info
	*/
	public function getLatestJot($iLotId, $iProfileId = 0){
		$sWhere = '';
		$aWhere['lot'] = $iLotId;
		
		if ($iProfileId){
			$sWhere = " AND `{$this->CNF['FIELD_MESSAGE_AUTHOR']}` = :profile"; 
			$aWhere['profile'] = $iProfileId;
		}
		
		return $this -> getRow("SELECT *
			FROM `{$this->CNF['TABLE_MESSAGES']}` 
			WHERE  `{$this->CNF['FIELD_MESSAGE_FK']}` = :lot {$sWhere}
			ORDER BY `{$this->CNF['FIELD_MESSAGE_ADDED']}` DESC	
			LIMIT 1", $aWhere);
	}
	
	/**
	* Get the latest posted jot(message)
	*@param int $iLotId lot id
	*@param int $iProfileId if not specified the just latest jot of any member
	*@return array with jot info
	*/
	public function getJotById($iJotId){
		$sQuery = $this -> prepare("SELECT *
			FROM `{$this->CNF['TABLE_MESSAGES']}` 
			WHERE  `{$this->CNF['FIELD_MESSAGE_ID']}` = ?				
			ORDER BY `{$this->CNF['FIELD_MESSAGE_ADDED']}` DESC	
			LIMIT 1", $iJotId);
		
		return $this -> getRow($sQuery);
	}

	/**
	* Get all pages with comments block	
	*@return array list with pages
	*/	
	public function getPagesWithComments(){
		return $this -> getPairs("SELECT * FROM `sys_objects_cmts`", 'Name', 'BaseUrl');
	}

	/**
	* Check if the page already contains messenger block
	*@param $sPage page name 
	*@return boolean
	*/
	public function isBlockAdded($sPage){
		$sPage = bx_process_input($sPage);
		$sQuery = $this -> prepare("SELECT COUNT(*) FROM `sys_pages_blocks` WHERE `object` = ? AND `title` = '_bx_messenger_page_block_title_messenger'", $sPage);
		
		return $this -> getOne($sQuery) == 1;
	}

	/**
	* Add messenger block to the page
	*@param $sPage page name
	*@return affected rows
	*/
	public function addMessengerBlock($sPage){
		$sPage = bx_process_input($sPage);
		$aInfo = $this -> findCommentsBlock($sPage);
		
		if (!empty($aInfo))
				return $this -> query("INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`, `active`) VALUES
							  (:page, :cell, 'bx_messenger', '_bx_messenger_page_block_title_messenger', 0, 2147483647, 'service', 'a:3:{s:6:\"module\";s:12:\"bx_messenger\";s:6:\"method\";s:19:\"get_block_messenger\";s:6:\"params\";a:1:{i:0;s:6:\"{type}\";}}', 0, 0, :order, 0)",
							  array('page' => $sPage, 'cell' => $aInfo['cell_id'], 'order' => $aInfo['order'] + 1));

		return $this -> query("INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`, `active`) VALUES
							  (:page, 1, 'bx_messenger', '_bx_messenger_page_block_title_messenger', 0, 2147483647, 'service', 'a:3:{s:6:\"module\";s:12:\"bx_messenger\";s:6:\"method\";s:19:\"get_block_messenger\";s:6:\"params\";a:1:{i:0;s:6:\"{type}\";}}', 0, 0, 0, 0)",
							   array('page' => $sPage));
	}

	/**
	* Find comments block on the page
	*@param $sPage page name
	*@return array block info
	*/
	function findCommentsBlock($sPage){
		$sPage = bx_process_input($sPage);
		
		$sQuery = $this -> prepare("SELECT * FROM `sys_pages_blocks` WHERE `object` = ? AND `title` LIKE '%comments%'", $sPage);		
		return $this -> getRow($sQuery);
	}

	/**
	* Get all jots of the member
	*@param int $iProfileId
	*@param boolean $bUnread return only unread member
	*@return array list of jots
	*/
	function getMyJots($iProfileId, $bUnread = false){		
		$sWhere = '';
		$aWhere['profile'] = $iProfileId;
		
		if ($bUnread){
			$sWhere = " AND FIND_IN_SET(:parts, `j`.`{$this->CNF['FIELD_MESSAGE_NEW_FOR']}`)";
			$aWhere['parts'] = $iProfileId;
		}

		return $this-> getAll("SELECT `j`.*
			FROM `{$this->CNF['TABLE_ENTRIES']}` as `l`
			LEFT JOIN `{$this->CNF['TABLE_MESSAGES']}` as `j` ON `l`.`{$this->CNF['FIELD_ID']}` = `j`.`{$this->CNF['FIELD_MESSAGE_FK']}` 
			WHERE FIND_IN_SET(:profile, `l`.`{$this->CNF['FIELD_PARTICIPANTS']}`) {$sWhere}
			ORDER BY `j`.`{$this->CNF['FIELD_MESSAGE_ADDED']}` DESC", $aWhere);
	}

	/**
	* Get all member's lots
	*@param int $iProfileId
	*@param int $iType
	*@param string $sParam search keyword
	*@param boolean $bUnread get lots with unread jots only
	*@param int $iLotId lot id 
	*@return array list of lots
	*/
	function getMyLots($iProfileId, $iType = 0, $sParam = '', $bUnread = false, $iLotId = 0){
		$sHaving = $sWhere = '';
		$aSWhere = array();
		$aWhere['parts'] = $aWhere['profile'] = $iProfileId;			
		
		if ($sParam){
			$aSWhere[] = "(`j`.`{$this->CNF['FIELD_MESSAGE']}` LIKE :message OR `l`.`{$this->CNF['FIELD_TITLE']}` LIKE :title)";
			$aWhere['title'] = "%{$sParam}%";
			$aWhere['message'] = "%{$sParam}%";
		}

		if ($iType){
			$aSWhere[] = " `l`.`{$this->CNF['FIELD_TYPE']}` = :type ";
			$aWhere['type'] = $iType;			
		}			
		
		if ($bUnread){
			$sHaving = "HAVING `unread_jot_id` != 0";
		}
		
		if ($iLotId){
			$aSWhere[] = " `l`.`{$this->CNF['FIELD_ID']}` = :id ";
			$aWhere['id'] = $iLotId;	
		}
				
		if (!empty($aSWhere))
				$sWhere = ' AND ' . implode(' AND ', $aSWhere);
					
		return $this-> getAll("SELECT 
			`l`.*,
			`p`.`count` as `unread_num`,
			`p`.`{$this->CNF['FIELD_MESSAGE_ADDED']}` as `last_created`,
			MAX(`j`.`{$this->CNF['FIELD_MESSAGE_ADDED']}`) as `last_jot_created`
			FROM `{$this->CNF['TABLE_ENTRIES']}` as `l`			
			LEFT JOIN `{$this->CNF['TABLE_MESSAGES']}` as `j` ON `l`.`{$this->CNF['FIELD_ID']}` = `j`.`{$this->CNF['FIELD_MESSAGE_FK']}`
			LEFT JOIN (
						SELECT 
							`{$this->CNF['FIELD_MESSAGE_FK']}`,
							COUNT(*) as `count`, 
							MAX(`{$this->CNF['FIELD_MESSAGE_ADDED']}`) as `{$this->CNF['FIELD_MESSAGE_ADDED']}`
						FROM `{$this->CNF['TABLE_MESSAGES']}` 
						WHERE FIND_IN_SET(:parts, `{$this->CNF['FIELD_MESSAGE_NEW_FOR']}`)
						GROUP BY `{$this->CNF['FIELD_MESSAGE_FK']}`
					  ) as `p` ON `p`.`{$this->CNF['FIELD_MESSAGE_FK']}` = `l`.`{$this->CNF['FIELD_ID']}`
			WHERE FIND_IN_SET(:profile, `l`.`{$this->CNF['FIELD_PARTICIPANTS']}`) {$sWhere} 
			GROUP BY `l`.`{$this->CNF['FIELD_ID']}`
			{$sHaving}
			ORDER BY `last_created` DESC, `last_jot_created` DESC", $aWhere);
	}
	
	/**
	* Get member's unread messages number
	*@param int $iProfileId
	*@return int number
	*/
	function getNewMessagesNum($iProfileId){
		$aJots = $this -> getMyJots($iProfileId, true);
		return count($aJots);
	}

	/**
	* Check if the title of the lot type must contain link
	*@param int $iType
	*@return boolean
	*/
	function isLinkedTitle($iType){
		$sQuery = $this -> prepare("SELECT `{$this->CNF['FIELD_TYPE_LINKED']}` FROM `{$this->CNF['TABLE_TYPES']}` WHERE `{$this->CNF['FIELD_TYPE_ID']}` = ? LIMIT 1", $iType);
		return (int)$this -> getOne($sQuery) == 1;	
	}

	/**
	* Get time when member was online  
	*@param int  $iProfileId
	*@return boolean
	*/
	public function lastOnline($iProfileId)
	{
	   $sSql = $this -> prepare("SELECT 
				IF (`ts`.`date` != '', `ts`.`date`, `ta`.`logged`) as `logged` 
			FROM `sys_profiles` AS `tp` 
			INNER JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` 
			LEFT JOIN `sys_sessions` AS `ts` ON `tp`.`account_id`=`ts`.`user_id` 
			WHERE 
				`tp`.`id` = ? AND 
				`ta`.`profile_id`=`tp`.`id`        		 
			LIMIT 1", $iProfileId);
		
		return $this -> getOne($sSql);
	}

	/**
	* Delete all profiles info from lots and jots
	*@param int  $iProfileId
	*@return int affected rows
	*/
	public function deleteProfileInfo($iProfileId){
		$bResult = true;
		
		$aWhere['profile'] = (int)$iProfileId;
			
		$bResult &= $this-> query("DELETE
			FROM `{$this->CNF['TABLE_ENTRIES']}`
			WHERE `{$this->CNF['FIELD_AUTHOR']}`=:profile", $aWhere);

		$bResult &= $this-> query("DELETE
			FROM `{$this->CNF['TABLE_MESSAGES']}` 
			WHERE `{$this->CNF['FIELD_MESSAGE_AUTHOR']}`=:profile", $aWhere);

		$aJots = $this-> getAll("SELECT * 
			FROM `{$this->CNF['TABLE_ENTRIES']}` 
			WHERE FIND_IN_SET(:profile, `{$this->CNF['FIELD_PARTICIPANTS']}`)", $aWhere);
		
		if (empty($aJots)) 
				return $bResult;
		
		foreach($aJots as $iKey => $aJot){
			$bResult &= $this -> removeParticipant($aJot[$this->CNF['FIELD_ID']], $iProfileId);
		}
		
		return $bResult;	
	}

}

/** @} */
