<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxStripeConnectFormCommissions extends BxTemplStudioFormView
{
    protected $_sModule;
    protected $_oModule;

    function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
        
        $this->_sModule = 'bx_stripe_connect';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(($sKey = $CNF['FIELD_CMS_ACL_ID']) && isset($this->aInputs[$sKey])) {
            $this->aInputs[$sKey]['values'] = [
                ['key' => '0', 'value' => _t('_Select_one')]
            ];

            $aLevels = BxDolAcl::getInstance()->getMemberships(false, true, false, true);
            foreach($aLevels as $iLevelId => $sLevelTitle)
               $this->aInputs[$sKey]['values'][] = [
                   'key' => $iLevelId, 
                   'value' => _t($sLevelTitle)
                ];
        }
    }
}

/** @} */
