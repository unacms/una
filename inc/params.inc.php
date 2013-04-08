<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

if (defined('BX_PROFILER') && BX_PROFILER) require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/profiler/classes/BxProfiler.php');

// if IP is banned - total block
if ((int)getParam('ipBlacklistMode') == 1 && bx_is_ip_blocked()) {
    echo _t('_Sorry, your IP been banned');
    exit;
}

