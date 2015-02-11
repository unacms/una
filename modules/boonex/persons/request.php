<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     TridentModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

check_logged();

BxBaseModProfileRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

/** @} */
