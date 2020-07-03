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

class BxCoursesGridinvites extends BxBaseModGroupsGridInvites
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sContentModule = 'bx_courses';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
