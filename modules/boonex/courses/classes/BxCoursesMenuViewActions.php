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
 * 'View group' actions menu.
 */
class BxCoursesMenuViewActions extends BxBaseModGroupsMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_courses';

        parent::__construct($aObject, $oTemplate);

        $this->addMarkers(array(
            'js_object' => $this->_oModule->_oConfig->getJsObject('entry')
        ));
    }
}

/** @} */
