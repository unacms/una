<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

define('BX_DOL_REQUEST_ERROR_MODULE_NOT_FOUND', 1);
define('BX_DOL_REQUEST_ERROR_PAGE_NOT_FOUND', 2);

$GLOBALS['bxDolClasses'] = array();

class BxDolRequest extends BxDol {
    function BxDolRequest() {
        parent::BxDol();
    }
    function processAsFile($aModule, &$aRequest) {
        if(empty($aRequest) || ($sFileName = array_shift($aRequest)) == "")
            $sFileName = 'index';

        $sFile = BX_DIRECTORY_PATH_MODULES . $aModule['path'] . $sFileName . '.php';
        if(!file_exists($sFile))
            BxDolRequest::pageNotFound($sFileName, $aModule['uri']);
        else {
            if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginModule('file', ($sPrHash = uniqid(rand())), $aModule, $sFileName);
            include($sFile);
            if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endModule('file', $sPrHash);
        }
    }
    static function processAsAction($aModule, &$aRequest, $sClass = "Module") {
        $sAction = empty($aRequest) || (isset($aRequest[0]) && empty($aRequest[0])) ? 'Home' : array_shift($aRequest);
        $sMethod = 'action' . str_replace(' ', '', ucwords(str_replace('_', ' ', $sAction)));

        if(isset($GLOBALS['bx_profiler']))
            $GLOBALS['bx_profiler']->beginModule('action', ($sPrHash = uniqid(rand())), $aModule, $sClass, $sMethod);

        $mixedRet = BxDolRequest::_perform($aModule, $sClass, $sMethod, $aRequest);

        if(isset($GLOBALS['bx_profiler']))
            $GLOBALS['bx_profiler']->endModule('action', $sPrHash);

        return $mixedRet;
    }
    function processAsService($aModule, $sMethod, $aParams, $sClass = "Module") {
        if (isset($aModule['name']) && 'system' == $aModule['name'] && 'Module' == $sClass) 
            $sClass = 'BaseServices';

        $sMethod = 'service' . str_replace(' ', '', ucwords(str_replace('_', ' ', $sMethod)));

        if(isset($GLOBALS['bx_profiler']))
            $GLOBALS['bx_profiler']->beginModule('service', ($sPrHash = uniqid(rand())), $aModule, $sClass, $sMethod);

        $mixedRet = BxDolRequest::_perform($aModule, $sClass, $sMethod, $aParams, false);

        if(isset($GLOBALS['bx_profiler']))
            $GLOBALS['bx_profiler']->endModule('service', $sPrHash);

        return $mixedRet;
    }
    function serviceExists($mixedModule, $sMethod, $sClass = "Module") {
        return BxDolRequest::_methodExists($mixedModule, 'service', $sMethod, $sClass);
    }
    function actionExists($mixedModule, $sMethod, $sClass = "Module") {
        return BxDolRequest::_methodExists($mixedModule, 'action', $sMethod, $sClass);
    }
    function moduleNotFound($sModule) {
        BxDolRequest::_error('module', $sModule);
    }
    function pageNotFound($sPage, $sModule) {
        BxDolRequest::_error('page', $sPage, $sModule);
    }
    function methodNotFound($sMethod, $sModule) {
        BxDolRequest::_error('method', $sMethod, $sModule);
    }

    function _perform($aModule, $sClass, $sMethod, $aParams, $bTerminateOnError = true) {
        $sClass = $aModule['class_prefix'] . $sClass;        

        $oModule = BxDolRequest::_require($aModule, $sClass);
        if($oModule === false && $bTerminateOnError)
            BxDolRequest::methodNotFound($sMethod, $aModule['uri']);
        else if($oModule === false && !$bTerminateOnError)
            return false;

        $bMethod = method_exists($oModule, $sMethod);
        if($bMethod)
            return call_user_func_array(array($oModule, $sMethod), $aParams);
        else if(!$bMethod && $bTerminateOnError)
            BxDolRequest::methodNotFound($sMethod, $aModule['uri']);
        else if(!$bMethod && !$bTerminateOnError)
            return false;
    }
    function _require($aModule, $sClass) {
        if(!isset($GLOBALS['bxDolClasses'][$sClass])) {
            if ($aModule['path']) {
                $sFile = BX_DIRECTORY_PATH_MODULES . $aModule['path'] . 'classes/' . $sClass . '.php';
                if(!file_exists($sFile)) 
                    return false;
                require_once($sFile);
            } else {
                bx_import($sClass);
            }
            $oModule = new $sClass($aModule);
            $GLOBALS['bxDolClasses'][$sClass] = $oModule;
        }
        else
            $oModule = $GLOBALS['bxDolClasses'][$sClass];

        return $oModule;
    }
    function _methodExists($mixedModule, $sMethodType, $sMethodName, $sClass = "Module"){
        $aModule = $mixedModule;
        if(is_string($mixedModule)) {
            bx_import('BxDolModuleQuery');
            $aModule = BxDolModuleQuery::getInstance()->getModuleByUri($mixedModule);
        }

        if (!$aModule)
            return false;

        $sClass = $aModule['class_prefix'] . $sClass;
        if(($oModule = BxDolRequest::_require($aModule, $sClass)) === false)
            return false;

        $sMethod = $sMethodType . str_replace(' ', '', ucwords(str_replace('_', ' ', $sMethodName)));
        return method_exists($oModule, $sMethod);
    }
    function _error($sType, $sParam1 = '', $sParam2 = '') {
        header('Status: 404 Not Found');
        header('HTTP/1.0 404 Not Found');

        bx_import('BxDolTemplate');
        bx_import('BxDolLanguages');
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex(BX_PAGE_DEFAULT);
        $oTemplate->setPageHeader(_t("_sys_request_" . $sType . "_not_found_cpt"));
        $oTemplate->setPageContent('page_main_code', DesignBoxContent('123', MsgBox(_t("_sys_request_" . $sType . "_not_found_cnt", bx_process_output($sParam1), bx_process_output($sParam2))), BX_DB_PADDING_NO_CAPTION));
        $oTemplate->getPageCode();
        exit;
    }
}

