<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModConnectConfig extends BxBaseModGeneralConfig
{
    public $sDefaultRedirectUrl;
    public $sRedirectPage;
    public $sProfilesModule = 'system';
    public $isAlwaysConfirmEmail = false;
    public $isAlwaysAutoApprove = false;

    public $sSessionKey;
    public $sSessionUid;
    public $sSessionProfile;

    public $sEmailTemplatePasswordGenerated;
    public $bSendPasswordGenerated = true;
    
    public $sDefaultTitleLangKey;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->sDefaultRedirectUrl = BX_DOL_URL_ROOT;
    }
}

/** @} */
