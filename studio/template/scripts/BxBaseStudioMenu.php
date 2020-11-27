<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioMenu extends BxDolStudioMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }
    
    protected function _getMenuItem ($aItem)
    {
        $aItem = parent::_getMenuItem($aItem);
        if($aItem !== false)
            $aItem['class'] = isset($aItem['class']) ? $aItem['class'] : '';

        return $aItem;
    }

    /**
     * Check if menu items is selected.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isSelected ($a)
    {
        return isset($a['selected']) && $a['selected'] === true;
    }
}

/** @} */
