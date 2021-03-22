<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Donations Donations
 * @indroup     UnaModules
 *
 * @{
 */

class BxDonationsAlertsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    protected $_bLog;
    protected $_oLog;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_donations';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_bLog = false;
        $this->_oLog = BxDolLog::getInstance();
        $this->_oLog->setName('donations');
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(method_exists($this, $sMethod))
            $this->$sMethod($oAlert);
    }

    protected function _processSystemGetBadges($oAlert)
    {
    }
}

/** @} */
