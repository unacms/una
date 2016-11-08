<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Sites Sites
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

check_logged();

bx_import('BxDolLanguages');

BxDolRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

/** @} */
