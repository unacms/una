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
class BxDolLocationMap extends BxDolFactoryObject
{
    protected function __construct($aObject, $oTemplate = null)
    {
        parent::__construct($aObject, $oTemplate, 'BxDolLocationFieldQuery');
    }

   /**
     * Get object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    public static function getObjectInstance($sObject, $oTemplate = null)
    {
        return parent::getObjectInstanceByClassNames($sObject, $oTemplate, 'BxDolLocationMap', 'BxDolLocationMapQuery');
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
