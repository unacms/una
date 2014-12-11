<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

bx_import('BxDolStudioMenu');

class BxBaseStudioMenu extends BxDolStudioMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
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
