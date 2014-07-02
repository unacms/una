<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDol');

/**
 * System class for main configuration settings.
 */
class BxDolConfig extends BxDol implements iBxDolSingleton
{
    protected $aUrlStatic;
    protected $aUrlDynamic;

    protected $aPathStatic;
    protected $aPathDynamic;

    protected $aDb;

    /**
     * constructor
     */
    protected function __construct()
    {
        if(isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->aUrlStatic = array();
        $this->aUrlDynamic = array();

        $this->aPathStatic = array();
        $this->aPathDynamic = array();

        $this->aDb = array();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolConfig();
            $GLOBALS['bxDolClasses'][__CLASS__]->init();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function init()
    {
        $this->aUrlDynamic = array();

        $this->aPathDynamic = array();

        $this->aDb = array(
            'visual_processing' => true,
            'debug_mode' => true,
            'error_remort_by_email' => false
        );
    }

    function get($sGroup, $sName)
    {
        return $this->{$this->getVarName($sGroup)}[$sName];
    }

    function getGroup($sGroup)
    {
        return $this->{$this->getVarName($sGroup)};
    }

    function set($sGroup, $sName, $sValue, $bDefine = false)
    {
        if($bDefine)
            define($this->getDefineName($sGroup, $sName), $sValue);
        else
            $this->{$this->getVarName($sGroup)}[$sName] = $sValue;
    }

    function setGroup($sGroup, $aValue, $bDefine = false)
    {
        if($bDefine)
            foreach($aValue as $sName => $sValue)
                define($this->getDefineName($sGroup, $sName), $sValue);
        else {
            $sGroup = $this->getVarName($sGroup);

            if(!empty($this->$sGroup) && is_array($this->$sGroup))
                $this->$sGroup = array_merge($this->$sGroup, $aValue);
            else
                $this->$sGroup = $aValue;
        }
    }

    private function getVarName($sVar)
    {
        return 'a' . bx_gen_method_name($sVar);
    }

    private function getDefineName()
    {
        $aArgs = func_get_args();
        return 'BX_' . strtoupper(implode('_', $aArgs));
    }
}

/** @} */
