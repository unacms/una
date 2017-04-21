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

bx_import('BxDolLanguages');

check_logged();

$iSellerId = 0;
if(bx_get('seller_id') !== false)
    $iSellerId = (int)bx_get('seller_id');

$sUrl = BxDolPayments::getInstance()->getCartUrl($iSellerId);
if(empty($sUrl))
	BxDolTemplate::getInstance()->displayPageNotFound();

header('Location: ' . $sUrl);
exit;

/** @} */
