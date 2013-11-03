<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import ('BxDolModule');
bx_import ('BxDolAcl');

/**
 * Persons module
 *
 * Basic person profiles.
 */
class BxPersonsModule extends BxDolModule implements iBxDolProfileService {

    protected $_iProfileId;
    protected $_iAccountId;

    function __construct(&$aModule) {
        parent::__construct($aModule);
        $this->_iProfileId = bx_get_logged_profile_id();
        $this->_iAccountId = getLoggedId();
    }

    // ====== SERVICE METHODS


    public function serviceProfileUnit ($iContentId) {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;        
        return $this->_oTemplate->unit($aContentInfo);
    }

    public function serviceProfileThumb ($iContentId) {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;        
        return $this->_oTemplate->thumb($aContentInfo);
    }

    public function serviceProfileIcon ($iContentId) {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;        
        return $this->_oTemplate->icon($aContentInfo);
    }

    public function serviceProfileName ($iContentId) {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;        
        return $aContentInfo[BxPersonsConfig::$FIELD_NAME];
    }

    public function serviceProfileUrl ($iContentId) {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-persons-profile&id=' . $aContentInfo['id']);
    }

    public function serviceBrowseRecentPersons () {
        return $this->_serviceBrowse ('recent');
    }

    public function serviceBrowseConnections ($iProfileId, $sObjectConnections = 'sys_profiles_friends', $sConnectionsType = 'content', $iMutual = false, $iDesignBox = BX_DB_PADDING_DEF) {
        return $this->_serviceBrowse (
            'connections', 
            array(
                'object' => $sObjectConnections,
                'type' => $sConnectionsType,
                'mutual' => $iMutual,
                'profile' => (int)$iProfileId),  
            $iDesignBox
        );
    }

    public function _serviceBrowse ($sMode, $aParams = false, $iDesignBox = BX_DB_PADDING_DEF) {
        bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode, $aParams);

        $o->setDesignBoxTemplateId($iDesignBox);

        if ($o->isError)
            return false;

        if ($s = $o->processing())
            return $s;
        else
            return false;
    }

    public function serviceCreateProfile () {
        bx_import('ProfileForms', $this->_aModule);
        $oProfileForms = new BxPersonsProfileForms($this);
        return $oProfileForms->addDataForm();
    }

    public function serviceEditProfile ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('ProfileForms', $this->_aModule);
        $oProfileForms = new BxPersonsProfileForms($this);
        return $oProfileForms->editDataForm((int)$iContentId);
    }

    public function serviceEditCover ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('ProfileForms', $this->_aModule);
        $oProfileForms = new BxPersonsProfileForms($this);
        return $oProfileForms->editDataForm((int)$iContentId, 'bx_person_edit_cover');
    }

    public function serviceDeleteProfile ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('ProfileForms', $this->_aModule);
        $oProfileForms = new BxPersonsProfileForms($this);
        return $oProfileForms->deleteDataForm((int)$iContentId);
    }

    public function serviceProfileInfo ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('ProfileForms', $this->_aModule);
        $oProfileForms = new BxPersonsProfileForms($this);
        return $oProfileForms->viewDataForm((int)$iContentId);
    }

    public function serviceProfileCover ($iContentId = 0) {
       if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        return $this->_oTemplate->cover($aContentInfo);
    }

    public function serviceProfileFriends ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        
        return 'TODO: friends here';
    }

    // ====== ACTION METHODS

    function actionBrowse ($sMode = '') {

        $sMode = bx_process_input($sMode);

/*
        if ('user' == $sMode || 'my' == $sMode) {
            $aProfile = getProfileInfo ($this->_iProfileId);
            if (0 == strcasecmp($sValue, $aProfile['NickName']) || 'my' == $sMode) {
                $this->_browseMy ($aProfile);
                return;
            }
        }

        if ('tag' == $sMode || 'category' == $sMode)
            $sValue = uri2title($sValue);
*/

        if (CHECK_ACTION_RESULT_ALLOWED !== $this->isAllowedBrowse()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        bx_import ('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode);

        if ($o->isError) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        if (bx_get('rss')) {
            echo $o->rss();
            exit;
        }

        if (!($s = $o->processing())) {
            $this->_oTemplate->displayNoData ();
            return;
        }

        // TODO: remale to use "pages"

        $this->_oTemplate->addCss ('main.css'); 

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageHeader ($o->aCurrent['title']);
        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        $oTemplate->setPageContent ('page_main_code', $s);
        $oTemplate->getPageCode();
    }

    // ====== PERMISSION METHODS

    function _checkModeratorAccess ($isPerformAction = false) {
        // check moderator ACL
        $aCheck = checkActionModule($this->_iProfileId, 'edit any person profile', $this->getName(), $isPerformAction); 
        return $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    function isAllowedView ($aDataEntry, $isPerformAction = false) {

        // moderator and owner always have access
        if ($aDataEntry[BxPersonsConfig::$FIELD_AUTHOR] == $this->_iProfileId || $this->_checkModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'view person profile', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        // TODO: check privacy

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    function isAllowedBrowse () {
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedAdd ($isPerformAction = false) {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'create person profile', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedEdit ($aDataEntry, $isPerformAction = false) {
        // moderator always has access
        if ($this->_checkModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // owner (checked by account! not as profile as ususal) always have access
        bx_import('BxDolProfile');
        $oProfileAurhor = BxDolProfile::getInstance($aDataEntry[BxPersonsConfig::$FIELD_AUTHOR]);
        if ($oProfileAurhor->getAccountId() == $this->_iAccountId)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * Check if user can change cover image
     */
    function isAllowedChangeCover ($aDataEntry, $isPerformAction = false) {
        // moderator always has access
        if ($this->_checkModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // owner (checked by account! not as profile as ususal) always have access
        bx_import('BxDolProfile');
        $oProfileAurhor = BxDolProfile::getInstance($aDataEntry[BxPersonsConfig::$FIELD_AUTHOR]);
        if ($oProfileAurhor->getAccountId() == $this->_iAccountId)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedDelete (&$aDataEntry, $isPerformAction = false) {
        // moderator always has access
        if ($this->_checkModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL and owner (checked by account! not as profile as ususal)
        bx_import('BxDolProfile');
        $oProfileAurhor = BxDolProfile::getInstance($aDataEntry[BxPersonsConfig::$FIELD_AUTHOR]);

        $aCheck = checkActionModule($this->_iProfileId, 'delete person profile', $this->getName(), $isPerformAction);
        if ($oProfileAurhor->getAccountId() == $this->_iAccountId && $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedFriendAdd (&$aDataEntry, $isPerformAction = false) {
        return $this->_isAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedFriendRemove (&$aDataEntry, $isPerformAction = false) {
        return $this->_isAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', true, true);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedSubscribeAdd (&$aDataEntry, $isPerformAction = false) {
        return $this->_isAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_subscriptions', false, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedSubscribeRemove (&$aDataEntry, $isPerformAction = false) {
        return $this->_isAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_subscriptions', false, true);
    }

    function _isAllowedConnect (&$aDataEntry, $isPerformAction, $sObjConnection, $isMutual, $isInvertResult) {
        if (!$this->_iProfileId)
            return _t('_sys_txt_access_denied');

        $oProfileAuthor = BxDolProfile::getInstance($aDataEntry[BxPersonsConfig::$FIELD_AUTHOR]);
        $oProfile = $oProfileAuthor ? BxDolProfile::getInstanceByContentTypeAccount($aDataEntry[BxPersonsConfig::$FIELD_ID], 'bx_persons', $oProfileAuthor->getAccountId()) : false;
        if (!$oProfile || $oProfile->id() == $this->_iProfileId)
            return _t('_sys_txt_access_denied');

        bx_import('BxDolConnection');
        $oConn = BxDolConnection::getObjectInstance($sObjConnection);
        $isConnected = $oConn->isConnected($this->_iProfileId, $oProfile->id(), $isMutual);

        if ($isInvertResult)
            $isConnected = !$isConnected;

        return $isConnected ? _t('_sys_txt_access_denied') : CHECK_ACTION_RESULT_ALLOWED;
    }
}

/** @} */ 
