<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/payment/classes/BxPmtProvider.php');

class BxPfwProvider extends BxPmtProvider
{
	protected $_aConfig;

    /**
     * Constructor
     */
    function BxPfwProvider($oDb, $oConfig, $aConfig)
    {
        parent::BxPmtProvider($oDb, $oConfig, $aConfig);

        $this->_aConfig = $aConfig;
    }
}
