<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioDashboardQuery extends BxDolStudioPageQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function getModuleStorageSize($sModule)
    {
    	$sSql = "SELECT SUM(`current_size`) AS `size` FROM `sys_objects_storage` WHERE `object` LIKE " . $this->escape($sModule . '%') . " LIMIT 1";
    	return $this->getOne($sSql);
    }
}

/** @} */
