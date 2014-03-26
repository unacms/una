<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import ('BxBaseModTextModule');

define('BX_MSG_FOLDER_INBOX', 1);
define('BX_MSG_FOLDER_SENT', 2);
define('BX_MSG_FOLDER_DRAFTS', 3);
define('BX_MSG_FOLDER_SPAM', 4);
define('BX_MSG_FOLDER_TRASH', 5);

/**
 * Messages module
 */
class BxMsgModule extends BxBaseModTextModule 
{
    function __construct(&$aModule) 
    {
        parent::__construct($aModule);
    }

    public function sortCollaborators ($aCollaborators, $iProfileIdLastComment, $iProfileIdAuthor, $iProfileIdCurrent = 0) 
    {
        if (!$iProfileIdCurrent)
            $iProfileIdCurrent = bx_get_logged_profile_id();

        $aMoveUp = array($iProfileIdCurrent, $iProfileIdLastComment, $iProfileIdAuthor);

        asort($aCollaborators, SORT_NUMERIC);

        foreach ($aMoveUp as $iProfileId) {
            if (!isset($aCollaborators[$iProfileId]))
                continue;

            $a = array($iProfileId => $aCollaborators[$iProfileId]);
            unset($aCollaborators[$iProfileId]);
            $aCollaborators = $a + $aCollaborators;
        }

        return $aCollaborators;
    }

    public function setModuleSubmenu () 
    {
        bx_import('BxDolMenu');
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if ($oMenuSubmenu) 
            $oMenuSubmenu->setObjectSubmenu($this->_oConfig->CNF['OBJECT_MENU_SUBMENU'], array (
                'title' => 'Messages',
                'link' => 'javascript:void(0);',
                'icon' => '',
            ));
    }

    /**
     * Display messages in folder
     */
    public function actionFolder ($iFolderId) 
    {
        $this->setModuleSubmenu ();

        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance('bx_messages');
        if (!$oGrid){
            $this->_oTemplate->displayErrorOccured();
            exit;
        }

        // TODO: incorporate markers into custom class, so replace will work in search and so on
        $oGrid->addMarkers(array(
            'folder_id' => (int)$iFolderId,
            'profile_id' => bx_get_logged_profile_id(),
        ));

        // TODO: refactor below
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        $oTemplate->setPageContent ('page_main_code', $oGrid->getCode());
        $oTemplate->getPageCode();

    }

    /**
     * Get possible recipients for message compose form
     */
    public function actionAjaxGetRecipients () 
    {
        $sTerm = bx_get('term');

        $a = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');
        
        header('Content-Type:text/javascript; charset=utf-8');
        echo(json_encode($a));
    }

    /**
     * Update last comment time and author
     */
    public function serviceTriggerCommentPost ($iContentId, $iProfileId, $iTimestamp = 0) 
    {
        if (!(int)$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById((int)$iContentId);
        if (!$aContentInfo)
            return false;
   
        if (!$iTimestamp)
            $iTimestamp = time();

        if ($iProfileId == bx_get_logged_profile_id())
            $this->_oDb->updateReadComments($iProfileId, $aContentInfo[$this->_oConfig->CNF['FIELD_ID']], $aContentInfo[$this->_oConfig->CNF['FIELD_COMMENTS']]);

        return $this->_oDb->updateLastCommentTimeProfile((int)$iContentId, (int)$iProfileId, $iTimestamp);
    }

    /**
     * Entry collaborators block
     */
    public function serviceEntityCollaborators ($iContentId = 0) 
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
   
        return $this->_oTemplate->entryCollaborators ($aContentInfo, 5, 'right');
    }

    /**
     * No moderators for personal messages
     */
    protected function _isModerator ($isPerformAction = false) 
    {
        return false;
    }

    /**
     * No thumbs for messages
     */
    public function checkAllowedSetThumb () 
    {
        return _t('_sys_txt_access_denied');
    }

    /**
     * Only collaborators can view message
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false) 
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = parent::checkAllowedView ($aDataEntry, $isPerformAction)))
            return $sMsg;

        $aCollaborators = $this->_oDb->getCollaborators($aDataEntry[$this->_oConfig->CNF['FIELD_ID']]);
        return isset($aCollaborators[bx_get_logged_profile_id()]) ? CHECK_ACTION_RESULT_ALLOWED : _t('_sys_txt_access_denied');
    }
}

/** @} */
