<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    TwitterConnect Twitter Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTwitterConfig extends BxBaseModConnectConfig
{
    public $sApiID;
    public $sApiSecret;
    public $sApiUrl = 'https://api.twitter.com/v1';
    public $sOauthUrl = 'https://www.twitter.com/oauth/v2';

    public $sFields = 'id,firstName,lastName,picture-url,email-address';

    public $sPageStart;
    public $sPageHandle;

    public $sScope = 'r_basicprofile r_emailaddress';

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this -> sApiID = getParam('bx_twitter_api_key');
        $this -> sApiSecret = getParam('bx_twitter_secret');

        $this -> sSessionUid = 'twitter_session';
        $this -> sSessionProfile = 'twitter_session_profile';

        $this -> sEmailTemplatePasswordGenerated = 'bx_twitter_password_generated';
        $this -> sDefaultTitleLangKey = '_bx_twitter';

        $this -> sRedirectPage = getParam('bx_twitter_redirect_page');
        $this -> sProfilesModule = getParam('bx_twitter_module');
        $this -> isAlwaysConfirmEmail = (bool)getParam('bx_twitter_confirm_email'); 
        $this -> isAlwaysAutoApprove = (bool)getParam('bx_twitter_approve');

        $this -> sPageStart = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'start';
        $this -> sPageHandle = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'handle';
    }
}

/** @} */
