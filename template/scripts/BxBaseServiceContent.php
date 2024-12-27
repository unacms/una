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
     * @return array with code = 0 on success, or array with code != 0 and error message
     * 
     * @see BxBaseServiceContent::serviceDelete
     */
    /** 
     * @ref bx_system_general-delete "Delete content"
     */
    public function serviceDelete ($sContentObject, $iContentId)
    {
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
