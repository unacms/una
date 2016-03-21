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

class BxMarketVote extends BxTemplVote
{
	protected $MODULE;
	protected $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->MODULE = 'bx_market';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sSystem, $iId, $iInit);        
    }

    public function isAllowedVote($isPerformAction = false)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	if($this->getObjectAuthorId() == $this->_getAuthorId())
    		return false;

    	$oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VOTE']);
		if($oPrivacy && !$oPrivacy->check($this->_iId))
			return false;

    	return parent::isAllowedVote($isPerformAction);
    }

	protected function _getLabelCounter($iCount)
    {
        return _t('_bx_market_vote_counter', $iCount);
    }
}

/** @} */
