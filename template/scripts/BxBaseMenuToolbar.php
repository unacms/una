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
            if('account' != $r['name'])
                continue;

            $oProfile = BxDolProfile::getInstance(bx_get_logged_profile_id());

            $sUnit = $oProfile->getUnit(0, array('template' => 'unit_wo_info_links'));
            if(!empty($sUnit)) {
                $a[$k]['bx_if:icon']['condition'] = false;
                $a[$k]['bx_if:unit'] = array (
                    'condition' => true,
                    'content' => array('unit' => $sUnit),
                );

                break;
            }

            $sThumb = $oProfile->getThumb();
            if(!empty($sThumb)) {
                $a[$k]['bx_if:icon']['condition'] = false;
                $a[$k]['bx_if:image'] = array (
                    'condition' => true,
                    'content' => array('icon_url' => $sThumb),
                );

                break;
            }
        }

        return $a;
    }

    protected function _getMenuItem ($a)
    {
        $a = parent::_getMenuItem ($a);
        if($a === false)
            return $a;

        $a['bx_if:unit'] = array(
        	'condition' => false,
        	'content' => array()
        );

        if ('add-content' == $a['name'] || 'search' == $a['name'])
            $a['class_add'] .= ' bx-def-media-phone-hide';

        return $a;
    }

}

/** @} */
