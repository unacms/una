<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

use OAuth\ServiceFactory;
use OAuth\OAuth1\Service\Trident;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Storage\Session;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Consumer\Credentials;

require_once (BX_DIRECTORY_PATH_PLUGINS . 'OAuth/bootstrap.php');

class BxDolStudioOAuthPlugin extends BxDolStudioOAuth implements iBxDolSingleton
{
    protected $sService;
    protected $oStorage;

    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct ();

        $this->sKey = getParam('sys_oauth_key');
        $this->sSecret = getParam('sys_oauth_secret');
        $this->sDataRetrieveMethod = 'POST';

        $this->sService = 'Trident';
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
            $mixedSecret = $this->oSession->getValue('sys_oauth_secret');
            if(!$bToken && $mixedSecret !== false) {
                $this->oSession->unsetValue('sys_oauth_secret');
                $mixedSecret = false;
            }

            //--- Get request token and redirect to authorize.
            if(!$bToken && $mixedSecret === false)
            	return $this->getRequestToken($oService);

            //--- Get access token.
            if($bToken && $mixedSecret !== false)
            	return $this->getAccessToken(bx_get('oauth_token'), bx_get('oauth_verifier'), (int)bx_get('oauth_user'), $oService);
        }
        catch(TokenResponseException $e) {
        	$this->unsetAuthorizedUser();
            return $this->getRequestToken();
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

		$oToken = $oService->requestRequestToken();
		if(empty($oToken))
			return _t('_adm_err_oauth_cannot_get_token');

		$this->oSession->setValue('sys_oauth_secret', $oToken->getRequestTokenSecret());

		$oUrl = $oService->getAuthorizationUri(array('oauth_token' => $oToken->getRequestToken()));
		return _t('_adm_msg_oauth_need_authorize', bx_append_url_params($oUrl, array('sid' => bx_site_hash())));
    }

    protected function getAccessToken($sToken, $sVerifier, $iUser,  $oService)
    {
		$oToken = $this->oStorage->retrieveAccessToken($this->sService);
		$oAccessToken = $oService->requestAccessToken($sToken, $sVerifier, $oToken->getRequestTokenSecret());
		if(empty($oAccessToken))
			return _t('_adm_err_oauth_cannot_get_token');

		$this->oSession->setValue('sys_oauth_token', $oAccessToken->getAccessToken());
		$this->oSession->setValue('sys_oauth_secret', $oAccessToken->getAccessTokenSecret());
		$this->oSession->setValue('sys_oauth_authorized', 1);
		$this->oSession->setValue('sys_oauth_authorized_user', $iUser);

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
