<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Samples
 * @{
 */

/**
 * @page samples
 * @section email Send Email
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Send Email Example");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $oAccount = BxDolAccount::getInstance();
    $aAccountInfo = $oAccount ? $oAccount->getInfo() : false;
    if (!$aAccountInfo)
        return DesignBoxContent("Send Email example", 'Please login first', BX_DB_PADDING_DEF);

    echo "<h2>Account info</h2>";
    echo "Email: " . $aAccountInfo['email'] . '<br />';
    echo "Email Confirmed: " . ($aAccountInfo['email_confirmed'] ? 'yes' : 'no') . '<br />';
    echo "Receive site updates: " . ($aAccountInfo['receive_updates'] ? 'yes' : 'no') . '<br />';
    echo "Receive site newsletters: " . ($aAccountInfo['receive_news'] ? 'yes' : 'no') . '<br />';
    echo "Site emails are sent from: " . getParam('site_email_notify') . '<br />';

    $a = array (
        'sys' => array (
            'title' => "Send me system email",
            'type' => BX_EMAIL_SYSTEM,
            'subj' => 'System Email',
            'body' => 'This is system email <br /> {unsubscribe}',
        ),
        'notif' => array (
            'title' => "Send me notification",
            'type' => BX_EMAIL_NOTIFY,
            'subj' => 'Notification Email',
            'body' => 'This is notification email<br /> {unsubscribe}',
        ),
        'mass' => array (
            'title' => "Send me bulk email",
            'type' => BX_EMAIL_MASS,
            'subj' => 'Bulk Email',
            'body' => 'This is bulk email<br /> {unsubscribe}',
        ),
    );

    $sSendMail = bx_get('send');
    if ($sSendMail && isset($a[$sSendMail])) {
        echo "<h2>Send Email Result</h2>";
        $r = $a[$sSendMail];
        if (sendMail($aAccountInfo['email'], $r['subj'], $r['body'], 0, array(), $r['type']))
            echo MsgBox($r['subj'] . ' - successfully sent');
        else
            echo MsgBox($r['subj'] . ' - sent failed');
    }

    echo "<h2>Send email</h2>";
    foreach ($a as $k => $r) {
        echo '<a href="samples/email.php?send=' . $k . '">' . $r['title'] . '</a><br />';
    }

    return DesignBoxContent("Send Email Example", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
