<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * WIKI object.
 *
 * It's possble to create different WIKI object which will different URLs, Menus and permissions.
 * For example it's possible to create 
 * http://example.com/wiki/somepageshere and http://example.com/cocs/anotherpageshere
 *
 * @section wiki_create Creating the WIKI object:
 *
 *
 * Add record to 'sys_objects_wiki' table:
 *
 * - object: name of the WIKI object, this name will be user in URL as well, 
 *          for example, 'wiki' will have URLs like this: http://example.com/wiki/somepageshere
 * - title: title of the WIKI, for example, documentation, help, tutorial
 * - module: module name WIKI object belongs to
 * - allow_add_for_levels: allow to add pages and blocks for this member levels
 * - allow_edit_for_levels: allow to edit block for this member levels
 * - allow_delete_for_levels: allow to delet pages and blocks for this member levels
 * - allow_translate_for_levels: allow to add translations for this member levels
 * - override_class_name: user defined class name 
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 * Add record to 'sys_rewrite_rules' table:
 * - preg - regular expression which matches some URL
 * - service - service method to call if abbe regular expression matches
 * - active - active flag
 *
 * Add record to 'sys_permalinks' table:
 * - standard - how link should look like when permalinks are off
 * - permalink - how link should look like when permalinks are on, 
 *      some special server configuration may be required to make permalink to work, 
 *      such as 'mod_rewrite' and .htaccess file for Apache
 * - check - option name which enables/disables permalinks
 * - compare_by_prefix - compare by prefix
 */
class BxDolWiki extends BxDolFactory implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;

    /**
     * Constructor
     * @param $aObject array of WIKI options
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        $this->_oQuery = new BxDolWikiQuery($aObject);
    }

    /**
     * Get WIKI object instance by object URI
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstanceByUri($sUri, $oTemplate = false)
    {
        $aObject = BxDolWikiQuery::getWikiObjectByUri($sUri);
        if (!$aObject || !is_array($aObject))
            return false;

        return self::getObjectInstance($aObject['object'], $oTemplate);
    }

    /**
     * Get WIKI object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject, $oTemplate = false)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolWiki!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolWiki!'.$sObject];

        $aObject = BxDolWikiQuery::getWikiObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = empty($aObject['override_class_name']) ? 'BxDolWiki' : $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass($aObject, $oTemplate);

        return ($GLOBALS['bxDolClasses']['BxDolWiki!'.$sObject] = $o);
    }

    /**
     * Get object name
     */
    public function getObjectName ()
    {
        return $this->_sObject;
    }

    /**
     * Get WIKI block content
     * @param $iBlockId block ID
     * @param $sLang optional language name
     * @return block content
     */
    public function getBlockContent ($iBlockId, $sLang = false)
    {
        if (!$sLang)
            $sLang = bx_lang_name();
        $s = $this->_oQuery->getBlockContent ($iBlockId, $sLang);

        require_once(BX_DIRECTORY_PATH_PLUGINS . "parsedown/Parsedown.php");
        $oParsedown = new Parsedown();
        $s = $oParsedown->text($s);

        $s = $this->addControls($iBlockId, $s);

        return $s;
    }

    /**
     * Add controls for edit, delete, translate, history, etc content
     * @param $iBlockId block ID
     * @param $sLang optional language name
     * @return block content
     */
    public function addControls ($iBlockId, $s)
    {
        $sControl = "<div>TODO: wiki - controls here. On left - Edit, Delete version, Delete block, Translate, History. On right - Last modified time and List of missing and outdated translations. History and Last modified time should be controlled by regular menu privacy, while other actions should have custom privacy for particular wiki object.</div>";
        return $s . $sControl;
    }

    /**
     * Check if partucular action is allowed
     * @param $sType action type: add, edit, delete, translate
     * @param $sProfileId profile to check, if not provided then current profile is used
     * @return true if action is allowed, false otherwise
     */
    public function isAllowed ($sType, $iProfileId = false)
    {
        $aTypes = array(
            'add' => 'allow_add_for_levels',
            'edit' => 'allow_edit_for_levels',
            'delete' => 'allow_delete_for_levels',
            'translate' => 'allow_translate_for_levels',
        );
        if (!isset($aTypes[$sType]) || !isset($this->_aObject[$aTypes[$sType]]))
            return false;

        $oAcl = BxDolAcl::getInstance();
        return $oAcl->isMemberLevelInSet($this->_aObject[$aTypes[$sType]], $iProfileId); 
    }
}

/** @} */
