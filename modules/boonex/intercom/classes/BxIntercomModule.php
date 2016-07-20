<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Intercom Intercom integration module
 * @ingroup     TridentModules
 *
 * @{
 */

class BxIntercomModule extends BxDolModule
{
    protected $sHostnameAPI = 'https://api.intercom.io';

    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceIntegrationCode ()
    {
        $sAppId = getParam('bx_intercom_option_app_id');
        if (!$sAppId)
            return '';

        $aSettings = array(
            'app_id' => $sAppId,
        );

        if (isLogged() && ($oProfile = BxDolProfile::getInstance()) && ($oAccountObject = $oProfile->getAccountObject())) {
            $aInfo = $oAccountObject->getInfo();
	        $aSettings['user_id'] = $oAccountObject->id();
	        $aSettings['name'] = $oProfile->getDisplayName();
	        $aSettings['email'] = $oAccountObject->getEmail();
	        $aSettings['created_at'] = $aInfo['added'];
        }
        
        $sSettings = json_encode($aSettings);

        return <<<EOS

        <!-- BEGIN Intercom integration -->
        <script>
            window.intercomSettings = $sSettings;
        </script>
        <script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/{$sAppId}';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
        <!-- END Intercom integration -->

EOS;
    }

    /**
     * remove user from intercom by email or account id
     */      
    public function serviceRemoveAccount($mixed)
    {
        if (!$mixed)
            return false;

        if ($mixed && !(int)$mixed) {
            $sId = BxDolAccount::getID($mixed);
        } else {
            $sId = (int)$mixed;
        }
        
        $s = $this->_request('/users?user_id=' . $sId, 'DELETE', array(), $sErrorCurl);
        if ($sErrorJson = $this->_checkForErrors($s, $sErrorCurl))
            return false;
    
        return true;
    }

    /**
     * update user by email or account id
     */      
    public function serviceUpdateAccount($sEmail)
    {
        if (!$sEmail || !($oAccount = BxDolAccount::getInstance($sEmail)))
            return false;

        $aUserInfo = $this->_prepareUser($oAccount);

        $s = $this->_request('/users', 'POST', $aUserInfo, $sErrorCurl);
        if ($sErrorJson = $this->_checkForErrors($s, $sErrorCurl))
            return false;

        return true;
    }
    
    /**
     * remove user from intercom by email or account id
     */      
    public function actionAccountRemove($sEmail)
    {
        if (!isAdmin())
            die('{}');

        if (!$this->serviceRemoveAccount($sEmail))
            die(json_encode(array('error' => true, 'message' => "Delete failed")));

        echo json_encode(array('message' => "User was successfully deleted"));
    }

    /**
     * update user by email or account id
     */  
    public function actionAccountUpdate($sEmail)
    {
        if (!isAdmin())
            die('{}');

        if (!$this->serviceUpdateAccount($sEmail))
            die(json_encode(array('error' => true, 'message' => "Update failed")));

        echo json_encode(array('message' => "User was successfully updated"));
    }

    /**
     * Add members in bulk from local database to Intercom
     */      
    public function actionBulkAdd($iLimit = 5000)
    {
        if (!isAdmin())
            die('{}');

        set_time_limit(3600);
        $aIds = $this->_oDb->getInitialUsers($iLimit);
        $aChunks = array_chunk($aIds, 50);
        $iTotal = count($aIds);
        $iCounter = 0;
        foreach ($aChunks as $aIds) {
            $aUsers = array();
            foreach ($aIds as $iAccountId) {
                if (!($oAccount = BxDolAccount::getInstance($iAccountId)))
                    continue;
                if (!($aUserInfo = $this->_prepareUser($oAccount)))
                    continue;
                $aUsers[] = $aUserInfo;
            }
            $s = $this->_request('/users/bulk', 'POST', array('users' => $aUsers), $sErrorCurl);
            if (trim($s) && $sErrorJson = $this->_checkForErrors($s, $sErrorCurl))
                die($sErrorJson);
            $iCounter += count($aUsers);
            header('X-Users-Processed: ' . $iCounter . '/' . $iTotal);
            flush();
            sleep(1);
        }
        echo json_encode(array('message' => "{$iCounter} users were successfully added"));
    }

    /**
     * Test query to return number of users registered in intercom
     */     
    public function actionNum()
    {
        if (!isAdmin())
            die('{}');

        $s = $this->_request('/users', 'GET', array(), $sErrorCurl);
        if ($sErrorJson = $this->_checkForErrors($s, $sErrorCurl))
            die($sErrorJson);

        $a = json_decode($s);
        
        echo json_encode(array(
            'type' => $a->type,
            'total_count' => $a->total_count,
        ));
    }

    protected function _prepareUser($oAccount)
    {
        $oProfile = BxDolProfile::getInstanceByAccount($oAccount->id());
        if (!$oProfile)
            return false;

        $aInfoProfile = $oProfile->getInfo();
        $aInfoAccount = $oAccount->getInfo();
        $aInfoSession = $this->_oDb->getSessionRowByAccountId($oAccount->id());
        $aProfilesIds = $oAccount->getProfilesIds();

        // membership
        $aMembership = BxDolAcl::getInstance()->getMemberMembershipInfo($oProfile->id());

        // profiles
        $sProfiles = '';
        foreach ($aProfilesIds as $iId) {
            if (!($o = BxDolProfile::getInstance($iId)))
                continue;
            $sProfiles .= $o->getDisplayName() . ', ';
        }
        $sProfiles = trim($sProfiles, ', ');

        return array (
            'user_id' => $oAccount->id(),
            'name' => $oProfile->getDisplayName(),
            'email' => $oAccount->getEmail(),
            'unsubscribed_from_emails' => !$aInfoAccount['receive_news'],
            'signed_up_at' => $aInfoAccount['added'],
            //'last_seen_ip' => ?,
            'last_request_at' => isset($aInfoSession['date']) ? $aInfoSession['date'] : $aInfoAccount['logged'],
            'custom_attributes' => array (
                'link' => $oProfile->getUrl(),

                'email_confirmed' => $aInfoAccount['email_confirmed'] ? 'yes' : 'no',
                'email_receive_news' => $aInfoAccount['receive_news'] ? 'yes' : 'no',
                'email_receive_updates' => $aInfoAccount['receive_updates'] ? 'yes' : 'no',
                
                'membership' => _t($aMembership['name']) . (isset($aMembership['date_expires']) && $aMembership['date_expires'] ? ' (expires:' . bx_time_utc($aMembership['date_expires']) . ')' : ''),
                'status' => $aInfoProfile['status'],
                'type' => $aInfoProfile['type'],

                'all profiles' => $sProfiles,
            ),
        );
    }

    /**
     * @return json error object on error or empty string on success
     */
    protected function _checkForErrors($s, $sErrorCurl, $sReturnType = 'json')
    {
        if (!$s)
            return $this->_returnErrors(array('error' => 'curl error: ' . $sErrorCurl), $sReturnType);

        $a = json_decode($s);
        if (null === $a)
            return $this->_returnErrors(array('error' => 'json decode failed:' . htmlspecialchars($s)), $sReturnType);

        if (0 === strncmp('error', $a->type, 5)) {
            $sError = '';
            foreach ($a->errors as $o)
                $sError .= $o->code . ": " . $o->message . "\n";
            return $this->_returnErrors(array('error' => $sError), $sReturnType);
        }

        return '';
    }

    protected function _returnErrors($mixed, $sReturnType = 'json')
    {
        switch ($sReturnType) {
            case 'array':
                return $mixed;
            case 'json':
            default:
                return json_encode($mixed);
        }
    }

    /**
     * Perform JSON request to the API endpoint and get JSON response
     * @return false on error
     */
    protected function _request($sURI, $sMethod = 'GET', $aData = array(), &$sError)
    {
        $sAppId = getParam('bx_intercom_option_app_id');
        $sApiKey = getParam('bx_intercom_option_api_key');
        if (!$sAppId || !$sApiKey) {
            $sError = "No App ID or API Key";
            return false;
        }
        
        $r = curl_init();
    
        $aHeaders = array(
            'Accept: application/json',
        );

        curl_setopt($r, CURLOPT_USERPWD, $sAppId . ":" . $sApiKey);
        curl_setopt($r, CURLOPT_TIMEOUT, 10);
        curl_setopt($r, CURLOPT_URL, $this->sHostnameAPI . $sURI);
        curl_setopt($r, CURLOPT_HEADER, 0);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_FOLLOWLOCATION, 1);

        if ('POST' == $sMethod) {

            $sDataJson = json_encode($aData); 

            curl_setopt($r, CURLOPT_POST, true);
            curl_setopt($r, CURLOPT_POSTFIELDS, $sDataJson);

            $aHeaders = array_merge($aHeaders, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($sDataJson)
            )); 

        } elseif ('DELETE' == $sMethod) {

            curl_setopt($r, CURLOPT_CUSTOMREQUEST, "DELETE");

        }

        curl_setopt($r, CURLOPT_HTTPHEADER, $aHeaders); 

        $sResult = curl_exec($r);        

        if (false === $sResult) {
            $sError = curl_error ($r);
            curl_close($r);
            return false;
        }

        curl_close($r);
        return $sResult;
    }
}

/** @} */
