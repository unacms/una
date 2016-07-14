<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     TridentModules
 *
 * @{
 */

use OAuth2\Response;

class BxOAuthAPI extends BxDol
{
    protected $_oModule;
    protected $_oDb;
    public $aAction2Scope = array (
        'me' => 'basic,market',
        'user' => 'basic',
        'friends' => 'basic',
        'service' => 'service',
        'market' => 'market',
    );

    function __construct($oModule)
    {
        $this->_oModule = $oModule;
        $this->_oDb = $oModule->_oDb;
    }
    
    function me($aToken)
    {        
        if (!($oProfile = BxDolProfile::getInstance($aToken['user_id']))) {
            $this->errorOutput('404', 'not_found', 'Profile was not found');
            return;
        }
    
        $this->output($this->_prepareProfileArray($oProfile, false));
    }

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

    function friends($aToken)
    {
        $iProfileId = (int)bx_get('id');

        if (!($oProfile = $this->_getProfileWithAccessChecking($iProfileId)))
            return;

        $this->output(array(
            'user_id' => $iProfileId,
            'friends' => getMyFriendsEx($iProfileId),
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
