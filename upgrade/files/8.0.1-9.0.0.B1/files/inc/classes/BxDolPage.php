<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects
 * @section page Page
 * @ref BxDolPage
 */

/**
 * Pages.
 *
 * It allows to display pages which are built from studio.
 *
 * The new system has the following page features:
 * - Layouts: page can have any structure, not just columns.
 * - SEO: page can have own SEO options, like meta tags and meta keywords, as well as instructions for search bots.
 * - Cache: page can be cached on the server.
 * - Access control: page access can be controlled using member levels.
 *
 *
 *
 * @section page_create Creating the Page object:
 *
 * 1. add record to 'sys_objects_page' table:
 *
 * - object: name of the page object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing; for example: bx_profiles_view - profile view page.
 * - title: name to display as page title.
 * - module: the module this page belongs to.
 * - layout_id: page layout to use, this is id of the record from 'sys_pages_layouts' table.
 * - visible_for_levels: bit field with set of member level ids. To use member level id in bit field - the level id minus 1 as power of 2 is used, for example:
 *      - user level id = 1 -> 2^(1-1) = 1
 *      - user level id = 2 -> 2^(2-1) = 2
 *      - user level id = 3 -> 2^(3-1) = 4
 *      - user level id = 4 -> 2^(4-1) = 8
 * - visible_for_levels_editable: it determines if 'visible_for_levels' field is editable from page builder, visibility options can be overriden by custom class and shouldn't be editable in this case.
 * - url: the page url, if it is static page.
 * - meta_description: meta description of the page.
 * - meta_keywords: meta keywords of the page.
 * - meta_robots: instructions for search bots.
 * - cache_lifetime: number of seconds to store cache for.
 * - cache_editable: it determines if cache can be edited from page builder.
 * - deletable: it determines if page can be deleted from page builder.
 * - override_class_name: user defined class name which is derived from BxTemplPage.
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 * Page can select appropriate menu automatically if 'module' and 'object' fields in 'sys_objects_page' table are matched with 'module' and 'name' fields in 'sys_menu_items' table.
 *
 *
 * Page layout are stored in 'sys_pages_layouts' table:
 * - name: inner unique layout name.
 * - icon: layout icon to display in page builder, it should represent basic view of the layout to help studio operator determine the layout structure.
 * - title: layout name to display in page builder.
 * - template: template name to use to display page with certain layout.
 * - cells_number: number of areas in the layout, page blocks can be places into this areas(cells).
 * To define areas in the layout they should be named as '__cell_N__', where N is cell number, starting from 1.
 *
 *
 * 2. Add page blocks to 'sys_pages_blocks' table:
 * - object: page object name this block belongs to.
 * - cell_id: cell number in page layout to place block to.
 * - module: module name this block belongs to.
 * - title: block title.
 * - designbox_id: design box to use to diplay page block, it is id of the record from 'sys_pages_design_boxes' table.
 * - visible_for_levels: bit field with set of member level ids. To use member level id in bit field - the level id minus 1 as power of 2 is used, for example:
 *      - user level id = 1 -> 2^(1-1) = 1
 *      - user level id = 2 -> 2^(2-1) = 2
 *      - user level id = 3 -> 2^(3-1) = 4
 *      - user level id = 4 -> 2^(4-1) = 8
 * - type: block type
 *      - raw: HTML block, displayed in page builder as HTML textarea.
 *      - html: HTML block, displayed in page builder as visual editor, like TinyMCE.
 *      - lang: translatable language string, displayed in page builder as editable language key.
 *      - image: just an image, displayed in page builder as HTML upload form.
 *      - rss: RSS block, displayed in page builder as editable URL to RSS resource, along with number of displayed items.
 *      - menu: menu block, displayed as menu selector.
 *      - service: to display block content, the provided service method is used.
 * - content: depending on 'type' field:
 *      - raw: HTML string.
 *      - html: HTML string.
 *      - lang: language key.
 *      - image: image id in the storage and alignment (left, center, right) for example: 36#center
 *      - rss: URL to RSS with number of displayed items, for example: http://www.example.com/rss#4
 *      - menu: menu object name.
 *      - service: serialized array of service call parameters: module - module name, method - service method name, params - array of parameters.
 * - deletable: is block deletable from page builder
 * - copyable: is block can be copied to any other page from page builder.
 * - order: block order in particular cell.
 *
 * Block design boxes are stored in 'sys_pages_design_boxes' table:
 * - id: consistent id, there are the following defines can be used in the code for each system block style:
 *      - 0 - BX_DB_CONTENT_ONLY: design box with content only - no borders, no background, no caption.
 *      - 1 - BX_DB_DEF: default design box with content, borders and caption.
 *      - 2 - BX_DB_EMPTY: just empty design box, without anything.
 *      - 3 - BX_DB_NO_CAPTION: design box with content, like BX_DB_DEF but without caption.
 *      - 10 - BX_DB_PADDING_CONTENT_ONLY: design box with content only wrapped with default padding - no borders, no background, no caption; it can be used to just wrap content with default padding.
 *      - 11 - BX_DB_PADDING_DEF: default design box with content wrapped with default padding, borders and caption.
 *      - 13 - BX_DB_PADDING_NO_CAPTION: design box with content wrapped with default padding, like BX_DB_DEF but without caption.
 * - title: block name which is displayed in studio, describes block styles.
 * - template: template name to use to display page block.
 *
 *
 * 3. Display Page.
 * Use the following sample code to display page:
 * @code
 *     $oPage = BxDolPage::getObjectInstance('sample'); // 'sample' is 'object' field from 'sys_objects_page' table, it automatically creates instance of default or custom class by object name
 *     if ($oPage)
 *         echo $oPage->getCode(); // print page
 * @endcode
 *
 */
class BxDolPage extends BxDol implements iBxDolFactoryObject, iBxDolReplaceable
{
    protected $_sObject;
    protected $_aObject;
    protected $_oQuery;
    protected $_aMarkers = array ();

    /**
     * Constructor
     * @param $aObject array of page options
     */
    public function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        $this->_oQuery = new BxDolPageQuery($this->_aObject);
    }

    /**
     * Get page object instance by page URI
     * @param $sURI unique page URI
     * @return object instance or false on error
     */
    static public function getObjectInstanceByURI($sURI)
    {
        $sObject = BxDolPageQuery::getPageObjectNameByURI($sURI);
        return $sObject ? self::getObjectInstance($sObject) : false;
    }

    /**
     * Get page object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolPage!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolPage!'.$sObject];

        $aObject = BxDolPageQuery::getPageObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxTemplPage';
        if (!empty($aObject['override_class_name'])) {
            $sClass = $aObject['override_class_name'];
            if (!empty($aObject['override_class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);
        }

        $o = new $sClass($aObject);

        return ($GLOBALS['bxDolClasses']['BxDolPage!'.$sObject] = $o);
    }

	/**
     * Process page triggers.
     * Page triggers allow to automatically add page blocks to modules with no different if dependant module was install before or after the module page block belongs to.
     * For example module "Notes" adds page blocks to all profiles modules (Persons, Organizations, etc)
     * with no difference if persons module was installed before or after "Notes" module was installed.
     * @param $sPageTriggerName trigger name to process, usually specified in module installer class - @see BxBaseModGeneralInstaller
     * @return always true, always success
     */
    static public function processPageTrigger ($sPageTriggerName)
    {
        // get list of active modules
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array(
            'type' => 'modules',
            'active' => 1,
        ));

        // get list of page block triggers
        $aPageBlocks = BxDolPageQuery::getPageTriggers($sPageTriggerName);

        // check each page block trigger for all modules
        foreach ($aPageBlocks as $aPageBlock) {
            foreach ($aModules as $aModule) {
                if (!BxDolRequest::serviceExists($aModule['name'], 'get_page_object_for_page_trigger'))
                    continue;

				$mixedPageObject = BxDolService::call($aModule['name'], 'get_page_object_for_page_trigger', array($sPageTriggerName));
                if (!$mixedPageObject)
                    continue;

                $aPageBlockRow = $aPageBlock;

                if (is_array($mixedPageObject))
                    $aPageBlockRow = array_merge($aPageBlockRow, $mixedPageObject);
                else
                    $aPageBlockRow['object'] = $mixedPageObject;
                
                BxDolPageQuery::addPageBlockToPage($aPageBlockRow);
            }
        }

        return true;
    }

    /**
     * Add replace markers. Markers are replaced in raw, html, lang blocks and page title, description, keywords and block titles.
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
     * Replace provided markers in a string
     * @param $mixed string or array to replace markers in
     * @return string where all occured markers are replaced
     */
    protected function _replaceMarkers ($mixed)
    {
        return bx_replace_markers($mixed, $this->_aMarkers);
    }

    /**
     * Check if page block is visible.
     */
    protected function _isVisibleBlock ($a)
    {
        return BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']);
    }

    /**
     * Check if page is visible.
     */
    protected function _isVisiblePage ($a)
    {
        return BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']);
    }

}

/** @} */
