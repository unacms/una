<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolRequest.php' );
if(isset($aRequest[0]) && substr($aRequest[0], 0, 4) == 'act_') {
    $aRequest[0] = str_replace('act_', '', $aRequest[0]);
    echo BxDolRequest::processAsAction($aModule, $aRequest);
} else
    BxDolRequest::processAsAction($aModule, $aRequest);
