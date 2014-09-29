<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolSession');
bx_import('BxDolStudioInstallerUtils');

define('BX_DOL_OAUTH_URL_BASE', BX_DOL_UNITY_URL_ROOT . 'scripts_public/');
define('BX_DOL_OAUTH_URL_REQUEST_TOKEN', BX_DOL_OAUTH_URL_BASE . 'oauth_request_token.php5');
define('BX_DOL_OAUTH_URL_AUTHORIZE', BX_DOL_OAUTH_URL_BASE . 'oauth_authorize.php5');
define('BX_DOL_OAUTH_URL_ACCESS_TOKEN', BX_DOL_OAUTH_URL_BASE . 'oauth_access_token.php5');
define('BX_DOL_OAUTH_URL_FETCH_DATA', BX_DOL_OAUTH_URL_BASE . 'oauth_fetch_data.php5');

class BxDolStudioOAuth extends BxDol
{
    protected $oSession;

	protected $sErrorCode;
    protected $sErrorMessage;

	protected $sKey;
    protected $sSecret;
    protected $sDataRetrieveMethod;

    public function __construct()
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

    protected function isAuthorized()
    {
        return self::isAuthorizedClient();
    }

    protected function getAuthorizedUser()
    {
    	return self::getAuthorizedClient();
    }

    protected function isServerError($aResult)
    {
        return isset($aResult[$this->sErrorCode]) && isset($aResult[$this->sErrorMessage]);
    }

    protected function processServerError($aResult)
    {
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
