<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Organizations Organizations
 * @ingroup     TridentModules
 *
 * @{
 */

class BxOrgsAlertsResponse extends BxBaseModProfileAlertsResponse
{
    public function __construct()
    {
    	$this->MODULE = 'bx_organizations';
        parent::__construct();
    }
}

/** @} */
