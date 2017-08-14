<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaConnect Dolphin Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDolConModule extends BxBaseModConnectModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * Redirect to remote site login form
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
            $sCode =  MsgBox( _t('_bx_dolcon_profile_error_api_keys') );
            $this->_oTemplate->getPage(_t('_bx_dolcon'), $sCode);            
        } 
        else {

            // define redirect URL to the remote site                
            $sUrl = bx_append_url_params($this->_oConfig->sApiUrl . 'auth', array(
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

        // check token
        if ($this->_getToken() != bx_get('state')) {
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_sys_connect_state_invalid')));
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

        $oSession = BxDolSession::getInstance();
        $oSession->setValue($this->getName() . '_access_token', $sAccessToken);

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
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_sys_connect_profile_error_info')));
        }
    }

    /**
     * Make friends
     *
     * @param $iProfileId integer
     * @return void
     */
    protected function _makeFriends($iProfileId)
    {
        if (!$this->_oConfig->bAutoFriends)
            return;

        $oConnFrinds = BxDolConnection::getObjectInstance('sys_profiles_friends');
        if (!$oConnFrinds)
            return;

        // request info about profile
        if (!($iRemoteProfileId = $this->_oDb->getRemoteProfileId($iProfileId)))
            return;
        $oSession = BxDolSession::getInstance();
        if (!($sAccessToken = $oSession->getValue($this->getName() . '_access_token')))
            return;
        $s = bx_file_get_contents($this->_oConfig->sApiUrl . 'api/friends?id=' . $iRemoteProfileId, array(), 'get', array(
            'Authorization: Bearer ' . $sAccessToken,
        ));

        // handle error
        if (!$s || NULL === ($aResponse = json_decode($s, true)) || !$aResponse || isset($aResponse['error']) || !isset($aResponse['friends'])) {
            $sErrorDescription = isset($aResponse['error_description']) ? $aResponse['error_description'] : _t('_error occured');
            return;
        }

        // add friends & followers
        foreach ($aResponse['friends'] as $iRemoteProfileId => $a) {
            if (!($iLocalProfileId = $this->_oDb->getProfileId($iRemoteProfileId)))
                continue;
            $oConnFrinds->actionAdd($iProfileId, $iLocalProfileId);
            $oConnFrinds->actionAdd($iLocalProfileId, $iProfileId);
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

        $sFullname = $aProfileInfo['NickName'];
        if (!empty($aProfileInfo['FullName']))
            $sFullname = $aProfileInfo['FullName'];
        if (!empty($aProfileInfo['FirstName']))
            $sFullname = $aProfileInfo['FirstName'] . (!empty($aProfileInfo['LastName']) ? ' ' . $aProfileInfo['LastName'] : '');

        $aProfileFields['name'] = $aProfileInfo['NickName'];
        $aProfileFields['fullname'] = $sFullname;
        $aProfileFields['picture'] = $aProfileInfo['picture'];
        $aProfileFields['allow_view_to'] = getParam('bx_dolcon_privacy');
            
        return $aProfileFields;
    }
}

/** @} */
