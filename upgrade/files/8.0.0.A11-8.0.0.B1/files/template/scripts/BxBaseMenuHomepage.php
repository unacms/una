<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Homepage menu representation.
 */
class BxBaseMenuHomepage extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode ()
    {
        $s = parent::getCode ();
        return '<div id="bx-homepage-menu-' . $this->_sObject . '">' . $s . '</div>';
    }
}

/** @} */
