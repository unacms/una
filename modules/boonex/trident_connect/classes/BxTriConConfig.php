<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentConnect Trident Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxTriConConfig extends BxBaseModConnectConfig
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

        $this -> sApiID = getParam('bx_tricon_api_key');
        $this -> sApiSecret = getParam('bx_tricon_secret');
        $this -> sApiUrl = trim(getParam('bx_tricon_url'), '/') . (getParam('bx_tricon_url_rewrite') ? '/m/oauth2/' : '/modules/?r=oauth2/');

        $this -> sSessionUid = 'tricon_session';
        $this -> sSessionProfile = 'tricon_session_profile';

        $this -> sEmailTemplatePasswordGenerated = 'bx_tricon_password_generated';
        $this -> sDefaultTitleLangKey = '_bx_tricon';

        $this -> sRedirectPage = getParam('bx_tricon_redirect_page');
        $this -> sProfilesModule = getParam('bx_tricon_module');
        $this -> isAlwaysConfirmEmail = (bool)getParam('bx_tricon_confirm_email'); 
        $this -> isAlwaysAutoApprove = (bool)getParam('bx_tricon_approve');

        $this -> bAutoFriends = 'on' == getParam('bx_tricon_auto_friends');

        $this -> sPageStart = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'start';
        $this -> sPageHandle = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'handle';
    }
}

/** @} */
