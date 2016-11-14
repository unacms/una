<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolLiveUpdates
 */
class BxDolLiveUpdatesQuery extends BxDolDb
{
    public function __construct()
    {
        parent::__construct();
    }

	public function getSystems()
    {
        if(!isset($GLOBALS['bx_dol_live_updates_systems']))
            $GLOBALS['bx_dol_live_updates_systems'] = BxDolDb::getInstance()->fromCache('sys_objects_live_updates', 'getAllWithKey', '
                SELECT
                    `id` as `id`,
                    `name` AS `name`,
                    `frequency` AS `frequency`,
                    `service_call` AS `service_call`,
                    `active` AS `active`
                FROM `sys_objects_live_updates`', 'name');

        return $GLOBALS['bx_dol_live_updates_systems'];
    }
}

/** @} */
