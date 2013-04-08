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
class BxPersonsModule extends BxDolModule {

    protected $_iProfileId;

    function __construct(&$aModule) {
        parent::__construct($aModule);
        $this->_iProfileId = bx_get_logged_profile_id();
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
        return $this->_oTemplate->unit($aContentInfo);
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

    public function serviceProfilePicture ($iContentId = 0) {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        
        $sImageUrl = '';
        if (isset($aContentInfo[BxPersonsConfig::$FIELD_PICTURE]) && $aContentInfo[BxPersonsConfig::$FIELD_PICTURE]) {
            bx_import('BxDolImageTranscoder');        
            $oImagesTranscoder = BxDolImageTranscoder::getObjectInstance(BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_PREVIEW);
            if (!$oImagesTranscoder)
                return false;
            $sImageUrl = $oImagesTranscoder->getImageUrl($aContentInfo[BxPersonsConfig::$FIELD_PICTURE]);
        } 

        if (!$sImageUrl)
            $sImageUrl = $this->_oTemplate->getImageUrl('no-picture-preview.png');

        $aVars = array ('url' => $sImageUrl);
        return $this->_oTemplate->parseHtmlByName('block_picture.html', $aVars);
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

        // TODO: remale to use "pages"

        $this->_oTemplate->addCss ('main.css'); 

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageHeader ($o->aCurrent['title']);
        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        $oTemplate->setPageContent ('page_main_code', $s);
        $oTemplate->getPageCode();
    }

    function actionDiscardGhost ($iFileId, $iContentId = 0) {
        $this->_actionWithGhost('discardGhost', $iFileId, $iContentId);
    }

    function actionDeleteGhost ($iFileId, $iContentId = 0) {
        $this->_actionWithGhost('deleteGhost', $iFileId, $iContentId);
    }

    function _actionWithGhost ($sAction, $iFileId, $iContentId = 0) {
        $iFileId = (int)$iFileId;
        $iContentId = (int)$iContentId;

        if (!$iFileId) {
            echo _t('_sys_txt_error_occured');        
            exit;
        }

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('bx_person', 'bx_person_add'); 

        header('Content-type: text/html; charset=utf-8');
        echo $oForm->$sAction($iFileId, $iContentId);
        exit;
    }

    // ====== PERMISSION METHODS

    function _checkModeratorAccess ($isPerformAction = false) {
        // check moderator ACL
        $aCheck = checkActionModule($this->_iProfileId, 'edit any person profile', $this->getName(), $isPerformAction); 
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
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
        $aCheck = checkActionModule($this->_iProfileId, 'create person profile', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. 
     */
    function isAllowedEdit ($aDataEntry, $isPerformAction = false) {
        // moderator and owner always have access
        if ($aDataEntry[BxPersonsConfig::$FIELD_AUTHOR] == $this->_iProfileId || $this->_checkModeratorAccess($isPerformAction))
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
        $aCheck = checkActionModule($this->_iProfileId, 'delete person profile', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

}

/** @} */ 
