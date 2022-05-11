<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    AzureB2CConnect Azure B2C Connect
 * @ingroup     UnaModules
 *
 * @{
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../vendor/autoload.php');

use Alancting\Microsoft\JWT\AzureAd\AzureAdConfiguration;
use Alancting\Microsoft\JWT\AzureAd\AzureAdAccessTokenJWT;
use Alancting\Microsoft\JWT\AzureAd\AzureAdIdTokenJWT;

/**
 * This Azude AD connect code was created with instructions from:
 * https://www.sipponen.com/archives/4024
 */
class BxAzrB2CModule extends BxBaseModConnectModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * Redirect to remote site login form
     *
     * @return n/a - redirect or HTML page in case of error
     */
    function actionStart()
    {
        if (isset($_GET["error"])) {
            $this->_oTemplate->getPage(_t($this->_oConfig->sDefaultTitleLangKey), DesignBoxContent(_t($this->_oConfig->sDefaultTitleLangKey), MsgBox(bx_get('error'))));
            exit;
        }

        if (isLogged()) {
            $this->_redirect ($this -> _oConfig -> sDefaultRedirectUrl);
        }

        if (!$this->_oConfig->sClientID || !$this->_oConfig->sSecret) {
            require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
            bx_import('BxDolLanguages');
            $sCode =  MsgBox( _t('_bx_azrb2c_profile_error_api_keys') );
            $this->_oTemplate->getPage(_t('_bx_azrb2c'), $sCode);
        } 
        else {

            // First stage of the authentication process; This is just a simple redirect (first load of this page)

            $verifier_bytes = random_bytes(64);
            $code_verifier = rtrim(strtr(base64_encode($verifier_bytes), "+/", "-_"), "=");
            BxDolSession::getInstance()->setValue('code_verifier', $code_verifier);
            $challenge_bytes = hash("sha256", $code_verifier, true);
            $code_challenge = rtrim(strtr(base64_encode($challenge_bytes), "+/", "-_"), "=");

            $sBaseUrl = "https://" . $this->_oConfig->sDomain . "/" . $this->_oConfig->sTenant . "/" . $this->_oConfig->sPolicy;//B2C_1_login_una";
            $sUrl = bx_append_url_params($sBaseUrl . "/oauth2/v2.0/authorize", [
                'state' => $this->_genToken(), // This at least semi-random string is likely good enough as state identifier
                'scope' => $this->_oConfig->sScope, 
                'response_type' => 'code',
                'response_mode' => 'query',
                'prompt' => 'login',
                'client_id' => $this->_oConfig->sClientID,
                'redirect_uri' => $this->_oConfig->sPageHandle,
                'client_info' => 1,
                'code_challenge_method' => 'S256',
                'code_challenge' => $code_challenge,
            ]);
            $this->_redirect($sUrl); // So off you go my dear browser and welcome back for round two after some redirects at Azure end
        }
    }

    function actionHandle()
    {
        require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

        // check CSRF token
        if ($this->_getToken() != bx_get('state')) {
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_sys_connect_state_invalid')));
            return;
        }

        // check code
        $sCode = bx_get('code');
        if (!$sCode || bx_get('error')) {
            $sErrorDescription = bx_get('error_description') ? bx_get('error_description') : _t('_error occured');
            $this->_oTemplate->getPage(_t('_Error'), MsgBox($sErrorDescription));
            return;
        }

        // Verifying the received tokens with Azure and finalizing the authentication part

        $sBaseUrl = "https://" . $this->_oConfig->sDomain . "/" . $this->_oConfig->sTenant . "/" . $this->_oConfig->sPolicy;
        $s = bx_file_get_contents($sBaseUrl . "/oauth2/v2.0/token", [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->_oConfig->sClientID,
            'scope'         => $this->_oConfig->sScope,
            'redirect_uri'  => $this->_oConfig->sPageHandle,
            'code'          => $sCode,
            'client_secret' => $this->_oConfig->sSecret,
            'code_verifier' => BxDolSession::getInstance()->getValue('code_verifier'),
        ], 'post', array ('Content-Type: application/x-www-form-urlencoded'));
        $aAuthData = $this->_decodeResponseAndHandleError($s);

        // get the data, especially id_token

        $sIdToken = $aAuthData['id_token'];


        // parse token to get the data
        // 
        // to make library to with B2C correctly please comment out the following lines in 
        // vendor/alancting/php-microsoft-jwt/src/Base/MicrosoftConfiguration.php 
        // file near ~240 line
        // @code
        // !array_key_exists('userinfo_endpoint', $data) 
        // !array_key_exists('device_authorization_endpoint', $data)
        // @endcode
        // then further in the code add checking for not set values for these values

    	$aConfigOptions = [
            'tenant' => $this->_oConfig->sTenantID,
            'tenant_id' => $this->_oConfig->sTenantID,
  	    	'client_id' => $this->_oConfig->sClientID,
            'config_uri' => "https://" . $this->_oConfig->sDomain . "/" . $this->_oConfig->sTenant . "/"  . $this->_oConfig->sPolicy . "/v2.0/.well-known/openid-configuration",
	    ];
        $oConfig = new AzureAdConfiguration($aConfigOptions);
        $oIdTokenJWT = new AzureAdIdTokenJWT($oConfig, $sIdToken);
        $aUserData = [
            'id' => $oIdTokenJWT->get('oid'),
            'name' => $oIdTokenJWT->get('given_name'),
            'last_name' => $oIdTokenJWT->get('family_name'),
            'email' => reset($oIdTokenJWT->get('emails')),
        ];

        $aRemoteProfileInfo = $aUserData;

        if ($aRemoteProfileInfo) {

            // check if user logged in before
            $iLocalProfileId = $this->_oDb->getProfileId($aRemoteProfileInfo['id']);
            
            if ($iLocalProfileId && $oProfile = BxDolProfile::getInstance($iLocalProfileId)) {
                // user already exists
                $this->setLogged($oProfile ->id());
            }             
            else {  
                // register new user
                $this->_createProfile($aRemoteProfileInfo);
            }
        } 
        else {
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_sys_connect_profile_error_info')));
        }
    }

    /**
     * @param $aProfileInfo - remote profile info
     * @param $sAlternativeName - suffix to add to NickName to make it unique
     * @return profile array info, ready for the local database
     */
    protected function _convertRemoteFields($aProfileInfo, $sAlternativeName = '')
    {
        $aProfileFields = $aProfileInfo;

        $aProfileFields['fullname'] = !empty($aProfileInfo['last_name']) ? $aProfileInfo['name'] . ' ' . $aProfileInfo['last_name'] : $aProfileFields['name'];
        $aProfileFields['picture'] = ''; // isset($aProfileInfo['picture']) ? $aProfileInfo['picture'] : '';
        $aProfileFields['allow_view_to'] = getParam('bx_azrb2c_privacy');

        return $aProfileFields;
    }

    protected function _decodeResponseAndHandleError($s, $bDisplayErrorPage = true)
    {
        if (!$s || NULL === ($aData = json_decode($s, true)) || !$aData || isset($aData['error'])) {
            if (is_array($aData['error']) && !empty($aData['error']['message']))
                $sErrorDescription = $aData['error']['message'];
            else
                $sErrorDescription = isset($aData['error_description']) ? $aData['error_description'] : _t('_error occured');
            if ($bDisplayErrorPage) {
                $this->_oTemplate->getPage(_t('_Error'), MsgBox($sErrorDescription));
                exit;
            }
            else {  
                return false;
            }
        }
        return $aData;
    }
}

/** @} */
