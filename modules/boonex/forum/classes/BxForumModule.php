<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

class BxForumModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function sortParticipants ($aParticipants, $iProfileIdLastComment, $iProfileIdAuthor, $iProfileIdCurrent = 0)
    {
        if (!$iProfileIdCurrent)
            $iProfileIdCurrent = bx_get_logged_profile_id();

        $aMoveUp = array($iProfileIdCurrent, $iProfileIdLastComment, $iProfileIdAuthor);

        asort($aParticipants, SORT_NUMERIC);

        foreach ($aMoveUp as $iProfileId) {
            if (!isset($aParticipants[$iProfileId]))
                continue;

            $a = array($iProfileId => $aParticipants[$iProfileId]);
            unset($aParticipants[$iProfileId]);
            $aParticipants = $a + $aParticipants;
        }

        return $aParticipants;
    }

    /**
     * Action methods
     */
    public function actionUpdateStatus($sAction = '', $iContentId = 0)
    {
    	if(empty($sAction) && bx_get('action') !== false)
   			$sAction = bx_process_input(bx_get('action'));

		if(empty($iContentId) && bx_get('id') !== false)
    		$iContentId = (int)bx_get('id');

    	$aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
			return echoJson(array('code' => 1, 'message' => _t('_bx_forum_err_not_found')));

		$sMethodCheck = 'checkAllowed' . bx_gen_method_name($sAction) . 'AnyEntry';
		$sResult = $this->$sMethodCheck($aContentInfo);
        if($sResult !== CHECK_ACTION_RESULT_ALLOWED)
        	return echoJson(array('code' => 2, 'message' => $sResult));

        if(!$this->_oDb->updateStatus($sAction, $aContentInfo))
        	return echoJson(array('code' => 3, 'message' => _t('_error occured')));

    	echoJson(array('code' => 0, 'id' => $iContentId, 'reload' => 1));
    }

    public function actionAjaxGetAuthors()
    {
        $aResult = BxDolService::call('system', 'profiles_search', array(bx_get('term')), 'TemplServiceProfiles');

        header('Content-Type:text/javascript; charset=utf-8');
        echo json_encode($aResult);
    }

    /**
     * Service methods
     */
	public function serviceBrowseNew ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
    	$sType = 'new';

    	if($sUnitView != 'table')   
        	return $this->_serviceBrowse($sType, $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);

        return $this->_serviceBrowseTable(array('type' => $sType));
    }

	public function serviceBrowseLatest($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
    	$sType = 'latest';

    	if($sUnitView != 'table')
        	return $this->_serviceBrowse($sType, $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);

		return $this->_serviceBrowseTable(array('type' => $sType));
    }

	public function serviceBrowseTop($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
    	$sType = 'top';

    	if($sUnitView != 'table')   
        	return $this->_serviceBrowse($sType, $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);

		return $this->_serviceBrowseTable(array('type' => $sType));
    }

    public function serviceBrowseCategories($bEmptyMessage = true)
    {
    	return $this->_oTemplate->getCategories($bEmptyMessage);
    }

    public function serviceBrowseCategory($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
    	$sType = 'category';
    	$iCategory = bx_process_input(bx_get('category'), BX_DATA_INT);

    	$aCategory = $this->_oDb->getCategories(array('type' => 'by_category', 'category' => $iCategory));
    	if(!empty($aCategory['visible_for_levels']) && !BxDolAcl::getInstance()->isMemberLevelInSet($aCategory['visible_for_levels']))
    		return $bEmptyMessage ? MsgBox(_t('_sys_txt_access_denied')) : '';

    	if($sUnitView != 'table')   
        	return $this->_serviceBrowse($sType, $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);

		return $this->_serviceBrowseTable(array('type' => $sType, 'where' => array(
			array('fld' => 'cat', 'val' => $iCategory, 'opr' => '=')
		)));
    }

    public function serviceBrowseSearchResults($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
    	$sType = 'search';

    	$aAutors = bx_process_input(bx_get('author'));
    	$iCategory = bx_process_input(bx_get('category'), BX_DATA_INT);
    	$sKeyword = bx_process_input(bx_get('keyword'));

    	$aCategory = $this->_oDb->getCategories(array('type' => 'by_category', 'category' => $iCategory));
    	if(!empty($aCategory['visible_for_levels']) && !BxDolAcl::getInstance()->isMemberLevelInSet($aCategory['visible_for_levels']))
    		return $bEmptyMessage ? MsgBox(_t('_sys_txt_access_denied')) : '';

    	if($sUnitView != 'table')   
        	return $this->_serviceBrowse('', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);

        $aWhereGroupAnd = array('grp' => 1, 'opr' => 'AND', 'cnds' => array());
        if(!empty($aAutors) && is_array($aAutors))
        	$aWhereGroupAnd['cnds'][] = array('fld' => 'author', 'val' => $aAutors, 'opr' => 'IN');

        if(!empty($iCategory))
        	$aWhereGroupAnd['cnds'][] = array('fld' => 'cat', 'val' => $iCategory, 'opr' => '=');

        if(!empty($sKeyword)) {
        	$aWhereGroupOr = array('grp' => 1, 'opr' => 'OR', 'cnds' => array(
        		array('fld' => 'title', 'val' => $sKeyword, 'opr' => 'LIKE'),
        		array('fld' => 'text', 'val' => $sKeyword, 'opr' => 'LIKE')
        	));

        	$aEntriesIds = $this->_oDb->getComments(array('type' => 'entries_keyword_search', 'keyword' => $sKeyword));
        	if(!empty($aEntriesIds) && is_array($aEntriesIds))
        		$aWhereGroupOr['cnds'][] = array('fld' => 'id', 'val' => $aEntriesIds, 'opr' => 'IN');

        	$aWhereGroupAnd['cnds'][] = $aWhereGroupOr;
        }

		return $this->_serviceBrowseTable(array('type' => $sType, 'where' => $aWhereGroupAnd), false);
    }

    /**
     * Get number of unread messages for spme profile
     * @param $iProfileId - profile to get unread messages for, if omitted then currently logged is profile is used
     * @return integer
     */
    public function serviceGetUnrepliedDiscussionsNum ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        return $this->_oDb->getUnrepliedDiscussionsNum((int)$iProfileId);
    }

    /**
     * Update last comment time and author
     */
    public function serviceTriggerCommentPost ($iContentId, $iProfileId, $iCommentId, $iTimestamp = 0, $sCommentText = '')
    {
    	$CNT = $this->_oConfig->CNF;

    	$iContentId = (int)$iContentId;
        if(!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
			return false;

        if(!$iTimestamp)
			$iTimestamp = time();

        if(!$this->_oDb->updateLastCommentTimeProfile((int)$iContentId, (int)$iProfileId, $iCommentId, $iTimestamp))
			return false;

        // send notification to author
        if($iProfileId != $aContentInfo[$CNT['FIELD_AUTHOR']]) {
	        $oProfile = BxDolProfile::getInstance($iProfileId);
	        if($oProfile)
                sendMailTemplate('bx_forum_new_reply', 0, $aContentInfo[$CNT['FIELD_AUTHOR']], array(
                    'SenderDisplayName' => $oProfile->getDisplayName(),
                    'SenderUrl' => $oProfile->getUrl(),
                    'Message' => $sCommentText,
                ), BX_EMAIL_NOTIFY);
        }

        return true;
    }

    public function serviceEntityBreadcrumb ($iContentId = 0)
    {
    	if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

		return $this->_oTemplate->entryBreadcrumb($aContentInfo);
    }

    /**
     * Entry collaborators block
     */
    public function serviceEntityParticipants ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        return $this->_oTemplate->entryParticipants ($aContentInfo, 5, 'right');
    }

    /**
     * No social sharing for private discussions
     */
    public function serviceEntitySocialSharing($iContentId = 0)
    {
        return '';
    }

	public function serviceSearch()
    {
    	$CNF = $this->_oConfig->CNF;
    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_SEARCH'], $CNF['OBJECT_FORM_SEARCH_DISPLAY_FULL'], $this->_oTemplate);
    	$oForm->initChecker();

        return $oForm->getCode();
    }

    /**
     * No thumbs for discussions
     */
    public function checkAllowedSetThumb()
    {
        return _t('_sys_txt_access_denied');
    }

    public function checkAllowedStickAnyEntry($aDataEntry, $isPerformAction = false)
    {
    	$CNF = $this->_oConfig->CNF;

    	if((int)$aDataEntry[$CNF['FIELD_STICK']] != 0)
    		return false;

		return $this->_checkAllowedAction('stick any entry', $aDataEntry, $isPerformAction);
    }

    public function checkAllowedUnstickAnyEntry($aDataEntry, $isPerformAction = false)
    {
    	$CNF = $this->_oConfig->CNF;

    	if((int)$aDataEntry[$CNF['FIELD_STICK']] != 1)
			return false;

    	return $this->_checkAllowedAction('stick any entry', $aDataEntry, $isPerformAction);
    }

    public function checkAllowedLockAnyEntry($aDataEntry, $isPerformAction = false)
    {
    	$CNF = $this->_oConfig->CNF;

    	if((int)$aDataEntry[$CNF['FIELD_LOCK']] != 0)
    		return false;

		return $this->_checkAllowedAction('lock any entry', $aDataEntry, $isPerformAction);
    }

	public function checkAllowedUnlockAnyEntry($aDataEntry, $isPerformAction = false)
    {
    	$CNF = $this->_oConfig->CNF;

    	if((int)$aDataEntry[$CNF['FIELD_LOCK']] != 1)
    		return false;

		return $this->_checkAllowedAction('lock any entry', $aDataEntry, $isPerformAction);
    }

    public function checkAllowedHideAnyEntry($aDataEntry, $isPerformAction = false)
    {
    	$CNF = $this->_oConfig->CNF;

    	if($aDataEntry[$CNF['FIELD_STATUS_ADMIN']] == 'hidden')
    		return false;

		return $this->_checkAllowedAction('hide any entry', $aDataEntry, $isPerformAction);
    }

	public function checkAllowedUnhideAnyEntry($aDataEntry, $isPerformAction = false)
    {
    	$CNF = $this->_oConfig->CNF;

    	if($aDataEntry[$CNF['FIELD_STATUS_ADMIN']] != 'hidden')
    		return false;

		return $this->_checkAllowedAction('hide any entry', $aDataEntry, $isPerformAction);
    }

	/**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    protected function _checkAllowedAction($sAction, $aDataEntry, $isPerformAction = false)
    {
        if($this->_isModerator($isPerformAction))
			return CHECK_ACTION_RESULT_ALLOWED;

		$aCheck = checkActionModule($this->_iProfileId, $sAction, $this->getName(), $isPerformAction);
    	if($aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
    		return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

	protected function _serviceBrowseTable($aParams, $isDisplayHeader = true)
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->CNF['OBJECT_GRID']);
        if(!$oGrid)
			return false;

		$oGrid->setBrowseParams($aParams);

        return $oGrid->getCode($isDisplayHeader);
    }
}

/** @} */
