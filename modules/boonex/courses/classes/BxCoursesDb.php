<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Courses module database queries
 */
class BxCoursesDb extends BxBaseModGroupsDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
}

/** @} */
