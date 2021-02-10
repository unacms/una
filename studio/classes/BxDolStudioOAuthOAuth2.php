<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioOAuthOAuth2 extends BxDolStudioOAuth implements iBxDolSingleton
{
    protected $sSessionKeyStateToken;
    protected $sSessionKeyCsrfToken;
    protected $sSessionKeyCsrfTokenTime;

    protected $sApiUrl;
    protected $sScope;
    protected $sPageHandle;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct ();

        $this->sSessionKeyStateToken = 'bx_studio_store_state_token';
        $this->sSessionKeyCsrfToken = 'bx_studio_store_csrf_token';
        $this->sSessionKeyCsrfTokenTime = 'bx_studio_store_csrf_token_time';

        $this->sKey = $this->_getCredential('key');
        $this->sSecret = $this->_getCredential('secret');
        $this->sApiUrl = BX_DOL_MARKET_URL_ROOT . 'm/oauth2/';
        $this->sScope = 'market';
        $this->sPageHandle = BX_DOL_URL_STUDIO . 'store.php?page=goodies';
        $this->sDataRetrieveMethod = 'post';
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance()
    {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $sClass = __CLASS__;
            $GLOBALS['bxDolClasses'][__CLASS__] = new $sClass();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    protected function authorize()
    {
		if($this->isAuthorized())
			return true;

		$bCode = bx_get('code') !== false;
		$bState = bx_get('state') !== false;
	    if($bCode && $bState && $this->_getState() != bx_get('state'))
            return _t('_adm_err_oauth_cannot_read_answer');

		//--- Get access token.
		if($bCode)
			return $this->getAccessToken(bx_get('code'));

		//--- Get request token and redirect to authorize.
        return $this->getRequestToken();
    }

    protected function getRequestToken()
    {
    	$sUrl = bx_append_url_params($this->sApiUrl . 'auth', array(
			'response_type' => 'code',
			'client_id' => $this->sKey,
			'redirect_uri' => $this->sPageHandle,
			'scope' => $this->sScope,
			'state' => $this->_genState(),
		));

		return array(
		    'redirect' => $sUrl, 
		    'message' => _t('_adm_msg_oauth_need_authorize', $sUrl)
		);
    }

    protected function getAccessToken($sCode)
    {
    	$sResponse = bx_file_get_contents($this->sApiUrl . 'token', array(
            'client_id' => $this->sKey,
            'client_secret' => $this->sSecret,
            'grant_type'    => 'authorization_code',
            'code' => $sCode,
            'redirect_uri'  => $this->sPageHandle,
            'scope' => $this->sScope,
        ), $this->sDataRetrieveMethod);

        if (!$sResponse || ($aResponse = json_decode($sResponse, true)) === NULL || !isset($aResponse['access_token']) || isset($aResponse['error']))
            return isset($aResponse['error_description']) ? $aResponse['error_description'] : _t('_error occured');

        // get access_token 
        $sAccessToken = $aResponse['access_token'];

        // request info about profile
        $sResponse = bx_file_get_contents($this->sApiUrl . 'api/me', array(), 'get', array(
            'Authorization: Bearer ' . $sAccessToken,
        ));

        // handle error
        if (!$sResponse || ($aResponse = json_decode($sResponse, true)) === NULL || !$aResponse || isset($aResponse['error']))
            return isset($aResponse['error_description']) ? $aResponse['error_description'] : _t('_error occured');

        $this->oSession->setValue(self::$sSessionKeyToken, $sAccessToken);
        $this->oSession->setValue(self::$sSessionKeyAuthorized, 1);
        $this->oSession->setValue(self::$sSessionKeyAuthorizedUser, (int)$aResponse['id']);
        $this->oSession->setValue(self::$sSessionKeyAuthorizedOwner, (bool)$aResponse['owner'] ? 1 : 0);

        return true;
    }

    protected function fetch($aParams = array())
    {
        if(!$this->isAuthorized())
            return array();

        $iTimeout = 120;
        if(ini_get('safe_mode') == 0) {
            ini_set('max_execution_time', $iTimeout);
            ini_set('memory_limit', '512M');
        }

        $sHttpCode = null;
        $sResponse = bx_file_get_contents($this->sApiUrl . 'api/market', $aParams, 'get', array(
            'Authorization: Bearer ' . $this->oSession->getValue(self::$sSessionKeyToken),
        ), $sHttpCode, array(), $iTimeout);

        //echo $sResponse; exit;		//--- Uncomment to debug
        if (!$sResponse || ($aResponse = json_decode($sResponse, true)) === NULL || !$aResponse || isset($aResponse['error'])) {
        	if(isset($aResponse['error']) && $this->isReloginRequired($aResponse['error']))
        		$this->unsetAuthorizedUser();

			return isset($aResponse['error_description']) ? $aResponse['error_description'] : _t('_error occured');
        }

        return $aResponse['data'];
    }

    protected function isReloginRequired($sError)
    {
    	if(in_array($sError, array('invalid_token', 'expired_token')))
    		return true;

    	return false;
    }

    protected function _getCredential($sName)
    {
        $sResult = getParam('sys_oauth_' . $sName);
        return $sResult !== false ? trim($sResult) : '';
    }

    protected function _genState()
    {
        $sResult = $this->_genCsrfToken();
        if($sResult !== false)
            return $sResult;

        return $this->_genStateToken();
    }

    protected function _getState()
    {
        $sResult = $this->_getCsrfToken();
        if($sResult !== false)
            return $sResult;

        return $this->_getStateToken();
    }

    protected function _genCsrfToken()
    {
        if(getParam('sys_security_form_token_enable') != 'on' || defined('BX_DOL_CRON_EXECUTE'))
            return false;

        $oSession = BxDolSession::getInstance();

        $iCsrfTokenLifetime = (int)getParam('sys_security_form_token_lifetime');
        $sToken = $oSession->getValue($this->sSessionKeyCsrfToken);
        if($sToken !== false && ($iCsrfTokenLifetime == 0 || time() - (int)$oSession->getValue($this->sSessionKeyCsrfTokenTime) < $iCsrfTokenLifetime))
            return $sToken;

        $sToken = genRndPwd(20, false);
        $oSession->setValue($this->sSessionKeyCsrfToken, $sToken);
        $oSession->setValue($this->sSessionKeyCsrfTokenTime, time());

        return $sToken;
    }

    protected function _getCsrfToken()
    {
        if(getParam('sys_security_form_token_enable') != 'on' || defined('BX_DOL_CRON_EXECUTE'))
            return false;

        return BxDolSession::getInstance()->getValue($this->sSessionKeyCsrfToken);
    }

    protected function _genStateToken()
    {
        $oSession = BxDolSession::getInstance();

        $sToken = $oSession->getValue($this->sSessionKeyStateToken);
        if($sToken !== false)
            return $sToken;

        $sToken = genRndPwd(20, false);
        $oSession->setValue($this->sSessionKeyStateToken, $sToken);

        return $sToken;
    }

    protected function _getStateToken()
    {
        return BxDolSession::getInstance()->getValue($this->sSessionKeyStateToken);
    }
}

/** @} */
