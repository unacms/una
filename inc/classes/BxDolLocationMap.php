<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * This class allow different implementations for displaying maps.
 *
 * Add record to sys_objects_location_map table, like you are doing this for Comments or Voting objects:
 * - object: your object name, usually it is in the following format - vendor prefix, underscore, module prefix;
 * - module: module name
 * - title: translatable title
 * - class_name: user defined class name which is derived from BxDolLocationField.
 * - class_file: the location of the user defined class, leave it empty if class is located in system folders.
 */
class BxDolLocationMap extends BxDolFactory implements iBxDolFactoryObject
{
	protected $_oDb;
	protected $_sObject;
    protected $_aObject;
    protected $_oTemplate;

    /**
     * Constructor
     */
    protected function __construct($aObject, $oTemplate = null)
    {
        parent::__construct();

        $this->_aObject = $aObject;
        $this->_sObject = $aObject['object'];
        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
        $this->_oDb = new BxDolLocationMapQuery($this->_aObject);
    }

   /**
     * Get object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    public static function getObjectInstance($sObject, $oTemplate = null)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolLocationMap!' . $sObject]))
            return $GLOBALS['bxDolClasses']['BxDolLocationMap!' . $sObject];

        $aObject = BxDolLocationMapQuery::getLocationMapObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxDolLocationMap';
        if(!empty($aObject['class_name'])) {
            $sClass = $aObject['class_name'];
            if(!empty($aObject['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['class_file']);
        }        

        $o = new $sClass($aObject, $oTemplate);
        return ($GLOBALS['bxDolClasses']['BxDolLocationMap!' . $sObject] = $o);
    }

    /**
     * Get current object name
     */
    public function getObjectName()
    {
        return $this->_aObject['object'];
    }

    /**
     * Get location map for single address
     * @param $aLocation location array with the following indexes: 
     *          lat, lng, country, state, city, zip, street, street_number
     * @param $sLocationHtml formatted address string
     * @param $aParams some specific params
     */
    public function getMapSingle($aLocation, $sLocationHtml = '', $aParams = array())
    {
        // override this method
        return '';
    }

    public function addCssJs()
    {
    }
}

/** @} */
