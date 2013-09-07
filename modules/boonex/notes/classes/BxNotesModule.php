<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import ('BxDolModule');
bx_import ('BxDolAcl');

/**
 * Notes module
 */
class BxNotesModule extends BxDolModule {

    protected $_iProfileId;

    function __construct(&$aModule) {
        parent::__construct($aModule);
        $this->_iProfileId = bx_get_logged_profile_id();
    }

    // ====== SERVICE METHODS

    public function serviceBrowseRecentNotes () {
        return $this->_serviceBrowse ('recent');
    }

    public function _serviceBrowse ($sMode, $aParams = false) {
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        bx_import('SearchResult', $this->_aModule);
        $o = new $sClass($sMode, $aParams);

        if ($o->isError)
            return false;

        if ($s = $o->processing())
            return $s;
        else
            return false;
    }

    public function serviceEntityCreate () {
        bx_import('NoteForms', $this->_aModule);
        $oProfileForms = new BxNotesNoteForms($this);
        return $oProfileForms->addDataForm();
    }

    /**
     * @return edit note form string
     */
    public function serviceEntityEdit ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('NoteForms', $this->_aModule);
        $oProfileForms = new BxNotesNoteForms($this);
        return $oProfileForms->editDataForm((int)$iContentId);
    }

    /**
     * @return edit note form block
     */
    public function serviceEntityEditBlock ($iContentId = 0) {
        $s = $this->serviceEntityEdit($iContentId);
        return array('content' => $s, 'menu' => 'bx_notes_view');
    }

    /**
     * @return delete note form string
     */
    public function serviceEntityDelete ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('NoteForms', $this->_aModule);
        $oProfileForms = new BxNotesNoteForms($this);
        return $oProfileForms->deleteDataForm((int)$iContentId);
    }

    /**
     * @return delete note form block
     */
    public function serviceEntityDeleteBlock ($iContentId = 0) {
        $s = $this->serviceEntityDelete($iContentId);
        return array('content' => $s, 'menu' => 'bx_notes_view');
    }

    /**
     * Just a note's text
     */
    public function serviceEntityText ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('NoteForms', $this->_aModule);
        $oProfileForms = new BxNotesNoteForms($this);
        return $oProfileForms->viewDataText((int)$iContentId);
    }

    /**
     * A note's text with some additional controls, like menu
     */
    public function serviceEntityTextBlock ($iContentId = 0) {
        $s = $this->serviceEntityText($iContentId);
        return array('content' => $s, 'menu' => 'bx_notes_view');
    }

    public function serviceEntityInfo ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        bx_import('NoteForms', $this->_aModule);
        $oProfileForms = new BxNotesNoteForms($this);
        return $oProfileForms->viewDataForm((int)$iContentId);
    }

    public function serviceEntitySocialSharing ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        bx_import('BxDolPermalinks');
        $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=view-note&id=' . $aContentInfo['id']);

        $aCustomParams = false;
        if ($aContentInfo[BxNotesConfig::$FIELD_THUMB]) {
            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance(BxNotesConfig::$OBJECT_STORAGE);
            if ($oStorage && ($sImgUrl = $oStorage->getFileUrlById($aContentInfo[BxNotesConfig::$FIELD_THUMB]))) {
                $aCustomParams = array (
                    'img_url' => $sImgUrl,
                    'img_url_encoded' => rawurlencode($sImgUrl),
                );
            }
        }

        bx_import('BxTemplSocialSharing');
        return BxTemplSocialSharing::getInstance()->getCode($sUrl, $aContentInfo['title'], $aCustomParams);
    }

    public function serviceEntityComments ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        return "TODO: Comments here";
    }

    public function serviceEntityAuthor ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        $oProfile = BxDolProfile::getInstance($aContentInfo[BxNotesConfig::$FIELD_AUTHOR]);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }
        return $oProfile->getUnit();
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

        if (CHECK_ACTION_RESULT_ALLOWED != $this->isAllowedBrowse()) {
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

        // TODO: remake to use "pages"

        $this->_oTemplate->addCss ('main.css'); 

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageHeader ($o->aCurrent['title']);
        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        $oTemplate->setPageContent ('page_main_code', $s);
        $oTemplate->getPageCode();
    }

    // ====== PERMISSION METHODS

    function _checkModeratorAccess ($isPerformAction = false) {
        // check moderator ACnoteL
        $aCheck = checkActionModule($this->_iProfileId, 'edit any note', $this->getName(), $isPerformAction); 
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    function isAllowedView ($aDataEntry, $isPerformAction = false) {

        // moderator and owner always have access
        if ($aDataEntry[BxNotesConfig::$FIELD_AUTHOR] == $this->_iProfileId || $this->_checkModeratorAccess($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'view note', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
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
        $aCheck = checkActionModule($this->_iProfileId, 'create note', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedEdit ($aDataEntry, $isPerformAction = false) {
        // moderator and owner always have access
        if ($aDataEntry[BxNotesConfig::$FIELD_AUTHOR] == $this->_iProfileId || $this->_checkModeratorAccess($isPerformAction))
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

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'delete note', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedSetThumb () {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'set thumb', $this->getName(), false);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

}

/** @} */ 
