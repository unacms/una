<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

// TODO: move it to bx_process_input

list ($iImpactLog, $iImpactBlock) = bx_sys_security_get_impact_threshold ();

if ((-1 != $iImpactLog || -1 != $iImpactBlock) && !defined('BX_DOL_CRON_EXECUTE')) {

    if (version_compare(phpversion(), '5.1.6', '>=')) {

        set_include_path (
            get_include_path()
            . PATH_SEPARATOR
            . BX_DIRECTORY_PATH_PLUGINS . 'phpids/'
        );

        require_once 'IDS/Init.php';
        $request = array(
            'GET' => $_GET,
            'POST' => $_POST,
            'COOKIE' => $_COOKIE,
            'PHP_SELF' => $_SERVER['PHP_SELF'],
        );
        $init = IDS_Init::init(BX_DIRECTORY_PATH_PLUGINS . 'phpids/IDS/Config/Config.ini');
        $init->config['General']['base_path'] = BX_DIRECTORY_PATH_PLUGINS . 'phpids/IDS/';
        $init->config['General']['use_base_path'] = true;
        $init->config['General']['tmp_path'] = '../../../tmp/';
        $init->config['Caching']['path'] = '../../../tmp/default_filter.cache';


        if (defined('BX_SECURITY_JSON') && is_array($aBxSecurityJSON)) {
            $init->config['General']['json'] = array_merge ($init->config['General']['json'], $aBxSecurityJSON);
        }
        $init->config['General']['json'] = array_merge($init->config['General']['json'], bx_sys_security_get_fields ('json'));


        if (defined('BX_SECURITY_HTML') && is_array($aBxSecurityHTML)) {
            $init->config['General']['html'] = array_merge ($init->config['General']['html'], $aBxSecurityHTML);
        }
        $init->config['General']['html'] = array_merge($init->config['General']['html'], bx_sys_security_get_fields ('html'));


        if (defined('BX_SECURITY_EXCEPTIONS') && is_array($aBxSecurityExceptions)) {
            $init->config['General']['exceptions'] = array_merge ($init->config['General']['exceptions'], $aBxSecurityExceptions);
        }
        $init->config['General']['exceptions'] = array_merge($init->config['General']['exceptions'], bx_sys_security_get_fields ('exceptions'));


        $init->config['General']['HTML_Purifier_Path'] = BX_DIRECTORY_PATH_PLUGINS . 'htmlpurifier/HTMLPurifier.standalone.php';
        $init->config['General']['HTML_Purifier_Cache'] = '../../htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer/';

        $ids = new IDS_Monitor($request, $init);
        $result = $ids->run();


        if (!$result->isEmpty() && $result->getImpact() >= $iImpactLog) {

            require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');
            require_once(BX_DIRECTORY_PATH_INC . 'db.inc.php');
            bx_import('BxDolService');

            $s = (string)$result;
            $s .=  "\nREMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'];
            $s .=  "\nHTTP_X_FORWARDED_FOR: " . $_SERVER['HTTP_X_FORWARDED_FOR'];
            $s .=  "\nHTTP_CLIENT_IP: " . $_SERVER['HTTP_CLIENT_IP'];
            $s .=  "\nSCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'];
            $s .=  "\nQUERY_STRING: " . $_SERVER['QUERY_STRING'];
            $s .=  "\nREQUEST_URI: " . $_SERVER['REQUEST_URI'];
            $s .=  "\nQUERY_STRING: " . $_SERVER['QUERY_STRING'];
            $s .=  "\nSCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'];
            $s .=  "\nPHP_SELF: " . $_SERVER['PHP_SELF'];
            if ($result->getImpact() >= $iImpactBlock) {
                sendMail(getParam('site_email_bug_report'), BX_DOL_URL_ROOT . ' -  security attack was stopped!', $s, 0, array(), BX_EMAIL_NOTIFY, 'text'); // TODO: email template
                echo 'Possible security attack!!! All data has been collected and sent to the site owner for analysis.';
                exit;
            } else {
                sendMail(getParam('site_email_bug_report'), BX_DOL_URL_ROOT . ' -  possible security attack!', $s, 0, array(), BX_EMAIL_NOTIFY, 'text'); // TODO: email template
            }
        }
    } else {
        echo 'Site security module is disabled, please upgrade to php 5.1.6 or higher to make your site secure.';
    }
}

function bx_sys_security_get_fields ($sType) {

    switch ($sType) {
    case 'html':
    case 'json':
    case 'exceptions':
        break;
    default:
        return array();
    }

    $sCacheFile = BX_DIRECTORY_PATH_CACHE . 'sys_options_' . bx_site_hash() . '.php';
    if (!file_exists($sCacheFile)) {
        require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
        require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
        $mixedVar = getParam("sys_{$sType}_fields");
    } else {
        include $sCacheFile;
        $mixedVar = $mixedData["sys_{$sType}_fields"];
        $mixedData = null;
    }

    $mixedVar = unserialize ($mixedVar);
    if (!$mixedVar || !is_array($mixedVar))
        return array ();
    $a = array ();
    foreach ($mixedVar as $r)
        $a = array_merge ($a, $r);

    return $a;
}

function bx_sys_security_get_impact_threshold () {
    // TODO: remake security to check in bx_process_input function only
    $sCacheFile = BX_DIRECTORY_PATH_CACHE . 'sys_options_' . bx_site_hash('', true) . '.php';
    if (!file_exists($sCacheFile)) {
        require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
        require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
        return array (getParam('sys_security_impact_threshold_log'), getParam('sys_security_impact_threshold_block'));
    } else {
        include $sCacheFile;
        $iThresholdLog = $mixedData['sys_security_impact_threshold_log'];
        $iThresholdBlock = $mixedData['sys_security_impact_threshold_block'];
        $mixedData = null;
        return array ($iThresholdLog, $iThresholdBlock);
    }
}

