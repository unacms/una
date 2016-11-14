<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
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
