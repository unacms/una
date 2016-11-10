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
class BxOrgsFormEntry extends BxBaseModProfileFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_organizations';
        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
