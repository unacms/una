<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    FacebookConnect Facebook Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxFaceBookConnectModule extends BxBaseModConnectModule
{
    protected $oFacebook;
    protected $sLastError;

    /**
     * Class constructor ;
     *
     * @param   : $aModule (array) - contain some information about this module;
     *                  [ id ]           - (integer) module's  id ;
     *                  [ title ]        - (string)  module's  title ;
     *                  [ vendor ]       - (string)  module's  vendor ;
     *                  [ path ]         - (string)  path to this module ;
     *                  [ uri ]          - (string)  this module's URI ;
     *                  [ class_prefix ] - (string)  this module's php classes file prefix ;
     *                  [ db_prefix ]    - (string)  this module's Db tables prefix ;
     *                  [ date ]         - (string)  this module's date installation ;
     */
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        require_once(BX_DIRECTORY_PATH_PLUGINS . 'facebook-php-sdk/src/Facebook/autoload.php');

        // Create our Application instance.
        $this -> oFacebook = null;

        if ($this -> _oConfig -> mApiID) {
            session_start();
            $this -> oFacebook = new Facebook\Facebook(array(
                'app_id'  => $this -> _oConfig -> mApiID,
                'app_secret' => $this -> _oConfig -> mApiSecret,
                'default_graph_version' => 'v2.4',  
            ));
        }
    }


    /**
     * Facebook login callback url;
     *
     * @return (text) - html presentation data;
     */
    function actionLoginCallback()
    {
        if (isLogged()) {
            header ('Location:' . $this -> _oConfig -> sDefaultRedirectUrl);
            exit;
        }

        require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
        bx_import('BxDolLanguages');

        if (!$this -> _oConfig -> mApiID || !$this -> _oConfig -> mApiSecret)
            $this->_setLastError(_t('_bx_facebook_profile_error_api_keys'));

        if ($sError = $this->_setAccessToken())
            $this->_setLastError($sError);

        if (!$this->_getLastError()) {

            //we already logged in facebook
            try {
                $oResponse = $this -> oFacebook -> get('/me?fields=' . $this -> _oConfig -> sFaceBookFields);
                $aFacebookProfileInfo = $oResponse -> getDecodedBody();
                $aFacebookProfileInfo['nick_name'] = $aFacebookProfileInfo['name'];

            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                $this->_setLastError($e->getMessage());
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                $this->_setLastError($e->getMessage());
            }

            //process profile info
            if($aFacebookProfileInfo) {

                // try define user id
                $iProfileId = $this -> _oDb
                    -> getProfileId($aFacebookProfileInfo['id']);

                if ($iProfileId) {                    
                    $this -> setLogged($iProfileId);
                } 
                else {
                    // process profile's nickname
                    $aFacebookProfileInfo['nick_name'] = $this
                        -> _proccesNickName($aFacebookProfileInfo['first_name']);

                    // try to get profile's image
                    if ($oFacebookProfileImageResponse = $this -> oFacebook -> get('/me/picture?type=large&redirect=false')) {

                        $aFacebookProfileImage = $oFacebookProfileImageResponse -> getDecodedBody();
                        $aFacebookProfileInfo['picture'] = isset($aFacebookProfileImage['data']['url']) && !$aFacebookProfileImage['data']['is_silhouette']
                            ? $aFacebookProfileImage['data']['url']
                            : '';
                    }

                    // create new profile
                    $this -> _createProfile($aFacebookProfileInfo);
                }
            } else {
                // FB profile info is not defined;
                $this->_setLastError(_t('_bx_facebook_profile_error_info'));
            }
        }

        $this -> _oTemplate -> dislayPageError();
    }

    /**
     * Generare facebook login form;
     *
     * @return (text) - html presentation data;
     */
    function actionLoginForm()
    {
        if (isLogged()) {
            header ('Location:' . $this -> _oConfig -> sDefaultRedirectUrl);
            exit;
        }

        if (!$this -> _oConfig -> mApiID || !$this -> _oConfig -> mApiSecret) {
            require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
            bx_import('BxDolLanguages');
            $this->_setLastError(_t('_bx_facebook_profile_error_api_keys'));
        } 
        else {

            $oFacebookRedirectLoginHelper = $this -> oFacebook -> getRedirectLoginHelper();

            //redirect to facebook login form
            $sLoginUrl = $oFacebookRedirectLoginHelper->getLoginUrl(
                $this -> _oConfig -> aFaceBookReqParams['redirect_uri'],
                explode(',', $this -> _oConfig -> aFaceBookReqParams['scope'])
            );

            header('location: ' . $sLoginUrl);
            exit;
        }

        $this -> _oTemplate -> dislayPageError();
    }

    function serviceLastError()
    {
        return MsgBox($this->sLastError ? $this->sLastError : _t('_Empty'));
    }

    /**
     * Make friends
     *
     * @param $iProfileId integer
     * @return void
     */
    protected function _makeFriends($iProfileId)
    {
        if (!$this->_oConfig->bAutoFriends) {
            return;
        }

        try {
            //get friends from facebook
            $oFriendsResponse = $this -> oFacebook -> get('/me/friends?limit=50');
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return;
        }

        // paginate through the result
        $oPagesEdge = $oFriendsResponse->getGraphEdge();
        do {
            foreach ($oPagesEdge as $oPage) {
                $aFriend = $oPage->asArray();
                $iFriendId = $this -> _oDb -> getProfileId($aFriend['id']);

                // TODO:
            }
        } while ($oPagesEdge = $this -> oFacebook -> next($oPagesEdge));
    }

    /**
     * @param $aProfileInfo - remote profile info
     * @param $sAlternativeName - suffix to add to NickName to make it unique
     * @return profile array info, ready for the local database
     */
    protected function _convertRemoteFields($aProfileInfo, $sAlternativeName = '')
    {
        // process the date of birth
        if( isset($aProfileInfo['birthday']) ) {
            $aProfileInfo['birthday'] = isset($aProfileInfo['birthday'])
                ?  date('Y-m-d', strtotime($aProfileInfo['birthday']))
                :  '';
        }

        // define user's country and city
        $aLocation = array();
        if (isset($aProfileInfo['location']['name']))
            $aLocation = $aProfileInfo['location']['name'];
        elseif (isset($aProfileInfo['hometown']['name']))
            $aLocation = $aProfileInfo['hometown']['name'];

        if($aLocation) {
            $aCountryInfo = explode(',', $aLocation);
            $sCountry = $this -> _oDb -> getCountryCode( trim($aCountryInfo[1]) );
            $sCity = trim($aCountryInfo[0]);

            //set default country name, especially for American brothers
            if($sCity && !$sCountry) {
                $sCountry = $this -> _oConfig -> sDefaultCountryCode;
           }
        }

        // fill array with all needed values
        $aProfileFields = array(
            'name'      	=> $aProfileInfo['nick_name'] . $sAlternativeName,
            'email'         => isset($aProfileInfo['email']) ? $aProfileInfo['email'] : '',
            'gender'        => isset($aProfileInfo['gender']) ? $aProfileInfo['gender'] : '',
            'birthday'      => isset($aProfileInfo['birthday']) ? $aProfileInfo['birthday'] : '',
            'fullname'		=> (isset($aProfileInfo['first_name']) ? $aProfileInfo['first_name'] : '') . (isset($aProfileInfo['last_name']) ? ' ' . $aProfileInfo['last_name'] : ''),
            'description'   => clear_xss(isset($aProfileInfo['bio']) ? $aProfileInfo['bio'] : ''),
            'interests'     => clear_xss(isset($aProfileInfo['interests']) ? $aProfileInfo['interests'] : ''),
            'religion'      => clear_xss(isset($aProfileInfo['religion']) ? $aProfileInfo['religion'] : ''),
            'country'       => isset($sCountry) ? $sCountry : '',
            'city'       	=> isset($sCity) ? $sCity : '',
            'picture'       => $aProfileInfo['picture'],
            'allow_view_to' => getParam('bx_facebook_connect_privacy'),
        );

        return $aProfileFields;
    }

    /**
     * Function will clear all unnecessary sybmols from profile's nickname;
     *
     * @param  : $sProfileName (string) - profile's nickname;
     * @return : (string) - cleared nickname;
     */
    protected function _proccesNickName($sProfileName)
    {
        $sProfileName = preg_replace("/^http:\/\/|^https:\/\/|\/$/", '', $sProfileName);
        $sProfileName = str_replace('/', '_', $sProfileName);
        $sProfileName = str_replace('.', '-', $sProfileName);

        return $sProfileName;
    }

    protected function _setAccessToken()
    {
        $oFacebookRedirectLoginHelper = $this -> oFacebook -> getRedirectLoginHelper();

        try {
            $sAccessToken = $oFacebookRedirectLoginHelper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            return $e->getMessage();
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            return $e->getMessage();
        }

        if (!isset($sAccessToken))
            return $oFacebookRedirectLoginHelper->getErrorDescription() ? $oFacebookRedirectLoginHelper->getErrorDescription() : (bx_get('error_message') ? rawurldecode(bx_get('error_message')) : _t('_error occured'));

        $this -> oFacebook -> setDefaultAccessToken($sAccessToken);

        return '';
    }

    protected function _setLastError($s)
    {
        $this->sLastError = $s;
    }

    protected function _getLastError()
    {
        return $this->sLastError;
    }

}

/** @} */
