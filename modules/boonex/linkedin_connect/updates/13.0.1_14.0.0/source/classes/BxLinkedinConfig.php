<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    LinkedInConnect LinkedIn Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxLinkedinConfig extends BxBaseModConnectConfig
{
    public $sApiID;
    public $sApiSecret;
    public $sApiUrl = 'https://api.linkedin.com/v2';
    public $sOauthUrl = 'https://www.linkedin.com/oauth/v2';

    public $sFields = 'id,firstName,lastName,profilePicture(displayImage~:playableStreams)';

    public $sPageStart;
    public $sPageHandle;

    public $sScope = 'openid email profile';

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this -> sApiID = getParam('bx_linkedin_api_key');
        $this -> sApiSecret = getParam('bx_linkedin_secret');

        $this -> sSessionUid = 'linkedin_session';
        $this -> sSessionProfile = 'linkedin_session_profile';

        $this -> sEmailTemplatePasswordGenerated = 'bx_linkedin_password_generated';
        $this -> sDefaultTitleLangKey = '_bx_linkedin';

        $this -> sRedirectPage = getParam('bx_linkedin_redirect_page');
        $this -> sProfilesModule = getParam('bx_linkedin_module');
        $this -> isAlwaysConfirmEmail = (bool)getParam('bx_linkedin_confirm_email'); 
        $this -> isAlwaysAutoApprove = (bool)getParam('bx_linkedin_approve');

        $this -> sPageStart = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'start';
        $this -> sPageHandle = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'handle';
    }
}

/** @} */
