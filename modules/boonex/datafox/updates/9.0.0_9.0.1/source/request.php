<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DataFox Data Fox API integration
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php');

check_logged();

BxDolRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

/** @} */
