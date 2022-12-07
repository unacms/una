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
     * @subsubsection bx_system_pages-get_page Get page
     * 
     * @code bx_srv('system', 'get_page', ['about'], 'TemplServicePages'); @endcode
     * @code {{~system:get_page:TemplServicePages['about']~}} @endcode
     * 
     * Test method which page data
     * @param $sURI page URI 
     * 
     * @see BxBaseServicePages::serviceGetPage
     */
    /** 
     * @ref bx_system_general-get_page "get_page"
     */
    public function serviceGetPage ($sURI)
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
