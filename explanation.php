<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "languages.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

// TODO: move HTML to templates and maybe other refactoring

$oTemplate = BxDolTemplate::getInstance();

$sPageMainCode = PageMainCode();

if (!$sPageMainCode || !isset($_GET['explain']))
    $oTemplate->displayPageNotFound();

$sExplanation = _t("_".bx_process_pass($_GET['explain']));
$sHeader = _t("_EXPLANATION_H") . ": " . $sExplanation; // TODO: remake this line using parameters in lang string

$oTemplate->setPageNameIndex (BX_PAGE_POPUP);
$oTemplate->setPageHeader ($sHeader);
$oTemplate->setPageContent ('body_onload', 'javascript: void(0)');
$oTemplate->setPageContent ('page_main_code', DesignBoxContent($sHeader, $sPageMainCode, BX_DB_PADDING_DEF));

PageCode();

// --------------- page components functions

function membershipActionsList($membershipID)
{
    $oDb = BxDolDb::getInstance();
    $sQuery = $oDb->prepare("
        SELECT    IDAction,
                Name,
                AllowedCount,
                AllowedPeriodLen,
                AllowedPeriodStart,
                AllowedPeriodEnd,
                AdditionalParamName,
                AdditionalParamValue
        FROM    `sys_acl_matrix`
                INNER JOIN `sys_acl_actions`
                ON `sys_acl_matrix`.IDAction = `sys_acl_actions`.ID
        WHERE `sys_acl_matrix`.IDLevel = ?
        ORDER BY `sys_acl_actions`.Name", $membershipID);
    $aLevelActions = $oDb->getAll($sQuery);

    ob_start();
?>
<!-- [START] List Membership Actions -->

<style type="text/css">
table.allowedActionsTable{
    border-bottom:1px solid;
    border-right:1px solid;
}
table.allowedActionsTable td{
    padding: 5px;
    text-align: center;
    border-top:1px solid;
    border-left:1px solid;
}
</style>
<table cellpadding="0" cellspacing="0" border="0" style="font-size: 8pt" class="allowedActionsTable" align="center" width="100%">
<tr>
        <td colspan="5" align="center"><?= _t("_Allowed actions") ?></td>
</tr>
<tr>
        <td><b><?= _t("_Action") ?></b></td>
        <td><b><?= _t("_Times allowed") ?></b></td>
        <td><b><?= _t("_Period (hours)") ?></b></td>
        <td><b><?= _t("_Allowed Since") ?></b></td>
        <td><b><?= _t("_Allowed Until") ?></b></td>
</tr>
<?php
    if (!$aLevelActions) {
?>
<tr>
        <td colspan="5"><?= _t("_No actions allowed for this membership") ?></td>
</tr>
<?php
    }

    foreach ($aLevelActions as $membershipAction) {
?>
<tr>
        <td style="text-align: left;"><b><?= _t("_mma_".str_replace(' ', '_', $membershipAction['Name'])) ?></b></td>
        <td><?= $membershipAction['AllowedCount'] ? $membershipAction['AllowedCount'] : _t("_no limit") ?></td>
        <td><?= $membershipAction['AllowedPeriodLen'] ? $membershipAction['AllowedPeriodLen'] : _t("_no limit") ?></td>
        <td><?= $membershipAction['AllowedPeriodStart'] ? $membershipAction['AllowedPeriodStart'] : _t("_no limit") ?></td>
        <td><?= $membershipAction['AllowedPeriodEnd'] ? $membershipAction['AllowedPeriodEnd'] : _t("_no limit") ?></td>
</tr>
<?php
    }
?>
</table>

<?php
    return ob_get_clean();
}

/**
 * Prints HTML Code for explanation
 */
function PageMainCode() {
    $iType = bx_process_input(bx_get('type'), BX_DATA_INT);
    $sExplain = bx_process_input(bx_get('explain'));
    switch ($sExplain)
    {
        case 'Unconfirmed': return _t("_ATT_UNCONFIRMED_E");
        case 'Approval': return _t("_ATT_APPROVAL_E");
        case 'Active': return _t("_ATT_ACTIVE_E");
        case 'Rejected': return _t("_ATT_REJECTED_E");
        case 'Suspended': return _t("_ATT_SUSPENDED_E", getParam('site_title'));
        case 'membership': return membershipActionsList($iType);
        default: return false;
    }
}