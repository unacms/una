<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseTemplate Base classes for template modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModTemplateAlertsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    function __construct()
    {
        parent::__construct();

    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function response($oAlert)
    {
        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(!method_exists($this, $sMethod))
            return;

        $this->$sMethod($oAlert);
    }

    protected function _processSystemSaveSetting($oAlert)
    {
        $sPrefix = $this->_oModule->_oConfig->getPrefix('option');

        switch($oAlert->aExtras['option']) {
            case $sPrefix . 'site_logo':
                setParam($sPrefix . 'site_logo_aspect_ratio', '');
                break;
        }
    }

    protected function _processSystemChangeLogo($oAlert)
    {
        $sPrefix = $this->_oModule->_oConfig->getPrefix('option');

        if(!in_array($oAlert->aExtras['option'], ['sys_site_logo']))
            return;

        setParam($sPrefix . 'site_logo_aspect_ratio', '');
    }

    protected function _isActive()
    {
        return BxDolTemplate::getInstance()->getCode() == $this->_oModule->_oConfig->getUri();
    }
}

/** @} */
