<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * This class allow different implementations for location field.
 *
 * Add record to sys_objects_location_field table, like you are doing this for Comments or Voting objects:
 * - object: your object name, usually it is in the following format - vendor prefix, underscore, module prefix;
 * - module: module name
 * - title: translatable title
 * - class_name: user defined class name which is derived from BxDolLocationField.
 * - class_file: the location of the user defined class, leave it empty if class is located in system folders.
 */
class BxDolLocationField extends BxDolFactoryObject
{
    protected function __construct($aObject)
    {
        parent::__construct($aObject, null, 'BxDolLocationFieldQuery');
    }

   /**
     * Get object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    public static function getObjectInstance($sObject)
    {
        return parent::getObjectInstanceByClassNames($sObject, null, 'BxDolLocationField', 'BxDolLocationFieldQuery');
    }

    public function genInputLocation (&$aInput, $oForm)
    {        
        // override this
        return 'not implemented';
    }

    protected function getLocationVal ($aInput, $sIndex, $oForm) 
    {
        $aSpecificValues = $oForm->getSpecificValues();

        $s = $aInput['name'] . '_' . $sIndex;
        if (isset($aSpecificValues[$s]))
            return $aSpecificValues[$s];

        return $oForm->getCleanValue($s);
    }
}

/** @} */
