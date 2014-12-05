<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolMenuQuery');
bx_import('BxDolPermalinks');

/**
 * @page objects
 * @section menu Menu
 * @ref BxDolMenu
 */

/**
 * Menus.
 *
 * Menu uses some set of items and the template to display it,
 * so it is possible to have several menus which uses the same set of items but different templates.
 *
 * Menu is any set of some links or actions, for example menu can be links in site's footer or actions in profile view.
 *
 *
 * @section menu_create Creating the Menu object:
 *
 * 1. Add record to 'sys_objects_menu' table:
 *
 * - object: name of the menu object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing; for example: bx_groups_actions - actions menu in group view.
 * - title: name of the menu, displayed in the studio menu builder.
 * - set_name: name of items' set.
 * - module: the module this menu belongs to.
 * - template_id: the template to use for menu displaying, this is id from 'sys_menu_templates' table.
 * - deletable: it determines if menu can be deleted from the studio menu builder.
 * - active: it is possible to disable particular menu, then it will not be displayed.
 * - override_class_name: user defined class name which is derived from BxTemplMenu.
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 * Menu templates are stored in 'sys_menu_templates' table:
 * - id: template id.
 * - template: template file.
 * - title: template title to display in the studio menu builder.
 * All menu templates iterate through 'bx_repeat:menu_items' and use the following template variables for each menu item: __link__, __target__, __onclick__, __title__, __class_add__.
 *
 *
 * 2. Add menu an empty menu set to 'sys_menu_sets' table (if you want to use new set of items for created menu):
 * - set_name: the set name.
 * - module: the module this set belongs to.
 * - title: name of the set, displayed in studio menu builder.
 * - deletable: it determines if the set can be deleted from menu builder.
 *
 *
 * 3. Add menu items to the set by adding records to 'sys_menu_items' table:
 * - set_name: the set name this item belogs to.
 * - module: the module this item belongs to.
 * - name: name of the item (not displayed to the end user), unique in the particular set.
 * - title: menu item title to display to the end user, please note that some templates can still display menu as icons without text titles.
 * - link: menu item URL.
 * - onclick: menu item onclick event.
 * - target: menu item target.
 * - icon: menu item icon, please note that some templates can still display menu as text without icons.
 * - addon: display additional data near menu item, only for supported menu templates, this is serialized array of service call parameters: module - module name, method - service method name, params - array of parameters.
 * - visible_for_levels: bit field with set of member level ids. To use member level id in bit field - the level id minus 1 as power of 2 is used, for example:
 *      - user level id = 1 -> 2^(1-1) = 1
 *      - user level id = 2 -> 2^(2-1) = 2
 *      - user level id = 3 -> 2^(3-1) = 4
 *      - user level id = 4 -> 2^(4-1) = 8
 * - active: it is possible to disable particular menu item, then it will not be displayed.
 * - order: menu item order in the particular set.
 *
 *
 * 4. Display Menu.
 * Use the following sample code to display menu:
 * @code
 *     bx_import('BxTemplMenu');
 *     $oMenu = BxTemplMenu::getObjectInstance('sample_menu'); // 'sample_menu' is 'object' field from 'sys_objects_menu' table.
 *     if ($oMenu)
 *         echo $oMenu->getCode; // display menu
 * @endcode
 *
 * But in most cases you don't need to use above code to display menu,
 * menu objects are integrated into pages - there is special 'menu' page block type for it.
 *
 */
class BxDolMenu extends BxDol implements iBxDolFactoryObject, iBxDolReplaceable
{
    protected static $SEL_MODULE = '';
    protected static $SEL_NAME = '';

    protected $_sSelModule;
    protected $_sSelName;

    protected $_sObject;
    protected $_aObject;
    protected $_oQuery;
    protected $_oPermalinks;
    protected $_aMarkers = array ();

    /**
     * Constructor
     * @param $aObject array of menu options
     */
    public function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = isset($aObject['object']) ? $aObject['object'] : 'bx-menu-obj-' . time() . rand(0, PHP_INT_MAX);
        $this->_aObject = $aObject;
        $this->_oQuery = new BxDolMenuQuery($this->_aObject);
        $this->_oPermalinks = BxDolPermalinks::getInstance();

        if (isLogged()) {
            bx_import('BxDolProfile');
            $oProfile = BxDolProfile::getInstance();
            if ($oProfile) {
                $this->addMarkers(array(
                    'member_id' => $oProfile->id(),
                    'member_display_name' => $oProfile->getDisplayName(),
                    'member_url' => $oProfile->getUrl(),
                ));
            }
        }
    }

    /**
     * Get menu object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject, $oTemplate = false)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolMenu!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolMenu!'.$sObject];

        $aObject = BxDolMenuQuery::getMenuObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        bx_import('BxTemplMenu');
        $sClass = 'BxTemplMenu';
        if (!empty($aObject['override_class_name'])) {
            $sClass = $aObject['override_class_name'];
            if (!empty($aObject['override_class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);
            else
                bx_import($sClass);
        }

        $o = new $sClass($aObject, $oTemplate);

        return ($GLOBALS['bxDolClasses']['BxDolMenu!'.$sObject] = $o);
    }

    /**
     * Set selected menu item globally.
     * @param $sModule menu item module to set as selected
     * @param $sName menu item name to set as selected
     */
    static public function setSelectedGlobal ($sModule, $sName)
    {
        self::$SEL_MODULE = $sModule;
        self::$SEL_NAME = $sName;
    }

    /**
     * Process menu triggers.
     * Menu triggers allow to automatically add menu items to modules with no different if dependant module was install before or after the module menu item belongs to.
     * For example module "Notes" adds menu items to all profiles modules (Persons, Organizations, etc)
     * with no difference if persons module was installed before or after "Notes" module was installed.
     * @param $sMenuTriggerName trigger name to process, usually specified in module installer class - @see BxBaseModGeneralInstaller
     * @return always true, always success
     */
    static public function processMenuTrigger ($sMenuTriggerName)
    {
        // get list of active modules
        bx_import('BxDolModuleQuery');
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array(
            'type' => 'modules',
            'active' => 1,
        ));

        // get list of menu triggers
        $aMenuItems = BxDolMenuQuery::getMenuTriggers($sMenuTriggerName);

        // check each menu item trigger for all modules
        foreach ($aMenuItems as $aMenuItem) {
            foreach ($aModules as $aModule) {
                if (!BxDolRequest::serviceExists($aModule['name'], 'get_menu_set_name_for_menu_trigger'))
                    continue;

                if (!($sMenuSet = BxDolService::call($aModule['name'], 'get_menu_set_name_for_menu_trigger', array($sMenuTriggerName))))
                    continue;

                $aMenuItem['set_name'] = $sMenuSet;
                BxDolMenuQuery::addMenuItemToSet($aMenuItem);
            }
        }

        return true;
    }

    /**
     * Set selected menu item for current menu object only.
     * @param $sModule menu item module to set as selected
     * @param $sName menu item name to set as selected
     */
    public function setSelected ($sModule, $sName)
    {
        $this->_sSelModule = $sModule;
        $this->_sSelName = $sName;
    }

    /**
     * Add replace markers.
     * @param $a array of markers as key => value
     * @return true on success or false on error
     */
    public function addMarkers ($a)
    {
        if (empty($a) || !is_array($a))
            return false;
        $this->_aMarkers = array_merge ($this->_aMarkers, $a);
        return true;
    }

    /**
     * Remove marker
     * @param $s marker key
     */
    public function removeMarker ($s) 
    {
        unset($this->_aMarkers[$s]);
    }

    /**
     * Check if menu items is selected.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isSelected ($a)
    {
        if ($this->_sSelModule || $this->_sSelName)
            return (!isset($a['module']) || $a['module'] == $this->_sSelModule) && (isset($a['name']) && $a['name'] == $this->_sSelName) ? true : false;
        return (!isset($a['module']) || $a['module'] == self::$SEL_MODULE) && (isset($a['name']) && $a['name'] == self::$SEL_NAME) ? true : false;
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        bx_import('BxDolAcl');
        return BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']);
    }

    /**
     * Replace provided markers in menu item array, curently markers are replaced in title, link and onclick fields.
     * @param $a menu item array
     * @return array where markes are replaced with real values
     */
    protected function _replaceMarkers ($a)
    {
        if (empty($this->_aMarkers))
            return $a;
        $aReplacebleFields = array ('title', 'link', 'onclick');
        foreach ($aReplacebleFields as $sField)
            if (isset($a[$sField]))
                $a[$sField] = bx_replace_markers($a[$sField], $this->_aMarkers);
        return $a;
    }

}

/** @} */
