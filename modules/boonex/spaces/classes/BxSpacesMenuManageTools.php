<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * 'Spaces manage tools' menu.
 */
class BxSpacesMenuManageTools extends BxBaseModGroupsMenuManageTools
{

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_spaces';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
