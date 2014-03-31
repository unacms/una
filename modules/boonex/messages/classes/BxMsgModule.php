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

define('BX_MSG_FOLDER_PRIMARY', 1);
define('BX_MSG_FOLDER_DRAFTS', 2);
define('BX_MSG_FOLDER_SPAM', 3);
define('BX_MSG_FOLDER_TRASH', 4);

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

    public function setModuleSubmenu ($iCurrentFolderId = 0) 
    {
        $CNF = &$this->_oConfig->CNF;

        $aMapFolderId2MenuName = array (
            BX_MSG_FOLDER_PRIMARY => 'messages-folder-primary',
            BX_MSG_FOLDER_DRAFTS => 'messages-drafts',
            BX_MSG_FOLDER_SPAM => 'messages-spam',
            BX_MSG_FOLDER_TRASH => 'messages-trash',
        );

        bx_import('BxDolMenu');
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if (!$oMenuSubmenu) 
            return;

        $oMenuSubmenu->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU'], array (
            'title' => _t('_bx_msg'),
            'link' => BX_DOL_URL_ROOT . $CNF['URL_HOME'],
            'icon' => '',
        ));

        $oMenuModule = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SUBMENU']);
        if ($oMenuModule && isset($aMapFolderId2MenuName[$iCurrentFolderId]))
            $oMenuModule->setSelected('bx_messages', $aMapFolderId2MenuName[$iCurrentFolderId]);
    }

    /**
     * Mark message as unread for the current user
     * @return error message on error, or empty message on success
     */
    public function markUnread ($iContentId) 
    {
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return _t('_sys_request_page_not_found_cpt');

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedView($aContentInfo)))
            return $sMsg;

        if (!$this->_oDb->updateReadComments(bx_get_logged_profile_id(), (int)$iContentId, -1))
            return _t('_error occured');

        return '';
    }

    /**
     * Delete message for current user by content id
     * @return error message on error, or empty message on success
     */
    public function deleteMessage ($iContentId) 
    {
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return _t('_sys_request_page_not_found_cpt');

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedDelete($aContentInfo)))
            return $sMsg;

        if (!$this->_oDb->moveMessage((int)$iContentId, bx_get_logged_profile_id(), BX_MSG_FOLDER_TRASH))
            return _t('_error occured');

        return '';
    }

    public function actionMarkUnread($iContentId) 
    {
        header('Content-Type:text/plain; charset=utf-8');

        if ($s = $this->markUnread ($iContentId)) {
            echo $s;
            exit;
        }

        echo BX_DOL_URL_ROOT . $this->_oConfig->CNF['URL_HOME'];
        exit;
    }

    public function actionDelete($iContentId) 
    {
        header('Content-Type:text/plain; charset=utf-8');

        if ($s = $this->deleteMessage ($iContentId)) {
            echo $s;
            exit;
        }

        echo BX_DOL_URL_ROOT . $this->_oConfig->CNF['URL_HOME'];
        exit;
    }

    /**
     * Display messages in folder
     */
    public function actionFolder ($iFolderId) 
    {
        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance('bx_messages');
        if (!$oGrid){
            $this->_oTemplate->displayErrorOccured();
            exit;
        }

        $aFolder = $this->_oDb->getFolder((int)$iFolderId);
        if (!$aFolder) {
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        $this->setModuleSubmenu ((int)$iFolderId);

        // TODO: incorporate markers into custom class, so replace will work in search and so on
        $oGrid->addMarkers(array(
            'folder_id' => (int)$iFolderId,
            'profile_id' => bx_get_logged_profile_id(),
        ));

        // TODO: refactor below
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        $oTemplate->setPageHeader (str_replace('{folder}', _t($aFolder['name']), _t('_bx_msg_page_title_folder')));
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
