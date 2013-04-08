<?php

// TODO: decide later what to do with profiles functionality

require_once('./inc/header.inc.php');
require_once( BX_DIRECTORY_PATH_INC  . 'design.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'admin.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'db.inc.php');

bx_import('BxDolProfileFields');
bx_import('BxDolProfilesController');
bx_import("BxTemplProfileView");
bx_import("BxTemplProfileView");
bx_import("BxTemplSearchProfile");

check_logged();

bx_import('BxDolTemplate');
$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex(7);
$oTemplate->setPageParams(array(
    'header' => _t('_People_Calendar')
));
$oTemplate->setPageContent('page_main_code', getBlockCode_Results(100));
PageCode();

function getBlockCode_Results($iBlockID) {
    $sAction = strip_tags($_GET['action']);
    switch ($sAction) {
        case 'browse':
            $sCode = getProfilesByDate($_GET['date']);
            break;
        default:
            $sCode = getCalendar();
    }
    return $sCode;
}

function getProfilesByDate ($sDate) {
    $sDate = strip_tags($sDate);
    $aDateParams = explode('/', $sDate);
    $oSearch = new BxTemplSearchProfile('calendar', (int)$aDateParams[0], (int)$aDateParams[1], (int)$aDateParams[2]);
    $oSearch -> aConstants['linksTempl']['browseAll'] = 'calendar.php?';

    $sCode = $oSearch->displayResultBlock();
    return $oSearch->displaySearchBox('<div class="search_container">'
        . $sCode . '</div>', $oSearch->showPagination(false, false, false));
}

function getCalendar () {
    $oProfile = new BxBaseProfileGenerator(getLoggedId());
    $mSearchRes = $oProfile->GenProfilesCalendarBlock();
    list($sResults, $aDBTopMenu, $sPagination, $sTopFilter) = $mSearchRes;
    return DesignBoxContent(_t('_People_Calendar'), $sResults, 1);
}

?>
