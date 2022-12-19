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
    protected $_bJsCssAdded = false;

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
    public function serviceWikiPage ($sWikiObjectUri, $sUri)
    {
        $oWiki = BxDolWiki::getObjectInstanceByUri($sWikiObjectUri);
        if (!$oWiki) {
            $oTemplate = BxDolTemplate::getInstance();
            $oTemplate->displayPageNotFound();
            return;
        }

        $oPage = BxDolPage::getObjectInstanceByModuleAndURI($oWiki->getObjectName(), $sUri);
        if ($oPage) {

            $_GET['i'] = $sUri;

            $oPage->displayPage();

        } else {

            if ($oWiki->isAllowed('add-page')) {
                $oPage = BxDolPage::getObjectInstanceByURI($sUri);
                $oTemplate = BxDolTemplate::getInstance();
                if ($oPage) {                    
                    $oTemplate->displayErrorOccured(_t("_sys_wiki_error_page_exists", bx_process_output($sUri)));
                } else {
                    $this->_addCssJs (true);
                    $s = $oTemplate->parseHtmlByName('wiki_create_page.html', array(
                        'page_uri' => bx_process_output($sUri),
                        'action_uri' => $oWiki->getUri(),
                        'create_page' => _t('_sys_wiki_add_page'),
                        'text' => _t('_sys_wiki_add_page_text'),
                    ));
                    $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
                    $oTemplate->setPageHeader (_t('_sys_wiki_add_page'));
                    $oTemplate->setPageContent ('page_main_code', $s);
                    $oTemplate->getPageCode();
                }
            } 
            else {
                $oTemplate = BxDolTemplate::getInstance();
                $oTemplate->displayPageNotFound();
            }
        }

    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-wiki Wiki
     * @subsubsection bx_system_general-wiki_action wiki_action
     * 
     * @code bx_srv('system', 'wiki_action', [...], 'TemplServiceWiki'); @endcode
     * 
     * Perform WIKI action.
     * @param $sWikiObjectUri wiki object URI
     * 
     * @see BxBaseServiceWiki::serviceWikiAction
     */
    /** 
     * @ref bx_system_general-wiki_action "wiki_action"
     */
    public function serviceWikiAction ($sWikiObjectUri, $sAction)
    {
        $oWiki = BxDolWiki::getObjectInstanceByUri($sWikiObjectUri);
        if (!$oWiki) {
            echoJson(array('code' => 1, 'actions' => 'ShowMsg', 'msg' => _t('_sys_wiki_error_missing_wiki_object', $sWikiObjectUri)));
            return;
        }

        $sMethod = 'action' . bx_gen_method_name($sAction, array('-'));
        if (!method_exists($oWiki, $sMethod) || !$oWiki->isAllowed($sAction)) {            
            echoJson(array('code' => 2, 'actions' => 'ShowMsg', 'msg' => _t('_sys_wiki_error_action_not_allowed', $sAction, $sWikiObjectUri)));
            return;
        }
        
        $mixed = $oWiki->$sMethod();
        if (is_array($mixed))
            echoJson($mixed);
        else
            echo $mixed;
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-wiki Wiki
     * @subsubsection bx_system_general-wiki_controls wiki_controls
     * 
     * Get wiki block controls panel
     * 
     * @see BxBaseServiceWiki::serviceWikiControls
     */
    /** 
     * @ref bx_system_general-wiki_controls "wiki_controls"
     */
    public function serviceWikiControls ($oWikiObject, $aWikiVer, $aWikiVerLatest, $sBlockId)
    {
        $this->_addCssJs ();
    
        $sInfo = '';
        if ($aWikiVer && $aWikiVerLatest['revision'] == $aWikiVer['revision']) {
            $sInfo = bx_time_js($aWikiVer['added']);
        } 
        elseif ($aWikiVer) {
            $oProfile = BxDolProfile::getInstanceMagic($aWikiVer['profile_id']);
            $sInfo = _t('_sys_wiki_view_rev', $aWikiVer['revision'], $oProfile->getUrl(), $oProfile->getDisplayName(), bx_time_js($aWikiVer['added']));
        }

        $o = BxDolTemplate::getInstance();
        $o->addJs('stackedit.js/stackedit.min.js');
        return $o->parseHtmlByName('wiki_controls.html', array(
            'obj' => $oWikiObject->getObjectName(),
            'block_id' => $sBlockId,
            'info' => $sInfo,
            'options' => json_encode(array(
                'block_id' => $sBlockId,
                'language' => isset($aWikiVer['language']) ? $aWikiVer['language'] : bx_lang_name(),
                'wiki_action_uri' => $oWikiObject->getUri(),
                't_confirm_block_deletion' => _t('_sys_wiki_confirm_block_deletion'),
            )),
            'bx_if:menu' => array(
                'condition' => $oWikiObject->isAllowed('history'),
                'content' => array(
                    'obj' => $oWikiObject->getObjectName(),
                    'block_id' => $sBlockId,
                ),
            ),
        ));
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-wiki Wiki
     * @subsubsection bx_system_general-wiki_add_block wiki_add_block
     * 
     * Get "add wiki block" panel
     * 
     * @see BxBaseServiceWiki::serviceWikiAddBlock
     */
    /** 
     * @ref bx_system_general-wiki_add_block "wiki_add_block"
     */
    public function serviceWikiAddBlock ($oWikiObject, $sPageObject, $sCellId)
    {
        $this->_addCssJs ();
        if (!preg_match("/cell_(\d+)/", $sCellId, $aMatches))
            return '';
        $iCellId = $aMatches[1];

        $o = BxDolTemplate::getInstance();        
        return $o->parseHtmlByName('wiki_add_block.html', array(
            'add_block' => _t('_sys_wiki_add_block'),
            'page' => $sPageObject,
            'cell_id' => $iCellId,
            'action_uri' => $oWikiObject->getUri(),
        ));
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-wiki Wiki
     * @subsubsection bx_system_general-get_design_boxes get_design_boxes
     * 
     * Get "design boxes" array
     * 
     * @see BxBaseServiceWiki::serviceGetDesignBoxes
     */
    /** 
     * @ref bx_system_general-get_design_boxes "get_design_boxes"
     */
    public function serviceGetDesignBoxes ()
    {
        $o = new BxDolStudioBuilderPageQuery();
        $aItems = array();
        if (!$o->getDesignBoxes(array('type' => 'all'), $aItems, false))
            return array();
        $a = array();
        foreach($aItems as $r)
            $a[$r['id']] = _t($r['title']);
        return $a;
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-wiki Wiki
     * @subsubsection bx_system_general-pages_list pages_list
     * 
     * Get list of pages in current module
     * 
     * @see BxBaseServiceWiki::servicePagesList
     */
    /** 
     * @ref bx_system_general-pages_list "pages_list"
     */
    public function servicePagesList ()
    {
        if (!($sPageObject = BxDolPageQuery::getPageObjectNameByURI(bx_get('i'))))
            return '';
        if (!($aPage = BxDolPageQuery::getPageObject ($sPageObject)))
            return '';
        if (!($oWiki = BxDolWiki::getObjectInstance($aPage['module'])))
            return '';

        BxDolTemplate::getInstance()->addCss(['wiki.css']);

        return $oWiki->getContents();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-wiki Wiki
     * @subsubsection bx_system_general-page_contents page_contents
     * 
     * Get table of contents for current page, generated automatically from paragraphs headers
     * 
     * @see BxBaseServiceWiki::servicePageContents
     */
    /** 
     * @ref bx_system_general-page_contents "page_contents"
     */
    public function servicePageContents ()
    {
        $o = BxDolTemplate::getInstance();
        $o->addJs('toc.js');
        return $o->parseHtmlByName('wiki_page_contents.html', []);
    }

    protected function _addCssJs ($bAddPage = false)
    {
        if ($this->_bJsCssAdded)
            return false;

        $o = BxDolTemplate::getInstance();
        $o->addCss(['wiki.css']);
        $o->addJs('BxDolWiki.js');
        $o->addJsTranslation('_sys_wiki_external_editor_references_comment');
        if ($bAddPage)
            $o->addJs('studio/js/|forms.js');
        $this->_bJsCssAdded = true;
    }
}

/** @} */
