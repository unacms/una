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

    public function getLocationVal ($aInput, $sIndex, $oForm) 
    {
        $aSpecificValues = $oForm->getSpecificValues();

        $s = $aInput['name'] . '_' . $sIndex;
        if (isset($aSpecificValues[$s]))
            return $aSpecificValues[$s];

        return $oForm->getCleanValue($s);
    }

    public function setLocationVals ($aInput, $aVals, $oForm)
    {	
        $oForm->setSpecificValue($aInput['name'] . '_lat', $aVals['lat']);
        $oForm->setSpecificValue($aInput['name'] . '_lng', $aVals['lng']);
        $oForm->setSpecificValue($aInput['name'] . '_country', $aVals['country']);
        $oForm->setSpecificValue($aInput['name'] . '_state', $aVals['state']);
        $oForm->setSpecificValue($aInput['name'] . '_city', $aVals['city']);
        $oForm->setSpecificValue($aInput['name'] . '_zip', $aVals['zip']);
        $oForm->setSpecificValue($aInput['name'] . '_street', $aVals['street']);
        $oForm->setSpecificValue($aInput['name'] . '_street_number', $aVals['street_number']);

        return true;
    }

    public function setLocationVal ($aInput, $sIndex, $sVal, $oForm)	
    {	
        $s = $aInput['name'] . '_' . $sIndex;
        $oForm->setSpecificValue($s, $sVal);
        return true;
    }
}

/** @} */
