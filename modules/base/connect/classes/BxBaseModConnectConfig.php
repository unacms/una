<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     TridentModules
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
    public $sDefaultTitleLangKey;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->sDefaultRedirectUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=dashboard');
    }
}

/** @} */
