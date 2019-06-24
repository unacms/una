<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileVote extends BxTemplVoteLikes
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct($sSystem, $iId, $iInit);        

    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function getObjectAuthorId($iObjectId = 0)
    {
        if(!BxDolService::call($this->_sModule, 'act_as_profile'))
            return parent::getObjectAuthorId ($iObjectId);

        $oProfile = BxDolProfile::getInstanceByContentAndType($iObjectId ? $iObjectId : $this->getId(), $this->_sModule);
        if(!$oProfile)
            return 0;

        return $oProfile->id();
    }
}

/** @} */
