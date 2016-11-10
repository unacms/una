<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GoogleConnect Google Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGoogleConConfig extends BxBaseModConnectConfig
{
    public $sApiID;
    public $sApiSecret;
    public $sApiUrl = 'https://www.googleapis.com';
    public $sOauthUrl = 'https://accounts.google.com/o/oauth2/v2';

    public $sFields = 'id,firstName,lastName,picture-url,email-address';

    public $sPageStart;
    public $sPageHandle;

    public $sScope = 'email profile';

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this -> sApiID = getParam('bx_googlecon_api_key');
        $this -> sApiSecret = getParam('bx_googlecon_secret');

        $this -> sSessionUid = 'googlecon_session';
        $this -> sSessionProfile = 'googlecon_session_profile';

        $this -> sEmailTemplatePasswordGenerated = 'bx_googlecon_password_generated';
        $this -> sDefaultTitleLangKey = '_bx_googlecon';

        $this -> sRedirectPage = getParam('bx_googlecon_redirect_page');
        $this -> sProfilesModule = getParam('bx_googlecon_module');
        $this -> isAlwaysConfirmEmail = (bool)getParam('bx_googlecon_confirm_email'); 
        $this -> isAlwaysAutoApprove = (bool)getParam('bx_googlecon_approve');

        $this -> sPageStart = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'start';
        $this -> sPageHandle = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'handle';
    }
}

/** @} */
