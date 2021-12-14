<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Xero Xero
 * @indroup     UnaModules
 *
 * @{
 */

class BxXeroAlertsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_xero';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(method_exists($this, $sMethod))
            $this->$sMethod($oAlert);
    }

    protected function _processSystemSaveSetting($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!in_array($oAlert->aExtras['option'], [$CNF['PARAM_CLIENT_ID'], $CNF['PARAM_CLIENT_SECRET']]))
            return;

        if(strcmp($oAlert->aExtras['value'], $oAlert->aExtras['value_prior']) == 0)
            return;

        $this->_oModule->_oConfig->cleanSession();
        $this->_oModule->_oDb->cleanData();
    }
}

/** @} */
