<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModConnectModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceGetSafeServices()
    {
        return array();
    }

    public function serviceGetProfilesModules ()
    {
        $aModules = array();
        $a = BxDolService::call('system', 'get_profiles_modules', array(), 'TemplServiceProfiles');
        foreach ($a as &$aModule)
            $aModules[$aModule['name']] = $aModule['title'];
        return $aModules;
    }

    public function serviceGetPrivacyGroups ()
    {
        $oPrivacyQuery = new BxDolPrivacyQuery();
        $a = $oPrivacyQuery->getGroupsBy(array('type' => 'active'));
        $aGroups = array();
        foreach ($a as $r) {
            if (!(int)$r['active'])
               continue;
            $aGroups[$r['id']] = _t($r['title']);
        }
        return $aGroups;
    }
    
    /**
     * Logged profile
     *
     * @param $iProfileId integer
     * @param $sPassword string
     * @param $sCallbackUrl
     * @param $bRedirect boolean
     * @return void
     */
    function setLogged($iProfileId, $sCallbackUrl = '', $bRedirect = true, $bRememberMe = false)
    {
        $oProfile = BxDolProfile::getInstance($iProfileId);
 
        bx_login($oProfile->getAccountId(), $bRememberMe);

        if ($bRedirect) {
            $sCallbackUrl = $sCallbackUrl
                ? $sCallbackUrl
                : $this -> _oConfig -> sDefaultRedirectUrl;

            header('Location: ' . $sCallbackUrl);
        }
    }

    /**
     * Create new profile;
     *
     * @param  : $aProfileInfo (array) - remote profile's information;
     *
     * @param  : $sAlternativeName (string) - profiles alternative nickname;
     */
    function _createProfile($aProfileInfo, $sAlternativeName = '')
    {
        $mixed = $this->_createProfileRaw($aProfileInfo, $sAlternativeName);

        /**
         * @hooks
         * @hookdef hook-bx_base_connect-profile_created '{module_name}', 'profile_created' - hook after a profile was created 
         * - $unit_name - module name
         * - $action - equals `profile_created`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `override_result` - [string] or [array] by ref, result of profile creation, can be overridden in hook processing. If `string` then show error, if `array` with `join_page_redirect` key then show Create Account page, if `array` with `profile_id` key then redirect to profile page
         *      - `remote_profile_info` - [array] array with remote profile info
         * @hook @ref hook-bx_base_connect-profile_created
         */
        bx_alert($this->getName(), 'profile_created', 0, 0, [
            'override_result' => &$mixed, 
            'remote_profile_info' => $aProfileInfo
        ]);
        
        // display error
        if (is_string($mixed)) {
            $this->_oTemplate->getPage(_t($this->_oConfig->sDefaultTitleLangKey), DesignBoxContent(_t($this->_oConfig->sDefaultTitleLangKey), MsgBox($mixed)));
            exit;
        } 

        // display join page
        if (is_array($mixed) && isset($mixed['join_page_redirect'])) {
            $this->_getJoinPage($mixed['profile_fields'], $mixed['remote_profile_info']['id']);
            exit;
        } 

        // continue profile creation
        if (is_array($mixed) && isset($mixed['profile_id'])) {
            $iProfileId = (int)$mixed['profile_id'];

            //redirect to other page
            header('location:' . $this->_getRedirectUrl($iProfileId, $mixed['existing_profile']));
            exit;
        }

        $this->_oTemplate->getPage( _t($this->_oConfig->sDefaultTitleLangKey), MsgBox(_t('_Error Occured')) );
        exit;
    }

    /**
     * @param $aProfileInfo - remote profile info
     * @param $sAlternativeName - suffix to add to NickName to make it unique
     * @return profile array info, ready for the local database
     */
    protected function _convertRemoteFields($aProfileInfo, $sAlternativeName = '')
    {
    }

    /**
     * Create new profile;
     *
     * @param  : $aProfileInfo (array) - remote profile's information;
     *
     * @param  : $sAlternativeName (string) - profiles alternative nickname;
     * @return : error string or error or request invite form or profile info array on success
     */
    function _createProfileRaw($aProfileInfo, $sAlternativeName = '', $isAutoFriends = true, $isSetLoggedIn = true)
    {
        // join by invite only
        if (BxDolRequest::serviceExists('bx_invites', 'account_add_form_check') && $sCode = BxDolService::call('bx_invites', 'account_add_form_check'))
            return $sCode;

        // convert fields to unique format
        $aFieldsProfile = $aFieldsAccount = $this->_convertRemoteFields($aProfileInfo, $sAlternativeName);

        /**
         * @hooks
         * @hookdef hook-bx_base_connect-fields_converted '{module_name}', 'fields_converted' - hook before a profile was created, which allows to modify account and/or profile fields before creation
         * - $unit_name - module name
         * - $action - equals `fields_converted`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `override_profile_fields` - [array] by ref, profile fields, can be overridden in hook processing
         *      - `override_account_fields` - [array] by ref, account fields, can be overridden in hook processing
         *      - `remote_profile_info` - [array] array with remote profile info
         * @hook @ref hook-bx_base_connect-fields_converted
         */
        bx_alert($this->getName(), 'fields_converted', 0, 0, [
            'override_profile_fields' => &$aFieldsProfile, 
            'override_account_fields' => &$aFieldsAccount, 
            'remote_profile_info' => $aProfileInfo
        ]);

        if (empty($aFieldsProfile['email']))
            return _t('_Incorrect Email');

        // prepare fields for particular module
        $aFieldsAccount = BxDolService::call('system', 'prepare_fields', array($aFieldsAccount));
        $aFieldsProfile = BxDolService::call($this->_oConfig->sProfilesModule, 'prepare_fields', array($aFieldsProfile));

        // check fields existence in Account
        $oFormHelperAccount = BxDolService::call('system', 'forms_helper');
        $oFormAccount = $oFormHelperAccount->getObjectFormAdd();
        foreach ($aFieldsAccount as $sKey => $mValue) {
            if (!$oFormAccount->isFieldExist($sKey))
                unset($aFieldsAccount[$sKey]);
        }

        // check fields existence in Profile
        if ('system' != $this->_oConfig->sProfilesModule && $oFormHelperProfile = BxDolService::call($this->_oConfig->sProfilesModule, 'forms_helper')) {
            $oFormProfile = $oFormHelperProfile->getObjectFormEdit();
            foreach ($aFieldsProfile as $sKey => $mValue) {
                if (!$oFormProfile->isFieldExist($sKey))
                    unset($aFieldsProfile[$sKey]);
            }
        }

        // antispam check
        $sErrorMsg = '';
        $bSetPendingApproval = false;
        
        /**
         * @hooks
         * @hookdef hook-account-check_join 'account', 'check_join' - hook to check email address for spam
         * - $unit_name - equals `account`
         * - $action - equals `check_join`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `error_msg` - [string] by ref, error message, can be overridden in hook processing
         *      - `email` - [string] email address to check
         *      - `approve` - [boolean] by ref, pending approval status for created profile, can be overridden in hook processing
         * @hook @ref hook-account-check_join
         */
        bx_alert('account', 'check_join', 0, false, [
            'error_msg' => &$sErrorMsg, 
            'email' => $aFieldsAccount['email'], 
            'approve' => &$bSetPendingApproval
        ]);
        if ($sErrorMsg)
            return $sErrorMsg;

        // check if user with the same email already exists
        $oExistingAccount = $this->_getExistingAccount($aProfileInfo, $aFieldsAccount, $aFieldsProfile);

        // check redirect page
        if ('join' == $this->_oConfig->sRedirectPage && !$oExistingAccount)
            return array('remote_profile_info' => $aProfileInfo, 'profile_fields' => $aFieldsAccount, 'join_page_redirect' => true);

        // create new profile
        if ($oExistingAccount) {

            if (!($oExistingProfile = BxDolProfile::getInstanceByAccount($oExistingAccount->id(), true)))
                return _t('_sys_txt_error_account_creation');

            $iProfileId = $oExistingProfile->id();

            $this->setLogged($iProfileId);
        }
        else {

            // create account
            $aFieldsAccount['password'] = $this->_genPwd($aProfileInfo, $aFieldsAccount, $aFieldsProfile);
            if (!($iAccountId = $oFormAccount->insert($aFieldsAccount)))
                return _t('_sys_txt_error_account_creation');

            $isSetPendingApproval = $this->_isSetPendingApprovalAccount($aProfileInfo, $aFieldsAccount, $aFieldsProfile);
            $iAccountProfileId = $oFormHelperAccount->onAccountCreated ($iAccountId, $isSetPendingApproval, BX_PROFILE_ACTION_EXTERNAL);

            // create profile
            if (isset($oFormProfile) && $oFormProfile) {
                
                $aFieldsProfile['author'] = $iAccountProfileId;

                $aFieldsProfile['picture'] = $this->_processImage($aFieldsProfile, $iAccountProfileId, $oFormHelperProfile);
                $_POST['picture'] = $aFieldsProfile['picture']; // set POST variable to correctly process images in processFiles method in form object

                if (!($iContentId = $oFormProfile->insert($aFieldsProfile)))
                    return _t('_sys_txt_error_account_creation');

                $isSetPendingApprovalProfile = $this->_isSetPendingApprovalProfile($aProfileInfo, $oFormHelperProfile, $aFieldsAccount, $aFieldsProfile);
                $oFormHelperProfile->setAutoApproval($isSetPendingApprovalProfile);
                if ($sErrorMsg = $oFormHelperProfile->onDataAddAfter ($iAccountId, $iContentId))
                    return $sErrorMsg;
                
                $oProfile = BxDolProfile::getInstanceByAccount($iAccountId, true);
                $iProfileId = $oProfile->id();
            } 
            else {
                $iProfileId = $iAccountProfileId;
            }

            $oAccount = BxDolAccount::getInstance($iAccountId);
            if ($oAccount)
                $oAccount->updateEmailConfirmed($this->_oConfig->isAlwaysConfirmEmail);

            // send email with password
            if ($this->_oConfig->bSendPasswordGenerated)
                sendMailTemplate($this->_oConfig->sEmailTemplatePasswordGenerated, $iAccountId, $iProfileId, array('password' => $aFieldsAccount['password']), BX_EMAIL_SYSTEM);
        }

        // remember remote profile id for created member
        $this ->_oDb->saveRemoteId($iProfileId, $aProfileInfo['id']);

        // auto-friend members if they are already friends on remote site
        if ($isAutoFriends && method_exists($this, '_makeFriends'))
            $this->_makeFriends($iProfileId);

        return array('remote_profile_info' => $aProfileInfo, 'profile_id' => $iProfileId, 'existing_profile' => $oExistingAccount ? true : false);
    }

    protected function _processImage($aFieldsProfile, $iAccountProfileId, $oFormHelperProfile)
    {
        if (!isset($aFieldsProfile['picture']) || !$aFieldsProfile['picture'])
            return 0;

        if (!($oStorage = $oFormHelperProfile->getObjectStorage()))
            return 0;
        
        if (!($iFileId = $oStorage->storeFileFromUrl($aFieldsProfile['picture'], false, $iAccountProfileId)))
            return 0;

        return $iFileId;
    }

     /**
      * Get join page
      *
      * @param $aProfileFields array
      * @param $iRemoteProfileId remote profile id
      * @return void
      */
    function _getJoinPage($aProfileFields, $iRemoteProfileId)
    {
        bx_import('BxDolSession');
        $oSession = BxDolSession::getInstance();
        $oSession->setValue($this->_oConfig->sSessionUid, $iRemoteProfileId);

        $oPage = BxDolPage::getObjectInstanceByURI('create-account');

        BxBaseAccountForms::$PROFILE_FIELDS = $aProfileFields;

        $this->_oTemplate->getPage(false, $oPage->getCode());
    }

    /**
     * get redirect URL
     * 
     * @param $iProfileId integer - profile ID
     * @return string redirect URL
     */
    function _getRedirectUrl($iProfileId, $isExistingProfile = false)
    {
        $sRedirectUrl = $this->_oConfig->sDefaultRedirectUrl;

        switch($this->_oConfig->sRedirectPage) {
            case 'index':
                $sRedirectUrl = BX_DOL_URL_ROOT;
                break;

            case 'settings':
                if (!$isExistingProfile) { 
                    $sRedirectUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=account-settings-email'));
                    break;
                }

            case 'dashboard':
            default:
                $sRedirectUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=dashboard'));
                break;
            }

        return $sRedirectUrl;
    }

    protected function _redirect($sUrl, $iStatus = 302)
    {
        header("Location:{$sUrl}", true, $iStatus);
        exit;
    }

    protected function _genToken($bReturn = false)
    {
        $oSession = BxDolSession::getInstance();

        $sPrefix = $this->getName();

        $iCsrfTokenLifetime = 3600;
        if ($oSession->getValue($sPrefix . '_token') === false || (time() - (int)$oSession->getValue($sPrefix . '_token_time') > $iCsrfTokenLifetime)) {
            $sToken = genRndPwd(20, false);
            $oSession->setValue($sPrefix . '_token', $sToken);
            $oSession->setValue($sPrefix . '_token_time', time());
        }
        else {
            $sToken = $oSession->getValue($sPrefix . '_token');
        }

        return $sToken;
    }

    protected function _getToken()
    {
        $oSession = BxDolSession::getInstance();
        return $oSession->getValue($this->getName() . '_token');
    }

    protected function _getExistingAccount($aProfileInfo, &$aFieldsAccount, &$aFieldsProfile)
    {
        return BxDolAccount::getInstance($aFieldsAccount['email']);
    }

    protected function _genPwd($aProfileInfo, &$aFieldsAccount, &$aFieldsProfile)
    {
        return genRndPwd();
    }

    protected function _isSetPendingApprovalAccount($aProfileInfo, &$aFieldsAccount, &$aFieldsProfile)
    {
        return $this->_oConfig->isAlwaysAutoApprove ? false : !(bool)getParam('sys_account_autoapproval');
    }

    protected function _isSetPendingApprovalProfile($aProfileInfo, $oFormHelperProfile, &$aFieldsAccount, &$aFieldsProfile)
    {
        return $oFormHelperProfile->isAutoApproval() ? true : $this->_oConfig->isAlwaysAutoApprove;
    }
}

/** @} */
