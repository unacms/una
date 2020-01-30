<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Wiki Wiki
 * @ingroup     UnaModules
 *
 * @{
 */

class BxWikiModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceGetSafeServices()
    {
        return array (
            'Contents' => '',
        );
    }

    /**
     * @page service Service Calls
     * @section bx_wiki Wiki
     * @subsection bx_wiki-browse Browse
     * @subsubsection bx_wiki-contents contents
     * 
     * @code bx_srv('bx_wiki', 'contents'); @endcode
     * 
     * Wiki contents - list of pages
     * @code {{~bx_wiki:contents~}} @endcode
     *
     * @see BxWikiModule::serviceContents
     */
    /** 
     * @ref bx_wiki-contents "contents"
     */
    public function serviceContents ()
    {
        if (!($oWiki = BxDolWiki::getObjectInstance($this->getName())))
            return '';
        
        return $oWiki->getContents();
    }

}

/** @} */
