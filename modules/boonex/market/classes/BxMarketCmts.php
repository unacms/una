<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarketCmts extends BxTemplCmts
{
	protected $MODULE;
	protected $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->MODULE = 'bx_market';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sSystem, $iId, $iInit);

        $this->_aT['block_comments_title'] = '_bx_market_page_block_title_entry_comments_n';
    }

    public function isPostAllowed ($isPerformAction = false)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_COMMENT']);
        if($oPrivacy && !$oPrivacy->check($this->_iId))
            return false;

        return parent::isPostAllowed($isPerformAction);
    }
}

/** @} */
