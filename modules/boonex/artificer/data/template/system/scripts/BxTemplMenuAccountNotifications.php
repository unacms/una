<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolMenu
 */
class BxTemplMenuAccountNotifications extends BxBaseMenuAccountNotifications
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    protected function _getMenuItem ($a)
    {
        $a = parent::_getMenuItem ($a);
        if($a === false)
            return $a;

        $a['class_add_a'] = '';
        switch ($a['name']) {
            case 'add-content':
                if(isset($a['onclick']))
                    $a['onclick'] = str_replace('bx_menu_slide_inline', "$('.bx-popup-applied:visible').dolPopupHide(); bx_menu_popup_inline", $a['onclick']);
                break;
        }

        return $a;
    }
}

/** @} */
