<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOrgsAlertsResponse extends BxBaseModGroupsAlertsResponse
{
    public function __construct()
    {
    	$this->MODULE = 'bx_organizations';
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        parent::response($oAlert);

        $sMethod = 'process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
    	if(method_exists($this, $sMethod))
    		$this->$sMethod($oAlert);
    }

    protected function processAccountCheckSwitchContext(&$oAlert)
    {
        if ($oAlert->aExtras['override_result'])
            return;
        
        $iProfile = (int)$oAlert->aExtras['switch_to_profile'];
        $oProfile = BxDolProfile::getInstance($iProfile);
        if(!$oProfile || $oProfile->getModule() != $this->_oModule->_oConfig->getName())
            return;

        $aDataEntry = $this->_oModule->_oDb->getContentInfoById($oProfile->getContentId());
        if(empty($aDataEntry) || !is_array($aDataEntry))
            return;

        $oAlert->aExtras['override_result'] = $oAlert->iObject == $oAlert->aExtras['viewer_account'] || $this->_oModule->isAllowedActionByRole(BX_ORGANIZATIONS_ACTION_SWITCH_TO_PROFILE, $aDataEntry, $iProfile, $oAlert->iSender);
    }
}

/** @} */
