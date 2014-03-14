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
}

/** @} */ 
