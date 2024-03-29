<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit Organization Form.
 */
class BxOrgsFormEntry extends BxBaseModGroupsFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_bAllowChangeUserForAdmins = true;
        
        $this->MODULE = 'bx_organizations';
        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
