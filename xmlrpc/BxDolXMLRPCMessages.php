<?php

class BxDolXMLRPCMessages
{

    function getMessagesInbox($sUser, $sPwd)
    {
        return BxDolXMLRPCMessages::_getMessages($sUser, $sPwd, true);
    }

    function getMessagesSent($sUser, $sPwd)
    {
        return BxDolXMLRPCMessages::_getMessages($sUser, $sPwd, false);
    }

    function getMessageInbox($sUser, $sPwd, $iMsgId)
    {
        return BxDolXMLRPCMessages::_getMessage($sUser, $sPwd, $iMsgId, true);
    }

    function getMessageSent($sUser, $sPwd, $iMsgId)
    {
        return BxDolXMLRPCMessages::_getMessage($sUser, $sPwd, $iMsgId, false);
    }

    function sendMessage($sUser, $sPwd, $sRecipient, $sSubj, $sText, $sSendTo)
    {
        $aRet = array ();
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        bx_import('BxTemplMailBox');

        $sRecipient = process_db_input ($sRecipient, BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION);
        $aRecipient = db_arr("SELECT * FROM `Profiles` WHERE `NickName` = '$sRecipient'");
        if (!$aRecipient)
            return new xmlrpcval (BX_MAILBOX_SEND_UNKNOWN_RECIPIENT);

        $aMailBoxSettings = array ('member_id' => $iId);
        $oMailBox = &new BxTemplMailBox('mail_page_compose', $aMailBoxSettings);

        $aComposeSettings = array (
            'send_copy' => 'recipient' == $sSendTo || 'both' == $sSendTo ? true : false,
            'notification' => false,
            'send_copy_to_me' => 'me' == $sSendTo || 'both' == $sSendTo ? true : false,
        );
        $oMailBox->sendMessage($sSubj, nl2br($sText), $aRecipient['ID'], $aComposeSettings);
        return new xmlrpcval ($oMailBox->iSendMessageStatusCode);
    }

    function _getMessage($sUser, $sPwd, $iMsgId, $isInbox)
    {
        $aRet = array ();
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        $aMsgs = array ();
        $iMsgId = (int)$iMsgId;
        $sJoinOn = $isInbox ? " `m`.`Sender` = `p`.`ID` " : " `m`.`Recipient` = `p`.`ID` ";
        $aRow = db_arr ("SELECT
                `m`.`ID`, `m`.`Date`, `m`.`Sender`, `m`.`Recipient`, `m`.`Subject`, `m`.`Text`, `m`.`New`,
                `p`.`NickName` AS `Nick`
            FROM `sys_messages` AS `m`
            LEFT JOIN `Profiles` AS `p` ON ($sJoinOn)
            WHERE `m`.`ID` = '$iMsgId'");
        if ($aRow)
        {
            $sIcon = BxDolXMLRPCUtil::getThumbLink($isInbox ? $aRow['Sender'] : $aRow['Recipient'], 'thumb');
            $aMsg = array (
                'ID' => new xmlrpcval($aRow['ID']),
                'Date' => new xmlrpcval($aRow['Date']),
                'Sender' => new xmlrpcval($aRow['Sender']),
                'Recipient' => new xmlrpcval($aRow['Recipient']),
                'Subject' => new xmlrpcval($aRow['Subject']),
                'Text' => new xmlrpcval($aRow['Text']),
                'New' => new xmlrpcval($aRow['New']),
                'Nick' => new xmlrpcval($aRow['Nick']),
                'Thumb' => new xmlrpcval($sIcon),
            );
            if ($isInbox && $aRow['New'])
                db_res("UPDATE `sys_messages` SET `New` = 0 WHERE `ID` = '$iMsgId'");
        }
        else
        {
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));
        }
        return new xmlrpcval ($aMsg, "struct");
    }

    function _getMessages($sUser, $sPwd, $isInbox)
    {
        $aRet = array ();
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        $aMsgs = array ();

        if ($isInbox)
            $sWhere = "`Recipient` = '$iId' AND NOT FIND_IN_SET('recipient', `Trash`)";
        else
            $sWhere = "`Sender` = '$iId' AND NOT FIND_IN_SET('sender', `Trash`)";

        $sJoinOn = $isInbox ? " `m`.`Sender` = `p`.`ID` " : " `m`.`Recipient` = `p`.`ID` ";
        $r = db_res ("SELECT
                `m`.`ID`, `m`.`Date`, `m`.`Sender`, `m`.`Recipient`, `m`.`Subject`, `m`.`New`,
                `p`.`NickName` AS `Nick`
            FROM `sys_messages` AS `m`
            LEFT JOIN `Profiles` AS `p` ON ($sJoinOn)
            WHERE $sWhere
            ORDER BY `Date` DESC");
        while ($aRow = mysql_fetch_array ($r))
        {
            $sIcon = BxDolXMLRPCUtil::getThumbLink($isInbox ? $aRow['Sender'] : $aRow['Recipient'], 'thumb');
            $aMsg = array (
                'ID' => new xmlrpcval($aRow['ID']),
                'Date' => new xmlrpcval($aRow['Date']),
                'Sender' => new xmlrpcval($aRow['Sender']),
                'Recipient' => new xmlrpcval($aRow['Recipient']),
                'Subject' => new xmlrpcval($aRow['Subject']),
                'New' => new xmlrpcval($aRow['New']),
                'Nick' => new xmlrpcval($aRow['Nick']),
                'Thumb' => new xmlrpcval($sIcon),
            );
            $aMsgs[] = new xmlrpcval($aMsg, 'struct');
        }
        return new xmlrpcval ($aMsgs, "array");
    }
}

?>
