<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GoogleConnect Google Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGoogleConModule extends BxBaseModConnectModule
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
        if (isLogged())
            $this->_redirect ($this -> _oConfig -> sDefaultRedirectUrl);

        if (!$this->_oConfig->sApiID || !$this->_oConfig->sApiSecret) {
            require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
            bx_import('BxDolLanguages');
            $sCode =  MsgBox( _t('_bx_googlecon_profile_error_api_keys') );
            $this->_oTemplate->getPage(_t('_bx_googlecon'), $sCode);
        } 
        else {

            // define redirect URL to the remote site                
            $sUrl = bx_append_url_params($this->_oConfig->sOauthUrl . '/auth', array(
                'response_type' => 'code',
                'client_id' => $this->_oConfig->sApiID,
                'redirect_uri' => $this->_oConfig->sPageHandle,
                'scope' => $this->_oConfig->sScope,
                'state' => $this->_genToken(),
            ));
            $this->_redirect($sUrl);
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

        // make request for token
        $s = bx_file_get_contents($this->_oConfig->sApiUrl . '/oauth2/v4/token', array(
            'client_id'     => $this->_oConfig->sApiID,
            'client_secret' => $this->_oConfig->sApiSecret,
            'grant_type'    => 'authorization_code',
            'code'          => $sCode,
            'redirect_uri'  => $this->_oConfig->sPageHandle,
        ), 'post', array ('Content-Type: application/x-www-form-urlencoded'));

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

        // request info about profile
        $s = bx_file_get_contents($this->_oConfig->sApiUrl . '/oauth2/v1/userinfo', array(), 'get', array(
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
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_sys_connect_profile_error_info')));
        }
    }

    public function serviceGetSafeServices()
    {
        return array_merge(parent::serviceGetSafeServices(), [
            'Handle' => '',
        ]);
    }

    public function serviceHandle($aRemoteProfileInfo = [])
    {
        if(!$this->_bIsApi)
            return;

        if(is_string($aRemoteProfileInfo))
            $aRemoteProfileInfo = bx_api_get_browse_params($aRemoteProfileInfo);

        if(empty($aRemoteProfileInfo) || !is_array($aRemoteProfileInfo))
            return [
                bx_api_get_msg(_t('_sys_connect_profile_error_info'))
            ];

        if(empty($aRemoteProfileInfo['id']) && !empty($aRemoteProfileInfo['sub']))
            $aRemoteProfileInfo['id'] = $aRemoteProfileInfo['sub'];
        
        // check if user logged in before
        $iProfileId = $this->_oDb->getProfileId($aRemoteProfileInfo['id']);
        if($iProfileId && $oProfile = BxDolProfile::getInstance($iProfileId))
            return $this->setLogged($oProfile->id());
        else
            return $this->_createProfile($aRemoteProfileInfo);
    }

    /**
     * @param $aProfileInfo - remote profile info
     * @param $sAlternativeName - suffix to add to NickName to make it unique
     * @return profile array info, ready for the local database
     */
    protected function _convertRemoteFields($aProfileInfo, $sAlternativeName = '')
    {
        $aProfileFields = $aProfileInfo;

        $aProfileFields['name'] = $aProfileInfo['given_name'];
        $aProfileFields['fullname'] = $aProfileInfo['name'];
        $aProfileFields['email'] = isset($aProfileInfo['email']) ? $aProfileInfo['email'] : '';
        $aProfileFields['picture'] = isset($aProfileInfo['picture']) ? $aProfileInfo['picture'] : '';
        $aProfileFields['allow_view_to'] = getParam('bx_googlecon_privacy');
        
        return $aProfileFields;
    }

}

/** @} */
