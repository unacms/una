<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplMenu');

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

    protected function _getMenuItems () {
        $a = parent::_getMenuItems ();

        if (!isLogged())
            return $a;
        
        foreach ($a as $k => $r) {
            if ('account' != $r['name'])
                continue;

            bx_import('BxDolProfile');
            $oProfile = BxDolProfile::getInstance(bx_get_logged_profile_id ());
            $sUrlIcon = $oProfile->getIcon();
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
}

/** @} */
