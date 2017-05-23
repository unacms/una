<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaConnect Dolphin Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDolConConfig extends BxBaseModConnectConfig
{
    public $sApiID;
    public $sApiSecret;
    public $sApiUrl;

    public $sPageStart;
    public $sPageHandle;

    public $sScope = 'basic';

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this -> sApiID = getParam('bx_dolcon_api_key');
        $this -> sApiSecret = getParam('bx_dolcon_secret');
        $this -> sApiUrl = trim(getParam('bx_dolcon_url'), '/') . (getParam('bx_dolcon_url_rewrite') ? '/m/oauth2/' : '/modules/?r=oauth2/');

        $this -> sSessionUid = 'dolcon_session';
        $this -> sSessionProfile = 'dolcon_session_profile';

        $this -> sEmailTemplatePasswordGenerated = 'bx_dolcon_password_generated';
        $this -> sDefaultTitleLangKey = '_bx_dolcon';

        $this -> sRedirectPage = getParam('bx_dolcon_redirect_page');
        $this -> sProfilesModule = getParam('bx_dolcon_module');
        $this -> isAlwaysConfirmEmail = (bool)getParam('bx_dolcon_confirm_email'); 
        $this -> isAlwaysAutoApprove = (bool)getParam('bx_dolcon_approve');

        $this -> bAutoFriends = 'on' == getParam('bx_dolcon_auto_friends');

        $this -> sPageStart = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'start';
        $this -> sPageHandle = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'handle';
    }
}

/** @} */
