<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */


define('BX_DOL_OAUTH_URL_BASE', BX_DOL_MARKET_URL_ROOT . 'scripts_public/');
define('BX_DOL_OAUTH_URL_REQUEST_TOKEN', BX_DOL_OAUTH_URL_BASE . 'oauth_request_token.php5');
define('BX_DOL_OAUTH_URL_AUTHORIZE', BX_DOL_OAUTH_URL_BASE . 'oauth_authorize.php5');
define('BX_DOL_OAUTH_URL_ACCESS_TOKEN', BX_DOL_OAUTH_URL_BASE . 'oauth_access_token.php5');
define('BX_DOL_OAUTH_URL_FETCH_DATA', BX_DOL_OAUTH_URL_BASE . 'oauth_fetch_data.php5');

class BxDolStudioOAuthOAuth1 extends BxDolStudioOAuth
{
    protected function __construct()
    {
    	parent::__construct();
    }

    protected function unsetAuthorizedUser()
    {
        $this->oSession->unsetValue(self::$sSessionKeySecret);

        parent::unsetAuthorizedUser();
    }
}

/** @} */
