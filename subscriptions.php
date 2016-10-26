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

$sUrl = BxDolPayments::getInstance()->getSubscriptionsUrl();
if(empty($sUrl))
	BxDolTemplate::getInstance()->displayPageNotFound();

header('Location: ' . $sUrl);
exit;

/** @} */
