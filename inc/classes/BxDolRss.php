<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolRssQuery');

/**
 * @page objects
 * @section rss Rss
 * @ref BxDolRss
 */


class BxDolRss extends BxDol implements iBxDolFactoryObject
{
	protected $_oDb;
	protected $_sObject;
    protected $_aObject;

    public static $bInitialized = false;

    /**
     * Constructor
     */
    function __construct($aObject)
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

        bx_import('BxDolRssQuery');
        $aObject = BxDolRssQuery::getRssObject($sObject);
        if(!$aObject || !is_array($aObject))
            return false;

        bx_import('BxTemplRss');
        $sClass = 'BxTemplRss';
        if(!empty($aObject['class_name'])) {
            $sClass = $aObject['class_name'];
            if(!empty($aObject['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['class_file']);
            else
                bx_import($sClass);
        }

        $o = new $sClass($aObject);
        return ($GLOBALS['bxDolClasses']['BxDolRss!' . $sObject] = $o);
    }

    public function getUrl($mixedId) {}
}

/** @} */
