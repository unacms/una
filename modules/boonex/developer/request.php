<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolRequest.php' );

if (isset($aRequest[0]) && 0 == strncmp($aRequest[0], 'act_', 4)) {
    $aRequest[0] = str_replace('act_', '', $aRequest[0]);
    // TODO: is header with 'utf8' encoding needed here ?
    echo BxDolRequest::processAsAction($aModule, $aRequest);
} else {
    BxDolRequest::processAsAction($aModule, $aRequest);
}

/** @} */
