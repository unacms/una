<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Xero Xero
 * @ingroup     UnaModules
 *
 * @{
 */

class BxXeroConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    protected $_oSession;
    protected $_sSessionKey;

    protected $_bLog;
    protected $_oLog;

    protected $_sClientId;
    protected $_sClientSecret;
    protected $_sRedirectUrl;
    protected $_sAuthorizeUrl;

    protected $_sWebhookKey;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oSession = BxDolSession::getInstance();
        $this->_sSessionKey = 'bx_xero_session';

        $this->_bLog = true;
        if($this->_bLog) {
            $this->_oLog = BxDolLog::getInstance();
            $this->_oLog->setName('xero');
        }

        $this->_sAuthorizeUrl = BX_DOL_URL_STUDIO . bx_append_url_params('module.php', ['name' => $this->getName(), 'page' => 'authorize']);

        $this->CNF = array (
            // database tables
            'TABLE_CONTACTS' => $aModule['db_prefix'] . 'contacts',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_EMAIL' => 'email',
            'FIELD_CONTACT' => 'contact',

            // some params
            'PARAM_CLIENT_ID' => 'bx_xero_client_id',
            'PARAM_CLIENT_SECRET' => 'bx_xero_client_secret',
            'PARAM_REDIRECT_URL' => 'bx_xero_redirect_url',
            'PARAM_WEBHOOK_KEY' => 'bx_xero_webhook_key',
            'PARAM_INVOICE_SEND' => 'bx_xero_invoice_send',
        );

        $this->_aJsClasses = array(
            'main' => 'BxXeroMain',
        );

        $this->_aJsObjects = array(
            'main' => 'oBxXeroMain',
        );
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        //NOTE: Some settings can be inited here.
    }

    public function getObjectLog()
    {
        if(!$this->_bLog)
            return false;

        return $this->_oLog;
    }

    public function getClientId()
    {
        if(empty($this->_sClientId))
            $this->_sClientId = $this->_oDb->getParam($this->CNF['PARAM_CLIENT_ID']);

        return $this->_sClientId;
    }

    public function getClientSecret()
    {
        if(empty($this->_sClientSecret))
            $this->_sClientSecret = $this->_oDb->getParam($this->CNF['PARAM_CLIENT_SECRET']);

        return $this->_sClientSecret;
    }

    public function getRedirectUrl()
    {
        if(empty($this->_sRedirectUrl))
            $this->_sRedirectUrl = $this->_oDb->getParam($this->CNF['PARAM_REDIRECT_URL']);

        return $this->_sRedirectUrl;
    }

    public function getAuthorizeUrl($iCode = false)
    {
        return $iCode !== false ? bx_append_url_params($this->_sAuthorizeUrl, ['code' => $iCode]) : $this->_sAuthorizeUrl;
    }

    public function getWebhookKey()
    {
        if(empty($this->_sWebhookKey))
            $this->_sWebhookKey = $this->_oDb->getParam($this->CNF['PARAM_WEBHOOK_KEY']);

        return $this->_sWebhookKey;
    }

    public function cleanSession()
    {
        $aData = $this->_oSession->getValue($this->_sSessionKey);
        if(empty($aData) || !is_array($aData))
            return;

        $this->_oSession->unsetValue($this->_sSessionKey);
    }

    public function setState($sState)
    {
        $aData = $this->_oSession->getValue($this->_sSessionKey);
        if(empty($aData) || !is_array($aData))
            $aData = [];

        $aData['state'] = $sState;

        return $this->_oSession->setValue($this->_sSessionKey, $aData);
    }

    public function unsetState()
    {
        $aData = $this->_oSession->getValue($this->_sSessionKey);
        if(isset($aData['state']))
            unset($aData['state']);

        return $this->_oSession->setValue($this->_sSessionKey, $aData);
    }

    public function getState()
    {
        $aData = $this->_oSession->getValue($this->_sSessionKey);

        return isset($aData['state']) ? $aData['state'] : false;
    }

    public function setData($sAccessToken, $iAccessTokenExpires, $sTenantId, $sRefreshToken, $sIdToken)
    {
        $aData = $this->_oSession->getValue($this->_sSessionKey);
        if(empty($aData) || !is_array($aData))
            $aData = [];

        $aData['data'] = [
            'token' => $sAccessToken,
            'expires' => $iAccessTokenExpires,
            'tenant_id' => $sTenantId,
            'refresh_token' => $sRefreshToken,
            'id_token' => $sIdToken
        ];

        $this->_oDb->setData($aData['data']);

        return $this->_oSession->setValue($this->_sSessionKey, $aData);
    }

    public function getData($bCheckLifetime = false)
    {
        $aData = $this->_oSession->getValue($this->_sSessionKey);
        if(empty($aData['data'])) {
            $aData['data'] = $this->_oDb->getData();
            if(empty($aData['data']))
                return false;
        }

        if($bCheckLifetime && $aData['data']['expires'] !== null && $aData['data']['expires'] <= time())
            return false;

        return $aData['data'];
    }

    public function isDataExpired()
    {
        $aData = $this->getData();
        if(empty($aData))
            return true;

        if(time() > $aData['expires'])
            return true;

        return false;
    }

    public function getTenantId()
    {
        $aData = $this->getData();
        if(empty($aData) || empty($aData['tenant_id']))
            return false;

        return $aData['tenant_id'];
    }

    public function getAccessToken()
    {
        $aData = $this->getData();
        if(empty($aData) || empty($aData['token']))
            return true;

        return (string)$aData['token'];
    }

    public function getRefreshToken()
    {
        $aData = $this->getData();
        if(empty($aData) || empty($aData['refresh_token']))
            return true;

        return (string)$aData['refresh_token'];
    }
}

/** @} */
