<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

use OAuth\ServiceFactory;
use OAuth\OAuth1\Service\Una;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Storage\Session;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Consumer\Credentials;

class BxDolStudioOAuthPlugin extends BxDolStudioOAuthOAuth1 implements iBxDolSingleton
{
    protected $sService;
    protected $oStorage;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct ();

        $this->sKey = getParam('sys_oauth_key');
        $this->sSecret = getParam('sys_oauth_secret');
        $this->sDataRetrieveMethod = 'POST';

        $this->sService = 'Una';
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

		$oService = $this->getServiceObject();

        try {
            $bToken = bx_get('oauth_token') !== false;
            $mixedSecret = $this->oSession->getValue(self::$sSessionKeySecret);
            if(!$bToken && $mixedSecret !== false) {
                $this->oSession->unsetValue(self::$sSessionKeySecret);
                $mixedSecret = false;
            }

            //--- Get request token and redirect to authorize.
            if(!$bToken && $mixedSecret === false)
            	return $this->getRequestToken($oService);

            //--- Get access token.
            if($bToken && $mixedSecret !== false)
            	return $this->getAccessToken(bx_get('oauth_token'), bx_get('oauth_verifier'), (int)bx_get('oauth_user'), $oService);
        }
        catch(TokenNotFoundException $e) {
        	$this->unsetAuthorizedUser();
            return $this->getRequestToken();
        }

        return $this->getRequestToken();
    }

    protected function getRequestToken($oService = null)
    {
    	if(empty($oService))
    		$oService = $this->getServiceObject();

    	$sError = _t('_adm_err_oauth_cannot_get_token');
    	try {
			$oToken = $oService->requestRequestToken();
    	}
    	catch (TokenResponseException $e) {
    		if(!$this->isAuthorized())
    			return $sError;

			$this->unsetAuthorizedUser();
            return $this->getRequestToken($oService);
    	}

		if(empty($oToken))
			return $sError;

		$this->oSession->setValue(self::$sSessionKeySecret, $oToken->getRequestTokenSecret());

		$oUrl = $oService->getAuthorizationUri(array('oauth_token' => $oToken->getRequestToken()));
		$sUrl = bx_append_url_params($oUrl, array('sid' => bx_site_hash()));

		return array(
		    'redirect' => $sUrl, 
		    'message' => _t('_adm_msg_oauth_need_authorize', $sUrl)
		);
    }

    protected function getAccessToken($sToken, $sVerifier, $iUser,  $oService)
    {
		$oToken = $this->oStorage->retrieveAccessToken($this->sService);

		$sError = _t('_adm_err_oauth_cannot_get_token');
    	try {
			$oAccessToken = $oService->requestAccessToken($sToken, $sVerifier, $oToken->getRequestTokenSecret());
    	}
    	catch (TokenResponseException $e) {
    		$sErrorException = $e->getMessage();
    		if(!empty($sErrorException))
    			$sError = $sErrorException;

    		if(!$this->isAuthorized())
    			return $sError;

			$this->unsetAuthorizedUser();
            return $this->getRequestToken($oService);
    	}

		if(empty($oAccessToken))
			return $sError;

		$this->oSession->setValue(self::$sSessionKeyToken, $oAccessToken->getAccessToken());
		$this->oSession->setValue(self::$sSessionKeySecret, $oAccessToken->getAccessTokenSecret());
		$this->oSession->setValue(self::$sSessionKeyAuthorized, 1);
		$this->oSession->setValue(self::$sSessionKeyAuthorizedUser, $iUser);

		return true;
    }

    protected function fetch($aParams = array())
    {
        if(!$this->isAuthorized())
            return array();

        try {
			$sResponse = $this->getServiceObject()->request(BX_DOL_OAUTH_URL_FETCH_DATA, $this->sDataRetrieveMethod, $aParams);

            //echo $sResponse; exit;	//--- Uncomment to debug
            return json_decode($sResponse, true);
        }
    	catch(TokenNotFoundException $e) {
        	$this->unsetAuthorizedUser();
            return $this->getRequestToken();
        }
    }

    private function getServiceObject()
    {
	 	$this->oStorage = new Session();

    	$oUrl = new Uri(BX_DOL_URL_STUDIO . 'store.php?page=purchases');
		$oCredentials = new Credentials($this->sKey, $this->sSecret, $oUrl->getAbsoluteUri());

    	$oServiceFactory = new ServiceFactory();
		return $oServiceFactory->createService($this->sService, $oCredentials, $this->oStorage);
    }
}

/** @} */
