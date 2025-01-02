<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Services for content objects
 */
class BxBaseServiceContent extends BxDol
{
    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-login Login user and get user session
     * 
     * Login user with account id = 12 and get session id back, session id can be used
     * as cookie header to perform other API calls under logged user:
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/login/TemplServiceContent&params[]=12" @endcode
     * 
     * @param $iAccountId account id, or account email
     * @param $bRememberMe remeber session
     * @return array with session and user ids on success or false on error
     * 
     * @see BxBaseServiceContent::serviceLogin
     */
    /** 
     * @ref bx_system_general_cnt-login "Login user and get user's session"
     */
    function serviceLogin($iAccountId, $bRememberMe = false)
    {
        $oAccount = BxDolAccount::getInstance($iAccountId);
        if (!$oAccount)
            return false;

        bx_login($oAccount->id(), $bRememberMe);
        $aRet = $this->getUserIds($oAccount);
        if ($aRet)
            $aRet['session'] = BxDolSession::getInstance()->getId();
        else
            $aRet = ['session' => BxDolSession::getInstance()->getId()];
        return $aRet;
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-get_user_ids Get user's IDs
     * 
     * Get user's account id, profile id and profile module content id by account id.
     *
     * Get user's IDs by user's account id(12):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/get_user_ids/TemplServiceContent&params[]=12" @endcode
     * 
     * @param $iAccountId account id, or account email
     * @return array with user ids on success or false on error
     * 
     * @see BxBaseServiceContent::serviceGetUserIds
     */
    /** 
     * @ref bx_system_general_cnt-get_user_ids "Get user's IDs"
     */
    function serviceGetUserIds($iAccountId)
    {
        $oAccount = BxDolAccount::getInstance($iAccountId);
        if (!$oAccount)
            return false;

        return $this->getUserIds($oAccount);
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-get_info Get content info
     * 
     * @code bx_srv('system', 'get_info', ["bx_posts", 123], 'TemplServiceContent'); @endcode
     * @code {{~system:get_info:TemplServiceContent["bx_posts", 123]~}} @endcode
     *
     * Get post info by post id(123):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/get_info/TemplServiceContent&params[]=bx_posts&params[]=123" @endcode
     * Get account info by account id(12):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/get_info/TemplServiceContent&params[]=sys_account&params[]=12" @endcode
     * 
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @param $bRawInfo if true - raw info return data directly from DB, if false - returns strutured array
     * @return content info array on success or empty array on error
     * 
     * @see BxBaseServiceContent::serviceGetInfo
     */
    /** 
     * @ref bx_system_general_cnt-get_info "Get content info"
     */
    public function serviceGetInfo ($sContentObject, $iContentId, $bRawInfo = false)
    {
        if ('sys_account' == $sContentObject) {
            $o = BxDolAccount::getInstance($iContentId);
            if (!$o)
                return false;
            return $o->getInfo();
        }

        $o = BxDolContentInfo::getObjectInstance($sContentObject);
        if (!$o)
            return false;

        if ($bRawInfo)
            return $o->getContentInfo($iContentId, false);
        else
            return $o->getContentInfoAPI($iContentId, false);
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-get_link Get content link
     * 
     * @code bx_srv('system', 'get_link', ["bx_posts", 123], 'TemplServiceContent'); @endcode
     * @code {{~system:get_link:TemplServiceContent["bx_posts", 123]~}} @endcode
     *
     * Get post link by post id(123):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/get_link/TemplServiceContent&params[]=bx_posts&params[]=123" @endcode
     * 
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @return string with link on success or empty string on error
     * 
     * @see BxBaseServiceContent::serviceGetLink
     */
    /** 
     * @ref bx_system_general_cnt-get_link "Get content link"
     */
    public function serviceGetLink ($sContentObject, $iContentId)
    {
        if ('sys_account' == $sContentObject) {
            return false;
        }

        $o = BxDolContentInfo::getObjectInstance($sContentObject);
        if (!$o)
            return false;

        return $o->getContentLink($iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-delete Delete content
     * 
     * @code bx_srv('system', 'delete', ["bx_posts", 123], 'TemplServiceContent'); @endcode
     *
     * Delete post by post id(123):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/delete/TemplServiceContent&params[]=bx_posts&params[]=123" @endcode
     * Delete account with all its content by account id(12):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/delete/TemplServiceContent&params[]=sys_account&params[]=12" @endcode
     * 
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @param $aParams array of params: 
     *          'with_content' - true(default)|false: for sys_account, bx_persons, bx_organizations; 
     *          'force' - true|false(default): for bx_persons, bx_organizations; 
     *          'scheduled' - true|false(default): for sys_account;
     * @return array with code = 0 on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceDelete
     */
    /** 
     * @ref bx_system_general_cnt-delete "Delete content"
     */
    public function serviceDelete ($sContentObject, $iContentId, $aParams = [])
    {
        if ('sys_account' == $sContentObject) {
            $o = BxDolAccount::getInstance($iContentId);
            if (!$o)
                return false;
            return $o->delete(isset($aParams['with_content']) ? $aParams['with_content'] : true, isset($aParams['scheduled']) ? $aParams['scheduled'] : false);
        }
        if (in_array($sContentObject, ['bx_persons', 'bx_organizations'])) {
            $o = BxDolProfile::getInstanceByContentAndType($iContentId, $sContentObject);
            if (!$o)
                return false;
            return $o->delete(false, isset($aParams['with_content']) ? $aParams['with_content'] : true, isset($aParams['force']) ? $aParams['force'] : false);
        }

        $o = BxDolContentInfo::getObjectInstance($sContentObject);
        if (!$o)
            return false;
        if ($sErrorMsg = $o->deleteContent($iContentId))
            return ['code' => 500, 'error' => $sErrorMsg];
        else
            return ['code' => 0];
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-update Update content
     * 
     * @code bx_srv('system', 'update', ["bx_posts", 123, ["title" => "new title"]], 'TemplServiceContent'); @endcode
     *
     * Update post text by post id(123):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/update/TemplServiceContent&params=%5B%22bx_posts%22%2C123%2C%7B%22text%22%3A%22new%20text%22%7D%5D" @endcode
     * Update account email by account id(4):
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/update/TemplServiceContent&params=%5B%22sys_account%22%2C4%2C%7B%22email%22%3A%22new%40email.com%22%7D%5D" @endcode
     * 
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @param $aValues key value pairs to update
     * @return array with code = 0 on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceUpdate
     */
    /** 
     * @ref bx_system_general_cnt-update "Update content"
     */
    public function serviceUpdate ($sContentObject, $iContentId, $aValues)
    {
        if ('sys_account' == $sContentObject) {
            $o = BxDolAccount::getInstance($iContentId);
            if (!$o)
                return false;
            $oQuery = BxDolAccountQuery::getInstance();
            foreach ($aValues as $k => $v) {
                if (!$oQuery->isFieldExists('sys_accounts', $k))
                    return ['code' => 500, 'error' => _t('_sys_txt_forms_unknown_field_err', $k)];
            }
            foreach ($aValues as $k => $v) {
                if (!$oQuery->_updateField($o->id(), $k, $v))
                    return ['code' => 500, 'error' => _t('_error occured')];
            }
            return true;
        }

        $o = BxDolContentInfo::getObjectInstance($sContentObject);
        if (!$o)
            return false;
        if ($sErrorMsg = $o->updateContent($iContentId, $aValues))
            return ['code' => 500, 'error' => $sErrorMsg];
        else
            return ['code' => 0];
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-add Add content
     * 
     * @code bx_srv('system', 'add', [$sContentObject, $aValues], 'TemplServiceContent'); @endcode
     *
     * Add new post with specified title, text and privacy, post authour is identified by memberSession cookie:
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/add/TemplServiceContent&params=%5B%22bx_posts%22%2C%7B%22title%22%3A%22Post%20title%22%2C%22text%22%3A%22Some%20text%22%2C%22cat%22%3A2%2C%22allow_view_to%22%3A3%7D%5D" @endcode
     * Add new account with specified name, email and password, also mark email as confirmed:
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/add/TemplServiceContent&params=%5B%22sys_account%22%2C%20%7B%22name%22%3A%22Vasya%22%2C%22email%22%3A%22vasya%40vasya.com%22%2C%22email_confirmed%22%3A%221%22%2C%22password%22%3A%221234%22%7D%5D" @endcode
     * 
     * @param $sContentObject content object name
     * @param $aValues key value pairs to add
     * @return array with code = 0 on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceAdd
     */
    /** 
     * @ref bx_system_general_cnt-add "Add content"
     */
    public function serviceAdd ($sContentObject, $aValues)
    {
        if ('sys_account' == $sContentObject) {
            $o = new BxTemplAccountForms();
            $a = $o->createAccount ($aValues);
            if (isset($a['account_id']) && isset($aValues['email_confirmed'])) {
                $o = BxDolAccount::getInstance($a['account_id']);
                if ($o) {
                    $o->updateEmailConfirmed($aValues['email_confirmed'], isset($aValues['auto_send_confrmation_email']) ? $aValues['auto_send_confrmation_email'] : false);
                }
            }
            if (isset($a['account_id']) && isset($aValues['phone_confirmed'])) {
                $o = BxDolAccount::getInstance($a['account_id']);
                if ($o) {
                    $o->updatePhoneConfirmed($aValues['phone_confirmed']);
                }
            }
            return $a;
        }

        $o = BxDolContentInfo::getObjectInstance($sContentObject);
        if (!$o)
            return false;
        $a = $o->addContent($aValues);
        if (isset($a['code']) && !$a['code'] && isset($a['content'])) {
            return $a;
        } else {
            $a['code'] = 500 + $a['code'];
            $a['error'] = $a['message'];
            $a['data'] = $a['errors'];
            return $a;
        }
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-upload_from_url Upload file from URL
     * 
     * Upload file and associate with content:
     * @code bx_srv('system', 'upload_from_url', [$sContentObject, $sFileUrl, ['content_id' => 123]], 'TemplServiceContent'); @endcode
     *
     * Upload photo from `http://example.com/a.jpg` URL to `bx_persons_pictures` storage and associate
     * uploaded file with person by content id (123), returned value is newly uploaded file id, 
     * it can be used in conetnt update API cann to set profile picture.
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/upload_from_url/TemplServiceContent&params=%5B%22bx_persons_pictures%22%2C%22http%3A%2F%2Fexample.com%2Fa.jpg%22%2C%20%7B%22content_id%22%3A123%7D%5D" @endcode
     * 
     * @param $sStorageObject storage object name
     * @param $sFileUrl URL to file to store in the storage
     * @param $aParams array of params, possible array keys:
     *          'private' - true|false: set file as private or not, if omitted file is uploaded as public
     *          'profile_id' - int: set owner of file to this user, of omitted, then currently logged in user is becoming file owner
     *          'content_id' - int: associate file with this content
     * @return uploaded file Id on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceUploadFromUrl
     */
    /** 
     * @ref bx_system_general_cnt-upload_from_url "Upload file from URL"
     */
    public function serviceUploadFromUrl ($sStorageObject, $sFileUrl, $aParams = [])
    {
        $oStorage = BxDolStorage::getObjectInstance($sStorageObject);
        if (!$oStorage)
            return ['code' => 404, 'error' => _t('_sys_txt_not_found')];

        $iFileId = $oStorage->storeFileFromUrl($sFileUrl, isset($aParams['private']) && $aParams['private'] ? true : false, isset($aParams['profile_id']) ? $aParams['profile_id'] : 0, isset($aParams['content_id']) ? $aParams['content_id'] : 0);

        return $iFileId ? $iFileId : ['code' => $oStorage->getErrorCode(), 'error' => $oStorage->getErrorString()];
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-content-objects Content Objects
     * @subsubsection bx_system_general_cnt-delete_file Delete file
     * 
     * @code bx_srv('system', 'delete_file', ["bx_persons", 123], 'TemplServiceContent'); @endcode
     *
     * Delete file with id = 123 from `bx_persons_pictures` storage engine. 
     * <b>Please note</b> that associated with this file id content need to be updated by setting 
     * respective field to new file id or 0.
     * @code curl -s --cookie "memberSession=SESSIONIDHERE" -H "Authorization: Bearer APIKEYHERE" "http://example.com/api.php?r=system/upload_from_url/TemplServiceContent&params[]=bx_persons_pictures&params[]=123" @endcode
     * 
     * @param $sStorageObject storage object name
     * @param $iFileId file id
     * @return true on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceDeleteFile
     */
    /** 
     * @ref bx_system_general_cnt-delete_file "Delete file"
     */
    public function serviceDeleteFile ($sStorageObject, $iFileId)
    {
        $oStorage = BxDolStorage::getObjectInstance($sStorageObject);
        if (!$oStorage)
            return ['code' => 404, 'error' => _t('_sys_txt_not_found')];

        $b = $oStorage->deleteFile($iFileId);

        return $b ? $b : ['code' => $oStorage->getErrorCode(), 'error' => $oStorage->getErrorString()];
    }

    protected function getUserIds($oAccount)
    {
        $oProfile = BxDolProfile::getInstanceByAccount($oAccount->id());
        if (!$oProfile)
            return false;
        return [
            'account_id' => $oProfile->getAccountId(),
            'profile_id' => $oProfile->id(),
            'content_id' => $oProfile->getContentId(),
            'content_module' => $oProfile->getModule(),
        ];
    }
}

/** @} */
