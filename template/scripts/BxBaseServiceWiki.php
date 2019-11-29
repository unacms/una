<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Services for Wiki functionality
 */
class BxBaseServiceWiki extends BxDol
{

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-wiki Wiki
     * @subsubsection bx_system_general-wiki_page wiki_page
     * 
     * @code bx_srv('system', 'wiki_page', ["index"], 'TemplServiceWiki'); @endcode
     * @code {{~system:wiki_page:TemplServiceWiki["index"]~}} @endcode
     * 
     * Display WIKI page.
     * @param $sUri categories object name
     * 
     * @see BxBaseServiceWiki::serviceWikiPage
     */
    /** 
     * @ref bx_system_general-wiki_page "wiki_page"
     */
    public function serviceWikiPage ($sUri)
    {
        echo "Show wiki page with '$sUri' uri";
    }
}

/** @} */
