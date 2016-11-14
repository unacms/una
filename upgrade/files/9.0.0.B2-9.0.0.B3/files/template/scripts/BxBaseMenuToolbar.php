<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuToolbar extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getMenuItems ()
    {
        $a = parent::getMenuItems ();

        if (!isLogged())
            return $a;

        foreach ($a as $k => $r) {
            if ('account' != $r['name'])
                continue;

            $oProfile = BxDolProfile::getInstance(bx_get_logged_profile_id ());
            $sUrlIcon = $oProfile->getThumb();
            if (!$sUrlIcon)
                break;

            $a[$k]['bx_if:image'] = array (
                'condition' => true,
                'content' => array('icon_url' => $sUrlIcon),
            );
            $a[$k]['bx_if:icon']['condition'] = false;
        }

        return $a;
    }

    protected function _getMenuItem ($a)
    {
        $a = parent::_getMenuItem ($a);
        if ('add-content' == $a['name'] || 'search' == $a['name'])
            $a['class_add'] .= ' bx-def-media-phone-hide';
        return $a;
    }

}

/** @} */
