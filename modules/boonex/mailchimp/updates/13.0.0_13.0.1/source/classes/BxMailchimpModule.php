<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Mailchimp Mailchimp integration module
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_MAILCHIMP_LIMIT', 5000);

class BxMailchimpModule extends BxDolModule
{
    protected $sHostnameAPI = 'https://{dc}.api.mailchimp.com/3.0';                
    protected $aFields = array(
        'ACCOUNT_ID' => 'number', 
        'PROFILE_ID' => 'number',
        'MEMBERSHIP' => 'text', 
        'STATUS' => 'text', 
        'TYPE' => 'text', 
        'PROFILES' => 'text',
        'PROFILE_URL' => 'url',
        'IMAGE_URL' => 'imageurl',
        'PHONE' => 'phone',
    );

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        if (($sKey = getParam('bx_mailchimp_option_api_key')) && ($a = explode('-', $sKey)) && !empty($a[1]))
            $this->sHostnameAPI = str_replace('{dc}', $a[1], $this->sHostnameAPI);
        else
            $this->sHostnameAPI = '';
    }

    public function serviceUpdateMergeFields()
    {
        if (!isAdmin() || !($sListId = getParam('bx_mailchimp_option_list_id')))
            return false;

        foreach ($this->aFields as $sName => $sType) {
            $s = $this->_request('/lists/' . $sListId . '/merge-fields', 'POST', array('name' => $sName, 'tag' => $sName, 'type' => $sType), $sErrorCurl);
            if (!($a = $this->_checkForErrors($s, $sErrorCurl)) || isset($a['error']))
                return false;
        }

        return true;
    }

    public function serviceGetLists($bAddEmptyValue = true)
    {
        if (!isAdmin())
            return false;

        $s = $this->_request('/lists?count=100', 'GET', array(), $sErrorCurl);
        if (!($a = $this->_checkForErrors($s, $sErrorCurl)) || isset($a['error']))
            return array();
        
        if (isset($a['type']) && isset($a['detail']))
            die(json_encode(array('error' => $a['detail'])));

        if (!isset($a['lists']))
            return array();

        $aRet = array();
        foreach ($a['lists'] as $r)
            $aRet[] = array('key' => $r['id'], 'value' => $r['name']);

        if ($bAddEmptyValue)
            array_unshift($aRet, _t('_sys_please_select'));

        return $aRet;
    }

    /**
     * remove user from mailchimp by email or account id
     */      
    public function serviceRemoveAccount($mixed)
    {
        return $this->_accountAction($mixed, 'DELETE');
    }

    /**
     * update user in mailchimp by email or account id
     */      
    public function serviceUpdateAccount($sEmail)
    {
        return $this->_accountAction($sEmail, 'PUT');
    }

    protected function _accountAction($sEmail, $sMethod)
    {
        if (!$sEmail || !($oAccount = BxDolAccount::getInstance($sEmail)) || !($sListId = getParam('bx_mailchimp_option_list_id')))
            return false;

        $aUserInfo = $this->_prepareUser($oAccount);

        $s = $this->_request('/lists/' . $sListId . '/members/' . md5(strtolower($aUserInfo['email_address'])), $sMethod, $aUserInfo, $sErrorCurl);
        if (!($a = $this->_checkForErrors($s, $sErrorCurl)) || isset($a['error']))
            return false;

        if (isset($a['type']) && isset($a['detail']))
            return false;
        
        return true;
    }

    /**
     * remove user from mailchimp by email or account id
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
     * Add members in bulk from local database to mailchimp
     */      
    public function actionBulkAdd($iLimit = BX_MAILCHIMP_LIMIT)
    {
        if (!isAdmin() || !($sListId = getParam('bx_mailchimp_option_list_id')))
            die('{}');

        set_time_limit(3600);
        $aIds = $this->_oDb->getInitialUsers($iLimit);
        $aChunks = array_chunk($aIds, 1000);
        $iTotal = count($aIds);
        $iCounter = 0;
        foreach ($aChunks as $aIds) {
            $aOperations = array();
            $iTotal2 = 0;
            foreach ($aIds as $iAccountId) {
                if (!($oAccount = BxDolAccount::getInstance($iAccountId)))
                    continue;
                if (!($aUserInfo = $this->_prepareUser($oAccount)))
                    continue;
                $aOperations[] = array(
                    'method' => 'PUT',
                    'path' => '/lists/' . $sListId . '/members/' . md5(strtolower($aUserInfo['email_address'])),
                    'body' => json_encode($aUserInfo),
                );
                ++$iTotal2;
            }

            $s = $this->_request('/batches', 'POST', array('operations' => $aOperations), $sErrorCurl);
            if (!($a = $this->_checkForErrors($s, $sErrorCurl)) || isset($a['error']))
                die(json_encode($a));

            if (isset($a['type']) && isset($a['detail']))
                die(json_encode(array('error' => $a['detail'])));
        
            $iCounter += $iTotal2;
            sleep(1);
        }
        
        echo json_encode(array('message' => _t('_bx_mailchimp_accounts_processed', $iCounter)));
    }

    /**
     * Test query to return some stats
     */     
    public function actionStats()
    {
        if (!isAdmin() || !($sListId = getParam('bx_mailchimp_option_list_id')))
            die('{}');

        $s = $this->_request('/lists/' . $sListId, 'GET', array(), $sErrorCurl);
        if (!($a = $this->_checkForErrors($s, $sErrorCurl)) || isset($a['error']))
            die(json_encode($a));

        if (isset($a['type']) && isset($a['detail']))
            die(json_encode(array('error' => $a['detail'])));
        
        echo json_encode(array(
            'unsubscribed' => $a['stats']['unsubscribe_count'],
            'cleaned' => $a['stats']['cleaned_count'],
            'total' => $a['stats']['member_count'],
        ));
    }

    protected function _prepareUser($oAccount)
    {
        $oProfile = BxDolProfile::getInstanceByAccount($oAccount->id());
        if (!$oProfile)
            return false;

        $aInfoProfile = $oProfile->getInfo();
        $aInfoAccount = $oAccount->getInfo();
        //$aInfoSession = $this->_oDb->getSessionRowByAccountId($oAccount->id());
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
        $sEmail = $oAccount->getEmail();
        $aInfo = $oAccount->getInfo();
        $iAccountId = $oAccount->id();
        $iProfileId = $oProfile->id();

        $aMarkers = array (
            'FNAME' => $oProfile->getDisplayName(),
            'ACCOUNT_ID' => $iAccountId,
            'PROFILE_ID' => $iProfileId,
            'MEMBERSHIP' => _t($aMembership['name']) . (isset($aMembership['date_expires']) && $aMembership['date_expires'] ? ' (expires:' . bx_time_utc($aMembership['date_expires']) . ')' : ''),
            'STATUS' => $aInfoProfile['status'],
            'TYPE' => $aInfoProfile['type'],
            'PROFILES' => $sProfiles,
            'PROFILEURL' => $oProfile->getUrl(),
            'IMAGE_URL' => $oProfile->getAvatar(),
            'PHONE' => $aInfo['phone'],
            'RPASS_URL' => ''
        );

        if(($sResetPasswordUrl = bx_get_reset_password_link($sEmail)) !== false)
            $aMarkers['RPASS_URL'] = $sResetPasswordUrl;

        bx_alert($this->_aModule['name'], 'user_fields', $iAccountId, $iProfileId, array('email' => $sEmail, 'markers' => &$aMarkers));	

        return array (
            'email_address' => $oAccount->getEmail(),
            'status' => $aInfoAccount['receive_news'] ? 'subscribed' : 'unsubscribed',            
            'merge_fields' => $aMarkers
        );
    }

    /**
     * @return json error object on error or empty string on success
     */
    protected function _checkForErrors($s, $sErrorCurl)
    {
        if (!$s)
            return array('error' => 'curl error: ' . $sErrorCurl);

        $a = json_decode($s, true);
        if (null === $a)
            return array('error' => 'json decode failed:' . htmlspecialchars($s));

        return $a;
    }

    /**
     * Perform JSON request to the API endpoint and get JSON response
     * @return false on error
     */
    protected function _request($sURI, $sMethod, $aData, &$sError)
    {
        $sMethod = !empty($sMethod) ? $sMethod : 'GET';
        $aData = !empty($aData) ? $aData : array();

        $sApiKey = getParam('bx_mailchimp_option_api_key');
        if (!$sApiKey || !$this->sHostnameAPI) {
            $sError = "No API Key or hostname isn't configured";
            return false;
        }
        
        $r = curl_init();
    
        $aHeaders = array(
            'Accept: application/json',
        );

        curl_setopt($r, CURLOPT_USERPWD, "user:" . $sApiKey);
        curl_setopt($r, CURLOPT_TIMEOUT, 30);
        curl_setopt($r, CURLOPT_URL, $this->sHostnameAPI . $sURI);
        curl_setopt($r, CURLOPT_HEADER, 0);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_FOLLOWLOCATION, 1);

        if ('POST' == $sMethod || 'PUT' == $sMethod || 'PATCH' == $sMethod) {

            $sDataJson = json_encode($aData); 

            if ('POST' == $sMethod)
                curl_setopt($r, CURLOPT_POST, true);
            else
                curl_setopt($r, CURLOPT_CUSTOMREQUEST, $sMethod);

            curl_setopt($r, CURLOPT_POSTFIELDS, $sDataJson);

            $aHeaders = array_merge($aHeaders, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($sDataJson)
            )); 

        } elseif ('DELETE' == $sMethod) {

            curl_setopt($r, CURLOPT_CUSTOMREQUEST, "DELETE");

        }

        // $aHeaders[] = 'X-Trigger-Error: APIKeyMissing'; // test error handling

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
