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

bx_import('BxDolLanguages');

check_logged();

$sLink = BxDolPayments::getInstance()->getCartLink();
if(empty($sLink))
	BxDolTemplate::getInstance()->displayPageNotFound();

header('Location: ' . $sLink);
exit;

/** @} */
