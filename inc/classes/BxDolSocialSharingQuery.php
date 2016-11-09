<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolSocialSharing
 */
class BxDolSocialSharingQuery extends BxDolDb
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getActiveButtons ()
    {
        return $this->fromCache('sys_objects_social_sharing', 'getAll', 'SELECT * FROM `sys_objects_social_sharing` WHERE `active` = 1 ORDER BY `order` ASC');
    }

}

/** @} */
