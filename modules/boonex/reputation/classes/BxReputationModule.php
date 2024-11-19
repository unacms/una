<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reputation Reputation
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReputationModule extends BxBaseModNotificationsModule
{
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * SERVICE METHODS
     */
    public function serviceGetBlockSummary($iProfileId = 0)
    {
        if(!$iProfileId && ($iLoggedId = bx_get_logged_profile_id()))
            $iProfileId = $iLoggedId;
        if(!$iProfileId)
            return false;

        return $this->_oTemplate->getBlockSummary($iProfileId);
    }
}

/** @} */
