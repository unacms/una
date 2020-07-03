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

class BxCoursesGridAdministration extends BxBaseModGroupsGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_courses';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
