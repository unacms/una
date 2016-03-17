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

class BxMarketPrivacy extends BxTemplPrivacy
{
	protected $MODULE;
	protected $_oModule;

    function __construct($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_market';

    	$this->_oModule = BxDolModule::getInstance($this->MODULE);
    	if(!$oTemplate)
			$oTemplate = $this->_oModule->_oTemplate;

        parent::__construct($aOptions, $oTemplate);
    }

	/**
     * Check whethere viewer is a member of dynamic group.
     *
     * @param  mixed   $mixedGroupId   dynamic group ID.
     * @param  integer $iObjectOwnerId object owner ID.
     * @param  integer $iViewerId      viewer ID.
     * @return boolean result of operation.
     */
    function isDynamicGroupMember($mixedGroupId, $iObjectOwnerId, $iViewerId, $iObjectId)
    {
        if($mixedGroupId != 'c')
        	return false; 

        return $this->_oModule->_oDb->hasLicense($iViewerId, $iObjectId);
    }
}

/** @} */
