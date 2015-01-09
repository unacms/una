<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioJson extends BxDol implements iBxDolSingleton
{
    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct ();
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance()
    {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolStudioJson();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function load($sUrl, $aParams = array())
    {
        $sContent = bx_file_get_contents($sUrl, $aParams);
        if(empty($sContent))
            return false;

        //echo $sContent; exit;		//--- Uncomment to debug
        $mixedResult = json_decode($sContent, true);
        if(is_null($mixedResult))
            return false;

        return $mixedResult;
    }
}

/** @} */
