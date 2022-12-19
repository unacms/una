<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEventsAlertsResponse extends BxBaseModGroupsAlertsResponse
{
    public function __construct()
    {
    	$this->MODULE = 'bx_events';
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        parent::response($oAlert);
        if(isset($oAlert->aExtras['module'] ) && $oAlert->aExtras['module'] == $this->MODULE && $oAlert->sAction == 'check_allowed_fan_add' && $oAlert->sUnit == 'system'){
           if (getParam('bx_events_enable_subscribe_for_past_events') != 'on'){
               $CNF = $this->_oModule->_oConfig->CNF;
               if ($oAlert->aExtras['content_info'][$CNF['FIELD_DATE_END']] < time()){
                   $oAlert->aExtras['override_result'] = false;
               }
           }
        }
    }
}

/** @} */
