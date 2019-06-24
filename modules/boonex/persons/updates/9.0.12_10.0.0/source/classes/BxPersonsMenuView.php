<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * 'View person' menu.
 */
class BxPersonsMenuView extends BxBaseModProfileMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_persons';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
