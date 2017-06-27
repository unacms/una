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

define('BX_IM_TYPE_PUBLIC', 1);
define('BX_IM_TYPE_PRIVATE', 2);
define('BX_IM_TYPE_SETS', 3);
define('BX_IM_TYPE_GROUPS', 4);
define('BX_IM_TYPE_EVENTS', 5);
define('BX_IM_EMPTY_URL', '');
define('BX_IM_EMPTY', 0);

/**
 * Messenger module
 */
class BxMessengerModule extends BxBaseModTextModule
{
	private $_iUserId = 0;   
	function __construct(&$aModule)
	{
		parent::__construct($aModule);
		$this -> _iUserId = bx_get_logged_profile_id();
	}
	/**
	* Returns left side block for messenger page and loads config data
	*/
	public function serviceGetBlockInbox(){
		if (!$this -> isLogged()) return '';	   
		$iProfile = bx_get('profile');
		$iProfile = $iProfile == $this -> _iUserId ? 0 : $iProfile;
		return	$this -> _oTemplate -> getLotsColumn($this -> _iUserId, (int)$iProfile).
				$this -> _oTemplate -> loadConfig($this -> _iUserId);
	}
	/**
	* Returns right side block for messenger page
	*/
	public function serviceGetBlockLot(){
		if (!$this -> isLogged()) return '';
		$iProfile = bx_get('profile');
		$iProfile = $iProfile == $this -> _iUserId ? 0 : $iProfile;	   
		return $this -> _oTemplate -> getLotWindow($iProfile, BX_IM_EMPTY, true);
	}
	/**
	* Returns block with messenger for any page
	*@param string $sModule module name
	*/
	public function serviceGetBlockMessenger($sModule){
		if (!$this -> isLogged()) return '';
		   
		$this->_oTemplate-> loadCssJs('view');
		$aLotInfo = $this -> _oDb -> getLotByUrl($_SERVER['REQUEST_URI']);
		if (empty($aLotInfo) && $sModule)
			$aLotInfo = $this -> _oDb -> getLotByClass($sModule);
	   
		return	$this -> _oTemplate -> loadConfig($this -> _iUserId).
				$this -> _oTemplate -> getTalkBlock($this -> _iUserId, !empty($aLotInfo) ?
					(int)$aLotInfo[$this -> _oConfig -> CNF['FIELD_ID']] :
					BX_IM_EMPTY, $this -> _oConfig -> getTalkType($sModule), true /* create messenger window even if chat doesn't exist yet */);
	}
   
	/**
	* Adds messenger block to all pages with comments and trigger pages during installation
	*/
	public function serviceAddMessengerBlocks(){
		if (!isAdmin()) return '';
	   
		$aPages = $this -> _oDb -> getPagesWithComments();
	   
		$aUrl = array();
		foreach($aPages as $sModule => $sPage){
			$sParams = parse_url($sPage, PHP_URL_QUERY);
			if (!empty($sParams))
					parse_str($sParams, $aUrl);
			   
			if (isset($aUrl['i'])){
				$sPage = BxDolPageQuery::getPageObjectNameByURI($aUrl['i']);   
				if (!$this -> _oDb -> isBlockAdded($sPage))
						$this -> _oDb -> addMessengerBlock($sPage);
			}
		}	   
	}

	/**
	* Builds main messenger page
	*/   
	public function actionMain()
	{
		if (!$this -> isLogged())
			bx_login_form();
	  
		$oTemplate = BxDolTemplate::getInstance();
		$oPage = BxDolPage::getObjectInstance('bx_messenger_main');

		if (!$oPage) {
			$this->_oTemplate->displayPageNotFound();
			exit;
		}
	   
		$s = $oPage->getCode();

		$this->_oTemplate = BxDolTemplate::getInstance();
		$this->_oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
		$this->_oTemplate->setPageContent ('page_main_code', $s);
		$this->_oTemplate->getPageCode();
	}
   
	/**
	* Create List of participants received from request (POST, GET)
	* @param mixed $mixedPartisipants participants list
	* @return array  participants list
	*/
	private function getParticipantsList($mixedPartisipants){
		if (empty($mixedPartisipants)) return array();
		$aParticipants = is_array($mixedPartisipants) ? $mixedPartisipants : array(intval($mixedPartisipants));
		$aParticipants[] = $this -> _iUserId;
		return array_unique($aParticipants, SORT_NUMERIC);
	}   
   
	/**
	* Send function occurs when member posts a message
	* @return array json result
	*/
	function actionSend(){	   
		$sUrl =	$sTitle = '';
		$sMessage = trim(bx_get('message'));
		$iLotId = (int)bx_get('lot');	   
		$iType = bx_get('type');
		$iTmpId = bx_get('tmp_id');

		if (!$this -> isLogged() || !$sMessage){
			return echoJson(array('code' => 1, 'message' => _t('_bx_messenger_send_message_only_for_logged')));
		};
	   
		$iType = $this -> _oDb -> isLotType($iType) ? $iType : BX_IM_TYPE_PUBLIC;	   
		if ($iType != BX_IM_TYPE_PRIVATE){
			$sUrl = bx_get('url');
			$sTitle = bx_get('title');
		}	   
	   
		// prepare participants list
		$aParticipants = $this -> getParticipantsList(bx_get('participants'));	   
		if (!$iLotId && empty($aParticipants) && $iType == BX_IM_TYPE_PRIVATE)
			return echoJson(array('code' => 1));
	   
		if ($sMessage){		   
			$sMessage = html2txt($sMessage, '<br>');
			$sMessage = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $sMessage);
			$sMessage = strmaxtextlen($sMessage, $this->_oConfig-> CNF['MAX_SEND_SYMBOLS']);		   

			 if ($iType != BX_IM_TYPE_PRIVATE && $sUrl)
					$sUrl = $this -> getPreparedUrl($sUrl);
		   
		} else
			return echoJson(array('code' => 1, 'message' => _t('_bx_messenger_send_message_no_data')));
	   
		$aResult = array('code' => 0);
		if ($sMessage && ($iId = $this -> _oDb -> saveMessage(array(
												'message'	=> $sMessage,
												'type'		=> $iType,
												'member_id' => $this -> _iUserId,
												'url' => $sUrl,
												'title'	=> $sTitle,
												'lot' => $iLotId
											), $aParticipants))){
		   
			if (!$iLotId)
					$aResult['lot_id'] = $this -> _oDb -> getLotByJotId($iId);
		   
			$aResult['jot_id'] =  $iId;
			$aResult['tmp_id'] =  $iTmpId;		   
		}
		else
			$aResult = array('code' => 1, 'message' => _t('_bx_messenger_send_message_save_error'));
	   
		BxDolSession::getInstance()-> exists($this -> _iUserId);			   
		echoJson($aResult);
	}
   
	/**
	* Loads talk to the right side block when member choose conversation or when open messenger page
	* @return array with json result
	*/
	public function actionLoadTalk(){	   
		$iId = (int)bx_get('id');
	   
		if (!$this -> isLogged() || !$iId || !$this -> _oDb -> isParticipant($iId, $this -> _iUserId)){
			return echoJson(array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_logged'))));
		};
	   
		$this -> _oDb -> readAllMessages($iId, $this -> _iUserId);
		$sContent = $this -> _oTemplate -> getTalkBlock($this -> _iUserId, $iId);
	   
		echoJson(array('code' => 0, 'html' =>  $sContent));
	}
   
	/**
	* Loads messages for  specified lot(conversation)
	* @return array with json
	*/
	function actionLoadJots(){	   
		if (!$this -> isLogged())
			return echoJson(array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_logged'))));
	   
		$iId = (int)bx_get('id');
	   
		if (!$this -> isLogged())
			return echoJson(array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_logged'))));
	   
		if (!$iId)
			$sContent = $this -> _oTemplate -> getPostBoxWithHistory($this -> _iUserId, BX_IM_EMPTY, BX_IM_TYPE_PRIVATE);
		else
			$sContent = $this -> _oTemplate -> getPostBoxWithHistory($this -> _iUserId, $iId, BX_IM_TYPE_PRIVATE);
	   
		echoJson(array('code' => 0, 'html' =>  $sContent));
	}
   
	/**
	* Search for Lots by keywords in the right side block
	* @return array with json 
	*/
	function actionSearch(){	   
		if (!$this -> isLogged())
			return echoJson(array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_logged'))));
	   
		$sParam = bx_get('param');
		$iType = bx_get('type');
	   
		$aMyLots = $this -> _oDb -> getMyLots($this -> _iUserId, $iType, $sParam);
		if (empty($aMyLots))
				$sContent  = MsgBox(_t('_bx_messenger_txt_msg_no_results'));
			else	   
				$sContent = $this -> _oTemplate -> getLotsPreview($this -> _iUserId, $aMyLots);
			   
		echoJson(array('code' => 0, 'html' =>  $sContent));
	}
   
	/**
	* Search for Lots by keywords in the right side block
	* @return array with json 
	*/
	public function actionUpdateLotBrief(){
		if (!$this -> isLogged())
			return echoJson(array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_logged'))));
	   
		$iLotId = (int)bx_get('lot_id');
	   
		if (!$this -> isLogged() || !$iLotId)
				return echoJson(array('code' => 1, 'html' => ''));
	   
		$aMyLots = $this -> _oDb -> getMyLots($this -> _iUserId, 0, '', false, $iLotId);
		$sContent = $this -> _oTemplate -> getLotsPreview($this -> _iUserId, $aMyLots);			   
		echoJson(array('code' => 0, 'html' =>  $sContent));	   
	}
   
	/**
	* Prepare url for Lot title if if was created on separated page
	* @param string URL
	* @return string URL
	*/
	private function getPreparedUrl($sUrl){
		if (!$sUrl) return false;
		$aUrl = parse_url($sUrl);
		return strtolower($aUrl['path'] . (isset($aUrl['query']) ? '?' . $aUrl['query'] : ''));
	}
   
	/**
	* Loads messages for  lot(conversation) (when member wants to view history or get new messages from participants)
	* @return array with json
	*/
	function actionUpdate(){	   
		if (!$this -> isLogged())
			return echoJson(array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_logged'))));
			   
		$sUrl = bx_get('url');
		$iStart = (int)bx_get('start');
		$iLotId = (int)bx_get('lot');
		$sLoad = bx_get('load');
	   
		if ($sLoad == 'new' && $iStart == 0){
			$aMyLatestJot = $this -> _oDb -> getLatestJot($iLotId, $this -> _iUserId);

			if (empty($aMyLatestJot))
				return echoJson(array('code' => 1));
			else
				$iStart = (int)$aMyLatestJot[$this -> _oConfig -> CNF['FIELD_MESSAGE_ID']];
		}   
		   
	   
		if ($sUrl)
			$sUrl = bx_get('url') ? $this -> getPreparedUrl(bx_get('url')) : '';
				   
		$sContent = $this -> _oTemplate -> getJotsOfLot($this -> _iUserId, $iLotId, $sUrl, $iStart, $sLoad);
	   
		$aResult = array('code' => 1);
		if (!$sContent)
			$aResult = array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_found')));
		else
			$aResult = array('code' => 0, 'html' => $sContent);
	   
		// update session
		BxDolSession::getInstance()-> exists($this -> _iUserId);	   
		echoJson($aResult);
	}
   
	/**
	* Occurs when member wants to create new conversation(lot)
	* @return array with json
	*/
	public function actionCreateLot(){	   
		if (!$this -> isLogged())
			return echoJson(array('code' => 1, 'html' => MsgBox(_t('_bx_messenger_not_logged'))));
	   
		$iProfileId = (int)bx_get('profile');
		$iLot = (int)bx_get('lot');
		echoJson(array('html' => $this -> _oTemplate -> getLotWindow($iProfileId, $iLot, false)));
	}
   
	/**
	* Occurs when member adds or edit participants list for new of specified lot
	* @return array with json
	*/
	public function actionGetAutoComplete(){
		$aUsers = BxDolService::call('system', 'profiles_search', array(bx_get('term'), 5), 'TemplServiceProfiles');
		if (empty($aUsers)) return array();

		$iProfile = $this -> _iUserId;	   
		foreach($aUsers as $iKey => $aValue){
				if ((int)$aValue['value'] == $this -> _iUserId) continue;
			   
				$oProfile = BxDolProfile::getInstance($aValue['value']);
				if ($oProfile)
					$aResult[] = array(   
							'value' => $oProfile -> getDisplayName(),
							'icon' => $oProfile -> getThumb(),
							'id' => $oProfile -> id(),
					);			   
		}
			   
		echoJson($aResult);
	}
   
	/**
	* Search for lot by participants list and occurs when member edit participants list
	* @return array with json
	*/
	public function actionFindLot(){
		$aParticipants = $this -> getParticipantsList(bx_get('participants'));

		$aResult = array('lotId' => 0);
		if (!empty($aParticipants) && ($aChat = $this -> _oDb -> getLotByUrlAndPariticipantsList(BX_IM_EMPTY_URL, $aParticipants, BX_IM_TYPE_PRIVATE)))
				$aResult['lotId'] = $aChat[$this -> _oConfig -> CNF['FIELD_ID']];
		   
		echoJson($aResult);
	}

	/**
	* Updats participants list (occurs when create new lost with specified participants or update already existed list)
	* @return array with json
	*/
	public function actionSaveLotsParts(){
		$iLotId = bx_get('lot');   
		$aParticipants = $this -> getParticipantsList(bx_get('participants'));

		$aResult = array('message' => _t('_bx_messenger_save_part_failed'), 'code' => 1);
		if (!$iLotId || !$this -> _oDb -> isAuthor($iLotId, $this -> _iUserId) || empty($aParticipants)){
			return echoJson($aResult);
		}

		if ($this -> _oDb -> savePariticipantsList($iLotId, $aParticipants))
				$aResult = array('message' => _t('_bx_messenger_save_part_success'), 'code' => 0);
		   
		echoJson($aResult);
	}
   
	/**
	* Removes specefied lot
	* @return array with json
	*/
	public function actionDelete(){
		$iLotId = bx_get('lot');	   
		$aResult = array('message' => _t('_bx_messenger_can_not_delete'), 'code' => 1);

		if (!$iLotId || !($this -> _oDb -> isAuthor($iLotId, $this -> _iUserId) || isAdmin())){
			return echoJson($aResult);
		}

		if ($this -> _oDb -> deleteLot($iLotId))
				$aResult = array('message' => _t('_bx_messenger_delete_success'), 'code' => 0);
		   
		echoJson($aResult);
	}
 
	  /**
	* Remove member from participants list
	* @return array with json
	*/
	public function actionLeave(){
		$iLotId = bx_get('lot');	   

		if (!$iLotId || !$this -> _oDb -> isParticipant($iLotId, $this -> _iUserId)){
			return echoJson(array('message' => _t('_bx_messenger_not_participant'), 'code' => 1));
		}

		if ($this -> _oDb -> isAuthor($iLotId, $this -> _iUserId))
			return echoJson(array('message' => _t('_bx_messenger_cant_leave'), 'code' => 1));


		if ($this -> _oDb -> leaveLot($iLotId, $this -> _iUserId))
			return echoJson(array('message' => _t('_bx_messenger_successfully_left'), 'code' => 0));   
	}

	/**
	* Block notifications from specified lot(conversation)
	* @return array with json
	*/
	public function actionMute(){
		$iLotId = bx_get('lot');	   

		$aResult = array('code' => 1);   
		if (!$iLotId || !$this -> _oDb -> isParticipant($iLotId, $this -> _iUserId)){
			return echoJson($aResult);
		}

		if ($this -> _oDb -> muteLot($iLotId, $this -> _iUserId))
					$aResult = array('code' => 0);
		   
		echoJson($aResult);
	}

	/**
	* Returns number of unread messages for specified lot for logged member
	* @return int
	*/   
	function serviceGetNewMessagesNum(){   
		if (!$this -> isLogged()) return 0;
		return $this -> _oDb -> getNewMessagesNum($this -> _iUserId);
	}
   
	/**
	* Sends push notifications for participants of specified lot
	* @return boolean TRUE on success or FALSE on failure
	*/
	public function actionSendPushNotification(){
		$iLotId = (int)bx_get('lot');
		$aSent = is_array(bx_get('sent')) ? bx_get('sent') : array();

		if (!$this -> isLogged() || !$this->_oConfig-> CNF['IS_PUSH_ENABLED'] || !$iLotId || !$this -> _oDb -> isParticipant($iLotId, $this -> _iUserId)) return false;
			   
		$aLot = $this -> _oDb -> getLotInfoById($iLotId, false);	   
		if (empty($aLot)) return false;	   
		   
		$aParticipantList = $this -> _oDb -> getParticipantsList($aLot[$this -> _oConfig -> CNF['FIELD_ID']], true, $this -> _iUserId);
		if (empty($aParticipantList)) return false;
	   
		$oLanguage = BxDolStudioLanguagesUtils::getInstance();
		$sLanguage = $oLanguage->getCurrentLangName(false);

		$aLatestJot = $this -> _oDb -> getLatestJot($iLotId, $this -> _iUserId);

		$aContent = array(
			 $sLanguage => strmaxtextlen($aLatestJot[$this -> _oConfig -> CNF['FIELD_MESSAGE']], (int)$this->_oConfig->CNF['PARAM_PUSH_NOTIFICATIONS_DEFAULT_SYMBOLS_NUM'])
		);	   

		$oProfile = BxDolProfile::getInstance($this -> _iUserId);						   
		if($oProfile)
			$aHeadings = array(
				 $sLanguage => _t('_bx_messenger_push_message_title', $oProfile -> getDisplayName())
			);
		else
			return false;
	   
		$aWhere = array();
		foreach($aParticipantList as $iKey => $iValue){   
			if (array_search($iValue, $aSent) !== FALSE || $this -> _oDb -> isMuted($aLot[$this -> _oConfig -> CNF['FIELD_ID']], $iValue)) continue;
			$aWhere[] = array("field" => "tag", "key" => "user", "relation" => "=", "value" => $iValue);
			$aWhere[] = array("operator" => "OR");		   
		}   
		   
		unset($aWhere[count($aWhere) - 1]);

		$aFields = array(
			'app_id' => $this->_oConfig-> CNF['PUSH_APP_ID'],
			'filters' => $aWhere,
			'contents' => $aContent,
			'headings' => $aHeadings,
		);
	   
		$aFields = json_encode($aFields);
	   
		$oCh = curl_init();
		curl_setopt($oCh, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($oCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic ' . $this->_oConfig-> CNF['PUSH_REST_API']));
		curl_setopt($oCh, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($oCh, CURLOPT_HEADER, FALSE);
		curl_setopt($oCh, CURLOPT_POST, TRUE);
		curl_setopt($oCh, CURLOPT_POSTFIELDS, $aFields);
		curl_setopt($oCh, CURLOPT_SSL_VERIFYPEER, FALSE);

		$sResult = curl_exec($oCh);
		curl_close($oCh);
	   
		return $sResult;
	}   

	/**
	* Creates template with member's avatar, name and etc... It is used when member posts a message to add message to member history immediately
	* @return json
	*/
	function actionLoadMembersTemplate(){   
		if (!$this -> isLogged()) return '';	   
		echoJson(array('data' => $this -> _oTemplate -> getMembersJotTemplate($this -> _iUserId)));
	}

	/**
	 * Delete all content by profile ID
	 * @param object oAlert
	 * @return boolean
	*/
   
	function serviceDeleteHistoryByAuthor($oAlert){	   
		return $oAlert -> iObject ? $this -> _oDb -> deleteProfileInfo($oAlert -> iObject) : false;
	}

}

/** @} */
