<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

bx_import('BxDolStudioInstallerUtils');

class BxDolStudioOAuth extends BxDolFactory
{
    protected $oSession;

	protected $sErrorCode;
    protected $sErrorMessage;

	protected $sKey;
    protected $sSecret;
    protected $sDataRetrieveMethod;

    protected function __construct()
    {
        parent::__construct ();

        $this->oSession = BxDolSession::getInstance();

        $this->sErrorCode = 'oauth_err_code';
        $this->sErrorMessage = 'oauth_err_message';
    }

    static function isAuthorizedClient()
    {
        return (int)BxDolSession::getInstance()->getValue('sys_oauth_authorized') == 1;
    }

    static function getAuthorizedClient()
    {
        return (int)BxDolSession::getInstance()->getValue('sys_oauth_authorized_user');
    }

    public function loadItems($aParams = array())
    {
        if(empty($this->sKey) || empty($this->sSecret))
            return _t('_adm_err_oauth_empty_key_secret');

        $mixedResult = $this->authorize();
        if($mixedResult !== true)
            return $this->onAuthorizeFailed($mixedResult);

        $aItems = $this->fetch($aParams);
        if(is_null($aItems))
            return _t('_adm_err_oauth_cannot_read_answer');
        else if(empty($aItems))
            return _t('_Empty');

        return $aItems;
    }

    public function doAuthorize()
    {
    	if(empty($this->sKey) || empty($this->sSecret))
            return _t('_adm_err_oauth_empty_key_secret');

		$mixedResult = $this->authorize();
		if($mixedResult !== true)
		    return $this->onAuthorizeFailed($mixedResult);

        BxDolStudioInstallerUtils::getInstance()->checkModules(true);
        return $mixedResult;
    }

    protected function onAuthorizeFailed($mixedResult)
    {
        if(is_string($mixedResult))
            return MsgBox($mixedResult);

        $bArray = is_array($mixedResult);
        if($bArray && !empty($mixedResult['redirect'])) {
            header('Location: ' . $mixedResult['redirect']);
            exit;
        }

        if($bArray && !empty($mixedResult['message']))
            return MsgBox($mixedResult['message']);
    }

    protected function isAuthorized()
    {
        return self::isAuthorizedClient();
    }

    protected function getAuthorizedUser()
    {
    	return self::getAuthorizedClient();
    }

	protected function unsetAuthorizedUser()
	{
		$this->oSession->unsetValue('sys_oauth_token');
        $this->oSession->unsetValue('sys_oauth_authorized');
		$this->oSession->unsetValue('sys_oauth_authorized_user');
	}

    protected function isServerError($aResult)
    {
        return isset($aResult[$this->sErrorCode]) && isset($aResult[$this->sErrorMessage]);
    }
}

/** @} */
