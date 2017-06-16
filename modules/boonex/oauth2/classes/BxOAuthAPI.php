<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     UnaModules
 *
 * @{
 */

use OAuth2\Response;

class BxOAuthAPI extends BxDol
{
    protected $_oModule;
    protected $_oDb;
    public $aAction2Scope = array (
        'me' => 'basic,market,service',
        'user' => 'basic,market,service',
        'friends' => 'basic,market,service',
        'service' => 'service',
        'market' => 'market',
    );

    function __construct($oModule)
    {
        $this->_oModule = $oModule;
        $this->_oDb = $oModule->_oDb;
    }

    /**
     * @page private_api API Private
     * @section private_api_me /m/oauth2/api/me
     * 
     * Provides information about current profile.  
     *
     * **Grant types:** 
     * `basic`, `service`, `market`
     * 
     * **HTTP Method:** 
     * `GET`
     *
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "id":"123",
     *     "type":"bx_organizations",
     *     "email":"test@example.com",
     *     "role":"1",
     *     "name":"Test",
     *     "profile_display_name":"Test",
     *     "profile_link":"http:\/\/example.com\/path-to-una\/page\/view-organization-profile?id=12",
     *     "picture":"http:\/\/example.com\/path-to-una\/image_transcoder.php?o=bx_organizations_picture&h=36&dpx=1&t=1496247306"
     * }
     * @endcode
     *
     * **Response (error):**
     * @code
     * {  
     *    "error":"short error description here",
     *    "error_description":"long error description here"
     * }
     * @endcode
     * 
     */
    function me($aToken)
    {        
        if (!($oProfile = BxDolProfile::getInstance($aToken['user_id']))) {
            $this->errorOutput('404', 'not_found', 'Profile was not found');
            return;
        }
    
        $this->output($this->_prepareProfileArray($oProfile, false));
    }

    /**
     * @page private_api API Private
     * @section private_api_user /m/oauth2/api/user
     * 
     * Provides information about particular profile profile.
     *
     * **Parameters:**
     * - `id` - profile ID
     *
     * Everything else is equivalent to @ref private_api_me
     */     
    function user($aToken)
    {
        $iProfileId = (int)bx_get('id');

        if ($iProfileId == $aToken['user_id']) {
            $this->me($aToken);
            return;
        }

        if (!($oProfile = $this->_getProfileWithAccessChecking($iProfileId)))
            return;
        
        $this->output($this->_prepareProfileArray($oProfile, !isAdmin($aToken['user_id'])));
    }

    /**
     * @page private_api API Private
     * @section private_api_friends /m/oauth2/api/friends
     * 
     * Get list of friends.
     *
     * **Grant types:** 
     * `basic`, `service`, `market`
     * 
     * **HTTP Method:** 
     * `GET`
     *
     * **Parameters:**
     * - `id` - profile ID
     * 
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "user_id":30,
     *     "friends":[  
     *         "24",
     *         "29",
     *         "51"
     *     ]
     * }
     * @endcode
     *
     * **Response (error):**
     * @code
     * {  
     *    "error":"short error description here",
     *    "error_description":"long error description here"
     * }
     * @endcode
     * 
     */     
    function friends($aToken)
    {
        $iProfileId = (int)bx_get('id');

        if (!($oProfile = $this->_getProfileWithAccessChecking($iProfileId)))
            return;

        if (!($oConn = BxDolConnection::getObjectInstance('sys_profiles_friends'))) {
            $this->errorOutput(405, 'not_supported', 'Friends lists aren\'t supported');
            return false;
        }
        
        $this->output(array(
            'user_id' => $iProfileId,
            'friends' => $oConn->getConnectedContent($iProfileId, true),
        ));
    }

    function market($aToken) 
    {
    	$_GET['key'] = $_POST['key'] = $aToken['client_id'];
        $_GET['module'] = $_POST['module'] = 'bx_market_api';
        $this->service($aToken);
    }

    /**
     * Service call should look like this
     * http://example.com/m/oauth2/api/service?module=bx_market&method=test&params[]=1&params[]=abc&class=custom_class_name_or_remove_it_if_module_class
     * or
     * http://example.com/m/oauth2/api/service?module=bx_market&method=test&params=serialized_string_of_params
     */ 
    function service($aToken) 
    {
        bx_login($aToken['user_id'], false, false);

        $sModule = bx_get('module');
        $sMethod = bx_get('method');

        if (!($aParams = bx_get('params')))
            $aParams = array();
        elseif (is_string($aParams) && preg_match('/^a:[\d+]:\{/', $aParams))
            $aParams = @unserialize($aParams);
        if (!is_array($aParams))
            $aParams = array($aParams);

        if (!($sClass = bx_get('class')))
            $sClass = 'Module';

        if (!BxDolRequest::serviceExists($sModule, $sMethod, $sClass)) {
            $this->errorOutput(404, 'not_found', 'Service was not found');
            return false;
        }

        $mixedRet = BxDolService::call($sModule, $sMethod, $aParams, $sClass);

        $this->output(array(
            'module' => $sModule,
            'method' => $sMethod,
            'data' => $mixedRet,
        ));
    }

    function errorOutput($iHttpCode, $sError, $sErrorDesc)
    {
        $oReponse = new Response();
        $oReponse->setError($iHttpCode, $sError, $sErrorDesc);
        $oReponse->send();
    }

    function output($a)
    {
        $oReponse = new Response();
        $oReponse->setParameters($a);
        $oReponse->send();
    }

    protected function _getProfileWithAccessChecking ($iProfileId) 
    {
        if (!$oProfile = BxDolProfile::getInstance($iProfileId)) {
            $this->errorOutput('404', 'not_found', 'Profile was not found');
            return false;
        }
/*        
        // TODO: check visibility
        if (!bx_check_profile_visibility($iProfileId, $aToken['user_id'], true)) {
            $this->errorOutput(403, 'access_denied', 'You have no rights to view this user info');
            return false;
        }
*/
        return $oProfile;
    }

    protected function _prepareProfileArray ($oProfile, $bPublicFieldsOnly = true) 
    {
        $oAccount = $oProfile->getAccountObject();
        $aProfileInfo = $oProfile->getInfo();
        $aAccountInfo = $oAccount->getInfo();

        if ($bPublicFieldsOnly) {
            $aProfileInfo = array(
                'id' => $aProfileInfo['id'],
            );
        } 
        else {
            unset($aProfileInfo['account_id']);
            unset($aProfileInfo['content_id']);
            unset($aProfileInfo['status']);

            $aProfileInfo['email'] = $oAccount->getEmail();

            // TODO: fetch extended info from profile module
        }


        $aProfileInfo['role'] = $aAccountInfo['role'];
        $aProfileInfo['name'] = $oAccount->getDisplayName();
        $aProfileInfo['profile_display_name'] = $oProfile->getDisplayName();
        $aProfileInfo['profile_link'] = $oProfile->getUrl();
        $aProfileInfo['picture'] = $oProfile->getPicture();

        return $aProfileInfo;
    }

}

/** @} */
