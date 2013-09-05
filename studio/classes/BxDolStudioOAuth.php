<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

class BxDolStudioOAuth extends BxDol implements iBxDolSingleton {
	protected $sKey;
	protected $sSecret;

	protected $sErrorCode;
	protected $sErrorMessage;

	protected $oSession;

    public function __construct() {
    	if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct ();

		$this->sKey = getParam('sys_oauth_key');
		$this->sSecret = getParam('sys_oauth_secret');

    	$this->sErrorCode = 'oauth_err_code';
		$this->sErrorMessage = 'oauth_err_message';

		bx_import('BxDolSession');
		$this->oSession = BxDolSession::getInstance();
    }

	public function __clone() {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

	static function getInstance() {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
        	$sClass = __CLASS__;
            $GLOBALS['bxDolClasses'][__CLASS__] = new $sClass();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

	static function isAuthorizedClient() {
		$sClass = __CLASS__;
    	return $sClass::getInstance()->isAuthorized();
    }

    static function getAuthorizedClient() {
    	$sClass = __CLASS__;
    	return $sClass::getInstance()->getAuthorizedUser();
    }

	public function loadItems($aParams = array()) {
        if(empty($this->sKey) || empty($this->sSecret)) 
        	return _t('_adm_err_oauth_empty_key_secret');

		$mixedResult = $this->authorize($this->sKey, $this->sSecret);
		if($mixedResult !== true)
			return $mixedResult;

		$aItems = $this->fetch($this->sKey, $this->sSecret, $aParams);
		if(is_null($aItems))
			return _t('_adm_err_oauth_cannot_read_answer');
		else if(empty($aItems))
			return MsgBox(_t('_Empty'));

		if($this->isServerError($aItems))
			return $this->processServerError($aItems);

		return $aItems;
    }

    protected function isAuthorized() {
		return (int)$this->oSession->getValue('sys_oauth_authorized') == 1;
    }

	protected function getAuthorizedUser() {
		if(!$this->isAuthorized())
			return 0;

		return (int)$this->oSession->getValue('sys_oauth_authorized_user');
    }

    protected function authorize($sKey, $sSecret) {
    	if($this->isAuthorized())
			return true;

		try {
			$oConsumer = new OAuth($sKey, $sSecret);
			$oConsumer->setAuthType(OAUTH_AUTH_TYPE_URI);
			$oConsumer->enableDebug();

			$bToken = bx_get('oauth_token') !== false;
			$mixedSecret = $this->oSession->getValue('sys_oauth_secret');
			if(!$bToken && $mixedSecret !== false) {
				$this->oSession->unsetValue('sys_oauth_secret');
				$mixedSecret = false;
			}

			//--- Get request token and redirect to authorize. 
			if(!$bToken && $mixedSecret === false) {
			    $aRequestToken = $oConsumer->getRequestToken(BX_DOL_OAUTH_URL_REQUEST_TOKEN);			    
			    if(empty($aRequestToken))
			    	return _t('_adm_err_oauth_cannot_get_token');

			    if($this->isServerError($aRequestToken))
			    	return $this->processServerError($aRequestToken);

			    $this->oSession->setValue('sys_oauth_secret', $aRequestToken['oauth_token_secret']);
			    return _t('_adm_msg_oauth_need_authorize', bx_append_url_params(BX_DOL_OAUTH_URL_AUTHORIZE, array('oauth_token' => $aRequestToken['oauth_token'], 'sid' => generateSid())));
			}

			//--- Get access token. 
			if($bToken && $mixedSecret !== false) {
			    $oConsumer->setToken(bx_get('oauth_token'), $mixedSecret);
			    $aAccessToken = $oConsumer->getAccessToken(bx_append_url_params(BX_DOL_OAUTH_URL_ACCESS_TOKEN, array('oauth_verifier' => bx_get('oauth_verifier'))));
			    if(empty($aAccessToken))
			    	return _t('_adm_err_oauth_cannot_get_token');

				if($this->isServerError($aAccessToken))
			    	return $this->processServerError($aAccessToken);

			    $this->oSession->setValue('sys_oauth_token', $aAccessToken['oauth_token']);
			    $this->oSession->setValue('sys_oauth_secret', $aAccessToken['oauth_token_secret']);
			    $this->oSession->setValue('sys_oauth_authorized', 1);
			    $this->oSession->setValue('sys_oauth_authorized_user', (int)bx_get('oauth_user'));

			    return true;
			}
		}
		catch(OAuthException $e) {
			//TODO: Write in LOG print_r($e)
			return _t('_adm_err_oauth_cannot_get_token');
		}
    }

    protected function fetch($sKey, $sSecret, $aParams = array()) {
		if(!$this->isAuthorized())
			return array();

		try {
			$oConsumer = new OAuth($sKey, $sSecret);
			$oConsumer->setAuthType(OAUTH_AUTH_TYPE_URI);
			$oConsumer->enableDebug();

			$oConsumer->setToken($this->oSession->getValue('sys_oauth_token'), $this->oSession->getValue('sys_oauth_secret'));
			$oConsumer->fetch(BX_DOL_OAUTH_URL_FETCH_DATA, $aParams, OAUTH_HTTP_METHOD_POST);

			//--- Uncomment to debug
			//echo $oConsumer->getLastResponse(); exit;
			return json_decode($oConsumer->getLastResponse(), true);
		}
    	catch(OAuthException $e) {
    		//TODO: Write in LOG print_r($e);
			return array();
		}
    }

    protected function isServerError($aResult) {
    	return isset($aResult[$this->sErrorCode]) && isset($aResult[$this->sErrorMessage]);
    }

    protected function processServerError($aResult) {
    	$iCode = $aResult[$this->sErrorCode];
    	$sMessage = $aResult[$this->sErrorMessage];

    	switch($iCode) {
    		case '8':
    		case '16':
    		case '32':
    		case '64':
    		case '256':
    		case '1024':
    		case '2048':
    			bx_import('BxDolSession');
    			$this->oSession = BxDolSession::getInstance();
    			$this->oSession->unsetValue('sys_oauth_token');
			    $this->oSession->unsetValue('sys_oauth_secret');
			    $this->oSession->unsetValue('sys_oauth_authorized');
			    $this->oSession->unsetValue('sys_oauth_authorized_user');
			    break;
    	}

    	return $sMessage;
    }
}

/** @} */
