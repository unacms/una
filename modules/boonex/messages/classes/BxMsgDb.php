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

bx_import('BxBaseModTextDb');

/*
 * Module database queries
 */
class BxMsgDb extends BxBaseModTextDb 
{
    public function __construct(&$oConfig) 
    {
        parent::__construct($oConfig);
    }

    public function conversationToFolder($iConversationId, $iFolderId, $iProfileCollaborator, $iReadCommentsNum = -1) 
    {
        $sQuery = $this->prepare("INSERT INTO `" . $this->getPrefix() . "conv2folder` SET `conv_id` = ?, `folder_id` = ?, `collaborator` = ?, `read_comments` = ?", $iConversationId, $iFolderId, $iProfileCollaborator, $iReadCommentsNum);
        return $this->query($sQuery);
    }

    public function getFolder($iFolderId)
    {
        $sQuery = $this->prepare("SELECT `id`, `author`, `name` FROM `" . $this->getPrefix() . "folders` WHERE `id` = ?", $iFolderId);
        return $this->getRow($sQuery);
    }

    public function getCollaborators($iConversationId)
    {
        $sQuery = $this->prepare("SELECT `collaborator`, `read_comments` FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ?", $iConversationId);
        return $this->getPairs($sQuery, 'collaborator', 'read_comments');
    }

    public function updateReadComments($iProfileId, $iConversationId, $iReadComments)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->getPrefix() . "conv2folder` SET `read_comments` = ? WHERE `conv_id` = ? AND `collaborator` = ?", $iReadComments, $iConversationId, $iProfileId);
        return $this->query($sQuery);
    }

    public function updateLastCommentTimeProfile($iConversationId, $iProfileId, $iTimestamp)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->getPrefix() . "conversations` SET `last_reply_profile_id` = ?, `last_reply_timestamp` = ? WHERE `id` = ?", $iProfileId, $iTimestamp, $iConversationId);
        return $this->query($sQuery);
    }

    public function moveMessage($iConversationId, $iProfileId, $iFolderId)
    {
        $sQuery = $this->prepare("SELECT `folder_id` FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ? AND `collaborator` = ?", $iConversationId, $iProfileId);
        $iFolderIdOld = $this->getOne($sQuery);
        if (BX_MSG_FOLDER_TRASH == $iFolderIdOld) // if message is already in trash folder - delete it
            return $this->deleteMessage($iConversationId, $iProfileId);

        $sQuery = $this->prepare("UPDATE `" . $this->getPrefix() . "conv2folder` SET `folder_id` = ? WHERE `conv_id` = ? AND `collaborator` = ?", $iFolderId, $iConversationId, $iProfileId);
        return $this->query($sQuery);
    }

    public function deleteMessage($iConversationId, $iProfileId)
    {
        // delete message
        $sQuery = $this->prepare("DELETE FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ? AND `collaborator` = ?", $iConversationId, $iProfileId);
        if (!$this->query($sQuery))
            return false;

        // delete whole conversation if there is no refencences to the conversation in conv2folder table
        $sQuery = $this->prepare("SELECT `id` FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ?", $iConversationId);
        if (!$this->getOne($sQuery)) {
            $CNF = &$this->_oConfig->CNF;
            bx_import('BxDolForm');
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']);
            return $oForm->delete((int)$iConversationId);
        }

        return true;
    }
}

/** @} */ 
