<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import ('BxDolModule');
bx_import ('BxDolAcl');

/**
 * Base module class for text based modules
 */
class BxBaseModTextModule extends BxDolModule
{
    protected $_iProfileId;

    function __construct(&$aModule) 
    {
        parent::__construct($aModule);
        $this->_iProfileId = bx_get_logged_profile_id();
    }

    // ====== ACTIONS METHODS

    public function actionRss ($sMode = '', $sExtra = '') 
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedBrowse())) {
            $this->_oTemplate->displayAccessDenied ();
            exit;
        }

        $aParams = array ();
        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'author':
                $aParams = array('author' => (int)$sExtra);
                break;
        }

        bx_import ('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode, $aParams);

        if ($o->isError) {
            $this->_oTemplate->displayPageNotFound ();
            exit;
        }

        $o->outputRSS();
        exit;
    }

    // ====== SERVICE METHODS

    /**
     * Display pablic entries
     * @return HTML string
     */
    public function serviceBrowsePublic () 
    {
        return $this->_serviceBrowse ('public');
    }

    /**
     * Display featured entries
     * @return HTML string
     */
    public function serviceBrowseFeatured () 
    {
        return $this->_serviceBrowse ('public');
    }

    /**
     * Display entries of the author
     * @return HTML string
     */
    public function serviceBrowseAuthor ($iProfileId = 0) 
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return '';
        return $this->_serviceBrowse ('author', array('author' => $iProfileId));
    }

    /**
     * Add entry form
     * @return HTML string
     */
    public function serviceEntityCreate () 
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->addDataForm();
    }

    /**
     * Edit entry form
     * @return HTML string
     */
    public function serviceEntityEdit ($iContentId = 0) 
    {
        return $this->_serviceEntityForm ('editDataForm', $iContentId);
    }

    /**
     * Delete entry form
     * @return HTML string
     */
    public function serviceEntityDelete ($iContentId = 0) 
    {
        return $this->_serviceEntityForm ('deleteDataForm', $iContentId);
    }

    /**
     * Entry text with some additional controls
     */
    public function serviceEntityTextBlock ($iContentId = 0) 
    {
        return $this->_serviceEntityForm ('viewDataEntry', $iContentId);
    }

    /**
     * Additional entry info
     */
    public function serviceEntityInfo ($iContentId = 0) 
    {
        return $this->_serviceEntityForm ('viewDataForm', $iContentId);
    }

    /**
     * Entry social sharing block
     */
    public function serviceEntitySocialSharing ($iContentId = 0) 
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        $CNF = &$this->_oConfig->CNF;

        bx_import('BxDolPermalinks');
        $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

        $aCustomParams = false;
        if ($aContentInfo[$CNF['FIELD_THUMB']]) {
            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
            if ($oStorage && ($sImgUrl = $oStorage->getFileUrlById($aContentInfo[$CNF['FIELD_THUMB']]))) {
                $aCustomParams = array (
                    'img_url' => $sImgUrl,
                    'img_url_encoded' => rawurlencode($sImgUrl),
                );
            }
        }

        //TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Share, etc.
        $sVotes = '';
        bx_import('BxDolVote');
        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aContentInfo[$CNF['FIELD_ID']]);
        if ($oVotes)
            $sVotes = $oVotes->getElementBlock(array('show_do_vote_as_button' => true)); 

        $sShare = '';
        if (BxDolRequest::serviceExists('bx_timeline', 'get_share_element_block'))
        	$sShare = BxDolService::call('bx_timeline', 'get_share_element_block', array(bx_get_logged_profile_id(), $this->_aModule['name'], 'added', $aContentInfo[$CNF['FIELD_ID']], array('show_do_share_as_button' => true)));

        bx_import('BxTemplSocialSharing');
		$sSocial = BxTemplSocialSharing::getInstance()->getCode($iContentId, $this->_aModule['name'], BX_DOL_URL_ROOT . $sUrl, $aContentInfo[$CNF['FIELD_TITLE']], $aCustomParams);

        return $this->_oTemplate->parseHtmlByName('entry-share.html', array(
        	'vote' => $sVotes,
        	'share' => $sShare,
        	'social' => $sSocial,
        ));
		//TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Share, etc.
    }

    /**
     * Entry comments
     */
    public function serviceEntityComments ($iContentId = 0) 
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

		bx_import('BxDolCmts');
		$oCmts = BxDolCmts::getObjectInstance($this->_oConfig->CNF['OBJECT_COMMENTS'], $iContentId);
		if (!$oCmts || !$oCmts->isEnabled())
			return false;
        return $oCmts->getCommentsBlock(0, 0, false);
    }

    /**
     * Entry author block
     */
    public function serviceEntityAuthor ($iContentId = 0) 
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        return $this->_oTemplate->entryAuthor ($aContentInfo);
    }

    /**
     * Entry actions block
     */
    public function serviceEntityActions ($iContentId = 0) 
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        bx_import('BxTemplMenu');
        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * My entries actions block
     */
    public function serviceMyEntriesActions ($iProfileId = 0) 
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId || $iProfileId != $this->_iProfileId)
            return false;

        bx_import('BxTemplMenu');
        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_MY_ENTRIES']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * Data for Timeline
     */
	public function serviceGetTimelineData()
    {
        return array(
            'handlers' => array(
                array('type' => 'insert', 'alert_unit' => $this->_aModule['name'], 'alert_action' => 'added', 'module_name' => $this->_aModule['name'], 'module_method' => 'get_timeline_post', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('type' => 'update', 'alert_unit' => $this->_aModule['name'], 'alert_action' => 'edited'),
                array('type' => 'delete', 'alert_unit' => $this->_aModule['name'], 'alert_action' => 'deleted')
            ),
            'alerts' => array(
                array('unit' => $this->_aModule['name'], 'action' => 'added'),
                array('unit' => $this->_aModule['name'], 'action' => 'edited'),
                array('unit' => $this->_aModule['name'], 'action' => 'deleted'),
            )
        );
    }

    /**
     * Entry post for Timeline
     */
    public function serviceGetTimelinePost($aEvent)
    {
    	$aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
    	if(empty($aContentInfo) || !is_array($aContentInfo))
    		return '';

        $CNF = &$this->_oConfig->CNF;

    	bx_import('BxDolPermalinks');
		$sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

		//--- Image(s)
	    $sImage = '';
        if($aContentInfo[$CNF['FIELD_THUMB']]) {
        	bx_import('BxDolStorage');
			$oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
            if($oStorage)
				$sImage = $oStorage->getFileUrlById($aContentInfo[$CNF['FIELD_THUMB']]);
        }

        //--- Votes
        bx_import('BxDolVote');
        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aEvent['object_id']);

        $aVotes = array(); 
		if ($oVotes && $oVotes->isEnabled())
			$aVotes = array(
				'system' => $CNF['OBJECT_VOTES'],
				'object_id' => $aContentInfo[$CNF['FIELD_ID']],
				'count' => $aContentInfo['votes']
			);

        //--- Comments
        bx_import('BxDolCmts');
        $oCmts = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $aEvent['object_id']);

        $aComments = array(); 
		if($oCmts && $oCmts->isEnabled())
			$aComments = array(
				'system' => $CNF['OBJECT_COMMENTS'],
				'object_id' => $aContentInfo[$CNF['FIELD_ID']],
				'count' => $aContentInfo['comments']
			);

    	return array(
    		'owner_id' => $aContentInfo[$CNF['FIELD_AUTHOR']],
    		'content' => array(
    			'sample' => _t($CNF['T']['txt_sample_single']),
    			'url' => $sUrl,
    			'title' => $aContentInfo[$CNF['FIELD_TITLE']],
    			'text' => $aContentInfo[$CNF['FIELD_TEXT']],
    			'images' => array(
    				array(
    					'url' => $sUrl,
    					'src' => $sImage
    				)
    			)
    		), //a string to display or array to parse default template before displaying.
    		'votes' => $aVotes,
    		'comments' => $aComments,
    		'title' => '', //may be empty.
    		'description' => '' //may be empty. 
    	);
    }

    // ====== PERMISSION METHODS

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false) 
    {
        $CNF = &$this->_oConfig->CNF;

        // moderator and owner always have access
        if ($aDataEntry[$CNF['FIELD_AUTHOR']] == $this->_iProfileId || $this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'view entry', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        // check privacy 
    	bx_import('BxDolPrivacy');
    	$oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
		if ($oPrivacy && !$oPrivacy->check($aDataEntry[$CNF['FIELD_ID']]))
            return _t('_sys_access_denied_to_private_content');

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedBrowse () 
    {
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedAdd ($isPerformAction = false) 
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'create entry', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false) 
    {
        // moderator and owner always have access
        if ($aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId || $this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;
        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false) 
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'delete entry', $this->getName(), $isPerformAction);
        if ($aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId && $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetThumb () 
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'set thumb', $this->getName(), false);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    // ====== PROTECTED METHODS

    protected function _isModerator ($isPerformAction = false) 
    {
        // check moderator ACL
        $aCheck = checkActionModule($this->_iProfileId, 'edit any entry', $this->getName(), $isPerformAction); 
        return $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED;
    }

    protected function _serviceBrowse ($sMode, $aParams = false) 
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedBrowse()))
            return MsgBox($sMsg);

        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        bx_import('SearchResult', $this->_aModule);
        $o = new $sClass($sMode, $aParams);
        
        $o->setDisplayEmptyMsg(true);

        if ($o->isError)
            return '';

        if ($s = $o->processing())
            return $s;
        else
            return '';
    }

    protected function _serviceEntityForm ($sFormMethod, $iContentId = 0) 
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->$sFormMethod((int)$iContentId);
    }
}

/** @} */ 
