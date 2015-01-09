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
bx_import('BxDolSearch');

$o = new BxDolSearch(bx_get('section'));
$o->setLiveSearch(bx_get('live_search') ? 1 : 0);
$s = $o->response();
if (!$s)
    $s = $o->getEmptyResult();

header('Content-type: text/html; charset=utf-8');
echo $s;

/** @} */
