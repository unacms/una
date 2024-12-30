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
     * @subsubsection bx_system_general-get_info Get content info
     * 
     * @code bx_srv('system', 'get_info', [$sContentObject, $iContentId], 'TemplServiceContent'); @endcode
     * @code {{~system:get_info:TemplServiceContent["bx_posts", 123]~}} @endcode
     * 
     * Content
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @param $bRawInfo if true - raw info return data directly from DB, if false - returns strutured array
     * @return content info array on success or empty array on error
     * 
     * @see BxBaseServiceContent::serviceGetInfo
     */
    /** 
     * @ref bx_system_general-get_info "Get content"
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
     * @subsubsection bx_system_general-get_link Get content link
     * 
     * @code bx_srv('system', 'get_link', [$sContentObject, $iContentId], 'TemplServiceContent'); @endcode
     * @code {{~system:get_link:TemplServiceContent["bx_posts", 123]~}} @endcode
     * 
     * Content
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @return string with link on success or empty string on error
     * 
     * @see BxBaseServiceContent::serviceGetLink
     */
    /** 
     * @ref bx_system_general-get_link "Get content link"
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
     * @subsubsection bx_system_general-delete Delete content
     * 
     * @code bx_srv('system', 'delete', [$sContentObject, $iContentId], 'TemplServiceContent'); @endcode
     * @code {{~system:get:TemplServiceContent["bx_posts", 123]~}} @endcode
     * 
     * Content
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @param $aParams array of params: 
     *          'with_content' - true|false: for sys_account, bx_persons, bx_organizations; 
     *          'force' - true|false: for bx_persons, bx_organizations; 
     *          'scheduled' - true|false: for sys_account;
     * @return array with code = 0 on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceDelete
     */
    /** 
     * @ref bx_system_general-delete "Delete content"
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
     * @subsubsection bx_system_general-update Update content
     * 
     * @code bx_srv('system', 'update', [$sContentObject, $iContentId, $aValues], 'TemplServiceContent'); @endcode
     * @code {{~system:get:TemplServiceContent["bx_posts", 123, ["title" => "new title"]]~}} @endcode
     * 
     * Content
     * @param $sContentObject content object name
     * @param $iContentId content id
     * @param $aValues key value pairs to update
     * @return array with code = 0 on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceUpdate
     */
    /** 
     * @ref bx_system_general-update "Update content"
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
     * @subsubsection bx_system_general-add Add content
     * 
     * @code bx_srv('system', 'add', [$sContentObject, $aValues], 'TemplServiceContent'); @endcode
     * @code {{~system:get:TemplServiceContent["bx_posts", ["title" => "new title", "text" => "new text"]]~}} @endcode
     * 
     * Content
     * @param $sContentObject content object name
     * @param $aValues key value pairs to add
     * @return array with code = 0 on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceAdd
     */
    /** 
     * @ref bx_system_general-add "Add content"
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
}

/** @} */
