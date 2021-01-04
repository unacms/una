<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLocationFieldQuery extends BxDolFactoryObjectQuery
{
    static public function getObject($sObject)
    {
        return parent::getObjectFromTable($sObject, 'sys_objects_location_field');
    }

    static public function getObjects ()
    {
        return parent::getObjectsFromTable('sys_objects_location_field');
    }
}

/** @} */
