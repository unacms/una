<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ChatPlus Chat+ module
 * @ingroup     TridentModules
 *
 * @{
 */

check_logged();

if ( empty($aRequest) || empty($aRequest[0]) ) {
    BxDolRequest::processAsFile($aModule, $aRequest);
} else {
    BxDolRequest::processAsAction($aModule, $aRequest);
}

/** @} */
