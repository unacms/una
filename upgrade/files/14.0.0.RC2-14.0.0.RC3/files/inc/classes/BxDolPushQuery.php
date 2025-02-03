<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for push objects.
 * @see BxDolPush
 */
class BxDolPushQuery extends BxDolFactoryObjectQuery
{
    static public function getObject($sObject)
    {
        return parent::getObjectFromTable($sObject, 'sys_objects_push');
    }

    static public function getObjects ()
    {
        return parent::getObjectsFromTable('sys_objects_push');
    }
}

/** @} */
