<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioOAuthOAuth2 extends BxDolStudioOAuth implements iBxDolSingleton
{
	protected $sApiUrl;
	protected $sScope;
	protected $sPageHandle;

    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct ();

        $this->sKey = getParam('sys_oauth_key');
        $this->sSecret = getParam('sys_oauth_secret');
        $this->sApiUrl = BX_DOL_UNA_URL_ROOT . 'm/oauth2/';
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
	    if($bCode && $bState && $this->_getCsrfToken() != bx_get('state'))
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
			'state' => $this->_genCsrfToken(),
		));

		return _t('_adm_msg_oauth_need_authorize', $sUrl);
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

		$this->oSession->setValue('sys_oauth_token', $sAccessToken);
		$this->oSession->setValue('sys_oauth_authorized', 1);
		$this->oSession->setValue('sys_oauth_authorized_user', $aResponse['id']);

		return true;
    }

    protected function fetch($aParams = array())
    {
        if(!$this->isAuthorized())
            return array();

		$sResponse = bx_file_get_contents($this->sApiUrl . 'api/market', $aParams, 'get', array(
            'Authorization: Bearer ' . $this->oSession->getValue('sys_oauth_token'),
        ));

        //echo $sResponse; exit;		//--- Uncomment to debug
        if (!$sResponse || ($aResponse = json_decode($sResponse, true)) === NULL || !$aResponse || isset($aResponse['error'])) {
        	if($this->isReloginRequired($aResponse['error']))
        		$this->unsetAuthorizedUser();

			return isset($aResponse['error_description']) ? $aResponse['error_description'] : _t('_error occured');
        }

        return $aResponse['data'];
    }

    protected function isReloginRequired($sError)
    {
    	if(in_array($sError, array('expired_token')))
    		return true;

    	return false;
    }

	protected function _genCsrfToken($bReturn = false)
    {
        if (getParam('sys_security_form_token_enable') != 'on' || defined('BX_DOL_CRON_EXECUTE'))
            return false;

        $oSession = BxDolSession::getInstance();

        $iCsrfTokenLifetime = (int)getParam('sys_security_form_token_lifetime');
        if ($oSession->getValue('bx_studio_store_csrf_token') === false || ($iCsrfTokenLifetime != 0 && time() - (int)$oSession->getValue('bx_studio_store_csrf_token_time') > $iCsrfTokenLifetime)) {
            $sToken = genRndPwd(20, false);
            $oSession->setValue('bx_studio_store_csrf_token', $sToken);
            $oSession->setValue('bx_studio_store_csrf_token_time', time());
        }
        else {
            $sToken = $oSession->getValue('bx_studio_store_csrf_token');
        }

        return $sToken;
    }

    protected function _getCsrfToken()
    {
        $oSession = BxDolSession::getInstance();
        return $oSession->getValue('bx_studio_store_csrf_token');
    }
}

/** @} */
