<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
 * 
 * @{
 */

bx_import('BxTemplFormAccount');

class BxAccntFormAccountCheckerHelper extends BxFormAccountCheckerHelper {}

class BxAccntFormAccount extends BxTemplFormAccount
{
    function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
