<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Spaces Spaces
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * List entry page
 */
class BxSpacesPageListEntry extends BxBaseModGroupsPageListEntry
{    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_spaces';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
