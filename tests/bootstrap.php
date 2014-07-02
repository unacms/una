<?php

define('BX_SKIP_INSTALL_CHECK', 1);

$aPathInfo = pathinfo(__FILE__);
$sHeaderPath = $aPathInfo['dirname'] . '/../inc/header.inc.php';
if (!file_exists($sHeaderPath))
    die("Script is not installed\n");

require_once($sHeaderPath);

class BxDolTestCase extends PHPUnit_Framework_TestCase
{

    function bxMockGet ($sClass, $aModule = array(), $bDisableContructor = false)
    {
        if ($aModule)
            bx_import(bx_ltrim_str($sClass, $aModule['class_prefix']), $aModule);
        else
            bx_import($sClass);

        if ($bDisableContructor) {
            $GLOBALS['bxDolClasses'][$sClass] = $this->getMockBuilder($sClass)
                ->disableOriginalConstructor()
                ->getMock();
        } else {
            $GLOBALS['bxDolClasses'][$sClass] = $this->getMock($sClass);
        }

        return $GLOBALS['bxDolClasses'][$sClass];
    }

    function bxMockFree (&$o)
    {
        $sClassName = bx_ltrim_str(get_class($o), 'Mock_');
        $sClassName = preg_replace('/_[A-Za-z0-9]+$/', '', $sClassName);
        unset($GLOBALS['bxDolClasses'][$sClassName]);
        unset($o);
    }

}
