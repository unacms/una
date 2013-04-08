<?php

// TODO: remake according to new design and principles, site setup part leave in admin and remake other functionality move to user part

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

define('BX_SECURITY_EXCEPTIONS', true);
$aBxSecurityExceptions = array(
    'POST.body',
    'REQUEST.body',
);

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );

bx_import('BxTemplFormView');
bx_import('BxDolEmailTemplates');

$logged['admin'] = member_auth(1, true, true);

if($_POST['queue_message'] && $_POST['msgs_id'])
    $sActionResult = QueueMessage();
if ($_POST['add_message'])
    $action = 'add';
if($_POST['delete_message'] && $_POST['msgs_id'])
    $sActionResult = DeleteMessage() ? _t('_adm_mmail_Message_was_deleted') : _t('_adm_mmail_Message_was_not_deleted');
if($_POST['preview_message'] && $_POST['msgs_id'])
    $action = 'preview';
if(bx_get('action') == 'empty' )
    $sActionResult = EmptyQueue() ? _t('_adm_mmail_Queue_empty') : _t('_adm_mmail_Queue_emptying_failed');

$iNameIndex = 13;
$_page = array(
    'name_index' => $iNameIndex,
    'css_name' => array('forms_adv.css'),
    'header' => _t('_adm_mmail_title')
);

$_page_cont[$iNameIndex] = array(
    'page_code_status' => PrintStatus($sActionResult),
    'page_code_new_message' => getEmailMessage($action),
    'page_code_preview_message' => $action == 'preview' && strlen($_POST['body']) ? PreviewMessage() : '',
    'page_code_all_messages' => getAllMessagesBox(),
    'page_code_queue_message' => getQueueMessage()
);

PageCodeAdmin();

function PrintStatus($sActionResult) {
    $sSubjC = _t('_Subject');
    $sEmailsC = _t('_adm_mmail_emails');
    $sEmptyQueueC = _t('_adm_mmail_Empty_Queue');
    $sCupidStatusC = _t('_adm_mmail_Cupid_mails_status');

    $sSingleEmailsTRs = '';

    // Select count of emails in queue per one message
    $iCount = (int)$GLOBALS['MySQL']->getOne("SELECT COUNT(`id`) AS `count` FROM `sys_sbs_queue`");
    if ($iCount <= 0)
        $sSingleEmailsTRs .= "<tr><td align=center><b><font color=red>" . _t('_adm_mmail_no_emails_in_queue') . "</font></b></td></tr>";
    else
       $sSingleEmailsTRs .= "<tr><td align=center>" . _t('_adm_mmail_mails_in_queue', $iCount) . "</td></tr>";

    $sEmptyQueueTable = '';
    // If queue is not empty then show link to clear it
    if($iCount > 0) {
        $sEmptyQueueTable = "<hr>
        <table class=\"text\" width=\"50%\" style=\"height: 30px;\">
            <tr class=\"table\">
                <td align=\"center\" colspan=\"3\">
                    <a href=\"" . BX_DOL_URL_ADMIN . "notifies.php?action=empty\">{$sEmptyQueueC}</a>
                </td>
            </tr>
        </table>
        <hr>";
    }

    ob_start();
?>
<div style="margin:9px;">
    <center>
        <table cellspacing=2 cellpadding=2 class=text border=0>
            <tr class=header align="center"><td><?=_t('_adm_mmail_Queue_status');?>:</td></tr>
            <?=$sSingleEmailsTRs;?>
        </table>
        <?=$sEmptyQueueTable;?>
    </center>
</div>
<?
    $sResult = ob_get_clean();

    if(!empty($sActionResult))
       $sResult = MsgBox($sActionResult, 3) . $sResult;

    return DesignBoxContent(_t('_Status'), $sResult, 1);
}

function getAllMessagesBox() {
    $aMessages = $GLOBALS['MySQL']->getAll("SELECT `id`, `subject`, (`id`=". (int)$_POST['msgs_id'] ." OR `subject`='". process_db_input($_POST['Subj']) ."' ) AS `selected` FROM `sys_sbs_messages`");

    $sAllMessagesOptions = '';
    foreach($aMessages as $aMessage)
        $sAllMessagesOptions .= "<option value=\"" . $aMessage['id'] . "\" " . ($aMessage['selected'] ? "selected=\"selected\"" : "") . ">" . $aMessage['subject'] . "</option>";

    ob_start();
?>
<form name="form_messages" method="POST" action="<?=$GLOBALS['site']['url_admin'] . 'notifies.php';?>">
    <input type="hidden" name="action" value="view">
    <center class="text"><?= _t('_Messages'); ?>:&nbsp;
        <select name=msgs_id onChange="javascript: document.forms['form_messages'].submit();">
            <option value=0><?=_t('_None');?></option>
            <?=$sAllMessagesOptions;?>
        </select>
    </center>
</form>
<?
    $sResult = ob_get_clean();

    return DesignBoxContent(_t('_adm_mmail_All_Messages'), '<div style="margin:9px;">' . $sResult . '</div>', 1);
}

function getEmailMessage($sAction) {
    $sErrorC = _t('_Error Occured');
    $sApplyChangesC = _t('_Submit');
    $sSubjectC = _t('_Subject');
    $sBodyC = _t('_adm_mmail_Body');
    $sTextBodyC = _t('_adm_mmail_Text_email_body');
    $sPreviewMessageC = _t('_Preview');
    $sDeleteC = _t('_Delete');

    $sMessageID = (int)$_POST['msgs_id'];

    $sSubject = $sBody = "";
    if($_POST['body'] && $_POST['action'] != 'delete' ) {
        $sSubject = process_pass_data( $_POST['subject'] );
        $sBody = process_pass_data( $_POST['body'] );
    } elseif ( $sMessageID )
        list($sSubject, $sBody) = $GLOBALS['MySQL']->getRow("SELECT `subject`, `body` FROM `sys_sbs_messages` WHERE `id`='". $sMessageID . "' LIMIT 1", MYSQL_NUM);

    $sSubject = htmlspecialchars($sSubject);

    $aForm = array(
        'form_attrs' => array(
            'name' => 'sys_sbs_messages',
            'action' => $GLOBALS['site']['url_admin'] . 'notifies.php',
            'method' => 'post',
        ),
        'params' => array (
            'db' => array(
                'table' => 'sys_sbs_messages',
                'key' => 'ID',
                'submit_name' => 'add_message',
            ),
        ),
        'inputs' => array(
            'subject' => array(
                'type' => 'text',
                'name' => 'subject',
                'value' => $sSubject,
                'caption' => $sSubjectC,
                'required' => true,
                'checker' => array (
                    'func' => 'length',
                    'params' => array(2,128),
                    'error' => $sErrorC,
                ),
                'db' => array (
                    'pass' => 'Xss',
                ),
            ),
            'body' => array(
                'type' => 'textarea',
                'name' => 'body',
                'value' => $sBody,
                'caption' => $sBodyC,
                'required' => true,
                'html' => 1,
                'checker' => array (
                    'func' => 'length',
                    'params' => array(10,32000),
                    'error' => $sErrorC,
                ),
                'db' => array (
                    'pass' => 'XssHtml',
                ),
            ),
            'msgs_id' => array(
                'type' => 'hidden',
                'name' => 'msgs_id',
                'value' => $sMessageID,
            ),
            'control' => array (
                'type' => 'input_set',
                array(
                    'type' => 'submit',
                    'name' => 'add_message',
                    'caption' => $sApplyChangesC,
                    'value' => $sApplyChangesC,
                ),
                array(
                    'type' => 'submit',
                    'name' => 'preview_message',
                    'caption' => $sPreviewMessageC,
                    'value' => $sPreviewMessageC,
                ),
            )
        ),
    );
    if($sMessageID) {
        $aForm['inputs']['control'][] = array (
            'type' => 'submit',
            'name' => 'delete_message',
            'caption' => $sDeleteC,
            'value' => $sDeleteC,
        );
    }

    $sResult = '';
    $oForm = new BxTemplFormView($aForm);
    $oForm->initChecker();
    if ($oForm->isSubmittedAndValid()) {
        if ($sAction == 'add') {
            if ($sMessageID > 0) {
                $oForm->update($sMessageID);
            } else {
                $sMessageID = $oForm->insert();
            }
        }

        $sResult = $sMessageID > 0 ? MsgBox(_t('_Success'), 3) : MsgBox($sErrorC);
    }

    return DesignBoxContent(_t('_adm_mmail_Email_message'), '<div style="margin:9px;">' . $sResult . $oForm->getCode() . '</div>', 1);
}

function getQueueMessage() {
    global $aPreValues;

    if ( $_POST['msgs_id'] ) {
        $aSexValues = getFieldValues('Sex');
        foreach($aSexValues as $sKey => $sValue)
            $aSexValues[$sKey] = _t($sValue);

        $aStartAgesOptions = array();
        $aEndAgesOptions = array();
        $gl_search_start_age = (int)getParam('search_start_age');
        $gl_search_end_age = (int)getParam('search_end_age');
        for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ ) {
            $aStartAgesOptions[$i] = $i;
        }
        for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ ) {
            $aEndAgesOptions[$i] = $i;
        }

        $aCountryOptions = array('all' => _t('_All'));
        foreach ( $aPreValues['Country'] as $key => $value ) {
            $aCountryOptions[$key] = _t($value['LKey']);
        }

        $aMembershipOptions = array('all' => _t('_All'));
        $memberships_arr = getMemberships();
        foreach ( $memberships_arr as $membershipID => $membershipName ) {
            if ($membershipID == MEMBERSHIP_ID_NON_MEMBER) continue;
            $aMembershipOptions[$membershipID] = $membershipName;
        }

        $iRecipientMembers = (int)$GLOBALS['MySQL']->getOne("SELECT COUNT(`ID`) AS `count` FROM `Profiles` WHERE `Status`<>'Unconfirmed' AND `EmailNotify` = 1 LIMIT 1");
        $aForm = array(
            'form_attrs' => array(
                'name' => 'form_queue',
                'class' => 'form_queue_form',
                'action' => $GLOBALS['site']['url_admin'] . 'notifies.php',
                'method' => 'post',
            ),
            'inputs' => array (
                'Send1' => array(
                    'type' => 'checkbox',
                    'name' => 'send_to_subscribers',
                    'label' => _t('_adm_mmail_Send_to_subscribers'),
                    'value' => 'non',
                    'checked' => true
                ),
                'Send2' => array(
                    'type' => 'checkbox',
                    'name' => 'send_to_members',
                    'label' => _t('_adm_mmail_Send_to_members'),
                    'value' => 'memb',
                    'checked' => true,
                    'attrs' => array(
                        'onClick' => 'setControlsState();',
                    ),
                    'info' => _t('_adm_mmail_Send_to_members_info', $iRecipientMembers),
                ),
                'sex' => array (
                    'type' => 'checkbox_set',
                    'name' => 'sex',
                    'values' => $aSexValues,
                    'value' => array_keys($aSexValues)
                ),
                'StartAge' => array (
                    'type' => 'select',
                    'name' => 'age_start',
                    'caption' => _t('_adm_mmail_Age') . ' ' . _t('_from'),
                    'values' => $aStartAgesOptions,
                    'value' => $gl_search_start_age,
                ),
                'EndAge' => array (
                    'type' => 'select',
                    'name' => 'age_end',
                    'caption' => _t('_to'),
                    'values' => $aEndAgesOptions,
                    'value' => $gl_search_end_age,
                ),
                'Country' => array (
                    'type' => 'select',
                    'name' => 'country',
                    'caption' => _t('_Country'),
                    'values' => $aCountryOptions,
                    'value' => 'all',
                ),
                'Membership' => array (
                    'type' => 'select',
                    'name' => 'membership',
                    'caption' => _t('_adm_mmi_membership_levels'),
                    'values' => $aMembershipOptions,
                    'value' => 'all',
                ),
                'msgs_id' => array (
                    'type' => 'hidden',
                    'name' => 'msgs_id',
                    'value' => (int)$_POST['msgs_id'],
                ),
                'submit' => array (
                    'type' => 'submit',
                    'name' => 'queue_message',
                    'value' => _t('_Submit'),
                )
            )
        );

        $oForm = new BxTemplFormView($aForm);
        $sTmplResult = $GLOBALS['oAdmTemplate']->parseHtmlByName('notifies_filter.html', array());
        return DesignBoxContent(_t('_adm_mmail_Queue_message'), '<div style="margin:9px;">' . $oForm->getCode() . $sTmplResult . '</div>', 1);
    }
}

function QueueMessage() {
    global $MySQL;

    $iEmails = 0;
    $sReturn = "";
    $iMsgId = (int)$_POST['msgs_id'];

    $aOriginalMessage = $MySQL->getRow("SELECT `id`, `subject`, `body` FROM `sys_sbs_messages` WHERE `id`='" . $iMsgId . "' LIMIT 1");
    if(!is_array($aOriginalMessage) || empty($aOriginalMessage)) {
        return _t('_adm_mmail_Failed_to_queue_emails_X', $iMsgId);
    }

    //--- Send to all subscribers
    $oEmailTemplates = new BxDolEmailTemplates();
    if($_POST['send_to_subscribers'] == 'non') {
        $sSql = "SELECT
                    `tsu`.`name` AS `user_name`,
                    `tsu`.`email` AS `user_email`,
                    `tst`.`template` AS `template_name`
                FROM `sys_sbs_types` AS `tst`
                INNER JOIN `sys_sbs_entries` AS `tse` ON `tst`.`id`=`tse`.`subscription_id` AND `tse`.`subscriber_type`='" . BX_DOL_SBS_TYPE_VISITOR . "'
                INNER JOIN `sys_sbs_users` AS `tsu` ON `tse`.`subscriber_id`=`tsu`.`id`
                WHERE
                    `tst`.`unit`='system' AND
                    `tst`.`action`='mass_mailer'";
        $aSubscribers = $MySQL->getAll($sSql);

        foreach($aSubscribers as $aSubscriber) {
            if(empty($aSubscriber['user_email']))
                continue;

            $aMessage = $oEmailTemplates->parseTemplate($aSubscriber['template_name'], array(
                'RealName' => $aSubscriber['user_name'],
                'Email' => $aSubscriber['user_email'],
                'MessageSubject' => $aOriginalMessage['subject'],
                'MessageText' => $aOriginalMessage['body']
            ));

            $mixedResult = $MySQL->query("INSERT INTO `sys_sbs_queue`(`email`, `subject`, `body`) VALUES('" . $aSubscriber['user_email'] . "', '" . process_db_input($aMessage['Subject'], BX_TAGS_STRIP) . "', '" . process_db_input($aMessage['Body'], BX_TAGS_VALIDATE) . "')");
            if($mixedResult === false) {
                $sReturn .= _t('_adm_mmail_Email_not_added_to_queue_X', $aSubscriber['user_email']);
                continue;
            }
            $iEmails++;
        }
    }

    //--- Send to all profiles
    if($_POST['send_to_members'] == 'memb') {
        //--- Sex filter
        $sex_filter_sql = '';
        if(is_array($_POST['sex']) && !empty($_POST['sex']))
            $sex_filter_sql = "AND `Sex` IN ('" . implode("','", $_POST['sex']) . "')";

        //--- Age filter
        $age_filter_sql = '';
        $age_start = (int)$_POST['age_start'];
        $age_end = (int)$_POST['age_end'];
        if ( $age_start && $age_end ) {
            $date_start = (int)( date( "Y" ) - $age_start );
            $date_end = (int)( date( "Y" ) - $age_end - 1 );
            $date_start = $date_start . date( "-m-d" );
            $date_end = $date_end . date( "-m-d" );
            $age_filter_sql = "AND (TO_DAYS(`DateOfBirth`) BETWEEN TO_DAYS('{$date_end}') AND (TO_DAYS('{$date_start}')+1))";
        }

        //--- Country filter
        $country_filter_sql = '';
        if($_POST['country'] != 'all') {
            $country = process_db_input($_POST['country']);
            $country_filter_sql = "AND `Country` = '{$country}'";
        }

        //--- Membership filter
        $membershipID = $_POST['membership'] != 'all' ? (int)$_POST['membership'] : -1;

        $aMembers = $MySQL->getAll("SELECT `ID` AS `id`, `Email` AS `email` FROM `Profiles` WHERE `Status` <> 'Unconfirmed' AND `EmailNotify` = 1 AND (`Couple` = '0' OR `Couple` > `ID`) {$sex_filter_sql} {$age_filter_sql} {$country_filter_sql}");
        foreach($aMembers as $aMember) {
            if(empty($aMember['email']))
                continue;

            //--- Dynamic membership filter
            $membership_info = getMemberMembershipInfo($aMember['id']);
            if ($membershipID != -1 && $membership_info['ID'] != $membershipID )
                continue;

            $aMessage = $oEmailTemplates->parseTemplate('t_AdminEmail', array(
                'MessageSubject' => $aOriginalMessage['subject'],
                'MessageText' => $aOriginalMessage['body']
            ), $aMember['id']);

            $mixedResult = $MySQL->query("INSERT INTO `sys_sbs_queue`(`email`, `subject`, `body`) VALUES('" . $aMember['email'] . "', '" . process_db_input($aMessage['Subject'], BX_TAGS_STRIP) . "', '" . process_db_input($aMessage['Body'], BX_TAGS_VALIDATE) . "')");
            if($mixedResult === false) {
                $sReturn .= _t('_adm_mmail_Email_not_added_to_queue_X', $aMember['email']);
                continue;
            }
            $iEmails++;
        }
    }

    $sReturn .= _t('_adm_mmail_X_emails_was_succ_added_to_queue', (int)$iEmails);
    return $sReturn;
}

function PreviewMessage() {
    $oEmailTemplate = new BxDolEmailTemplates();
    $aMessage = $oEmailTemplate->parseTemplate('t_AdminEmail', array(
        'MessageText' => process_pass_data($_POST['body'])
    ));

    $sPreview = '<div style="margin:9px;">' . $aMessage['Body'] . '</div>';
    return DesignBoxContent(_t('_Preview'), $sPreview, 1);
}

function DeleteMessage() {
    $mixedResult = $GLOBALS['MySQL']->query("DELETE FROM `sys_sbs_messages` WHERE `id`='". (int)$_POST['msgs_id'] . "' LIMIT 1");
    if($mixedResult === false)
        return $mixedResult;

    $_POST['msgs_id'] = 0;
    return true;
}

function EmptyQueue() {
    return db_res("TRUNCATE TABLE `sys_sbs_queue`");
}

?>
