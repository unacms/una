<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Organizations Organizations
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModProfileFormEntry');
bx_import('BxDolProfile');
bx_import('BxDolStorage');

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
