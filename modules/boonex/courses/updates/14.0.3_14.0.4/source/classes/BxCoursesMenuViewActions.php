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

    protected function _getMenuItem ($a)
    {
        $aResult = parent::_getMenuItem($a);
        if(!$aResult)
            return $aResult;

        if($this->_bIsApi && (($bHide = $a['name'] == 'hide-course-profile') || $a['name'] == 'unhide-course-profile')) {
            $aResult = array_merge($aResult, [
                'display_type' => 'callback',
                'data' => [
                    'request_url' => $this->MODULE . '/' . ($bHide ? 'hide' : 'publish') . '/&params[]=' . $this->_iContentId, 
                    'on_callback' => 'change',
                ]
            ]);
        }
        
        return $aResult;
    }
}

/** @} */
