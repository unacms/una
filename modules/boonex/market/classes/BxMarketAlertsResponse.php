<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 *
 * @{
 */

class BxMarketAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_market';
        parent::__construct();
    }

	public function response($oAlert)
    {
    	$oModule = BxDolModule::getInstance($this->MODULE);

    	if($oAlert->sAction == 'file_deleted')
    		switch ($oAlert->sUnit) {
    			case 'bx_market_files':
    				$oModule->_oDb->deassociateFileWithContent(0, $oAlert->iObject);
    				break;

    			case 'bx_market_photos':
    				$oModule->_oDb->deassociatePhotoWithContent(0, $oAlert->iObject);
    				break;
    		}

        parent::response($oAlert);
    }
}

/** @} */
