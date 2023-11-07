<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 *
 * @{
 */

class BxInvResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_invites';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
        if(!method_exists($this, $sMethod))
            return;

        return $this->$sMethod($oAlert);
    }

    protected function _processAccountAddForm($oAlert)
    {
        if(!($sCode = $this->_oModule->serviceAccountAddFormCheck())) {
            $sKeyCode = $this->_oModule->_oConfig->getKeyCode();
            $sKeyValue = BxDolSession::getInstance()->getValue($sKeyCode);

            $aInvite = $this->_oModule->_oDb->getInvites(['type' => 'by_key', 'key' => $sKeyValue]);
            if(!empty($aInvite) && is_array($aInvite) && !empty($aInvite['redirect'])) {
                $oAlert->aExtras['form_object']->aInputs['relocate'] = [
                    'name' => 'relocate',
                    'type' => 'hidden',
                    'value' => BxDolPermalinks::getInstance()->permalink($aInvite['redirect'])
                ];

                $oAlert->aExtras['form_code'] = $oAlert->aExtras['form_object']->getCode();
            }
        }
        else
            $oAlert->aExtras['form_code'] = $sCode;
    }

    protected function _processAccountAdded($oAlert)
    {
        $sKeyCode = $this->_oModule->_oConfig->getKeyCode();
        $sKey = BxDolSession::getInstance()->getUnsetValue($sKeyCode);
        if($sKey === false)
            return;

        $this->_oModule->attachAccountIdToInvite($oAlert->iObject, $sKey);
        
        $sKeysToRemove = $this->_oModule->_oDb->getInvites(array('type' => 'invites_code_by_single', 'value' => $sKey));
        $aKeysToRemove = explode(',', $sKeysToRemove);
        $oKeys = BxDolKey::getInstance();
        if($oKeys){
            foreach($aKeysToRemove as $sKeyToRemove) {
                if($oKeys->isKeyExists($sKeyToRemove))
                    $oKeys->removeKey($sKeyToRemove);
            }
        }
        
        return;
    }

    protected function _processProfileAdd($oAlert)
    {
        if (getParam('bx_invites_automatically_befriend') != 'on')
            return;
        
		$bNeedToFriend = true;
		bx_alert($this->_sModule, 'add_friend', 0, 0, [
			'profile_id' => $oAlert->iObject,
			'override_result' => &$bNeedToFriend,
		]);
		
		if ($bNeedToFriend){
			$oProfile = BxDolProfile::getInstanceMagic($oAlert->iObject);
			if ($oProfile && $oProfile->isActAsProfile()){
				$iProfileInvitor = $this->_oModule->_oDb->getInvites(array('type' => 'profile_id_by_joined_account_id', 'value' => $oProfile->getAccountId()));
				if ($iProfileInvitor){
					$oConnFrinds = BxDolConnection::getObjectInstance('sys_profiles_friends');
					$oConnFrinds->addConnection($oAlert->iObject, $iProfileInvitor);
					$oConnFrinds->addConnection($iProfileInvitor, $oAlert->iObject);
				}
			}  
		}
    }
    
    protected function _processProfileDelete($oAlert)
    {
        $this->_oModule->_oDb->deleteInvites(array('profile_id' => $oAlert->iObject));
    }
    
    protected function _processAccountDelete($oAlert)
    {
        $this->_oModule->_oDb->deleteInvitesByAccount(array('joined_account_id' => $oAlert->iObject));
    }
    
    protected function _processBxAnalyticsGetModules($oAlert)
    {
        $oAlert->aExtras['list'][$this->_oModule->_aModule['name']] = $this->_oModule->_aModule['title'];
    }
    
    protected function _processBxAnalyticsGetReports($oAlert)
    {
        if ($this->_oModule->_aModule['name'] == $oAlert->aExtras['module']){
            $oAlert->aExtras['list'] = array();
            $oAlert->aExtras['list']['content_total_invited'] = _t('_bx_invites_reports_for_analytics_invited_total');
            $oAlert->aExtras['list']['content_speed_invited'] = _t('_bx_invites_reports_for_analytics_invited_speed_grows');
            $oAlert->aExtras['list']['content_total_invitation'] = _t('_bx_invites_reports_for_analytics_invitation_total');
            $oAlert->aExtras['list']['content_speed_invitation'] = _t('_bx_invites_reports_for_analytics_invitation_speed_grows');
        }
    }
    
    protected function _processBxAnalyticsGetChartDataLine($oAlert)
    {
        if ($this->_oModule->_aModule['name'] == $oAlert->aExtras['module']){
            $bIsInvited = false; 
            if (substr_count($oAlert->aExtras['report_name'], '_invited') > 0)
                $bIsInvited = true; 
            
            if (substr_count($oAlert->aExtras['report_name'], '_total_') > 0)
                $oAlert->aExtras['report_type'] = BX_ANALYTICS_CONTENT_TOTAL;
            
            $oAlert->aExtras['data'] = $this->_oModule->_oDb->getDataForCharts($oAlert->aExtras['date_from'], $oAlert->aExtras['date_to'], $bIsInvited);
            $oAlert->aExtras['prev_value'] = $this->_oModule->_oDb->getInitValueForCharts($oAlert->aExtras['date_from'], $bIsInvited);
        }
    }
}

/** @} */
