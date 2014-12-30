<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Accounts Accounts
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralMenuManageTools');

/**
 * 'Persons manage tools' menu.
 */
class BxAccntMenuManageTools extends BxBaseModGeneralMenuManageTools
{

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_accounts';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
