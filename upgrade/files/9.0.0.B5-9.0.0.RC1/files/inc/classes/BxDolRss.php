<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * This class unifies the usage of RSS feeds in the script.
 *
 *
 * Usage.
 *
 * Step 1:
 * Add record to sys_objects_rss table, like you are doing this for Comments or Voting objects:
 * - object: your rss object name, usually it is in the following format - vendor prefix, underscore, module prefix;
 * - class_name: user defined class name which is derived from BxTemplRss.
 * - class_file: the location of the user defined class, leave it empty if class is located in system folders.
 * 
 * Step 2:
 * Write a code for getUrl method in your class (MyModuleRss) which is derived from BxTemplRss.
 * @code
 * public function getUrl($mixedId) {
 * 		if(!isset($this->aFeeds[$mixedId]))
 * 			return false;
 * 
 * 		return $this->aFeeds[$mixedId];
 * }
 * @endcode
 * 
 * Step 2:
 * Get an RSS placeholder to display somewhere on a page
 * @code
 *  BxDolRss::getObjectInstance('my_module_rss_object')->getHolder($mixedRssId, $iRssNum);
 * @endcode
 *
 */

class BxDolRss extends BxDolFactory implements iBxDolFactoryObject
{
	protected $_oDb;
	protected $_sObject;
    protected $_aObject;

    public static $bInitialized = false;

    /**
     * Constructor
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_aObject = $aObject;
        $this->_sObject = $aObject['object'];

        $this->_oDb = new BxDolRssQuery($this->_aObject);
    }

   /**
     * Get rss object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    public static function getObjectInstance($sObject)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolRss!' . $sObject]))
            return $GLOBALS['bxDolClasses']['BxDolRss!' . $sObject];

        $aObject = BxDolRssQuery::getRssObject($sObject);
        if(!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxTemplRss';
        if(!empty($aObject['class_name'])) {
            $sClass = $aObject['class_name'];
            if(!empty($aObject['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['class_file']);
        }

        $o = new $sClass($aObject);
        return ($GLOBALS['bxDolClasses']['BxDolRss!' . $sObject] = $o);
    }

    public function getUrl($mixedId) {}
}

/** @} */
