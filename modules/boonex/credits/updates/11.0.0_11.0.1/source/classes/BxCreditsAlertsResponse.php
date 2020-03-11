<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCreditsAlertsResponse extends BxBaseModGeneralAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_credits';

        parent::__construct();
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(!method_exists($this, $sMethod))
            return;

        return $this->$sMethod($oAlert);        
    }
    
    protected function _processProfileAdd($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        $iProfile = (int)$oAlert->iObject;
        $oProfile = BxDolProfile::getInstance($iProfile);
        if(!$oProfile)
            return;

        if(getParam($CNF['PARAM_PROVIDER_ENABLE']) == 'on') {
            $oProvider = BxDolPayments::getInstance()->getProvider($CNF['PARAM_PROVIDER_NAME'], $iProfile);
            if($oProvider)
                $oProvider->setOption('active', 'on', true);
        }
    }

    protected function _processProfileDelete($oAlert)
    {
        if(empty($oAlert->aExtras['delete_with_content']))
            return;

        return BxDolService::call($this->MODULE, 'delete_entities_by_author', array($oAlert->iObject));
    }
}

/** @} */
