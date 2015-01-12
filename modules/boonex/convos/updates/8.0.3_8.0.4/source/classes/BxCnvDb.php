<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModTextDb');

/*
 * Module database queries
 */
class BxCnvDb extends BxBaseModTextDb
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

    public function getConversationFolder($iConversationId, $iProfileCollaborator)
    {
        $sQuery = $this->prepare("SELECT `folder_id` FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ? AND `collaborator` = ?", $iConversationId, $iProfileCollaborator);
        return $this->getOne($sQuery);
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

    public function updateLastCommentTimeProfile($iConversationId, $iProfileId, $iCommentId, $iTimestamp)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->getPrefix() . "conversations` SET `last_reply_profile_id` = ?, `last_reply_timestamp` = ?, `last_reply_comment_id` = ? WHERE `id` = ?", $iProfileId, $iTimestamp, $iCommentId, $iConversationId);
        return $this->query($sQuery);
    }

    public function moveConvo($iConversationId, $iProfileId, $iFolderId)
    {
        $sQuery = $this->prepare("SELECT `folder_id` FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ? AND `collaborator` = ?", $iConversationId, $iProfileId);
        $iFolderIdOld = $this->getOne($sQuery);
        if (BX_CNV_FOLDER_TRASH == $iFolderIdOld) // if convo is already in trash folder - delete it
            return $this->deleteConvo($iConversationId, $iProfileId);

        $sQuery = $this->prepare("UPDATE `" . $this->getPrefix() . "conv2folder` SET `folder_id` = ? WHERE `conv_id` = ? AND `collaborator` = ?", $iFolderId, $iConversationId, $iProfileId);
        return $this->query($sQuery);
    }

    public function deleteConvo($iConversationId, $iProfileId = 0)
    {
        $aContentInfo = $this->getContentInfoById ($iConversationId);
        if (!$aContentInfo)
            return true;

        // delete convo
        $sWhere = '';
        if ($iProfileId)
            $sWhere = $this->prepare(" AND `collaborator` = ?", $iProfileId);

        $sQuery = $this->prepare("DELETE FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ?", $iConversationId);
        if (!$this->query($sQuery . $sWhere))
            return false;

        // delete whole conversation if there is no refencences to the conversation in conv2folder table
        $sQuery = $this->prepare("SELECT `id` FROM `" . $this->getPrefix() . "conv2folder` WHERE `conv_id` = ?", $iConversationId);
        if (!$this->getOne($sQuery)) {
            $CNF = &$this->_oConfig->CNF;
            bx_import('BxDolForm');
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']);
            return $oForm->delete((int)$iConversationId, $aContentInfo);
        }

        return true;
    }

    public function getUnreadMessagesNum ($iProfileId, $iFolderId = BX_CNV_FOLDER_INBOX)
    {
        $sQuery = $this->prepare("SELECT SUM(`c`.`comments` - `f`.`read_comments`)
            FROM `" . $this->getPrefix() . "conv2folder` as `f`
            INNER JOIN `" . $this->getPrefix() . "conversations` AS `c` ON (`c`.`id` = `f`.`conv_id`)
            WHERE `f`.`collaborator` = ? AND `f`.`folder_id` = ?", $iProfileId, $iFolderId);
        return $this->getOne($sQuery);
    }

    public function getMessagesPreviews ($iProfileId, $iStart = 0, $iLimit = 4, $iFolderId = BX_CNV_FOLDER_INBOX)
    {
        $sQuery = $this->prepare("SELECT `c`.`id`, `c`.`text`, `c`.`author`, `cmts`.`cmt_text`, `c`.`last_reply_profile_id`, `c`.`comments`, (`c`.`comments` - `f`.`read_comments`) AS `unread_messages`, `last_reply_timestamp`
            FROM `" . $this->getPrefix() . "conv2folder` as `f`
            INNER JOIN `" . $this->getPrefix() . "conversations` AS `c` ON (`c`.`id` = `f`.`conv_id`)
            LEFT JOIN `" . $this->getPrefix() . "cmts` AS `cmts` ON (`cmts`.`cmt_id` = `c`.`last_reply_comment_id`)
            WHERE `f`.`collaborator` = ? AND `f`.`folder_id` = ?
            ORDER BY `c`.`last_reply_timestamp` DESC
            LIMIT ?, ?", $iProfileId, $iFolderId, $iStart, $iLimit);
        return $this->getAll($sQuery);
    }
}

/** @} */
