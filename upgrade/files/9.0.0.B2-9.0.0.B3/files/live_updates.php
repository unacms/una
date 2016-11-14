<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
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
