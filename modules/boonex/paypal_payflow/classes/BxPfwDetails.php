<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/payment/classes/BxPmtDetails.php');

class BxPfwDetails extends BxPmtDetails
{
    /*
     * Constructor.
     */
    function BxPfwDetails(&$oDb, &$oConfig)
    {
    	parent::BxPmtDetails($oDb, $oConfig);
    }
}
