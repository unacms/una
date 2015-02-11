<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolLanguages');

class BxBaseModGeneralRequest extends BxDolRequest
{
    function __construct()
    {
        parent::__construct();
    }
}

/** @} */
