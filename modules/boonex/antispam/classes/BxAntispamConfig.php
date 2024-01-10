<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
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
        'disposable_email_domains_mode' => 'bx_antispam_disposable_email_domains_mode',
        'disposable_email_domains_behaviour_join' => 'bx_antispam_disposable_email_domains_behaviour_join',
        'profanity_enable' => 'bx_antispam_profanity_filter_enable',
        'toxicity_enable' => 'bx_antispam_toxicity_filter_enable',
        'toxicity_action' => 'bx_antispam_toxicity_filter_action',
        'toxicity_report' => 'bx_antispam_toxicity_filter_report',
        'lm_enable' => 'bx_antispam_lasso_moderation_enable',
        'lm_api_key' => 'bx_antispam_lasso_moderation_api_key',
        'lm_webhook_secret' => 'bx_antispam_lasso_moderation_webhook_secret',
        'lm_action' => 'bx_antispam_lasso_moderation_action',
        'lm_report' => 'bx_antispam_lasso_moderation_report',
        'lm_thd_toxicity' => 'bx_antispam_lasso_moderation_threshold_toxicity',
        'lm_thd_threat' => 'bx_antispam_lasso_moderation_threshold_threat',
        'lm_thd_identity_attack' => 'bx_antispam_lasso_moderation_threshold_identity_attack',
        'lm_thd_profanity' => 'bx_antispam_lasso_moderation_threshold_profanity',
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
        'profanity_enable' => '',
        'toxicity_enable' => '',
        'toxicity_action' => '',
        'toxicity_report' => '',
        'lm_enable' => '',
        'lm_api_key' => '',
        'lm_webhook_secret' => '',
        'lm_action' => '',
        'lm_report' => '',
        'lm_thd_toxicity' => '',
        'lm_thd_threat' => '',
        'lm_thd_identity_attack' => '',
        'lm_thd_profanity' => '',
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
