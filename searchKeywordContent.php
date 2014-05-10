<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');
bx_import('BxDolSearch');

$o = new BxDolSearch(bx_get('section'));
$s = $o->response();
if (!$s)
    $s = $o->getEmptyResult();

header('Content-type: text/html; charset=utf-8');
echo $s;
