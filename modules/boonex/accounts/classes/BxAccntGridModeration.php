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

require_once('BxAccntGridAdministration');

class BxAccntGridModeration extends BxAccntGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_accounts';
        parent::__construct ($aOptions, $oTemplate);

        $this->_sManageType = 'moderation';
    }
}

/** @} */
