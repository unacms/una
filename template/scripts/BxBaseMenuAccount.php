<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuAccount extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->addMarkers(array(
        	'studio_url' => BX_DOL_URL_STUDIO
        ));
    }

    /**
     * Check if menu items are visible with extended checking
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if($a['name'] == 'studio' && !isAdmin())
        	return false;

        return parent::_isVisible($a);
    }
}

/** @} */
