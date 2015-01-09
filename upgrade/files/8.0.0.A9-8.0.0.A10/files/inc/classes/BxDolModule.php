<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Base class for Module classes in modules engine.
 *
 * The object of the class contains major objects of the whole module. They are:
 * a. An object of config class
 * @see BxDolModuleConfig
 *
 * b. An object of database class.
 * @see BxDolModuleQuery
 *
 * c. An object of template class.
 * @see BxDolTemplate
 *
 *
 * Example of usage:
 * refer to any BoonEx module
 *
 *
 * Static Methods:
 *
 * Get an instance of a module's class.
 * @see BxDolModule::getInstance($sClassName)
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */
class BxDolModule extends BxDol
{
    public $_aModule;
    public $_oDb;
    public $_oTemplate;
    public $_oConfig;

    /**
     * constructor
     */
    function __construct($aModule)
    {
        parent::__construct();

        $this->_aModule = $aModule;

        $sClassPrefix = $aModule['class_prefix'];
        $sClassPath = BX_DIRECTORY_PATH_MODULES . $aModule['path'] . 'classes/';

        $sClassName = $sClassPrefix . 'Config';
        require_once($sClassPath . $sClassName . '.php');
        $this->_oConfig = new $sClassName($aModule);

        $sClassName = $sClassPrefix . 'Db';
        require_once($sClassPath . $sClassName . '.php');
        $this->_oDb = new $sClassName($this->_oConfig);

        $sClassName = $sClassPrefix . 'Template';
        require_once($sClassPath . $sClassName . '.php');
        $this->_oTemplate = new $sClassName($this->_oConfig, $this->_oDb);
        $this->_oTemplate->loadTemplates();
    }

    /**
     * Static method to get an instance of a module's class.
     *
     * @param $sName module name.
     */
    public static function getInstance($sName)
    {
        if (empty($sName))
            return null;

        bx_import('BxDolModuleQuery');
        $aModule = BxDolModuleQuery::getInstance()->getModuleByName($sName);
        if (empty($aModule) || !is_array($aModule))
            return null;

        $sClassName = $aModule['class_prefix'] . 'Module';

        if ('system' != $sName) {

            if (isset($GLOBALS['bxDolClasses'][$sClassName]))
                return $GLOBALS['bxDolClasses'][$sClassName];

            $sClassPath = BX_DIRECTORY_PATH_MODULES . $aModule['path'] . '/classes/' . $sClassName . '.php';
            if (!file_exists($sClassPath))
                return null;
            require_once($sClassPath);

            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName($aModule);
            return $GLOBALS['bxDolClasses'][$sClassName];

        } else {

            $sClassName = 'BxTemplServices';
            return bx_instance($sClassName, array($aModule));

        }
    }

    public static function getTitle($sUri)
    {
        return _t(self::getTitleKey($sUri));
    }

    public static function getTitleKey($sUri)
    {
        return '_sys_module_' . strtolower(str_replace(' ', '_', $sUri));
    }

	/**
     * is module enabled
     */
    function isEnabled()
    {
        return (int)$this->_aModule['enabled'] == 1;
    }

    /**
     * get module name
     */
    function getName()
    {
        return $this->_aModule['name'];
    }

    /**
     * Check whether user logged in or not.
     *
     * @return boolean result of operation.
     */
    public function isLogged()
    {
        return isLogged();
    }

    /**
     * Get currently logged in user ID.
     *
     * @return integer user ID.
     */
    public function getUserId()
    {
        return getLoggedId();
    }

    /**
     * Get currently logged in user password.
     *
     * @return string user password.
     */
    public function getUserPassword ()
    {
        return getLoggedPassword();
    }
}

/** @} */
