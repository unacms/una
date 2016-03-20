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

    public function isPostReplyAllowed ($isPerformAction = false)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_COMMENT']);
		if($oPrivacy && !$oPrivacy->check($this->_iId))
			return false;

		return parent::isPostReplyAllowed($isPerformAction);
    }
}

/** @} */
