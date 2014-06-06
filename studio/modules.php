<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinEndAdmin Dolphin Studio End Admin Pages 
 * @ingroup     DolphinStudio
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
require_once(BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php');

bx_import('BxDolLanguages');

bx_require_authentication(true);

bx_import('BxTemplStudioModules');
$oModules = new BxTemplStudioModules();
$oModules->processActions();
/** @} */
