<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for embed objects.
 * @see BxDolEmbed
 */
class BxDolEmbedQuery extends BxDolFactoryObjectQuery
{
    static public function getObject($sObject)
    {
        return parent::getObjectFromTable($sObject, 'sys_objects_embeds');
    }

    static public function getObjects ()
    {
        return parent::getObjectsFromTable('sys_objects_embeds');
    }
}

/** @} */
