<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioOAuthLib extends BxDolStudioOAuthOAuth1 implements iBxDolSingleton
{
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->sKey = getParam('sys_oauth_key');
        $this->sSecret = getParam('sys_oauth_secret');
        $this->sDataRetrieveMethod = OAUTH_HTTP_METHOD_POST;
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

		$oConsumer = $this->getServiceObject(false);

        try {
            $bToken = bx_get('oauth_token') !== false;
            $mixedSecret = $this->oSession->getValue(self::$sSessionKeySecret);
            if(!$bToken && $mixedSecret !== false) {
                $this->oSession->unsetValue(self::$sSessionKeySecret);
                $mixedSecret = false;
            }

            //--- Get request token and redirect to authorize.
            if(!$bToken && $mixedSecret === false)
                return $this->getRequestToken($oConsumer);

            //--- Get access token.
            if($bToken && $mixedSecret !== false)
                return $this->getAccessToken(bx_get('oauth_token'), $mixedSecret, bx_get('oauth_verifier'), (int)bx_get('oauth_user'),  $oConsumer);
        }
        catch(OAuthException $e) {
            return $this->getRequestToken();
        }

        return $this->getRequestToken();
    }

    protected function getRequestToken($oConsumer = null)
    {
    	if(empty($oConsumer))
    		$oConsumer = $this->getServiceObject();

    	$aRequestToken = $oConsumer->getRequestToken(BX_DOL_OAUTH_URL_REQUEST_TOKEN);
		if(empty($aRequestToken))
        	return _t('_adm_err_oauth_cannot_get_token');

		if($this->isServerError($aRequestToken))
			throw new OAuthException();

		$this->oSession->setValue(self::$sSessionKeySecret, $aRequestToken['oauth_token_secret']);

		$sUrl = bx_append_url_params(BX_DOL_OAUTH_URL_AUTHORIZE, array('oauth_token' => $aRequestToken['oauth_token'], 'sid' => bx_site_hash()));
		return array(
		    'redirect' => $sUrl, 
		    'message' => _t('_adm_msg_oauth_need_authorize', $sRedirect)
		);
    }

    protected function getAccessToken($sToken, $mixedSecret, $sVerifier, $iUser,  $oConsumer)
    {
        $oConsumer->setToken($sToken, $mixedSecret);
        $aAccessToken = $oConsumer->getAccessToken(bx_append_url_params(BX_DOL_OAUTH_URL_ACCESS_TOKEN, array('oauth_verifier' => $sVerifier)));
        if(empty($aAccessToken))
            return _t('_adm_err_oauth_cannot_get_token');

        if($this->isServerError($aAccessToken))
            throw new OAuthException();

        $this->oSession->setValue(self::$sSessionKeyToken, $aAccessToken['oauth_token']);
        $this->oSession->setValue(self::$sSessionKeySecret, $aAccessToken['oauth_token_secret']);
        $this->oSession->setValue(self::$sSessionKeyAuthorized, 1);
        $this->oSession->setValue(self::$sSessionKeyAuthorizedUser, $iUser);

        return true;
    }
    
    protected function fetch($aParams = array())
    {
        if(!$this->isAuthorized())
            return array();

		$oConsumer = $this->getServiceObject(false);

        try {
            $oConsumer->setToken($this->oSession->getValue(self::$sSessionKeyToken), $this->oSession->getValue(self::$sSessionKeySecret));
            $oConsumer->fetch(BX_DOL_OAUTH_URL_FETCH_DATA, $aParams, $this->sDataRetrieveMethod);

            //echo $oConsumer->getLastResponse(); exit;	//--- Uncomment to debug
            return json_decode($oConsumer->getLastResponse(), true);
        } 
        catch(OAuthException $e) {
            return array();
        }
    }

	private function getServiceObject($bClearStorage = true)
    {
    	if($bClearStorage)
    		$this->unsetAuthorizedUser();

    	$oConsumer = new OAuth($this->sKey, $this->sSecret);
		$oConsumer->setAuthType(OAUTH_AUTH_TYPE_URI);
        $oConsumer->enableDebug();

        return $oConsumer;
    }
}

/** @} */
