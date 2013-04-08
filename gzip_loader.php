<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');

bx_import('BxDolGzip');

$sFile = bx_process_input($_GET['file']);
BxDolGzip::load($sFile);
