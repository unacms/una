<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oLiveUpdates = BxDolLiveUpdates::getInstance();

if($oLiveUpdates) {
    $aResult = $oLiveUpdates->perform();

    header('Content-type: text/html; charset=utf-8');
	echo json_encode($aResult);
}

/** @} */
