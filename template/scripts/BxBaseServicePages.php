<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System service for pages functionality.
 */
class BxBaseServicePages extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_system_pages System Services 
     * @subsection bx_system_pages-pages Pages
     * @subsubsection bx_system_pages-get_page_by_request Get page by page URI
     * 
     * @code bx_srv('system', 'get_page_by_request', ['about'], 'TemplServicePages'); @endcode
     * @code {{~system:get_page_by_request:TemplServicePages['about']~}} @endcode
     * 
     * Test method which page data
     * @param $sURI page URI 
     * 
     * @see BxBaseServicePages::serviceGetPage
     */
    /** 
     * @ref bx_system_general-get_page_by_request "get_page_by_request"
     */
    public function serviceGetPageByRequest ($sRequest, $sBlocks = '', $sParams = '')
    {
        $mixed = null;

        if (substr_count($sRequest, 'page/')> 0){
            $_GET['i'] = str_replace('page/', '', $sRequest);
            $aParams = json_decode($sParams, true);
            if(!empty($aParams) && is_array($aParams))
                $_GET = array_merge($_GET, $aParams);
            
            $mixed = BxDolPage::getObjectInstanceByURI();
        }
        else{
            if(!empty($sParams)) {
                $aParams = json_decode($sParams, true);
                if(!empty($aParams) && is_array($aParams))
                    $_GET = array_merge($_GET, $aParams);
            }
            $mixed = BxDolPage::getPageBySeoLink($sRequest);
        }
       

        if (($sUrl = $mixed) && is_string($sUrl)) {
            $aRes = ['redirect' => $sUrl];
        }
        elseif (($oPage = $mixed) && is_object($oPage)) {
			
            $aBlocks = [];
            if(!empty($sBlocks))
                $aBlocks = explode(',', $sBlocks);

            return $oPage->getPageAPI($aBlocks);
        }
        else {
            $aRes = ['code' => 404, 'error' => _t("_sys_request_page_not_found_cpt"), 'data' => ['page_status' => 404]];
            
            if (isLogged()) {
                $o = BxDolProfile::getInstance();
                $oAccount = $o->getAccountObject();
                $aRes['data']['user'] = [
                    'id' => $o->id(),
                    'email' => $o->getAccountObject()->getEmail(),
                    'display_name' => $o->getDisplayName(),
                    'url' => bx_api_get_relative_url($o->getUrl()),
                    'avatar' => $o->getAvatar(),
                    'settings' => $o->getSettings(),
                    //'level' => BxDolAcl::getInstance()->getMemberMembershipInfo($o->id()),
                    'moderator' => (bool)BxDolAcl::getInstance()->isMemberLevelInSet([MEMBERSHIP_ID_ADMINISTRATOR, MEMBERSHIP_ID_MODERATOR], $o->id()),
                    'operator' => isAdmin(),
                    //'info' => $o->getInfo(),
                    'confirmed' => $oAccount->isConfirmed(),
                    'notifications' => 0,
                    'cart' => 0,
                    'active' => $o->isActive(),
                    'status' => $o->getStatus(),
                ];
             }
        }
        
        return $aRes;
    }

    /**
     * @page service Service Calls
     * @section bx_system_pages System Services 
     * @subsection bx_system_pages-pages Pages
     * @subsubsection bx_system_pages-get_page_by_uri Get page by page URI
     * 
     * @code bx_srv('system', 'get_page_by_uri', ['about'], 'TemplServicePages'); @endcode
     * @code {{~system:get_page_by_uri:TemplServicePages['about']~}} @endcode
     * 
     * Test method which page data
     * @param $sURI page URI 
     * 
     * @see BxBaseServicePages::serviceGetPage
     */
    /** 
     * @ref bx_system_general-get_page_by_uri "get_page_by_uri"
     */
    public function serviceGetPageByUri ($sURI)
    {
        $oPage = BxDolPage::getObjectInstanceByURI($sURI, false, true);
        if (!$oPage) {
            $aRes = ['error' => _t("_sys_request_page_not_found_cpt")];
        } 
        else {
            $aRes = $oPage->getPage();
        }
        return $aRes;
    }
}

/** @} */
