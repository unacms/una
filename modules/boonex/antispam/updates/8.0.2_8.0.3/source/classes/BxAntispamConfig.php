<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Antispam Antispam
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAntispamConfig extends BxDolModuleConfig
{
    /**
     * map system options to the local options names
     */
    protected $_aOptionsMap = array (
        'antispam_block' => 'bx_antispam_block',
        'antispam_report' => 'bx_antispam_report',
        'dnsbl_enable' => 'bx_antispam_dnsbl_enable',
        'uridnsbl_enable' => 'bx_antispam_uridnsbl_enable',
        'dnsbl_behaviour_login' => 'bx_antispam_dnsbl_behaviour_login',
        'dnsbl_behaviour_join' => 'bx_antispam_dnsbl_behaviour_join',
        'akismet_enable' => 'bx_antispam_akismet_enable',
    );
    /**
     * default local options, it is filled in with real system options in class contructor, @see restoreAntispamOptions
     */
    protected $_aOptions = array (
        'antispam_block' => '',
        'antispam_report' => '',
        'dnsbl_enable' => '',
        'uridnsbl_enable' => '',
        'dnsbl_behaviour_login' => 'block',
        'dnsbl_behaviour_join' => 'approval',
        'akismet_enable' => '',
    );

    public function __construct($aModule)
    {
        parent::__construct($aModule);
        $this->restoreAntispamOptions ();
    }

    /**
     * Set local option value, system value stays intact, useful for testing and debugging
     */
    public function setAntispamOption ($sOption, $mixedVal)
    {
        $this->_aOptions[$sOption] = $mixedVal;
    }

    /**
     * Get an option, local value
     */
    public function getAntispamOption ($sOption)
    {
        return $this->_aOptions[$sOption];
    }

    /**
     * set local options values from system options
     */
    public function restoreAntispamOptions ()
    {
        foreach ($this->_aOptionsMap as $sNameLocal => $sNameParam)
            $this->setAntispamOption($sNameLocal, getParam($sNameParam));
    }
}

/** @} */
