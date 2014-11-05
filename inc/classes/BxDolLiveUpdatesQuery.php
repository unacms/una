<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolDb');

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
