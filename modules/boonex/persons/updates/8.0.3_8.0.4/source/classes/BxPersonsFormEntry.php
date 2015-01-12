<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModProfileFormEntry');
bx_import('BxDolProfile');
bx_import('BxDolStorage');

/**
 * Create/Edit Person Form.
 */
class BxPersonsFormEntry extends BxBaseModProfileFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_persons';
        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
