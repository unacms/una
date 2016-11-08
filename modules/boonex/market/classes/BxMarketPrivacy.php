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
