<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaConnect UNA Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxUnaConConfig extends BxBaseModConnectConfig
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

        $this -> sApiID = getParam('bx_unacon_api_key');
        $this -> sApiSecret = getParam('bx_unacon_secret');
        $this -> sApiUrl = trim(getParam('bx_unacon_url'), '/') . (getParam('bx_unacon_url_rewrite') ? '/m/oauth2/' : '/modules/?r=oauth2/');

        $this -> sSessionUid = 'unacon_session';
        $this -> sSessionProfile = 'unacon_session_profile';

        $this -> sEmailTemplatePasswordGenerated = 'bx_unacon_password_generated';
        $this -> sDefaultTitleLangKey = '_bx_unacon';

        $this -> sRedirectPage = getParam('bx_unacon_redirect_page');
        $this -> sProfilesModule = getParam('bx_unacon_module');
        $this -> isAlwaysConfirmEmail = (bool)getParam('bx_unacon_confirm_email'); 
        $this -> isAlwaysAutoApprove = (bool)getParam('bx_unacon_approve');

        $this -> bAutoFriends = 'on' == getParam('bx_unacon_auto_friends');

        $this -> sPageStart = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'start';
        $this -> sPageHandle = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'handle';
    }
}

/** @} */
