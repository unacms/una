<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    API API to the UNA backend
 * @ingroup     UnaModules
 *
 * @{
 */

class BxApiModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        return array_merge($a, array (
            'DeletePage' => '',
            'ChangeAccountPassword' => '',
            'SwitchProfile' => ''
        ));
    }

    public function serviceGetPublicServices()
    {
        $a = parent::serviceGetPublicServices();
        return array_merge($a, array (
            'Test' => '',
            'GetPage' => '',
            'ResetPasswordSendRequest' => '',
            'ResetPasswordCheckCode' => '',
            'CreateAccount' => '',
        ));
    }

    /**
     * @page public_api API Public
     * @section public_api_api_test /m/oauth2/com/test
     * 
     * Test method to check public API
     * 
     * **HTTP Method:** 
     * `GET`
     *
     * **Request params:**
     * n/a
     *
     * **Response (success):**
     * @code
     * {  
     *    "result": "Test passed."
     * }
     * @endcode
     */
    public function serviceTest()
    {
        return array('result' => 'Test passed.');
    }

    /**
     * @page public_api API Public
     * @section public_api_api_get_page /m/oauth2/com/get_page
     * 
     * Get page with cells and blocks as array
     * 
     * **HTTP Method:** 
     * `GET`
     *
     * **Request params:**
     * - `uri` - page URI
     *
     * **Response (success):**
     * @code
     * {  
     *     "id": "123", // page ID
     *     "layout": "5", // page layout, 5 is simplest page with one cell
     *     "module": "system", // module which this page 
     *     "title": "Test page", // page title
     *     "type": 1, // page type, 1 is for default page with header and footer
     *     "uri": "test", // page URI, which is part of page URL
     *     "elements": {
     *         "cell_1": [
     *             {
     *                 "content": "test content", // block content, it can be array as well
     *                 "designbox_id": "11", // block design, such as padding, border, title
     *                 "hidden_on": "", // not empty block need to be hidden on mobile, or desktop
     *                 "id": "321",
     *                 "module": "system", // module name this block is related to
     *                 "order": "1", // block order
     *                 "title": "Test block", // block title
     *                 "type": "raw" // block type
     *             }
     *      ]
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
     */
    public function serviceGetPage()
    {
        $sUri = bx_get('uri');
        $oPage = BxDolPage::getObjectInstanceByURI($sUri);
        if (!$oPage) {
            return array(
                'code' => 404,
                'error' => 'Not Found',
                'desc' => 'This page doesn\'t exist',
            );
        }
        if (!$oPage->isVisiblePage()) {
            return array(
                'code' => 403,
                'error' => 'Forbidden',
                'desc' => 'This page requires special right to be viewed',
            );
        }

        return $oPage->getPage ();
    }

    /**
     * @page private_api API Private
     * @section private_api_api_delete_page /m/oauth2/com/delete_page
     * 
     * Delete page with all blocks
     * 
     * **Scopes:** 
     * `api`
     *
     * **HTTP Method:** 
     * `POST`
     *
     * **Request params:**
     * - `uri` - page URI
     *
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "code": 200,
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
     */
    public function serviceDeletePage()
    {        
        $sUri = bx_get('uri');
        $oPage = BxDolPage::getObjectInstanceByURI($sUri);
        if (!$oPage) {
            return array(
                'code' => 404,
                'error' => 'No Content',
                'desc' => 'Such page doesn\'t exist',
            );
        }

        if (!$oPage->isDeleteAllowed()) {
            return array(
                'code' => 403,
                'error' => 'Forbidden',
                'desc' => 'Not enough rights to delete this page',
            );
        }

        $oPageBuilder = new BxTemplStudioBuilderPage($oPage->getModule(), $oPage->getName());
        $mixed = $oPageBuilder->processAction('page_delete');
        if (is_array($mixed) && (isset($mixed['msg']) || isset($mixed['message']))) {
            return array(
                'code' => 500,
                'error' => 'Internal Error',
                'desc' => isset($mixed['msg']) ? $mixed['msg'] : $mixed['message'],
            );
        }

        return array('code' => 200);
    }
    
    /**
     * @page private_api API Private
     * @section private_api_api_change_account_password /m/oauth2/com/change_account_password
     * 
     * Change account password
     * 
     * **Scopes:** 
     * `api`
     *
     * **HTTP Method:** 
     * `POST`
     *
     * **Request params:**
     * - `password` - new password
     *
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "code": 200,
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
     */
    public function serviceChangeAccountPassword()
    {        
        $sPassword = bx_get('password');
        
        if ($sPassword == '') {
            return array(
                'code' => 404,
                'error' => 'No Content',
                'desc' => 'Password can\'t be empty',
            );
        }
        
        $oAccount = BxDolAccount::getInstance();
        if (!$oAccount->updatePassword($sPassword)){
            return array(
                'code' => 500,
                'error' => 'Internal Error',
            );
        }

        return array('code' => 200);
    }
    
    /**
     * @page private_api API Private
     * @section private_api_api_switch_profile /m/oauth2/com/switch_profile
     * 
     * Switch account active profile
     * 
     * **Scopes:** 
     * `api`
     *
     * **HTTP Method:** 
     * `POST`
     *
     * **Request params:**
     * - `profile_id` - profile id
     *
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "code": 200,
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
     */
    public function serviceSwitchProfile()
    {
        $mixedProfileIdToSwitch = bx_get('profile_id');
        
        if ($mixedProfileIdToSwitch == '') {
            return array(
                'code' => 404,
                'error' => 'No Content',
                'desc' => 'Profile_id can\'t be empty',
            );
        }
        
		if (bx_get_logged_profile_id() == (int)$mixedProfileIdToSwitch){
            return array(
                'code' => 404,
                'error' => 'Not Allowed',
                'desc' => 'Requested profile_id equal with current profile_id',
            );
		}
        
        $mixedRes = bx_srv('system', 'switch_profile', [$mixedProfileIdToSwitch], 'TemplServiceAccount');
        
        if (true === $mixedRes) {
            return array('code' => 200);
        } 
        else {
            return array(
                'code' => 500,
                'error' => 'Internal Error',
                'desc' => $mixedRes,
            );
        }
    }
    
    /**
     * @page public_api API Public
     * @section public_api_api_reset_password_send_request /m/oauth2/com/reset_password_send_request
     * 
     * Send email with reset password code
     * 
     * **Scopes:** 
     * `api`
     *
     * **HTTP Method:** 
     * `POST`
     *
     * **Request params:**
     * - `email` - email address
     *
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "code": 200,
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
     */
    public function serviceResetPasswordSendRequest(){
		$sEmail = bx_get('email');
        if ($sEmail == ''){
			return array(
                'code' => 404,
                'error' => 'No Content',
                'desc' => 'Email can\'t be empty',
            );
		}
		
		$oAccountQuery = BxDolAccountQuery::getInstance();
		$iAccountId = $oAccountQuery->getIdByEmail($sEmail);
		if (!$iAccountId){
			return array(
                'code' => 404,
                'error' => 'No Content',
                'desc' => 'Email is not valid',
            );
		}
		
		$oKey = BxDolKey::getInstance();
		$aPlus['key'] = $oKey->getNewKey(array('email' => $sEmail));
		$aPlus['forgot_password_url'] = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password', array('key' => $aPlus['key'])));
        $aTemplate = BxDolEmailTemplates::getInstance() -> parseTemplate('t_Forgot', $aPlus, $iAccountId);
        if ($aTemplate && sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, $aPlus, BX_EMAIL_SYSTEM)){
            return array('code' => 200);
		}
		
		return array(
            'code' => 500,
            'error' => 'Internal Error'
        );
	}
    
    /**
     * @page public_api API Public
     * @section public_api_api_reset_password_check_code /m/oauth2/com/reset_password_check_code
     * 
     * Check validation code & set new password 
     * 
     * **Scopes:** 
     * `api`
     *
     * **HTTP Method:** 
     * `POST`
     *
     * **Request params:**
     * - `code` - validation code
     * - `email` - email address
     *
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "code": 200,
     *     "password" : "[NEW PASSWORD]"
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
     */
    public function serviceResetPasswordCheckCode() {
        $sKey = bx_get('code');

        if ($sKey == '') {
            return array(
                'code' => 404,
                'error' => 'No Content',
                'desc' => 'Validation code can\'t be empty',
            );
        }

        $oKey = BxDolKey::getInstance();
        if (!$oKey || !$oKey->isKeyExists($sKey)) {
            return array(
                'code' => 404,
                'error' => 'Wrong code',
                'desc' => 'Validation code is not exist or expired',
            );
        }

        $aData = $oKey->getKeyData($sKey);
        if (!isset($aData['email'])){
            return array(
                'code' => 404,
                'error' => 'Wrong code',
                'desc' => 'Validation code is not exist or expired',
            );
        }

        $oAccountQuery = BxDolAccountQuery::getInstance();
        $iAccountId = $oAccountQuery->getIdByEmail($aData['email']);
        if (!$iAccountId) {
            return array(
                'code' => 404,
                'error' => 'Wrong code',
                'desc' => 'Validation code is not exist or expired',
            );
        }

        $oBxTemplServiceAccount = new BxTemplServiceAccount();
        $sPassword = $oBxTemplServiceAccount->generateUserNewPwd($iAccountId);

        $oKey->removeKey($sKey);

        $aPlus = array ('password' => $sPassword);
        $aTemplate = BxDolEmailTemplates::getInstance() -> parseTemplate('bx_api_password_reset', $aPlus, $iAccountId);

        $oAccountQuery->unlockAccount($iAccountId);

        if ($aTemplate && sendMail($aData['email'], $aTemplate['Subject'], $aTemplate['Body'], 0, $aPlus, BX_EMAIL_SYSTEM)) {
            return array(
                'code' => 200,
                'password' => $sPassword,
            );
        }
    }
    
    /**
     * @page public_api API Public
     * @section public_api_api_create_account /m/oauth2/com/create_account
     * 
     * Create account
     * 
     * **Scopes:** 
     * `api`
     *
     * **HTTP Method:** 
     * `POST`
     *
     * **Request params:**
     * - `name` - account name
     * - `email` - email address
     * - `password` - password
     *
     * **Request header:**
     * @code
     * Authorization: Bearer 9802c4a34e1535d8c3b721604ee0e7fb04116c49
     * @endcode
     *
     * **Response (success):**
     * @code
     * {  
     *     "code": 200,
     *     "profile_id" : "[PROFILE ID]"
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
     */
    public function serviceCreateAccount()
	{
		
		$sAccountName = bx_get('name');
		$sEmail = bx_get('email');
		$sPassword = bx_get('password');
		
        if (!($sAccountName && $sEmail && $sPassword)){
			return array(
                'code' => 404,
                'error' => 'No Content',
                'desc' => 'Account name, email, password code can\'t be empty',
            );
		}
		
		$oAccQuery = BxDolAccountQuery::getInstance();;
		$iId = $oAccQuery->getIdByEmail($sEmail);
		if ($iId > 0){
			return array(
                'code' => 404,
                'error' => 'Already exists',
                'desc' => 'Account with requested email already exists',
            );
		}

		$_POST['name'] = $sAccountName;
		$_POST['email'] = $sEmail;
		$_POST['password'] = $sPassword;
		$_POST['email_confirmed'] = false;
		$_POST['do_submit'] = 'Submit';

		$oForm = BxDolForm::getObjectInstance('bx_accounts_account', 'bx_accounts_account_create');
		$oForm->aParams['csrf']['disable'] = true;
		$oForm->initChecker();
		
		if($oForm->isSubmittedAndValid()) {
			$iAccountId = $oForm->insert();			
			if (!$iAccountId) {
				return $this->_error(_t('_sg_wtv_error_account_creation_failed'), 150);
			}

			$oBxTemplAccountForms = new BxTemplAccountForms();

			$iProfileId = $oBxTemplAccountForms->onAccountCreated($iAccountId, $oForm->isSetPendingApproval(), BX_PROFILE_ACTION_MANUAL, false);

			$sProfileModule = getParam('sys_account_default_profile_type');
			if (getParam('sys_account_auto_profile_creation') && !empty($sProfileModule)) {
				$oAccount = BxDolAccount::getInstance($iAccountId);
				$aProfileInfo = BxDolService::call($sProfileModule, 'prepare_fields', array(array(
					'author' => $iProfileId,
					'name' => $oAccount->getDisplayName(),
					)));
				$a = BxDolService::call($sProfileModule, 'entity_add', array($iProfileId, $aProfileInfo));
				if ($a['code'] != 0)
					return $this->_error($a['message']);
				$iProfileId = $a['content']['profile_id'];
			}
            
			return array(
				'code' => 200,
				'profile_id' => $iProfileId
			);
		}
		else{
			$s = $oForm->getFormErrors();
			return array(
                'code' => 500,
                'error' => 'Internal Error',
                'desc' => $s,
            );
		}
	}
}

/** @} */
