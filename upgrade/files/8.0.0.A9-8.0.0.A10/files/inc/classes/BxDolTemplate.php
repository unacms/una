<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

ini_set('pcre.backtrack_limit', 1000000);

define('BX_DOL_TEMPLATE_DEFAULT_CODE', 'lagoon');
define('BX_DOL_TEMPLATE_FOLDER_ROOT', 'template');

define('BX_DOL_TEMPLATE_INJECTIONS_CACHE', 'sys_injections.inc');

define('BX_DOL_TEMPLATE_CHECK_IN_BOTH', 'both');
define('BX_DOL_TEMPLATE_CHECK_IN_BASE', 'base');
define('BX_DOL_TEMPLATE_CHECK_IN_TMPL', 'tmpl');

define('BX_PAGE_DEFAULT', 0); ///< default, regular page
define('BX_PAGE_CLEAR', 2); ///< clear page, without any headers and footers
define('BX_PAGE_POPUP', 44); ///< popup page, without any headers and footers
define('BX_PAGE_TRANSITION', 150); ///< transition page with redirect to display some msg, like 'please wait', without headers footers

/**
 * Template engine.
 *
 * An object of the class allows to:
 *  1. Manage HTML templates.
 *  2. Get URL/path for any template image/icon.
 *  3. Attach CSS/JavaScript files to the output.
 *  4. Add some content to any template key using Injection engine.
 *
 *
 * Avalable constructions.
 *  1. <bx_include_auto:template_name.html /> - the content of the file would be inserted. File would be taken from current template if it existes there, and from base directory otherwise.
 *  2. <bx_include_base:template_name.html /> - the content of the file would be inserted. File would be taken from base directory.
 *  3. <bx_include_tmpl:template_name.html /> - the content of the file would be inserted. File would be taken from tmpl_xxx directory.
 *  4. <bx_url_root /> - the value of BX_DOL_URL_ROOT variable will be inserted.
 *  5. <bx_url_admin /> - the value of BX_DOL_URL_ADMIN variable will be inserted.
 *  6. <bx_text:_language_key /> - _language_key will be translated using language file(function _t()) and inserted.
       <bx_text_js:_language_key /> - _language_key will be translated using language file(function _t()) and inserted, use it to insert text into js string.
       <bx_text_attribute:_language_key /> - _language_key will be translated using language file(function _t()) and inserted, use it to insert text into html attribute.
 *  7. <bx_image_url:image_file_name /> - image with 'image_file_name' file name will be searched in the images folder of current template.
 *     If it's not found, then it will be searched in the images folder of base template. On success full URL will be inserted, otherwise an empty string.
 *  8. <bx_icon_url:icon_file_name /> - the same with <bx_image_url:image_file_name />, but icons will be searched in the images/icons/ folders.
 *  9. <bx_injection:injection_name /> - will be replaced with injections registered with the page and injection_name in the `sys_injections`/`sys_injections_admin`/ tables.
 *  10. <bx_if:tag_name>some_HTML</bx_if:tag_name> - will be replaced with provided content if the condition is true, and with empty string otherwise.
 *  11. <bx_repeat:cycle_name>some_HTML</bx_repeat:cycle_name> - an inner HTML content will be repeated in accordance with received data.
 *
 *
 * Related classes:
 *  BxDolTemplateAdmin - for processing admin templates.
 *  Template classes in modules - for processing modiles' templates.
 *
 *
 * Global variables:
 *  oSysTemplate - is used for template processing in user part.
 *  oAdmTemplate - is used for template processing in admin part.
 *
 *
 * Add injection:
 *  1. Register it in the `sys_injections` table or `sys_injections_admin` table for admin panel.
 *  2. Clear injections cache(sys_injections.inc and sys_injections_admin.inc in cache folder).
 *
 *
 * Predefined template keys to add injections:
 *  1. injection_head - add injections in the <head> tag.
 *  2. injection_body - add ingection(attribute) in the <body> tag.
 *  3. injection_header - add injection inside the <body> tag at the very beginning.
 *  4. injection_logo_before - add injection at the left of the main logo(inside logo's DIV).
 *  5. injection_logo_after - add injection at the right of the main logo(inside logo's DIV).
 *  6. injection_between_logo_top_menu - add injection between logo and top menu.
 *  7. injection_top_menu_before - add injection at the left of the top menu(inside top menu's DIV).
 *  8. injection_top_menu_after - add injection at the right of the top menu(inside top menu's DIV).
 *  13. injection_content_before - add injection just before main content(inside content's DIV).
 *  14. injection_content_after - add injection just after main content(inside content's DIV).
 *  15. injection_between_content_footer - add injection between content and footer.
 *  16. injection_footer_before - add injection at the left of the footer(inside footer's DIV).
 *  17. injection_footer_after - add injection at the right of the footer(inside footer's DIV).
 *  18. injection_footer - add injection inside the <body> tag at the very end.
 *
 *
 * Example of usage:
 * @code
 *  $oSysTemplate = BxDolTemplate::getInstance();
 *
 *  $oSysTemplate->addCss(array('test1.css', 'test2.css'));
 *  $oSysTemplate->addJs(array('test1.js', 'test2.js'));
 *  $oSysTemplate->parseHtmlByName('messageBox.html', array(
 *    'id' => $iId,
 *     'msgText' => $sText,
 *     'bx_if:timer' => array(
 *        'condition' => $iTimer > 0,
 *        'content' => array(
 *           'id' => $iId,
 *           'time' => 1000 * $iTimer,
 *           'on_close' => $sOnClose,
 *        )
 *     ),
 *     'bx_if:timer' => array(
 *        array(
 *           'name' => $sName,
 *           'title' => $sTitle
 *        ),
 *        array(
 *           'name' => $sName,
 *           'title' => $sTitle
 *        )
 *     )
 *  ));
 * @endcode
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
class BxDolTemplate extends BxDol implements iBxDolSingleton
{
    /**
     * Main fields
     */
    protected $_sPrefix;
    protected $_sRootPath;
    protected $_sRootUrl;
    protected $_sSubPath;
    protected $_sInjectionsTable;
    protected $_sInjectionsCache;
    protected $_sCode;
    protected $_sCodeKey;
    protected $_sKeyWrapperHtml;
    protected $_sFolderHtml;
    protected $_sFolderCss;
    protected $_sFolderImages;
    protected $_sFolderIcons;
    protected $_aTemplates;

    protected $_aLocations;
    protected $_aLocationsJs;

    /**
     * Cache related fields
     */
    protected $_bCacheEnable;
    protected $_sCacheFolderUrl;
    protected $_sCachePublicFolderUrl;
    protected $_sCachePublicFolderPath;
    protected $_sCacheFilePrefix;

    protected $_bImagesInline;
    protected $_iImagesMaxSize;

    protected $_bCssCache;
    protected $_bCssArchive;
    protected $_sCssCachePrefix;

    protected $_bJsCache;
    protected $_bJsArchive;
    protected $_sJsCachePrefix;

    /**
     * Less related fields
     */
    protected $_bLessEnable;
    protected $_sLessCachePrefix;

    /**
     * Minify related fields
     */
    protected $_bMinifyEnable;

    protected $aPage;
    protected $aPageContent;

    protected $_oConfigTemplate;

    /**
     * Constructor
     */
    protected function __construct($sRootPath = BX_DIRECTORY_PATH_ROOT, $sRootUrl = BX_DOL_URL_ROOT)
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_sPrefix = 'BxDolTemplate';

        $this->_sRootPath = $sRootPath;
        $this->_sRootUrl = $sRootUrl;
        $this->_sInjectionsTable = 'sys_injections';
        $this->_sInjectionsCache = BX_DOL_TEMPLATE_INJECTIONS_CACHE;

        $this->_sCodeKey = 'skin';

        $sCode = getParam('template');
        if(empty($sCode))
            $sCode = BX_DOL_TEMPLATE_DEFAULT_CODE;
        $this->_checkCode($sCode, false);

        //--- Check selected template in COOKIE(the lowest priority) ---//
        $sCode = !empty($_COOKIE[$this->_sCodeKey]) ? $_COOKIE[$this->_sCodeKey] : '';
        $this->_checkCode($sCode, false);

        //--- Check selected template in GET(the highest priority) ---//
        $sCode = !empty($_GET[$this->_sCodeKey]) ? $_GET[$this->_sCodeKey] : '';
        $this->_checkCode($sCode, true);

        if (!$this->_sSubPath)
            $this->_sSubPath = 'boonex/' . BX_DOL_TEMPLATE_DEFAULT_CODE . '/';

        if (!file_exists(BX_DIRECTORY_PATH_MODULES . $this->_sSubPath)) // just for 8.0.0-A6 upgrade
            $this->_sSubPath = 'boonex/uni/';

        if(isset($_GET[$this->_sCodeKey])) {
            bx_import('BxDolPermalinks');
            if(BxDolPermalinks::getInstance()->redirectIfNecessary(array($this->_sCodeKey)))
                exit;
        }

        $this->_sKeyWrapperHtml = '__';
        $this->_sFolderHtml = '';
        $this->_sFolderCss = 'css/';
        $this->_sFolderImages = 'images/';
        $this->_sFolderIcons = 'images/icons/';
        $this->_aTemplates = array();

        $this->addLocation('system', $this->_sRootPath, $this->_sRootUrl);

        $this->addLocationJs('system_inc_js', BX_DIRECTORY_PATH_INC . 'js/' , BX_DOL_URL_ROOT . 'inc/js/');
        $this->addLocationJs('system_inc_js_classes', BX_DIRECTORY_PATH_INC . 'js/classes/' , BX_DOL_URL_ROOT . 'inc/js/classes/');
        $this->addLocationJs('system_plugins_public', BX_DIRECTORY_PATH_PLUGINS_PUBLIC, BX_DOL_URL_PLUGINS_PUBLIC);

        $this->_bCacheEnable = !defined('BX_DOL_CRON_EXECUTE') && getParam('sys_template_cache_enable') == 'on';
        $this->_sCacheFolderUrl = '';
        $this->_sCachePublicFolderUrl = BX_DOL_URL_CACHE_PUBLIC;
        $this->_sCachePublicFolderPath = BX_DIRECTORY_PATH_CACHE_PUBLIC;
        $this->_sCacheFilePrefix = "bx_templ_";

        $this->_bImagesInline = getParam('sys_template_cache_image_enable') == 'on';
        $this->_iImagesMaxSize = (int)getParam('sys_template_cache_image_max_size') * 1024;

        $bArchive = getParam('sys_template_cache_compress_enable') == 'on';
        $this->_bCssCache = !defined('BX_DOL_CRON_EXECUTE') && getParam('sys_template_cache_css_enable') == 'on';
        $this->_bCssArchive = $this->_bCssCache && $bArchive;
        $this->_sCssCachePrefix = $this->_sCacheFilePrefix . 'css_';

        $this->_bJsCache = !defined('BX_DOL_CRON_EXECUTE') && getParam('sys_template_cache_js_enable') == 'on';
        $this->_bJsArchive = $this->_bJsCache && $bArchive;
        $this->_sJsCachePrefix = $this->_sCacheFilePrefix . 'js_';

        $this->_bLessEnable = true;
        $this->_sLessCachePrefix = $this->_sCacheFilePrefix . 'less_';

        $this->_bMinifyEnable = true;

        $this->aPage = array();
        $this->aPageContent = array();
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
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolTemplate();
            $GLOBALS['bxDolClasses'][__CLASS__]->init();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Load templates.
     */
    function loadTemplates()
    {
        $aResult = array();
        foreach($this->_aTemplates as $sName)
            $aResult[$sName] = $this->getHtml($sName . '.html');
        $this->_aTemplates = $aResult;
    }
    /**
     * Initialize template engine.
     * Note. The method is executed with the system, you shouldn't execute it in your subclasses.
     */
    function init()
    {
        //--- Load page elements related static variables ---//
        $this->aPage = array(
            'name_index' => BX_PAGE_DEFAULT,
            'header' => '',
            'header_text' => '',
            'keywords' => array(),
            'location' => array(),
            'description'  => '',
            'robots' => '',
            'css_name' => array(),
            'css_compiled' => array(),
            'css_system' => array(),
            'js_name' => array(),
            'js_compiled' => array(),
            'js_system' => array(),
            'js_options' => array(),
            'js_translations' => array(),
            'js_images' => array(),
            'injections' => array()
        );

        //--- Load default CSS ---//
        $this->addCssSystem(array(
            'common.css',
            'default.less',
            'general.css',
            'icons.css',
            'colors.css',
            'forms.css',
            'media-desktop.css',
            'media-tablet.css',
            'media-phone.css',
            'media-print.css',
            BX_DOL_URL_PLUGINS_PUBLIC . 'marka/marka.min.css',
        ));

        //--- Load default JS ---//
        $this->addJsSystem(array(
            'jquery/jquery.min.js',
            'jquery/jquery-migrate.min.js',
            'jquery-ui/jquery.ui.position.min.js',
            'spin.min.js',
            'jquery.easing.js',
            'jquery.cookie.min.js',
            'moment-with-langs.js',
            'functions.js',
            'jquery.webForms.js',
            'jquery.dolPopup.js',
            'marka/marka.min.js',
        ));

        //--- Load default JS ---//
        bx_import('BxDolLanguages');
        $this->addJsTranslation(array(
            '_are you sure?',
            '_error occured',
            '_sys_loading',
        ));

        //--- Load injection's cache ---//
        if (getParam('sys_db_cache_enable')) {
            $oDb = BxDolDb::getInstance();
            $oCache = $oDb->getDbCacheObject();
            $sCacheKey = $oDb->genDbCacheKey($this->_sInjectionsCache);

            $aInjections = $oCache->getData($sCacheKey);
            if ($aInjections === null) {
                $aInjections = $this->getInjectionsData();
                $oCache->setData ($sCacheKey, $aInjections);
            }
        } else {
            $aInjections = $this->getInjectionsData();
        }

        $this->aPage['injections'] = $aInjections;

        bx_import('BxTemplConfig');
        $this->_oConfigTemplate = BxTemplConfig::getInstance();
    }

    protected function getInjectionsData ()
    {
        $oDb = BxDolDb::getInstance();

        $aInjections = $oDb->getAll("SELECT `page_index`, `name`, `key`, `type`, `data`, `replace` FROM `" . $this->_sInjectionsTable . "` WHERE `active`='1'");
        if (!$aInjections)
            return array();

        foreach ($aInjections as $aInjection)
            $aInjections['page_' . $aInjection['page_index']][$aInjection['key']][] = $aInjection;

        return $aInjections;
    }

    /**
     * Set page name index
     * @param int $i name index
     */
    function setPageNameIndex($i)
    {
        $this->aPage['name_index'] = $i;
    }

    /**
     * Get page name index
     * @return int $i name index
     */
    function getPageNameIndex()
    {
        return isset($this->aPage['name_index']) ? (int)$this->aPage['name_index'] : 0;
    }

    /**
     * Set page header
     * @param string $s page header
     */
    function setPageHeader($s)
    {
        $this->aPage['header'] = $s;
    }

    /**
     * Get page header
     * @return string $s page header
     */
    function getPageHeader()
    {
        return $this->aPage['header'];
    }

    /**
     * Set page params. Available page params are: name_index, header
     * @param array $a page params
     */
    function setPageParams($a)
    {
        if (!empty($this->aPage))
            $this->aPage = array_merge($this->aPage, $a);
        else
            $this->aPage = $a;
    }

    /**
     * Get page params.
     * @return array $a page params
     */
    function getPageParams()
    {
        return $this->aPage;
    }

    /**
     * Set page description.
     *
     * @param string $sDescription necessary page description.
     */
    function setPageDescription($sDescription)
    {
        $this->aPage['description'] = $sDescription;
    }

    /**
     * Set page meta robots.
     *
     * @param string $s page meta robots.
     */
    function setPageMetaRobots($s)
    {
        $this->aPage['robots'] = $s;
    }

    /**
     * Set page content for some variable.
     * @param string $sVar     name of content variable
     * @param string $sContent content for $sVar variable
     * @param int    $iIndex   optional page index, default is index which was set before with @see setPageNameIndex function, or 0
     */
    function setPageContent($sVar, $sContent, $iIndex = false)
    {
        $i = false !== $iIndex ? $iIndex : $this->getPageNameIndex();
        $this->aPageContent[$i][$sVar] = $sContent;
    }

    /**
     * Get page content for some variable.
     * @param  string $sVar   name of content variable
     * @param  int    $iIndex optional page index, default is index which was set before with @see setPageNameIndex function, or 0
     * @return string page content for some variable or for the whole page.
     */
    function getPageContent($sVar = false, $iIndex = false)
    {
        $i = false !== $iIndex ? $iIndex : $this->getPageNameIndex();
        return false !== $sVar ? $this->aPageContent[$i][$sVar] : $this->aPageContent[$i];
    }

    /**
     * Get currently active template code.
     *
     * @return string template's code.
     */
    function getCode()
    {
        return $this->_sCode;
    }

    /**
     * Check whether code is associated with active template.
     *
     * @param string  $sCode      template's unique URI.
     * @param boolean $bSetCookie save code in COOKIE or not.
     */
    protected function _checkCode($sCode, $bSetCookie)
    {
        if(empty($sCode) || !preg_match('/^[A-Za-z0-9_-]+$/', $sCode))
            return;

        bx_import('BxDolModuleQuery');
        $aModule = BxDolModuleQuery::getInstance()->getModuleByUri($sCode);
        if(empty($aModule) || !is_array($aModule) || (int)$aModule['enabled'] != 1 || !file_exists($this->_sRootPath . 'modules/' . $aModule['path'] . 'data/template/'))
            return;

        bx_import('BxDolModuleConfig');
        $oConfig = new BxDolModuleConfig($aModule);

        $this->_sCode = $oConfig->getUri();
        $this->_sSubPath = $oConfig->getDirectory();

        if(!$bSetCookie || bx_get('preview'))
            return;

        $aUrl = parse_url(BX_DOL_URL_ROOT);
        $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';

        setcookie( $this->_sCodeKey, $this->_sCode, time() + 60*60*24*365, $sPath);
    }

    /**
     * Get currently active template path.
     *
     * @return string template's path.
     */
    function getPath()
    {
        return $this->_sSubPath;
    }

    /**
     * Set page title.
     * @deprecated use setPageHeader
     *
     * @param string $sTitle necessary page title.
     */
    function setPageTitle($sTitle)
    {
        $this->setPageHeader($sTitle);
    }

    /**
     * Set page's main box title.
     * @deprecated use setPageParams
     *
     * @param string $sTitle necessary page's main box title.
     */
    function setPageMainBoxTitle($sTitle)
    {
        $this->setPageParams(array('header_text' => $sTitle));
    }

    /**
     * Add location in array of locations.
     * Note. Location is the path/url to folder where 'templates' folder is stored.
     *
     * @param string $sKey          - location's    unique key.
     * @param string $sLocationPath - location's path. For modules: '[path_to_script]/modules/[vendor_name]/[module_name]/'
     * @param string $sLocationUrl  - location's url. For modules: '[url_to_script]/modules/[vendor_name]/[module_name]/'
     */
    function addLocation($sKey, $sLocationPath, $sLocationUrl)
    {
        $this->_aLocations[$sKey] = array(
            'path' => $sLocationPath,
            'url' => $sLocationUrl,
        );

        return $sKey;
    }
    /**
     * Add dynamic location.
     *
     * @param  string   $sLocationPath - location's path. For modules: '[path_to_script]/modules/[vendor_name]/[module_name]/'
     * @param  string   $sLocationUrl  - location's url. For modules: '[url_to_script]/modules/[vendor_name]/[module_name]/'
     * @return location key. Is needed to remove the location.
     */
    function addDynamicLocation($sLocationPath, $sLocationUrl)
    {
        $sLocationKey = time();
        $this->addLocation($sLocationKey, $sLocationPath, $sLocationUrl);

        return $sLocationKey;
    }
    /**
     * Remove location from array of locations.
     * Note. Location is the path/url to folder where templates are stored.
     *
     * @param string $sKey - location's    unique key.
     */
    function removeLocation($sKey)
    {
        if(isset($this->_aLocations[$sKey]))
           unset($this->_aLocations[$sKey]);
    }
    /**
     * Add JS location in array of JS locations.
     * Note. Location is the path/url to folder where JS files are stored.
     *
     * @param string $sKey          - location's    unique key.
     * @param string $sLocationPath - location's path. For modules: '[path_to_script]/modules/[vendor_name]/[module_name]/js/'
     * @param string $sLocationUrl  - location's url. For modules: '[url_to_script]/modules/[vendor_name]/[module_name]/js/'
     */
    function addLocationJs($sKey, $sLocationPath, $sLocationUrl)
    {
        $this->_aLocationsJs[$sKey] = array(
            'path' => $sLocationPath,
            'url' => $sLocationUrl
        );
    }
    /**
     * Add dynamic JS location.
     *
     * @param  string   $sLocationPath - location's path. For modules: '[path_to_script]/modules/[vendor_name]/[module_name]/'
     * @param  string   $sLocationUrl  - location's url. For modules: '[url_to_script]/modules/[vendor_name]/[module_name]/'
     * @return location key. Is needed to remove the location.
     */
    function addDynamicLocationJs($sLocationPath, $sLocationUrl)
    {
        $sLocationKey = time();
        $this->addLocationJs($sLocationKey, $sLocationPath, $sLocationUrl);

        return $sLocationKey;
    }
    /**
     * Remove JS location from array of locations.
     * Note. Location is the path/url to folder where templates are stored.
     *
     * @param string $sKey - JS location's    unique key.
     */
    function removeLocationJs($sKey)
    {
        if(isset($this->_aLocationsJs[$sKey]))
           unset($this->_aLocationsJs[$sKey]);
    }
    /**
     * Add Option in JS output.
     *
     * @param mixed $mixedName option's name or an array of options' names.
     */
    function addJsOption($mixedName)
    {
        if(is_string($mixedName))
            $mixedName = array($mixedName);

        foreach($mixedName as $sName)
            $this->aPage['js_options'][$sName] = getParam($sName);
    }
    /**
     * Add language translation for key in JS output.
     *
     * @param mixed $mixedKey language key or an array of keys.
     */
    function addJsTranslation($mixedKey)
    {
        if(is_string($mixedKey))
            $mixedKey = array($mixedKey);

        foreach($mixedKey as $sKey)
            $this->aPage['js_translations'][$sKey] = _t($sKey, '{0}', '{1}');
    }
    /**
     * Add image in JS output.
     *
     * @param array $aImages an array of image descriptors.
     *                       The descriptor is a key/value pear in the array of descriptors.
     */
    function addJsImage($aImages)
    {
        if(!is_array($aImages))
            return;

        foreach($aImages as $sKey => $sFile) {
            $sUrl = $this->getImageUrl($sFile);
            if(empty($sUrl))
                continue;

            $this->aPage['js_images'][$sKey] = $sUrl;
        }
    }
    /**
     * Add icon in JS output.
     *
     * @param array $aIcons an array of icons descriptors.
     *                      The descriptor is a key/value pear in the array of descriptors.
     */
    function addJsIcon($aIcons)
    {
        if(!is_array($aIcons))
            return;

        foreach($aIcons as $sKey => $sFile) {
            $sUrl = $this->getIconUrl($sFile);
            if(empty($sUrl))
                continue;

            $this->aPage['js_images'][$sKey] = $sUrl;
        }
    }
    /**
     * Set page keywords.
     *
     * @param mixed  $mixedKeywords necessary page keywords(string - single keyword, array - an array of keywords).
     * @param string $sDevider      - string devider.
     */
    function addPageKeywords($mixedKeywords, $sDevider = ',')
    {
        if(is_string($mixedKeywords))
            $mixedKeywords = strpos($mixedKeywords, $sDevider) !== false ? explode($sDevider, $mixedKeywords) : array($mixedKeywords);

        foreach($mixedKeywords as $iKey => $sValue)
            $mixedKeywords[$iKey] = trim($sValue);

        $this->aPage['keywords'] = isset($this->aPage['keywords']) && is_array($this->aPage['keywords']) ? array_merge($this->aPage['keywords'], $mixedKeywords) : $mixedKeywords;
    }
    /**
     * Set page locatoin coordinates.
     *
     * @param $fLat latitude
     * @param $fLng longitude
     */
    function addPageMetaLocation($fLat, $fLng, $sCountryCode)
    {
        $this->aPage['location'] = array('lat' => $fLat, 'lng' => $fLng, 'country' => $sCountryCode);
    }
    /**
     * Set page meta image.
     *
     * @param $sImageUrl meta image url
     */
    function addPageMetaImage($sImageUrl)
    {
        $this->aPage['image'] = $sImageUrl;
    }
    /**
     * Returns page meta info, like meta keyword, meta description, location, etc
     */
    function getMetaInfo()
    {
        $sRet = '';

        if (!empty($this->aPage['keywords']) && is_array($this->aPage['keywords']))
            $sRet .= '<meta name="keywords" content="' . bx_html_attribute(implode(',', $this->aPage['keywords'])) . '" />';

        if (!empty($this->aPage['description']) && is_string($this->aPage['description']))
            $sRet .= '<meta name="description" content="' . bx_html_attribute($this->aPage['description']) . '" />';

        if (!empty($this->aPage['location']) && isset($this->aPage['location']['lat']) && isset($this->aPage['location']['lng']) && isset($this->aPage['location']['country']))
            $sRet .= '
                <meta name="ICBM" content="' . $this->aPage['location']['lat'] . ';' . $this->aPage['location']['lng'] . '" />
                <meta name="geo.position" content="' . $this->aPage['location']['lat'] . ';' . $this->aPage['location']['lng'] . '" />
                <meta name="geo.region" content="' . bx_html_attribute($this->aPage['location']['country']) . '" />';

        if (!empty($this->aPage['image'])) {
            $sRet .= '<meta property="og:image" content="' . $this->aPage['image'] . '" />';
        } else {
            bx_import('BxTemplFunctions');
            $sRet .= BxTemplFunctions::getInstance()->getMetaIcons();
        }

        return $sRet;
    }
    /**
     * Get template, which was loaded earlier.
     * @see method this->loadTemplates and field this->_aTemplates
     *
     * @param  string $sName - template name.
     * @return string template's content.
     */
    function getTemplate($sName)
    {
        return $this->_aTemplates[$sName];
    }
    /**
     * Get full URL for the icon.
     *
     * @param  string $sName    icon's file name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string full URL.
     */
    function getIconUrl($sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        $sContent = "";
        if(($sContent = $this->_getInlineData('icon', $sName, $sCheckIn)) !== false)
            return $sContent;

        return $this->_getAbsoluteLocation('url', $this->_sFolderIcons, $sName, $sCheckIn);
    }
    /**
     * Get absolute Path for the icon.
     *
     * @param  string $sName    - icon's file name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string absolute path.
     */
    function getIconPath($sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        return $this->_getAbsoluteLocation('path', $this->_sFolderIcons, $sName, $sCheckIn);
    }
    /**
     * Get full URL for the image.
     *
     * @param  string $sName    - images's file name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string full URL.
     */
    function getImageUrl($sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        $sContent = "";
        if(($sContent = $this->_getInlineData('image', $sName, $sCheckIn)) !== false)
            return $sContent;

        return $this->_getAbsoluteLocation('url', $this->_sFolderImages, $sName, $sCheckIn);
    }
    /**
     * Get absolute Path for the image.
     *
     * @param  string $sName    - image's file name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string absolute path.
     */
    function getImagePath($sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        return $this->_getAbsoluteLocation('path', $this->_sFolderImages, $sName, $sCheckIn);
    }
    /**
     * Get full URL of CSS file.
     *
     * @param  string $sName    - CSS file name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string full URL.
     */
    function getCssUrl($sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        return $this->_getAbsoluteLocation('url', $this->_sFolderCss, $sName, $sCheckIn);
    }
    /**
     * Get full Path of CSS file.
     *
     * @param  string $sName    - CSS file name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string full URL.
     */
    function getCssPath($sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        return $this->_getAbsoluteLocation('path', $this->_sFolderCss, $sName, $sCheckIn);
    }

    /**
     * Get menu.
     * @param $s menu object name
     * @return html or empty string
     */
    function getMenu ($s)
    {
        bx_import('BxDolMenu');
        $oMenu = BxDolMenu::getObjectInstance($s);
        return $oMenu ? $oMenu->getCode () : '';
    }

    /**
     * Get content of HTML file.
     *
     * @param  string $sName    - HTML file name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string full content of the file and false on failure.
     */
    function getHtml($sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        $sAbsolutePath = $this->_getAbsoluteLocation('path', $this->_sFolderHtml, $sName, $sCheckIn);
        return !empty($sAbsolutePath) ? trim(file_get_contents($sAbsolutePath)) : false;
    }

    /**
     * Parse HTML template. Search for the template with accordance to it's file name.
     *
     * @see allows to use cache.
     *
     * @param  string $sName               - HTML file name.
     * @param  array  $aVariables          - key/value pairs. key should be the same as template's key, but without prefix and postfix.
     * @param  mixed  $mixedKeyWrapperHtml - key wrapper(string value if left and right parts are the same, array(left, right) otherwise).
     * @param  string $sCheckIn            where the content would be searched(base, template, both)
     * @return string the result of operation.
     */
    function parseHtmlByName($sName, $aVariables, $mixedKeyWrapperHtml = null, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginTemplate($sName, $sRand = time().rand());

        if (($sContent = $this->getCached($sName, $aVariables, $mixedKeyWrapperHtml, $sCheckIn)) !== false) {
            if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endTemplate($sName, $sRand, $sRet, true);
            return $sContent;
        }

        $sRet = '';
        if (($sContent = $this->getHtml($sName, $sCheckIn)) !== false)
            $sRet = $this->_parseContent($sContent, $aVariables, $mixedKeyWrapperHtml);

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endTemplate($sName, $sRand, $sRet, false);

        return $sRet;
    }
    /**
     * Parse HTML template.
     *
     * @see Doesn't allow to use cache.
     *
     * @param  string $sContent            - HTML file content.
     * @param  array  $aVariables          - key/value pairs. key should be the same as template's key, but without prefix and postfix.
     * @param  mixed  $mixedKeyWrapperHtml - key wrapper(string value if left and right parts are the same, array(left, right) otherwise).
     * @return string the result of operation.
     */
    function parseHtmlByContent($sContent, $aVariables, $mixedKeyWrapperHtml = null)
    {
        if(empty($sContent))
            return "";

        return $this->_parseContent($sContent, $aVariables, $mixedKeyWrapperHtml);
    }
    /**
     * Parse earlier loaded HTML template.
     *
     * @see Doesn't allow to use cache.
     *
     * @param  string $sName      - template name.
     * @param  array  $aVariables - key/value pairs. Key should be the same as template's key, excluding prefix and postfix.
     * @return string the result of operation.
     * @see $this->_aTemplates
     */
    function parseHtmlByTemplateName($sName, $aVariables, $mixedKeyWrapperHtml = null)
    {
        if(!isset($this->_aTemplates[$sName]) || empty($this->_aTemplates[$sName]))
            return "";

        return $this->_parseContent($this->_aTemplates[$sName], $aVariables, $mixedKeyWrapperHtml);
    }
    /**
     * Parse page HTML template. Search for the page's template with accordance to it's file name.
     *
     * @see allows to use cache.
     *
     * @param  string $sName      - HTML file name.
     * @param  array  $aVariables - key/value pairs. key should be the same as template's key, but without prefix and postfix.
     * @return string the result of operation.
     */
    function parsePageByName($sName, $aVariables)
    {
        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginPage($sName);

        $sContent = $this->parseHtmlByName($sName, $aVariables, $this->_sKeyWrapperHtml, BX_DOL_TEMPLATE_CHECK_IN_BOTH);
        if(empty($sContent))
            $sContent = $this->parseHtmlByName('default.html', $aVariables, $this->_sKeyWrapperHtml, BX_DOL_TEMPLATE_CHECK_IN_BOTH);

        //--- Add CSS and JS at the very last ---//
        if(strpos($sContent , '<bx_include_css />') !== false) {
            if (!empty($this->aPage['css_name']))
                $this->addCss($this->aPage['css_name']);
            $sContent = str_replace('<bx_include_css />', $this->includeFiles('css', true) . $this->includeFiles('css'), $sContent);
        }

        if(strpos($sContent , '<bx_include_js />') !== false) {
            if (!empty($this->aPage['js_name']))
                $this->addJs($this->aPage['js_name']);
            $sContent = str_replace('<bx_include_js />', $this->includeFiles('js', true) . $this->includeFiles('js'), $sContent);
        }

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endPage($sContent);

        return $sContent;
    }
    /**
     * Parse system keys.
     *
     * @param  string $sKey key
     * @return string value associated with the key.
     */
    function parseSystemKey($sKey, $mixedKeyWrapperHtml = null, $bProcessInjection = true)
    {
        $aKeyWrappers = $this->_getKeyWrappers($mixedKeyWrapperHtml);

        $sRet = '';
        switch( $sKey ) {
            case 'page_charset':
                $sRet = 'UTF-8';
                break;
            case 'page_robots':
                if(!empty($this->aPage['robots']) && is_string($this->aPage['robots']))
                    $sRet = '<meta name="robots" content="' . bx_html_attribute($this->aPage['robots']) . '" />';
                break;
            case 'meta_info':
                $sRet = $this->getMetaInfo();
                break;
            case 'page_header':
                if(isset($this->aPage['header']))
                    $sRet = bx_process_output(strip_tags($this->aPage['header']));
                break;
            case 'page_header_text':
                if(isset($this->aPage['header_text']))
                    $sRet = bx_process_output($this->aPage['header_text']);
                break;
            case 'popup_loading':
                bx_import('BxTemplFunctions');
                $s = $this->parsePageByName('popup_loading.html', array());
                $sRet = BxTemplFunctions::getInstance()->transBox('bx-popup-loading', $s, true);

                bx_import('BxTemplSearch');
                $oSearch = new BxTemplSearch();
                $oSearch->setLiveSearch(true);
                $sRet .= $this->parsePageByName('search.html', array(
                    'search_form' => $oSearch->getForm(BX_DB_CONTENT_ONLY),
                    'results' => $oSearch->getResultsContainer(),
                ));

                $sRet .= $this->getMenu ('sys_site');                
                $sRet .= isLogged() ? $this->getMenu ('sys_add_content') : '';
                $sRet .= isLogged() ? $this->getMenu ('sys_account_popup') : '';
                break;
            case 'lang':
                $sRet = bx_lang_name();
                break;
            case 'main_logo':
                bx_import('BxTemplFunctions');
                $sRet = BxTemplFunctions::getInstance()->getMainLogo();
                break;
            case 'informer':
                bx_import('BxDolInformer');
                $oInformer = BxDolInformer::getInstance($this);
                $sRet = $oInformer ? $oInformer->display() : '';
                break;
            case 'dol_images':
                $sRet = $this->_processJsImages();
                break;
            case 'dol_lang':
                $sRet = $this->_processJsTranslations();
                break;
            case 'dol_options':
                $sRet = $this->_processJsOptions();
                break;
            case 'bottom_text':
                $sRet = _t( '_bottom_text', date('Y') );
                break;
            case 'copyright':
                $sRet = _t( '_copyright',   date('Y') ) . getVersionComment();
                break;
            case 'extra_js':
                $sRet = empty($this->aPage['extra_js']) ? '' : $this->aPage['extra_js'];
                break;
            case 'is_profile_page':
                $sRet = (defined('BX_PROFILE_PAGE')) ? 'true' : 'false';
                break;

            default:
                bx_import('BxTemplFunctions');
                $sRet = ($sTemplAdd = BxTemplFunctions::getInstance()->TemplPageAddComponent($sKey)) !== false ? $sTemplAdd : $aKeyWrappers['left'] . $sKey . $aKeyWrappers['right'];
        }

        if($bProcessInjection)
            $sRet = $this->processInjection($this->getPageNameIndex(), $sKey, $sRet);

        return $sRet;
    }
    function getCacheFilePrefix($sType)
    {
    	$sResult = '';
    	switch($sType) {
    		case 'template':
				$sResult = $this->_sCacheFilePrefix;
				break;

    		case 'css':
    			$sResult = $this->_sCssCachePrefix;
    			break;

    		case 'js':
    			$sResult = $this->_sJsCachePrefix;
    			break;
    	}

    	return $sResult;
    }
    /**
     * Get cache object for templates
     * @return cache class instance
     */
    function getTemplatesCacheObject ()
    {
        $sCacheEngine = getParam('sys_template_cache_engine');
        $oCacheEngine = bx_instance('BxDolCache' . $sCacheEngine);
        if(!$oCacheEngine->isAvailable())
            $oCacheEngine = bx_instance('BxDolCacheFileHtml');
        return $oCacheEngine;
    }
    /**
     * Get template from cache if it's enabled.
     *
     * @param  string  $sName               template name
     * @param  string  $aVariables          key/value pairs. key should be the same as template's key, but without prefix and postfix.
     * @param  mixed   $mixedKeyWrapperHtml - key wrapper(string value if left and right parts are the same, array(0 => left, 1 => right) otherwise).
     * @param  string  $sCheckIn            where the content would be searched(base, template, both)
     * @param  boolean $bEvaluate           need to evaluate the template or not.
     * @return string  result of operation or false on failure.
     */
    function getCached($sName, &$aVariables, $mixedKeyWrapperHtml = null, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH, $bEvaluate = true)
    {
        // initialization

        if(!$this->_bCacheEnable)
            return false;

        $sAbsolutePath = $this->_getAbsoluteLocation('path', $this->_sFolderHtml, $sName, $sCheckIn);
        if(empty($sAbsolutePath))
            return false;

        $oCacheEngine = $this->getTemplatesCacheObject ();
        $isFileBasedEngine = $bEvaluate && method_exists($oCacheEngine, 'getDataFilePath');

        // try to get cached content

        $sCacheVariableName = "a";
        $sCacheKey = $this->_getCacheFileName('html', $sAbsolutePath) . '.php';
        if ($isFileBasedEngine)
            $sCacheContent = $oCacheEngine->getDataFilePath($sCacheKey);
        else
            $sCacheContent = $oCacheEngine->getData($sCacheKey);


        // recreate cache if it is empty

        if ($sCacheContent === null && ($sContent = file_get_contents($sAbsolutePath)) !== false && ($sContent = $this->_compileContent($sContent, "\$" . $sCacheVariableName, 1, $aVariables, $mixedKeyWrapperHtml)) !== false) {
            if (false === $oCacheEngine->setData($sCacheKey, trim($sContent)))
                return false;

            if ($isFileBasedEngine)
                $sCacheContent = $oCacheEngine->getDataFilePath($sCacheKey);
            else
                $sCacheContent = $sContent;
        }

        if ($sCacheContent === null)
            return false;

        // return simple cache content

        if(!$bEvaluate)
            return $sCacheContent;

        // return evaluated cache content

        ob_start();

        $$sCacheVariableName = &$aVariables;

        if ($isFileBasedEngine)
            include($sCacheContent);
        else
            eval('?'.'>' . $sCacheContent);

        $sContent = ob_get_clean();

        return $sContent;
    }

    /**
     * Add JS file(s) to global output.
     *
     * @param  mixed          $mixedFiles string value represents a single JS file name. An array - array of JS file names.
     * @param  boolean        $bDynamic   in the dynamic mode JS file(s) are not included to global output, but are returned from the function directly.
     * @return boolean/string result of operation.
     */
    function addJs($mixedFiles, $bDynamic = false)
    {
        return $this->_processFiles('js', 'add', $mixedFiles, $bDynamic);
    }

    /**
     * Add System JS file(s) to global output.
     * System JS files are the files which are attached to all pages. They will be cached separately from the others.
     *
     * @param  mixed          $mixedFiles string value represents a single JS file name. An array - array of JS file names.
     * @param  boolean        $bDynamic   in the dynamic mode JS file(s) are not included to global output, but are returned from the function directly.
     * @return boolean/string result of operation.
     */
    function addJsSystem($mixedFiles)
    {
        return $this->_processFiles('js', 'add', $mixedFiles, false, true);
    }

    /**
     * Delete JS file(s) from global output.
     *
     * @param  mixed   $mixedFiles string value represents a single JS file name. An array - array of JS file names.
     * @return boolean result of operation.
     */
    function deleteJs($mixedFiles)
    {
        return $this->_processFiles('js', 'delete', $mixedFiles);
    }

    /**
     * Delete System JS file(s) from global output.
     *
     * @param  mixed   $mixedFiles string value represents a single JS file name. An array - array of JS file names.
     * @return boolean result of operation.
     */
    function deleteJsSystem($mixedFiles)
    {
        return $this->_processFiles('js', 'delete', $mixedFiles, false, true);
    }

    /**
     * Compile JS files in one file.
     *
     * @param  string $sAbsolutePath CSS file absolute path(full URL for external CSS/JS files).
     * @param  array  $aIncluded     an array of already included JS files.
     * @return string result of operation.
     */
    function _compileJs($sAbsolutePath, &$aIncluded)
    {
        if(isset($aIncluded[$sAbsolutePath]))
           return '';

        $bExternal = strpos($sAbsolutePath, "http://") !== false || strpos($sAbsolutePath, "https://") !== false;
        if($bExternal) {
            $sPath = $sAbsolutePath;
            $sName = '';

            $sContent = bx_file_get_contents($sAbsolutePath);
        } else {
            $aFileInfo = pathinfo($sAbsolutePath);
            $sPath = $aFileInfo['dirname'] . DIRECTORY_SEPARATOR;
            $sName = $aFileInfo['basename'];

            $sContent = file_get_contents($sPath . $sName);
        }

        if(empty($sContent))
            return '';

        $sUrl = bx_ltrim_str($sPath, realpath(BX_DIRECTORY_PATH_ROOT), BX_DOL_URL_ROOT);
        $sUrl = str_replace(DIRECTORY_SEPARATOR, '/', $sUrl);

        $sContent = "\r\n/*--- BEGIN: " . $sUrl . $sName . "---*/\r\n" . $sContent . ";\r\n/*--- END: " . $sUrl . $sName . "---*/\r\n";
        $sContent = str_replace(array("\n\r", "\r\n", "\r"), "\n", $sContent);

        $aIncluded[$sAbsolutePath] = 1;

        return preg_replace(
            array(
                "'<bx_url_root />'",
                "'\r\n'"
            ),
            array(
                BX_DOL_URL_ROOT,
                "\n"
            ),
            $sContent
        );
    }
    /**
     * Wrap an URL to JS file into JS tag.
     *
     * @param  string $sFile - URL to JS file.
     * @return string the result of operation.
     */
    function _wrapInTagJs($sFile)
    {
        return "<script language=\"javascript\" type=\"text/javascript\" src=\"" . $sFile . "\"></script>";
    }
    /**
     * Wrap JS code into JS tag.
     *
     * @param  string $sCode - JS code.
     * @return string the result of operation.
     */
    function _wrapInTagJsCode($sCode)
    {
        return "<script language=\"javascript\" type=\"text/javascript\">\n<!--\n" . $sCode . "\n-->\n</script>";
    }

    /**
     * Add CSS file(s) to global output.
     *
     * @param  mixed          $mixedFiles string value represents a single CSS file name. An array - array of CSS file names.
     * @param  boolean        $bDynamic   in the dynamic mode CSS file(s) are not included to global output, but are returned from the function directly.
     * @return boolean/string result of operation
     */
    function addCss($mixedFiles, $bDynamic = false)
    {
        return $this->_processFiles('css', 'add', $mixedFiles, $bDynamic);
    }

    /**
     * Add System CSS file(s) to global output.
     * System CSS files are the files which are attached to all pages. They will be cached separately from the others.
     *
     * @param  mixed          $mixedFiles string value represents a single CSS file name. An array - array of CSS file names.
     * @return boolean/string result of operation
     */
    function addCssSystem($mixedFiles)
    {
        return $this->_processFiles('css', 'add', $mixedFiles, false, true);
    }

    /**
     * Delete CSS file(s) from global output.
     *
     * @param  mixed   $mixedFiles string value represents a single CSS file name. An array - array of CSS file names.
     * @return boolean result of operation.
     */
    function deleteCss($mixedFiles)
    {
        return $this->_processFiles('css', 'delete', $mixedFiles);
    }
    /**
     * Delete System CSS file(s) from global output.
     *
     * @param  mixed   $mixedFiles string value represents a single CSS file name. An array - array of CSS file names.
     * @return boolean result of operation.
     */
    function deleteCssSystem($mixedFiles)
    {
        return $this->_processFiles('css', 'delete', $mixedFiles, false, true);
    }
    /**
     * Compile CSS files' structure(@see @import css_file_path) in one file.
     *
     * @param  string $sAbsolutePath CSS file absolute path(full URL for external CSS/JS files).
     * @param  array  $aIncluded     an array of already included CSS files.
     * @return string result of operation.
     */
    function _compileCss($sAbsolutePath, &$aIncluded)
    {
        if(isset($aIncluded[$sAbsolutePath]))
           return '';

        $bExternal = strpos($sAbsolutePath, "http://") !== false || strpos($sAbsolutePath, "https://") !== false;
        if($bExternal) {
            $sPath = $sAbsolutePath;
            $sName = '';

            $sContent = bx_file_get_contents($sAbsolutePath);
        } else {
            $aFileInfo = pathinfo($sAbsolutePath);
            $sPath = $aFileInfo['dirname'] . DIRECTORY_SEPARATOR;
            $sName = $aFileInfo['basename'];

            $sContent = file_get_contents($sPath . $sName);
        }

        if(empty($sContent))
            return '';

        $sUrl = bx_ltrim_str($sPath, realpath(BX_DIRECTORY_PATH_ROOT), BX_DOL_URL_ROOT);
        $sUrl = str_replace(DIRECTORY_SEPARATOR, '/', $sUrl);

        $sContent = "\r\n/*--- BEGIN: " . $sUrl . $sName . "---*/\r\n" . $sContent . "\r\n/*--- END: " . $sUrl . $sName . "---*/\r\n";
        $aIncluded[$sAbsolutePath] = 1;

        $sContent = str_replace(array("\n\r", "\r\n", "\r"), "\n", $sContent);
        if($bExternal) {
            $sContent = preg_replace(
                array(
                    "'@import\s+url\s*\(\s*[\'|\"]*\s*([a-zA-Z0-9\.\/_-]+)\s*[\'|\"]*\s*\)\s*;'",
                    "'url\s*\(\s*[\'|\"]*\s*([a-zA-Z0-9\.\/\?\#_=-]+)\s*[\'|\"]*\s*\)'"
                ),
                array(
                    "",
                    "'url('" . $sPath . "'\\1)'"
                ),
                $sContent
            );
        }
        else {
        	try {
        		$oTemplate = &$this;

	            $sContent = preg_replace_callback(
	                "'@import\s+url\s*\(\s*[\'|\"]*\s*([a-zA-Z0-9\.\/_-]+)\s*[\'|\"]*\s*\)\s*;'", function ($aMatches)  use($oTemplate, $sPath, $aIncluded) {
	                	return $oTemplate->_compileCss(realpath($sPath . dirname($aMatches[1])) . DIRECTORY_SEPARATOR . basename($aMatches[1]), $aIncluded);
	                }, $sContent);
	
	            $sContent = preg_replace_callback(
	                "'url\s*\(\s*[\'|\"]*\s*([a-zA-Z0-9\.\/\?\#_=-]+)\s*[\'|\"]*\s*\)'", function ($aMatches)  use($oTemplate, $sPath) {
						$sFile = basename($aMatches[1]);
				        $sDirectory = dirname($aMatches[1]);
				
				        $sRootPath = realpath(BX_DIRECTORY_PATH_ROOT);
				        $sAbsolutePath = realpath(addslashes($sPath) . $sDirectory) . DIRECTORY_SEPARATOR . $sFile;
				
				        $sRootPath = str_replace(DIRECTORY_SEPARATOR, '/', $sRootPath);
				        $sAbsolutePath = str_replace(DIRECTORY_SEPARATOR, '/', $sAbsolutePath);
				
				        return 'url(' . bx_ltrim_str($sAbsolutePath, $sRootPath, BX_DOL_URL_ROOT) . ')';
	                }, $sContent);
			}
	    	catch(Exception $oException) {
	        	return '';
	        }
        }

        return $sContent;
    }

    /**
     * Less CSS
     *
     * @param  mixed $mixed CSS string to process with Less compiler or an array with CSS file's Path and URL.
     * @return mixed string or an array with CSS file's Path and URL.
     */
    function _lessCss($mixed)
    {
    	require_once(BX_DIRECTORY_PATH_PLUGINS . 'lessphp/Less.php');

        if(is_array($mixed) && isset($mixed['url']) && isset($mixed['path'])) {
            $sPathFile = realpath($mixed['path']);
            $aInfoFile = pathinfo($sPathFile);
            if (!isset($aInfoFile['extension']) || $aInfoFile['extension'] != 'less')
                return $mixed;

            require_once(BX_DIRECTORY_PATH_PLUGINS . 'lessphp/Cache.php');
        	$aFiles = array($mixed['path'] => $mixed['url']);
        	$aOptions = array('cache_dir' => $this->_sCachePublicFolderPath);
        	$sFile = Less_Cache::Get($aFiles, $aOptions, $this->_oConfigTemplate->aLessConfig);

            return array('url' => $this->_sCachePublicFolderUrl . $sFile, 'path' => $this->_sCachePublicFolderPath . $sFile);
        }

        $oLess = new Less_Parser();
        $oLess->ModifyVars($this->_oConfigTemplate->aLessConfig);
        $oLess->parse($mixed);
        return $oLess->getCss();
    }

    /**
     * Minify CSS
     *
     * @param  string $s CSS string to minify
     * @return string minified CSS string.
     */
    function _minifyCss($s)
    {
        require_once(BX_DIRECTORY_PATH_PLUGINS . 'minify/lib/Minify/CSS/Compressor.php');
        return Minify_CSS_Compressor::process($s);
    }

    /**
     * Wrap an URL to CSS file into CSS tag.
     *
     * @param  string $sFile - URL to CSS file.
     * @return string the result of operation.
     */
    function _wrapInTagCss($sFile)
    {
        if (!$sFile)
            return '';
        return "<link href=\"" . $sFile . "\" rel=\"stylesheet\" type=\"text/css\" />";
    }
    /**
     * Wrap CSS code into CSS tag.
     *
     * @param  string $sCode - CSS code.
     * @return string the result of operation.
     */
    function _wrapInTagCssCode($sCode)
    {
        return "<link rel=\"stylesheet\" type=\"text/css\">" . $sCode . "</link>";
    }
    /**
     * Include CSS/JS file(s) attached to the page in its head section.
     * @see the method is system and would be called automatically.
     *
     * @param  string $sType the type of file('js' or 'css')
     * @return string the result CSS code.
     */
    function includeFiles($sType, $bSystem = false)
    {
        $sUpcaseType = ucfirst($sType);

        $sArrayKey = $sType . ($bSystem ? '_system' : '_compiled');
        $aFiles = isset($this->aPage[$sArrayKey]) ? $this->aPage[$sArrayKey] : array();
        if(empty($aFiles) || !is_array($aFiles))
            return "";

        if(!$this->{'_b' . $sUpcaseType . 'Cache'})
            return $this->_includeFiles($sType, $aFiles);

        //--- If cache already exists, return it ---//
        $sMethodWrap = '_wrapInTag' . $sUpcaseType;
        $sMethodCompile = '_compile' . $sUpcaseType;
        $sMethodLess = '_less' . $sUpcaseType;
        $sMethodMinify = '_minify' . $sUpcaseType;

        ksort($aFiles);

        $sName = "";
        foreach($aFiles as $aFile)
            $sName .= $aFile['url'];
        $sName = $this->_getCacheFileName($sType, $sName);

        $sCacheAbsoluteUrl = $this->_sCachePublicFolderUrl . $sName . '.' . $sType;
        $sCacheAbsolutePath = $this->_sCachePublicFolderPath . $sName . '.' . $sType;
        if(file_exists($sCacheAbsolutePath)) {
            if($this->{'_b' . $sUpcaseType . 'Archive'})
                $sCacheAbsoluteUrl = $this->_getLoaderUrl($sType, $sName);

           return $this->$sMethodWrap($sCacheAbsoluteUrl);
        }

        //--- Collect all attached CSS/JS in one file ---//
        $bLess = $this->_bLessEnable && method_exists($this, $sMethodLess);

        $sResult = "";
        $aIncluded = array();
        foreach($aFiles as $aFile) {
        	if($bLess)
				$aFile = $this->$sMethodLess($aFile);

            if(($sContent = $this->$sMethodCompile($aFile['path'], $aIncluded)) !== false)
                $sResult .= $sContent;
        }

        if ($this->_bMinifyEnable && method_exists($this, $sMethodMinify))
            $sResult = $this->$sMethodMinify($sResult);

        $mixedWriteResult = false;
        if(!empty($sResult) && ($rHandler = fopen($sCacheAbsolutePath, 'w')) !== false) {
            $mixedWriteResult = fwrite($rHandler, $sResult);
            fclose($rHandler);
            @chmod ($sCacheAbsolutePath, 0666);
        }

        if($mixedWriteResult === false)
            return $this->_includeFiles($sType, $aFiles);

        if($this->{'_b' . $sUpcaseType . 'Archive'})
            $sCacheAbsoluteUrl = $this->_getLoaderUrl($sType, $sName);

        return $this->$sMethodWrap($sCacheAbsoluteUrl);
    }
    /**
     * Include CSS/JS files without caching.
     *
     * @param  string $sType  the file type (css or js)
     * @param  array  $aFiles CSS/JS files to be added to the page.
     * @return string result of operation.
     */
    function _includeFiles($sType, &$aFiles)
    {
        $sUpcaseType = ucfirst($sType);

        $sMethodWrap = '_wrapInTag' . $sUpcaseType;
        $sMethodLess = '_less' . $sUpcaseType;

        $sResult = "";
        foreach($aFiles as $aFile) {
            if($this->_bLessEnable && method_exists($this, $sMethodLess))
                $aFile = $this->$sMethodLess($aFile);

            $sResult .= $this->$sMethodWrap($aFile['url']);
        }

        return $sResult;
    }
    /**
     * Insert/Delete CSS file from output stack.
     *
     * @param  string  $sType      the file type (css or js)
     * @param  string  $sAction    add/delete
     * @param  mixed   $mixedFiles string value represents a single CSS file name. An array - array of CSS file names.
     * @return boolean result of operation.
     */
    function _processFiles($sType, $sAction, $mixedFiles, $bDynamic = false, $bSystem = false)
    {
        if(empty($mixedFiles))
            return $bDynamic ? "" : false;

        if(is_string($mixedFiles))
            $mixedFiles = array($mixedFiles);

        $sUpcaseType = ucfirst($sType);
        $sMethodLocate = '_getAbsoluteLocation' . $sUpcaseType;
        $sMethodWrap = '_wrapInTag' . $sUpcaseType;
        $sResult = '';
        foreach($mixedFiles as $sFile) {
            //--- Process 3d Party CSS/JS file ---//
            if(strpos($sFile, "http://") !== false || strpos($sFile, "https://") !== false) {
                $sUrl = $sFile;
                $sPath = $sFile;
            }
            //--- Process Custom CSS/JS file ---//
            else if(strpos($sFile, "|") !== false) {
                $sFile = implode('', explode("|", $sFile));

                $sUrl = BX_DOL_URL_ROOT . $sFile;
                $sPath = realpath(BX_DIRECTORY_PATH_ROOT . $sFile);
            }
            //--- Process Common CSS/JS file(check in default locations) ---//
            else {
                $sUrl = $this->$sMethodLocate('url', $sFile);
                $sPath = $this->$sMethodLocate('path', $sFile);
            }

            if(empty($sPath) || empty($sUrl))
                continue;

            $sArrayKey = $sType . ($bSystem ? '_system' : '_compiled');
            switch($sAction) {
                case 'add':
                    if($bDynamic)
                        $sResult .= $this->$sMethodWrap($sUrl);
                    else {
                        $bFound = false;
                        $aSearchIn = $bSystem ? $this->aPage[$sArrayKey] : array_merge($this->aPage[$sType . '_system'], $this->aPage[$sArrayKey]);
                        foreach($aSearchIn as $iKey => $aValue)
                            if($aValue['url'] == $sUrl && $aValue['path'] == $sPath) {
                                $bFound = true;
                                break;
                            }

                        if(!$bFound)
                            $this->aPage[$sArrayKey][] = array('url' => $sUrl, 'path' => $sPath);
                    }
                    break;
                case 'delete':
                    if(!$bDynamic)
                        foreach($this->aPage[$sArrayKey]  as $iKey => $aValue)
                            if($aValue['url'] == $sUrl) {
                                unset($this->aPage[$sArrayKey][$iKey]);
                                break;
                            }
                    break;
            }
        }

        return $bDynamic ? $sResult : true;
    }

    /**
     * Parse content.
     *
     * @param  string $sContent            - HTML file's content.
     * @param  array  $aVariables          - key/value pairs. key should be the same as template's key, but without prefix and postfix.
     * @param  mixed  $mixedKeyWrapperHtml - key wrapper(string value if left and right parts are the same, array(0 => left, 1 => right) otherwise).
     * @return string the result of operation.
     */
    function _parseContent($sContent, $aVariables, $mixedKeyWrapperHtml = null)
    {
        $aKeys = array_keys($aVariables);
        $aValues = array_values($aVariables);

        $aKeyWrappers = $this->_getKeyWrappers($mixedKeyWrapperHtml);

        $iCountKeys = count($aKeys);
        for ($i = 0; $i < $iCountKeys; $i++) {
            if (strncmp($aKeys[$i], 'bx_repeat:', 10) === 0) {
                $sKey = "'<" . $aKeys[$i] . ">(.*)<\/" . $aKeys[$i] . ">'s";

                $aMatches = array();
                preg_match($sKey, $sContent, $aMatches);

                $sValue = '';
                if(isset($aMatches[1]) && !empty($aMatches[1])) {
                    if(is_array($aValues[$i]))
                        foreach($aValues[$i] as $aValue)
                            $sValue .= $this->parseHtmlByContent($aMatches[1], $aValue, $mixedKeyWrapperHtml);
                    else if(is_string($aValues[$i]))
                        $sValue = $aValues[$i];
                }
            } else if (strncmp($aKeys[$i], 'bx_if:', 6) === 0) {
                $sKey = "'<" . $aKeys[$i] . ">(.*)<\/" . $aKeys[$i] . ">'s";

                $aMatches = array();
                preg_match($sKey, $sContent, $aMatches);

                $sValue = '';
                if(isset($aMatches[1]) && !empty($aMatches[1]))
                    if(is_array($aValues[$i]) && isset($aValues[$i]['content']) && isset($aValues[$i]['condition']) && $aValues[$i]['condition'])
                        $sValue .= $this->parseHtmlByContent($aMatches[1], $aValues[$i]['content'], $mixedKeyWrapperHtml);
            } else {
                $sKey = "'" . $aKeyWrappers['left'] . $aKeys[$i] . $aKeyWrappers['right'] . "'s";
                $sValue = str_replace('$', '\\$', $aValues[$i]);
            }

            $aKeys[$i] = $sKey;
            $aValues[$i] = $sValue;
        }

        try {
        	$oTemplate = &$this;

        	$aCallbackPatterns = array(
        		"'<bx_include_auto:([^\s]+) \/>'s" => BX_DOL_TEMPLATE_CHECK_IN_BOTH,
        		"'<bx_include_base:([^\s]+) \/>'s" => BX_DOL_TEMPLATE_CHECK_IN_BASE,
        		"'<bx_include_tmpl:([^\s]+) \/>'s" => BX_DOL_TEMPLATE_CHECK_IN_TMPL
        	);
	        foreach($aCallbackPatterns as $sPattern => $sCheckIn)
		        $sContent = preg_replace_callback($sPattern, function($aMatches) use($oTemplate, $aVariables, $mixedKeyWrapperHtml, $sCheckIn) {
		        	return $oTemplate->parseHtmlByName($aMatches[1], $aVariables, $mixedKeyWrapperHtml, $sCheckIn);
		        }, $sContent);

			$sContent = $this->_parseContentKeys($sContent, array(
				"'<bx_injection:([^\s]+) />'s" => 'get_injection',
            	"'<bx_menu:([^\s]+) \/>'s" => 'get_menu',
			));
        }
		catch(Exception $oException) {
        	return '';
        }

        $aKeys = array_merge($aKeys, array(
            "'<bx_url_root />'",
            "'<bx_url_studio />'",
        ));
        $aValues = array_merge($aValues, array(
            BX_DOL_URL_ROOT,
            BX_DOL_URL_STUDIO,
        ));

        //--- Parse Predefined Keys ---//
        $sContent = preg_replace($aKeys, $aValues, $sContent);

        //--- Parse System Keys ---//
        $sContent = preg_replace_callback("'" . $aKeyWrappers['left'] . "([a-zA-Z0-9_-]+)" . $aKeyWrappers['right'] . "'", function($aMatches) use($oTemplate, $mixedKeyWrapperHtml) {
        	return $oTemplate->parseSystemKey($aMatches[1], $mixedKeyWrapperHtml);
        }, $sContent);

        return $sContent;
    }

    /**
     * Compile content
     *
     * @param  string  $sContent            template.
     * @param  string  $aVarName            variable name to be saved in the output file.
     * @param  integer $iVarDepth           depth is used to process nesting, for example, in cycles.
     * @param  array   $aVarValues          values to be compiled in.
     * @param  mixed   $mixedKeyWrapperHtml key wrapper(string value if left and right parts are the same, array(0 => left, 1 => right) otherwise).
     * @return string  the result of operation.
     */
    function _compileContent($sContent, $aVarName, $iVarDepth, $aVarValues, $mixedKeyWrapperHtml = null)
    {
        $aKeys = array_keys($aVarValues);
        $aValues = array_values($aVarValues);

        $aKeyWrappers = $this->_getKeyWrappers($mixedKeyWrapperHtml);

        for($i = 0; $i < count($aKeys); $i++) {
            if(strpos($aKeys[$i], 'bx_repeat:') === 0) {
                $sKey = "'<" . $aKeys[$i] . ">(.*)<\/" . $aKeys[$i] . ">'s";

                $aMatches = array();
                preg_match($sKey, $sContent, $aMatches);

                $sValue = '';
                if(isset($aMatches[1]) && !empty($aMatches[1])) {
                    if(empty($aValues[$i]) || !is_array($aValues[$i]))
                        return false;

                    $sIndex = "\$" . str_repeat("i", $iVarDepth);
                    $sValue .= "<?php if(is_array(" . $aVarName . "['" . $aKeys[$i] . "'])) for(" . $sIndex . "=0; " . $sIndex . "<count(" . $aVarName . "['" . $aKeys[$i] . "']); " . $sIndex . "++){ ?>";
                    if(($sInnerValue = $this->_compileContent($aMatches[1], $aVarName . "['" . $aKeys[$i] . "'][" . $sIndex . "]", $iVarDepth + 1, current($aValues[$i]), $mixedKeyWrapperHtml)) === false)
                        return false;
                    $sValue .= $sInnerValue;
                    $sValue .= "<?php } else if(is_string(" . $aVarName . "['" . $aKeys[$i] . "'])) echo " . $aVarName . "['" . $aKeys[$i] . "']; ?>";
                }
            } else if(strpos($aKeys[$i], 'bx_if:') === 0) {
                $sKey = "'<" . $aKeys[$i] . ">(.*)<\/" . $aKeys[$i] . ">'s";

                $aMatches = array();
                preg_match($sKey, $sContent, $aMatches);

                $sValue = '';
                if(isset($aMatches[1]) && !empty($aMatches[1])) {
                    if(!is_array($aValues[$i]) || !isset($aValues[$i]['content']) || empty($aValues[$i]['content']) || !is_array($aValues[$i]['content']))
                        return false;

                    $sValue .= "<?php if(" . $aVarName . "['" . $aKeys[$i] . "']['condition']){ ?>";
                    if(($sInnerValue = $this->_compileContent($aMatches[1], $aVarName . "['" . $aKeys[$i] . "']['content']", $iVarDepth, $aValues[$i]['content'], $mixedKeyWrapperHtml)) === false)
                        return false;
                    $sValue .= $sInnerValue;
                    $sValue .= "<?php } ?>";
                }
            } else {
                $sKey = "'" . $aKeyWrappers['left'] . $aKeys[$i] . $aKeyWrappers['right'] . "'s";
                $sValue = "<?php echo " . $aVarName . "['" . $aKeys[$i] . "'];?>";
            }

            $aKeys[$i] = $sKey;
            $aValues[$i] = $sValue;
        }

        try {
        	$oTemplate = &$this;

        	$aCallbackPatterns = array(
        		"'<bx_include_auto:([^\s]+) \/>'s" => BX_DOL_TEMPLATE_CHECK_IN_BOTH,
        		"'<bx_include_base:([^\s]+) \/>'s" => BX_DOL_TEMPLATE_CHECK_IN_BASE,
        		"'<bx_include_tmpl:([^\s]+) \/>'s" => BX_DOL_TEMPLATE_CHECK_IN_TMPL
        	);
	        foreach($aCallbackPatterns as $sPattern => $sCheckIn)
		        $sContent = preg_replace_callback($sPattern, function($aMatches) use($oTemplate, $aVarValues, $mixedKeyWrapperHtml, $sCheckIn) {
		        	$mixedResult = $oTemplate->getCached($aMatches[1], $aVarValues, $mixedKeyWrapperHtml, $sCheckIn, false);
		        	if($mixedResult === false)
		        		throw new Exception('Unable to create cache file.');

		        	return $mixedResult;
		        }, $sContent);

			$sContent = $this->_parseContentKeys($sContent);
        }
        catch(Exception $oException) {
        	return false;
        }

        $aKeys = array_merge($aKeys, array(
            "'<bx_injection:([^\s]+) />'s",
            "'<bx_menu:([^\s]+) \/>'s",
            "'<bx_url_root />'",
            "'<bx_url_studio />'"
        ));
        $aValues = array_merge($aValues, array(
            "<?php echo \$this->processInjection(\$this->aPage['name_index'], '\\1'); ?>",
            "<?php echo \$this->getMenu('\\1'); ?>",
            BX_DOL_URL_ROOT,
            BX_DOL_URL_STUDIO
        ));

        //--- Parse Predefined Keys ---//
        $sContent = preg_replace($aKeys, $aValues, $sContent);
        //--- Parse System Keys ---//
        $sContent = preg_replace( "'" . $aKeyWrappers['left'] . "([a-zA-Z0-9_-]+)" . $aKeyWrappers['right'] . "'", "<?php echo \$this->parseSystemKey('\\1', \$mixedKeyWrapperHtml);?>", $sContent);

        return $sContent;
    }
    protected function _parseContentKeys($sContent, $aCallbackPatterns = array())
    {
    	$oTemplate = &$this;

    	$aCallbackPatterns = array_merge($aCallbackPatterns, array(
			"'<bx_image_url:([^\s]+) \/>'s" => "get_image_url",
			"'<bx_icon_url:([^\s]+) \/>'s" => "get_icon_url",
			"'<bx_text:([_\{\}\w\d\s]+[^\s]{1}) \/>'s" => "get_text",
			"'<bx_text_js:([^\s]+) \/>'s" => "get_text_js",
			"'<bx_text_attribute:([^\s]+) \/>'s" => "get_text_attribute",
		));

		foreach($aCallbackPatterns as $sPattern => $sAction)
			$sContent = preg_replace_callback($sPattern, function($aMatches) use($oTemplate, $sAction) {
		    	$sResult = '';

	        	switch($sAction) {
	        		case 'get_image_url':
	        			$sResult = $oTemplate->getImageUrl($aMatches[1]);
	        			break;
	        		case 'get_icon_url':
	        			$sResult = $oTemplate->getIconUrl($aMatches[1]);
	        			break;
					case 'get_text':
	        			$sResult = _t($aMatches[1]);
	        			break;
	        		case 'get_text_js':
	        			$sResult = bx_js_string(_t($aMatches[1]));
	        			break;
	        		case 'get_text_attribute':
	        			$sResult = bx_html_attribute(_t($aMatches[1]));
	        			break;
	        		case 'get_injection':
	        			$sResult = $oTemplate->processInjection($oTemplate->getPageNameIndex(), $aMatches[1]);
	        			break;
	        		case 'get_menu':
	        			$sResult = $oTemplate->getMenu($aMatches[1]);
	        			break;
	        	}

	        	return $sResult;
	        }, $sContent);

		return $sContent;
    }

    /**
     * Get absolute location of some template's part.
     *
     * @param  string $sType    - result type. Available values 'url' and 'path'.
     * @param  string $sFolder  - folders to be searched in. @see $_sFolderHtml, $_sFolderCss, $_sFolderImages and $_sFolderIcons
     * @param  string $sName    - requested part name.
     * @param  string $sCheckIn where the content would be searched(base, template, both)
     * @return string absolute location (path/url) of the part.
     */
    function _getAbsoluteLocation($sType, $sFolder, $sName, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH)
    {
        $sDirectory = $this->getPath();

        if($sType == 'path') {
            $sDivider = DIRECTORY_SEPARATOR;
            $sRoot = BX_DIRECTORY_PATH_ROOT;
        } else if($sType == 'url') {
            $sDivider = '/';
            $sRoot = BX_DOL_URL_ROOT;
        }

        if(strpos($sName,'|') !== false) {
            $aParts = explode('|', $sName);
            $sName = $aParts[1];

            if(strpos($aParts[0],'@') !== false) {
                $aLocationParts = explode('@', $aParts[0]);
                $sLocationKey = $this->addLocation($aLocationParts[0], BX_DIRECTORY_PATH_ROOT . $aLocationParts[1], BX_DOL_URL_ROOT . $aLocationParts[1]);
            }
        }

        $sResult = '';
        $aLocations = array_reverse($this->_aLocations, true);
        foreach($aLocations as $sKey => $aLocation) {
            if(($sCheckIn == BX_DOL_TEMPLATE_CHECK_IN_BOTH || $sCheckIn == BX_DOL_TEMPLATE_CHECK_IN_TMPL) && extFileExists(BX_DIRECTORY_PATH_MODULES . $this->getPath(). 'data' . DIRECTORY_SEPARATOR . BX_DOL_TEMPLATE_FOLDER_ROOT . DIRECTORY_SEPARATOR . $sKey . DIRECTORY_SEPARATOR . $sFolder . $sName))
                $sResult = $sRoot . 'modules' . $sDivider . $sDirectory. 'data' . $sDivider . 'template' . $sDivider . $sKey . $sDivider . $sFolder . $sName;
            else if(($sCheckIn == BX_DOL_TEMPLATE_CHECK_IN_BOTH || $sCheckIn == BX_DOL_TEMPLATE_CHECK_IN_BASE) && extFileExists($aLocation['path'] . BX_DOL_TEMPLATE_FOLDER_ROOT . DIRECTORY_SEPARATOR . $sFolder . $sName))
                $sResult = $aLocation[$sType] . BX_DOL_TEMPLATE_FOLDER_ROOT . $sDivider . $sFolder . $sName;
            else
                continue;
            break;
        }

        /**
         * try to find from received path
         */
        if(!$sResult && @is_file(BX_DIRECTORY_PATH_ROOT . $aParts[0] . DIRECTORY_SEPARATOR . $aParts[1])) {
            $sResult = $sRoot . $aParts[0] . $sDivider . $aParts[1];
        }

        if(isset($sLocationKey))
           $this->removeLocation($sLocationKey);

        return $sType == 'path' && !empty($sResult) ? realpath($sResult) : $sResult;
    }
    /**
     * Get absolute location of some template's part.
     *
     * @param  string $sType result type. Available values 'url' and 'path'.
     * @param  string $sName requested part name.
     * @return string absolute location (path/url) of the part.
     */
    function _getAbsoluteLocationJs($sType, $sName)
    {
        $sResult = '';
        $aLocations = array_reverse($this->_aLocationsJs, true);
        foreach($aLocations as $sKey => $aLocation) {
            if(extFileExists($aLocation['path'] . $sName))
                $sResult = $aLocation[$sType] . $sName;
            else
                continue;
            break;
        }
        return $sType == 'path' && !empty($sResult) ? realpath($sResult) : $sResult;
    }
    function _getAbsoluteLocationCss($sType, $sName)
    {
    	$sNameLess = str_replace('.css', '.less', $sName);

    	$sResult = $this->_getAbsoluteLocation($sType, $this->_sFolderCss, $sNameLess);
    	if(!empty($sResult))
    		return $sResult;

        return $this->_getAbsoluteLocation($sType, $this->_sFolderCss, $sName);
    }
    /**
     * Get inline data for Images and Icons.
     *
     * @param  string  $sType    image/icon
     * @param  string  $sName    file name
     * @param  string  $sCheckIn where the content would be searched(base, template, both)
     * @return unknown
     */
    function _getInlineData($sType, $sName, $sCheckIn)
    {
        switch($sType) {
            case 'image':
                $sFolder = $this->_sFolderImages;
                break;
            case 'icon':
                $sFolder = $this->_sFolderIcons;
                break;
        }
        $sPath = $this->_getAbsoluteLocation('path', $sFolder, $sName, $sCheckIn);

        $iFileSize = 0;
        if($this->_bImagesInline && ($iFileSize = filesize($sPath)) !== false && $iFileSize < $this->_iImagesMaxSize) {
            $aFileInfo = pathinfo($sPath);
            return "data:image/" . strtolower($aFileInfo['extension']) . ";base64," . base64_encode(file_get_contents($sPath));
        }

        return false;
    }
    /**
     * Get file name where the template would be cached.
     *
     * @param  string $sAbsolutePath template's real path.
     * @return string the result of operation.
     */
    function _getCacheFileName($sType, $sAbsolutePath)
    {
        $sResult = bx_site_hash($sAbsolutePath);
        switch($sType) {
            case 'html':
                $sResult = $this->_sCacheFilePrefix . bx_lang_name() . '_' . $this->_sCode .  '_' . $sResult;
                break;
            case 'css':
                $sResult = $this->_sCssCachePrefix . $sResult;
                break;
            case 'js':
                $sResult = $this->_sJsCachePrefix . $sResult;
                break;
        }

        return $sResult;
    }
    /**
     * Get template key wrappers(left, right)
     *
     * @param  mixed $mixedKeyWrapperHtml key wrapper(string value if left and right parts are the same, array(0 => left, 1 => right) otherwise).
     * @return array result of operation.
     */
    function _getKeyWrappers($mixedKeyWrapperHtml)
    {
        $aResult = array();
        if(!empty($mixedKeyWrapperHtml) && is_string($mixedKeyWrapperHtml))
            $aResult = array('left' => $mixedKeyWrapperHtml, 'right' => $mixedKeyWrapperHtml);
        else if(!empty($mixedKeyWrapperHtml) && is_array($mixedKeyWrapperHtml))
            $aResult = array('left' => $mixedKeyWrapperHtml[0], 'right' => $mixedKeyWrapperHtml[1]);
        else
            $aResult = array('left' => $this->_sKeyWrapperHtml, 'right' => $this->_sKeyWrapperHtml);
        return $aResult;
    }

    /**
     * Process all added language translations and return them as a string.
     *
     * @return string with JS code.
     */
    function _processJsTranslations()
    {
        $sReturn = '';
        if(isset($this->aPage['js_translations']) && is_array($this->aPage['js_translations'])) {
            foreach($this->aPage['js_translations'] as $sKey => $sString)
                $sReturn .= "'" .  bx_js_string($sKey) . "': '" . bx_js_string($sString) . "',";

            $sReturn = substr($sReturn, 0, -1);
        }

        return '<script type="text/javascript" language="javascript">var aDolLang = {' . $sReturn . '};</script>';
    }
    /**
     * Process all added options and return them as a string.
     *
     * @return string with JS code.
     */
    function _processJsOptions()
    {
        $sReturn = '';
        if(isset($this->aPage['js_options']) && is_array($this->aPage['js_options'])) {
            foreach($this->aPage['js_options'] as $sName => $mixedValue)
                $sReturn .= "'" .  bx_js_string($sName) . "': '" . bx_js_string($mixedValue) . "',";

            $sReturn = substr($sReturn, 0, -1);
        }

        return '<script type="text/javascript" language="javascript">var aDolOptions = {' . $sReturn . '};</script>';
    }
    /**
     * Process all added images and return them as a string.
     *
     * @return string with JS code.
     */
    function _processJsImages()
    {
        $sReturn = '';
        if(isset($this->aPage['js_images']) && is_array($this->aPage['js_images'])) {
            foreach($this->aPage['js_images'] as $sKey => $sUrl)
                $sReturn .= "'" .  bx_js_string($sKey) . "': '" . bx_js_string($sUrl) . "',";

            $sReturn = substr($sReturn, 0, -1);
        }

        return '<script type="text/javascript" language="javascript">var aDolImages = {' . $sReturn . '};</script>';
    }

    /**
     * Get Gzip loader URL.
     *
     * @param $sType content type CSS/JS
     * @param $sName file name.
     * @return string with URL
     */
    function _getLoaderUrl($sType, $sName)
    {
        return BX_DOL_URL_ROOT . 'gzip_loader.php?file=' . $sName . '.' . $sType;
    }

    /**
     *
     * Functions to display pages with errors, messages and so on.
     *
     */
    function displayAccessDenied ($sMsg = '')
    {
        header("HTTP/1.0 403 Forbidden");
        $this->displayMsg($sMsg ? $sMsg : _t('_Access denied'));
    }
    function displayNoData ($sMsg = '')
    {
        header("HTTP/1.0 204 No Content");
        $this->displayMsg($sMsg ? $sMsg : _t('_Empty'));
    }
    function displayErrorOccured ($sMsg = '')
    {
        header("HTTP/1.0 500 Internal Server Error");
        $this->displayMsg($sMsg ? $sMsg : _t('_error occured'));
    }
    function displayPageNotFound ($sMsg = '')
    {
        header("HTTP/1.0 404 Not Found");
        $this->displayMsg($sMsg ? $sMsg : _t('_sys_request_page_not_found_cpt'));
    }
    function displayMsg ($s, $bTranslate = false)
    {
        $sTitle = $bTranslate ? _t($s) : $s;
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        $oTemplate->setPageHeader ($sTitle);
        $oTemplate->setPageContent ('page_main_code', DesignBoxContent($sTitle, MsgBox($sTitle), BX_DB_PADDING_DEF));
        $oTemplate->getPageCode();
        exit;
    }

    /**
     * * * * Static methods for work with template injections * * *
     *
     * Static method is used to add/replace the content of some key in the template.
     * It's usefull when you don't want to modify existing template but need to add some data to existing template key.
     *
     * @param  integer $iPageIndex - page index where injections would processed. Use 0 if you want it to be done on all the pages.
     * @param  string  $sKey       - template key.
     * @param  string  $sValue     - the data to be added.
     * @return string  the result of operation.
     */
    function processInjection($iPageIndex, $sKey, $sValue = "")
    {
        if($iPageIndex != 0 && isset($this->aPage['injections']['page_0'][$sKey]) && isset($this->aPage['injections']['page_' . $iPageIndex][$sKey]))
           $aSelection = @array_merge($this->aPage['injections']['page_0'][$sKey], $this->aPage['injections']['page_' . $iPageIndex][$sKey]);
        else if(isset($this->aPage['injections']['page_0'][$sKey]))
           $aSelection = $this->aPage['injections']['page_0'][$sKey];
        else if(isset($this->aPage['injections']['page_' . $iPageIndex][$sKey]))
            $aSelection = $this->aPage['injections']['page_' . $iPageIndex][$sKey];
        else
            $aSelection = array();

        if(is_array($aSelection))
            foreach($aSelection as $aInjection) {

                if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginInjection($sRand = time().rand());

                $sInjData = '';
                switch($aInjection['type']) {
                    case 'text':
                        $sInjData = $aInjection['data'];
                        break;

                    case 'service':
                    	if(BxDolService::isSerializedService($aInjection['data']))
                    		$sInjData = BxDolService::callSerialized($aInjection['data']);                    	
                        break;
                }

                if((int)$aInjection['replace'] == 1)
                    $sValue = $sInjData;
                else
                    $sValue .= $sInjData;

                if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endInjection($sRand, $aInjection);

            }

        return $sValue != '__' . $sKey . '__' ? str_replace('__' . $sKey . '__', '', $sValue) : $sValue;
    }
    /**
     * Static method to add ingection available on the current page only.
     *
     * @param string  $sKey     - template's key.
     * @param string  $sType    - injection type(text, php).
     * @param string  $sData    - the data to be added.
     * @param integer $iReplace - replace already existed data or not.
     */
    function addInjection($sKey, $sType, $sData, $iReplace = 0)
    {
        $this->aPage['injections']['page_0'][$sKey][] = array(
            'page_index' => 0,
            'key' => $sKey,
            'type' => $sType,
            'data' => $sData,
            'replace' => $iReplace
        );
    }

    function getPageCode($oTemplate = null)
    {
        if (empty($oTemplate))
           $oTemplate = $this;

        header( 'Content-type: text/html; charset=utf-8' );
        header( 'X-Frame-Options: sameorigin' );
        echo $oTemplate->parsePageByName('page_' . $oTemplate->getPageNameIndex() . '.html', $oTemplate->getPageContent());
    }
}

/** @} */
