<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentConnect Trident Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxTriConModule extends BxBaseModConnectModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * Generate admin page;
     *
     * @return : (text) - html presentation data;
     */
    function actionAdministration()
    {
        parent::_actionAdministration('bx_tricon_api_key', '_bx_tricon_settings', '_bx_tricon_information', '_bx_tricon_information_block');
    }

    /**
     * Redirect to remote Trident site login form
     *
     * @return n/a/ - redirect or HTML page in case of error
     */
    function actionStart()
    {
        if (isLogged())
            $this->_redirect ($this -> _oConfig -> sDefaultRedirectUrl);

        if (!$this->_oConfig->sApiID || !$this->_oConfig->sApiSecret || !$this->_oConfig->sApiUrl) {
            require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
            bx_import('BxDolLanguages');
            $sCode =  MsgBox( _t('_bx_tricon_profile_error_api_keys') );
            $this->_oTemplate->getPage(_t('_bx_tricon'), $sCode);            
        } 
        else {

            // define redirect URL to the remote Trident site                
            $sUrl = bx_append_url_params($this->_oConfig->sApiUrl . 'auth', array(
                'response_type' => 'code',
                'client_id' => $this->_oConfig->sApiID,
                'redirect_uri' => $this->_oConfig->sPageHandle,
                'scope' => $this->_oConfig->sScope,
                'state' => $this->_genCsrfToken(),
            ));
            $this->_redirect($sUrl);
        }
    }

    function actionHandle()
    {
        require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

        // check CSRF token
        if ($this->_getCsrfToken() != bx_get('state')) {
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_bx_tricon_state_invalid')));
            return;
        }

        // check code
        $sCode = bx_get('code');
        if (!$sCode) {
            $sErrorDescription = bx_get('error_description') ? bx_get('error_description') : _t('_error occured');
            $this->_oTemplate->getPage(_t('_Error'), MsgBox($sErrorDescription));
            return;
        }

        // make request for token
        $s = bx_file_get_contents($this->_oConfig->sApiUrl . 'token', array(
            'client_id'     => $this->_oConfig->sApiID,
            'client_secret' => $this->_oConfig->sApiSecret,
            'grant_type'    => 'authorization_code',
            'code'          => $sCode,
            'redirect_uri'  => $this->_oConfig->sPageHandle,
        ), 'post');

        // handle error
        if (!$s || NULL === ($aResponse = json_decode($s, true)) || !isset($aResponse['access_token']) || isset($aResponse['error'])) {
            $sErrorDescription = isset($aResponse['error_description']) ? $aResponse['error_description'] : _t('_error occured');
            $this->_oTemplate->getPage(_t('_Error'), MsgBox($sErrorDescription));
            return;
        }

        // get the data, especially access_token
        $sAccessToken = $aResponse['access_token'];
        $sExpiresIn = $aResponse['expires_in'];
        $sExpiresAt = new \DateTime('+' . $sExpiresIn . ' seconds');
        $sRefreshToken = $aResponse['refresh_token'];

        // request info about profile
        $s = bx_file_get_contents($this->_oConfig->sApiUrl . 'api/me', array(), 'get', array(
            'Authorization: Bearer ' . $sAccessToken,
        ));

        // handle error
        if (!$s || NULL === ($aResponse = json_decode($s, true)) || !$aResponse || isset($aResponse['error'])) {
            $sErrorDescription = isset($aResponse['error_description']) ? $aResponse['error_description'] : _t('_error occured'); 
            $this->_oTemplate->getPage(_t('_Error'), MsgBox($sErrorDescription));
            return;
        }

        $aRemoteProfileInfo = $aResponse;

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
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_bx_tricon_profile_error_info')));
        }
    }

    protected function _redirect($sUrl, $iStatus = 302)
    {
        header("Location:{$sUrl}", true, $iStatus);
        exit;
    }

    /**
     * @param $aProfileInfo - remote profile info
     * @param $sAlternativeName - suffix to add to NickName to make it unique
     * @return profile array info, ready for the local database
     */
    protected function _convertRemoteFields($aProfileInfo, $sAlternativeName = '')
    {
        $aProfileFields = $aProfileInfo;

        $aProfileFields['name'] = $aProfileInfo['profile_display_name'];
        $aProfileFields['fullname'] = $aProfileInfo['profile_display_name'];

        return $aProfileFields;
    }

    protected function _genCsrfToken($bReturn = false)
    {
        if (getParam('sys_security_form_token_enable') != 'on' || defined('BX_DOL_CRON_EXECUTE'))
            return false;

        $oSession = BxDolSession::getInstance();

        $iCsrfTokenLifetime = (int)$this->_oDb->getParam('sys_security_form_token_lifetime');
        if ($oSession->getValue('bx_tricon_csrf_token') === false || ($iCsrfTokenLifetime != 0 && time() - (int)$oSession->getValue('csrf_token_time') > $iCsrfTokenLifetime)) {
            $sToken = genRndPwd(20, false);
            $oSession->setValue('bx_tricon_csrf_token', $sToken);
            $oSession->setValue('bx_tricon_csrf_token_time', time());
        }
        else {
            $sToken = $oSession->getValue('bx_tricon_csrf_token');
        }

        return $sToken;
    }

    protected function _getCsrfToken()
    {
        $oSession = BxDolSession::getInstance();
        return $oSession->getValue('bx_tricon_csrf_token');
    }

}

/** @} */
