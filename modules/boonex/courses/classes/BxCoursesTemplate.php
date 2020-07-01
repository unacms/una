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

/*
 * Courses module representation.
 */
class BxCoursesTemplate extends BxBaseModGroupsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_courses';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
