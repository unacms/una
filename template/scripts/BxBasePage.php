<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

/**
 * Page representation.
 * @see BxDolPage
 */
class BxBasePage extends BxDolPage
{
    protected $_oTemplate;

    protected $_sStorage; //--- Storage object for page's images like custom cover, HTML block attachments, etc.
    protected $_oPageCacheObject = null;
    protected $_oBlockCacheObject = null;
    
    protected $_bStickyColumns = false;

    protected $_bSubPage = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

		$this->_sStorage = 'sys_images';
        
        $this->_sJsClassName = 'BxDolPage';
        $this->_sJsObjectName = 'oBxDolPage';
        
        $this->_bStickyColumns = isset($this->_aObject['sticky_columns']) && $this->_aObject['sticky_columns'] == 1;

        $sHtmlId = str_replace(array('_' , ' '), array('-', '-'), $this->_sObject);
        $this->_aHtmlIds = array(
            'help_popup' => $sHtmlId . '-help-popup-',
        );
    }

    public function performActionGetHelp ()
    {
        $iBlockId = (int)bx_get('block_id');
        if(empty($iBlockId))
            return;

        $aBlock = $this->_oQuery->getPageBlock($iBlockId);
        if(empty($aBlock) || !is_array($aBlock) || empty($aBlock['help']))
            return;

        echo $this->_oTemplate->parseHtmlByName('designbox_help_popup.html', array(
            'content' => _t($aBlock['help'])
        ));
    }
    
    public function performActionGetShare ()
    {
        $aEmbedData = BxDolPage::getEmbedData(bx_get('url'));
        if(!isset($aEmbedData['url'])) 
            return; 

        $sMenu = '';
        if(getParam('sys_a2a_enable') != 'on') {
            if(($oMenu = BxDolMenu::getObjectInstance('sys_social_sharing')) !== false) {
                $oMenu->addMarkers([
                    'url' => $aEmbedData['url'],
                    'img_url_encoded' => '',
                    'title_encoded' => ''
                ]);
                $sMenu = $oMenu->getCode();
            }
        }
        else
            $sMenu = getParam('sys_a2a_code');

        echo PopupBox('sys_share_popup', _t('_sys_txt_share_popup_header'), $this->_oTemplate->parseHtmlByName('designbox_share_popup.html', [
            'url' => bx_process_output($aEmbedData['url']),
            'menu' => $sMenu,
            'code' => htmlspecialchars($aEmbedData['html']),
        ]), true);
    }
    
    public function performActionEmbed ()
    {
        $aCover = $this->getPageCoverImage();
        $sTitle = $this->_getPageTitle();
        $this->_oTemplate->getEmbed($this->_oTemplate->parseHtmlByName('embed_card.html', [
            'title' => $sTitle,
            'url' => BxDolPermalinks::getInstance()->permalink($this->_aObject['url']),
            'bx_if:thumb' => [
                'condition' => $this->isPageCover() && count($aCover) > 0,
                'content' => [
                    'title' => $sTitle,
                    'url' => BxDolPermalinks::getInstance()->permalink($this->_aObject['url']),
                    'background' => BxDolCover::getInstance(BxDolTemplate::getInstance())->getCoverImageUrl($aCover)
                ],
            ],
            'bx_if:no_thumb' => [
                'condition' => count($aCover) == 0 || !$this->isPageCover(),
                'content' => [
                    'title' => $sTitle,
                    'url' => BxDolPermalinks::getInstance()->permalink($this->_aObject['url']),
                ]
            ]
        ]
        ));
    }

    /**
     * Very similar to BxBasePage::getCode
     * but adds css and js files which are needed for the corect page display
     */ 
    public function getCodeDynamic ()
    {
        // TODO: remake to use use collect* template methods

        $oTemplate = BxDolTemplate::getInstance();

        // get js&css before the page code is generated
        $aCssBefore = $oTemplate->getCss();
        $aJsBefore = $oTemplate->getJs();

        // generate page code
        $sContent = $this->getCode();

        // get js&css after the page code is generated
        $aCssAfter = $oTemplate->getCss();
        $aJsAfter = $oTemplate->getJs();

        // compare files which were added before and after page code is generated
        $f = function ($a1, $a2) {
            return strcasecmp($a1['url'], $a2['url']);
        };
        $aCssNew = array_udiff($aCssAfter, $aCssBefore, $f);
        $aJsNew = array_udiff($aJsAfter, $aJsBefore, $f);

        // add newly added js&css files in static mode
        $sCss = $sJs = '';
        foreach ($aCssNew as $a)
            $sCss .= $oTemplate->addCss($a['url'], true);
        foreach ($aJsNew as $a)
            $sJs .= $oTemplate->addJs($a['url'], true);

        return $sJs . $sCss . $sContent;
    }
    
    /**
     * Get page code with automatic caching, adding necessary css/js files and system template vars.
     * @return string.
     */
    public function getCode ()
    {
        if (bx_get('dynamic') && ($iBlockId = (int)bx_get('pageBlock'))) {

            if (self::isLockedFromUnauthenticated($this->_aObject['uri']) || !$this->_isVisiblePage($this->_aObject)) {
                header('HTTP/1.0 403 Forbidden');
                exit;
            }

            bx_alert('system', 'page_output_block', 0, false, array(
                'page_name' => $this->_sObject,
                'page_object' => $this,
                'page_query' => $this->_oQuery,
                'block_id' => (int)$iBlockId,
            ));

            header( 'Content-type:text/html;charset=utf-8' );

            BxDolTemplate::getInstance()->collectingStart();

            $s = $this->_getBlockOnlyCode($iBlockId);

            $sCss = bx_get('includedCss');
            $sJs = bx_get('includedJs');
            echo BxDolTemplate::getInstance()->collectingEndGetCode($sCss ? @json_decode($sCss) : [], $sJs ? @json_decode($sJs) : []);

            echo $s;
            exit;
        }

        if (!$this->_isVisiblePage($this->_aObject))
            return $this->_getPageAccessDeniedMsg ();

        $this->_addJsCss();

        if (!$this->_bSubPage) {
            $this->_addSysTemplateVars();

            $this->_selectMenu();

            $this->_setSubmenu(array());
        }

        if (!getParam('sys_page_cache_enable') || !$this->_aObject['cache_lifetime'] || (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST')) {
            $sPageCode = $this->_getPageCode();
        }
        else {
            $oCache = $this->_getPageCacheObject();
            $sKey = $this->_getPageCacheKey();
            $sKeyCssJs = 'cssjs_' . $sKey;

            $mixedRet = $oCache->getData($sKey, $this->_aObject['cache_lifetime']);
            $aRetCssJs = $oCache->getData($sKeyCssJs, $this->_aObject['cache_lifetime']);

            if ($mixedRet !== null && $aRetCssJs !== null) {
                BxDolTemplate::getInstance()->collectingStart();                
                BxDolTemplate::getInstance()->collectingInject($aRetCssJs['css'], $aRetCssJs['js']);
                $sPageCode = BxDolTemplate::getInstance()->collectingEndGetCode();
                $sPageCode .= $mixedRet;
            } else {

                BxDolTemplate::getInstance()->collectingStart();

                $sPageCode = $this->_getPageCode();

                $aPageCssJs = BxDolTemplate::getInstance()->collectingEndGetCode(array(), array(), 'array');

                $oCache->setData($sKey, $sPageCode, $this->_aObject['cache_lifetime']);
                $oCache->setData($sKeyCssJs, $aPageCssJs, $this->_aObject['cache_lifetime']);
            }
        }
        
        bx_alert('system', 'page_output', 0, false, array(
            'page_name' => $this->_sObject,
            'page_object' => $this,
            'page_query' => $this->_oQuery,
            'page_code' => &$sPageCode,
            'sub_page' => $this->_bSubPage,
        ));

        if (!$this->_bSubPage)
            $sPageCode .= $this->getJsScript();
        
        return $sPageCode;
    }
    
    public function getJsClassName()
    {
        return $this->_sJsClassName;
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjectName;
    }
    
    function _wrapInTagJsCode($sCode)
    {
        return "<script language=\"javascript\">\n<!--\n" . $sCode . "\n-->\n</script>";
    }

    public function getJsScript()
    {
        $sJsObjName = $this->getJsObjectName();
        $sJsObjClass = $this->getJsClassName();
        $sCode = "if(window['" . $sJsObjName . "'] == undefined) var " . $sJsObjName . " = new " . $sJsObjClass . "(" . json_encode(array(
            'sObjName' => $sJsObjName,
            'sObject' => $this->_sObject,
            'sRootUrl' => BX_DOL_URL_ROOT,
            'aHtmlIds' => $this->_aHtmlIds,
            'isStickyColumns' => $this->_bStickyColumns
        )) . ");";

        return $this->_wrapInTagJsCode($sCode);
    }

    /**
     * Is page cover enabled.
     * @return string
     */
    public function isPageCover()
    {
        $bResult = false;
        switch((int)$this->_aObject['cover']) {
            case 1: //--- Enabled for all
                $bResult = true;
                break;

            case 2: //--- Enabled for visitors only
                $bResult = !isLogged();
                break;

            case 3: //--- Enabled for members only
                $bResult = isLogged();
                break;
        }

    	return $bResult;
    }

    public function setSubPage($b = true)
    {
        $this->_bSubPage = $b;
    }

    public function setPageCover($bCover = true)
    {
        $this->_aObject['cover'] = (bool)$bCover;
    }

	public function getPageCoverImage($bTranscoder = true)
    {
    	$iId = (int)$this->_aObject['cover_image'];
    	if(empty($iId)) {
    		$iId = (int)getParam('sys_site_cover_common');
    		if(empty($iId))
    			return array();
    	}

    	$aResult = array(
    		'id' => $iId
    	);
    	if($bTranscoder)
    		$aResult['transcoder'] = BX_DOL_TRANSCODER_OBJ_COVER;
    	else 
    		$aResult['object'] = $this->_sStorage;

    	return $aResult;
    }

    public function getPageCoverParams()
    {
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if($oMenuSubmenu) {
            $aParams = $oMenuSubmenu->getPageCoverParams();
            if(!empty($aParams) && is_array($aParams))
                return $aParams;
        }

    	return array (
            'title' => $this->_getPageTitle(),
            'actions' => '',
            'bx_if:image' => array (
                'condition' => false,
                'content' => array(),
            ),
            'bx_if:icon' => array (
                'condition' => false,
                'content' => array(),
            ),
        );
    }

    /**
     * Get block title.
     * @return string
     */
    public function getBlockTitle ($aBlock)
    {
        return $this->_replaceMarkers(_t($aBlock['title']), array('block_id' => $aBlock['id']));
    }

    /**
     * Get help control if help is available for the block.
     * @return string
     */
    public function getBlockHelp ($aBlock)
    {
        if(empty($aBlock['help']) || empty(strip_tags(_t($aBlock['help']))))
            return '';

        return $this->_oTemplate->parseHtmlByName('designbox_help.html', array(
            'js_object' => $this->_sJsObjectName,
            'block_id' => $aBlock['id']
        ));
    }

    /**
     * Get code to load block asyncroniously
     * @param $aBlock block code
     * @param $iAsync if greater than 0 the it defines loading indicator for the block
     * @return HTML code string
     */
    public function getBlockAsyncCode($aBlock, $iAsync)
    {
        $aContentPlaceholder = $this->_oQuery->getPageBlockContentPlaceholder($iAsync);
        if (!$aContentPlaceholder)
            return _t('_sys_txt_error_occured');
        $oTemplate = $this->_oTemplate;
        if ('system' != $aContentPlaceholder['module']) {
            $oModule = BxDolModule::getInstance($aContentPlaceholder['module']);
            if (!$oModule)
                return _t('_sys_txt_error_occured');
            $oTemplate = $oModule->_oTemplate;
        }
        return $oTemplate->parseHtmlByName($aContentPlaceholder['template'], $aBlock);
    }

    /**
     * Get page array with all cells and blocks
     */
    public function getPage ()
    {
        return array(
            'id' => $this->_aObject['id'],
            'title' => $this->_getPageTitle(),
            'uri' => $this->_aObject['uri'],
            'author' => $this->_aObject['author'],
            'added' => $this->_aObject['added'],
            'module' => $this->getModule (),
            'type' => $this->getType (),
            'layout' => $this->_aObject['layout_id'],
            'elements' => $this->getPageBlocks (),
        );
    }

    public function getPageBlocks ()
    {
        $aFieldsUnset = array('cell_id', 'active', 'copyable', 'deletable', 'object', 'text', 'text_updated', 'title_system', 'visible_for_levels');
        $aCells = $this->_oQuery->getPageBlocks();
        foreach ($aCells as $sKey => $aCell) {
            foreach ($aCell as $i => $aBlock) {                
                if (!$this->_isVisibleBlock($aBlock)){
                    unset($aCells[$sKey][$i]);
                }
                else{
                    $this->processPageBlock($aCells[$sKey][$i], true);
                    $aBlock = $aCells[$sKey][$i];

                    $sFunc = '_getBlock' . ucfirst($aBlock['type']);
                    $aCells[$sKey][$i]['content'] = method_exists($this, $sFunc) ? $this->$sFunc($aBlock) : $aBlock['content'];
                    $aCells[$sKey][$i]['title'] = $this->getBlockTitle($aBlock);
                    foreach ($aFieldsUnset as $s)
                        unset($aCells[$sKey][$i][$s]);
                }
            }
        }
        return $aCells;
    }

    /**
     * Get page code vars
     * @return string
     */
    protected function _getPageCodeVars ()
    {
    	$aHiddenOn = array(
            pow(2, BX_DB_HIDDEN_PHONE - 1) => 'bx-def-media-phone-hide',
            pow(2, BX_DB_HIDDEN_TABLET - 1) => 'bx-def-media-tablet-hide',
            pow(2, BX_DB_HIDDEN_DESKTOP - 1) => 'bx-def-media-desktop-hide',
            pow(2, BX_DB_HIDDEN_MOBILE - 1) => 'bx-def-mobile-app-hide'
        );

        $aVars = array (
            'page_id' => 'bx-page-' . $this->_aObject['uri'],
            'bx_if:show_layout_row_dump' => array(
                'condition' => false,
                'content' => array()
            )
        );
        $aBlocks = $this->_oQuery->getPageBlocks();
        foreach ($aBlocks as $sKey => $aCell) {
            $sCell = '';
            foreach ($aCell as $aBlock) {
                $this->processPageBlock($aBlock, false);

                $sContentWithBox = $this->_getBlockCodeWithCache($aBlock, $aBlock['async']);

            	$sClassAdd = '';
                if(!empty($aBlock['class']))
                    $sClassAdd .= ' ' . $aBlock['class'];

                if(!empty($aBlock['hidden_on']))
                    foreach($aHiddenOn as $iHiddenOn => $sHiddenOnClass)
                        if((int)$aBlock['hidden_on'] & $iHiddenOn)
                            $sClassAdd .= ' ' . $sHiddenOnClass;

                if ($sContentWithBox)
                    $sCell .= $this->_oTemplate->parseHtmlByName('designbox_container.html', array(
                        'class_add' => $sClassAdd,
                        'bx_if:show_html_id' => array(
                            'condition' => true,
                            'content' => array(
                                'html_id' => 'bx-page-block-' . $aBlock['id']
                            ),
                        ),
                        'content' => $sContentWithBox
                    ));
            }
            $aVars[$sKey] = $sCell;
        }

        return $aVars;
    }

    /**
     * Process block values, especially if someting need to be overrided 
     */
    protected function processPageBlock(&$aBlock, $bApi = false) 
    {

    }

    /**
     * Get page code only.
     * @return string
     */
    protected function _getPageCode ()
    {
        $aVars = $this->_getPageCodeVars ();
        return $this->_oTemplate->parseHtmlByName($this->_aObject['template'], $aVars);
    }

    /**
     * Get one block code only.
     * @return string
     */
    protected function _getBlockOnlyCode ($iBlockId)
    {
        if (!($aBlock = $this->_oQuery->getPageBlock((int)$iBlockId)))
            return '';
        return $this->_getBlockCodeWithCache($aBlock, 0);
    }


    protected function _getBlockCodeWithCache(&$aBlock, $iAsync = 0)
    {
        if (!getParam('sys_pb_cache_enable') || !$aBlock['cache_lifetime'] || $iAsync || (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST')) {
            $sBlockCode = $this->_getBlockCode($aBlock, $iAsync);
        }
        else {
            $oCache = $this->_getBlockCacheObject();
            $sKey = $this->_getBlockCacheKey(false, $aBlock);
            $sKeyCssJs = 'cssjs_' . $sKey;

            $mixedRet = $oCache->getData($sKey, $aBlock['cache_lifetime']);
            $aRetCssJs = $oCache->getData($sKeyCssJs, $aBlock['cache_lifetime']);

            if ($mixedRet !== null && $aRetCssJs !== null) {
                BxDolTemplate::getInstance()->collectingStart();
                BxDolTemplate::getInstance()->collectingInject($aRetCssJs['css'], $aRetCssJs['js']);
                $sBlockCode = BxDolTemplate::getInstance()->collectingEndGetCode();
                $sBlockCode .= $mixedRet;
            } else {
                BxDolTemplate::getInstance()->collectingStart();

                $sBlockCode = $this->_getBlockCode($aBlock, $iAsync);

                $aBlockCssJs = BxDolTemplate::getInstance()->collectingEndGetCode(array(), array(), 'array');

                $oCache->setData($sKey, $sBlockCode, $aBlock['cache_lifetime']);
                $oCache->setData($sKeyCssJs, $aBlockCssJs, $aBlock['cache_lifetime']);
            }
        }

        return $sBlockCode;
    }

    /**
     * Get block code.
     * @return string
     */
    protected function _getBlockCode(&$aBlock, $iAsync = 0)
    {
        $sContentWithBox = '';
        $oFunctions = $this->_oTemplate->getTemplateFunctions();

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginPageBlock(_t($aBlock['title']), $aBlock['id']);

        $aDbNoTitle = [BX_DB_CONTENT_ONLY, BX_DB_PADDING_CONTENT_ONLY, BX_DB_NO_CAPTION, BX_DB_PADDING_NO_CAPTION];

        $sTitle = $this->getBlockTitle($aBlock);
        $sHelp = $this->getBlockHelp($aBlock);

        $sFunc = '_getBlock' . ucfirst($aBlock['type']);
        $bBlockVisible = $this->_isVisibleBlock($aBlock);
        if ($iAsync && $bBlockVisible) {
            $iDesignboxId = $aBlock['designbox_id'];

            $sHelpTitle = $sHelpContent = '';
            if(!in_array($iDesignboxId, $aDbNoTitle))
                $sHelpTitle = $sHelp;
            else
                $sHelpContent = $sHelp;

            $sContent = $this->getBlockAsyncCode($aBlock, $iAsync);
            $aParams = array(
                $sTitle . $sHelpTitle,
                $sContent . $sHelpContent,
                $iDesignboxId
            );
            $sContentWithBox = call_user_func_array(array($oFunctions, 'designBoxContent'), $aParams);
        } 
        elseif ($bBlockVisible && method_exists($this, $sFunc)) {
            $mixedContent = $this->$sFunc($aBlock);

            $this->_oQuery->setReadOnlyMode(true);

            if(is_array($mixedContent) && !empty($mixedContent['content'])) {
                $iDesignboxId = isset($mixedContent['designbox_id']) ? $mixedContent['designbox_id'] : $aBlock['designbox_id'];

                $sHelpTitle = $sHelpContent = '';
                if(!in_array($iDesignboxId, $aDbNoTitle))
                    $sHelpTitle = $sHelp;
                else
                    $sHelpContent = $sHelp;

                $aParams = array(
                    (isset($mixedContent['title']) ? $mixedContent['title'] : $sTitle) . $sHelpTitle,
                    $mixedContent['content'] . $sHelpContent,
                    $iDesignboxId
                );

                $mixedMenu = false;
                if(isset($mixedContent['menu']))
                    $mixedMenu = $mixedContent['menu'];
                else if(!empty($aBlock['submenu']))
                    $mixedMenu = $aBlock['submenu'];
                $aParams[] = $mixedMenu;

                if(isset($mixedContent['buttons']))
                    $aParams[] = $mixedContent['buttons'];
                else if($mixedMenu)
                    $aParams[] = (int)$aBlock['tabs'] == 1 ? true : array();

                if(isset($mixedContent['class']))
                    $aBlock['class'] = $mixedContent['class'];
            }
            else if(is_string($mixedContent) && !empty($mixedContent)) {
                $iDesignboxId = $aBlock['designbox_id'];

                $sHelpTitle = $sHelpContent = '';
                if(!in_array($iDesignboxId, $aDbNoTitle))
                    $sHelpTitle = $sHelp;
                else
                    $sHelpContent = $sHelp;

                $aParams = array(
                    $sTitle . $sHelpTitle,
                    $mixedContent . $sHelpContent,
                    $iDesignboxId
                );

                $mixedMenu = !empty($aBlock['submenu']) ? $aBlock['submenu'] : false;
                $aParams[] = $mixedMenu;

                if($mixedMenu)
                    $aParams[] = (int)$aBlock['tabs'] == 1 ? true : array();
            }

            if(!empty($aParams))
                $sContentWithBox = call_user_func_array(array($oFunctions, 'designBoxContent'), $aParams);
        }

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endPageBlock($aBlock['id'], $sContentWithBox ? false : true, false );

        return $sContentWithBox;
    }

    /**
     * Add necessary js and css files.
     */
    protected function _addJsCss()
    {
        $this->_oTemplate->addJs(array('BxDolPage.js', 'theia-sticky-sidebar/theia-sticky-sidebar.min.js', 'theia-sticky-sidebar/ResizeSensor.min.js'));
        $this->_oTemplate->addCss('page_layouts.css');
    }

    /**
     * Set system template variables, like page title, meta description, meta keywords and meta robots.
     */
    protected function _addSysTemplateVars ()
    {
        $oTemplate = BxDolTemplate::getInstance();

        $sPageTitle = $this->_getPageTitle();
        if ($sPageTitle)
            $oTemplate->setPageHeader ($sPageTitle);

        $sMetaDesc = $this->_getPageMetaDesc();
        if ($sMetaDesc)
            $oTemplate->setPageDescription ($sMetaDesc);

        $sMetaRobots = $this->_getPageMetaRobots();
        if ($sMetaRobots)
            $oTemplate->setPageMetaRobots ($sMetaRobots);

        $sMetaImage = $this->_getPageMetaImage();
        if ($sMetaImage)
            $oTemplate->addPageMetaImage($sMetaImage);

        $sMetaKeywords = $this->_getPageMetaKeywords();
        if ($sMetaKeywords)
            $oTemplate->addPageKeywords ($sMetaKeywords);
    }

    /**
     * Select menu from page properties.
     */
    protected function _selectMenu ()
    {
        BxDolMenu::setSelectedGlobal ($this->_aObject['module'], $this->_aObject['uri']);
    }

    /**
     * Set page submenu if it's specified
     */
    protected function _setSubmenu ($aParams)
    {
        if(empty($this->_aObject['submenu']) || $this->_aObject['submenu'] == 'disabled')
            return;

        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if(!$oMenuSubmenu)
            return;

        $oMenuSubmenu->setObjectSubmenu($this->_aObject['submenu'], $aParams);
    }

    /**
     * Get content for 'raw' block type.
     * @return string
     */
    protected function _getBlockRaw ($aBlock)
    {
        $s = '<div class="bx-page-raw-container">' . BxDolTemplate::getInstance()->parseHtmlByContent($aBlock['content'], array()) . '</div>';
        $s = $this->_replaceMarkers($s, array('block_id' => $aBlock['id']));
        $s = bx_process_macros($s);
        return $s;
    }

    /**
     * Get content for 'custom' block type.
     * @return string
     */
    protected function _getBlockCustom ($aBlock)
    {
        $s = '<div class="bx-page-custom-container">' . BxDolTemplate::getInstance()->parseHtmlByContent($aBlock['content'], array()) . '</div>';
        $s = $this->_replaceMarkers($s, array('block_id' => $aBlock['id']));
        $s = bx_process_macros($s);
        return $s;
    }

    /**
     * Get content for 'html' block type.
     * @return string
     */
    protected function _getBlockHtml ($aBlock)
    {
        $s = '<div class="bx-page-html-container">' . $aBlock['content'] . '</div>';
        $s = $this->_replaceMarkers($s, array('block_id' => $aBlock['id']));
        $s = bx_process_macros($s);
        return $s;
    }

    /**
     * Get content for 'wiki' block type.
     * @return string
     */
    protected function _getBlockWiki ($aBlock)
    {
        $oWiki = BxDolWiki::getObjectInstance($this->_aObject['module']);
        if (!$oWiki) {
            $sContent = _t('_sys_wiki_error_missing_wiki_object', $this->_aObject['module']);
        } 
        else {
            $sContent = $oWiki->getBlockContent($aBlock['id'], false, (int)bx_get($aBlock['id'].'rev') ? (int)bx_get($aBlock['id'].'rev') : false);
        }

        $s = '<div id="bx-page-wiki-container-' . $aBlock['id'] . '" class="bx-page-wiki-container markdown-body bx-def-vanilla-html">' . $sContent . '</div>';
        $s = $this->_replaceMarkers($s, array('block_id' => $aBlock['id']));
        $s = bx_process_macros($s);
        return $s;
    }

    /**
     * Get content for 'lang' block type.
     * @return string
     */
    protected function _getBlockLang ($aBlock)
    {
        $s = '<div class="bx-page-lang-container">' . _t(trim($aBlock['content'])) . '</div>';
        $s = $this->_replaceMarkers($s, array('block_id' => $aBlock['id']));
        $s = bx_process_macros($s);
        return $s;
    }

    /**
     * Get content for 'image' block type.
     * @return string
     */
    protected function _getBlockImage ($aBlock)
    {
        if (empty($aBlock['content']))
            return false;

        list($iFileId, $sAlign ) = explode('#', $aBlock['content']);
        $iFileId = (int)$iFileId;
        if (!$iFileId)
            return false;

        $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);
        if (!$oStorage)
            return false;

        $sUrl = $oStorage->getFileUrlById($iFileId);
        if (!$sUrl)
            return false;

        $sStyleAdd = '';
        if ('center' == $sAlign || 'left' == $sAlign || 'right' == $sAlign)
            $sStyleAdd = 'style="text-align:' . $sAlign . '"';

        return '<div class="bx-page-image-container" ' . $sStyleAdd . '><img src="' . $sUrl . '" /></div>';
    }

    /**
     * Get content for 'rss' block type.
     * @return string
     */
    protected function _getBlockRss ($aBlock)
    {
        if (empty($aBlock['content']))
            return false;

        list( $sUrl, $iNum ) = explode('#', $aBlock['content']);
        $iNum = (int)$iNum;

        return BxDolRss::getObjectInstance('sys_page_block')->getHolder($aBlock['id'], $iNum);
    }

    /**
     * Get content for 'menu' block type.
     * @return string
     */
    protected function _getBlockMenu ($aBlock)
    {
        $oMenu = BxTemplMenu::getObjectInstance($aBlock['content']);
        return $oMenu ? $oMenu->getCode () : '';
    }

    /**
     * Get content for 'service' block type.
     * @return string
     */
    protected function _getBlockService ($aBlock)
    {
        return BxDolService::callSerialized($aBlock['content'], $this->_aMarkers);
    }

    /**
     * Get page title.
     * @return string
     */
    protected function _getPageTitle()
    {
        return $this->_replaceMarkers(_t($this->_aObject['title']));
    }

    /**
     * Get page meta description.
     * @return string
     */
    protected function _getPageMetaDesc()
    {
        return $this->_replaceMarkers(_t($this->_aObject['meta_description']));
    }

    /**
     * Get page meta image.
     * @return string
     */
    protected function _getPageMetaImage()
    {
        return '';
    }
    
    /**
     * Get page meta keywords.
     * @return string
     */
    protected function _getPageMetaKeywords()
    {
        return $this->_replaceMarkers(_t($this->_aObject['meta_keywords']));
    }

    /**
     * Get page meta robots.
     * @return string
     */
    protected function _getPageMetaRobots()
    {
        return _t($this->_aObject['meta_robots']);
    }

    /**
     * Get access denied message HTML.
     * @return string
     */
    protected function _getPageAccessDeniedMsg ()
    {
        return MsgBox(_t('_Access denied'));
    }

    /**
     * Get page cache object.
     * @return cache object instance
     */
    protected function _getPageCacheObject ()
    {
        return $this->_getCacheObject ();
    }

    /**
     * Get block cache object.
     * @return cache object instance
     */
    protected function _getBlockCacheObject ()
    {
        return $this->_getCacheObject ('Block', 'pb');
    }

    protected function _getCacheObject ($sSuffixObj = 'Page', $sSuffixParam = 'page')
    {
        $sObj = '_o' . $sSuffixObj . 'CacheObject';
        $sParam = 'sys_' . $sSuffixParam . '_cache_engine';

        if ($this->{$sObj} != null) {
            return $this->{$sObj};
        } else {
            $sEngine = getParam($sParam);
            $this->{$sObj} = bx_instance ('BxDolCache' . $sEngine);
            if (!$this->{$sObj}->isAvailable())
                $this->{$sObj} = bx_instance ('BxDolCacheFile');
            return $this->{$sObj};
        }
    }

    /**
     * Get page cache key.
     * @param $isPrefixOnly return key prefix only.
     * @return string
     */
    protected function _getPageCacheKey ($isPrefixOnly = false)
    {
        $s = 'page_' . $this->_aObject['object'] . '_';
        if ($isPrefixOnly)
            return $s;
        $s .= $this->_getPageCacheParams ();
        $s .= bx_site_hash() . '.php';
        return $s;
    }

    /**
     * Get block cache key.
     * @param $isPrefixOnly return key prefix only.
     * @return string
     */
    protected function _getBlockCacheKey ($isPrefixOnly = false, $aBlock = array())
    {
        $s = $this->_getPageCacheKey(true) . 'block_';
        if ($isPrefixOnly)
            return $s;
        $s .= ($aBlock ? $aBlock['id'] : 0) . '_' . $this->_getPageCacheParams ();
        $s .= bx_site_hash() . '.php';
        return $s;
    }

    /**
     * Additional cache key. In the case of dynamic page.
     * For example - profile page, where each profile has own page.
     * @return string
     */
    protected function _getPageCacheParams ()
    {
        return md5(serialize($_GET) . bx_lang_name());
    }

    /**
     * Clean page cache.
     * @param $isDelAllWithPagePrefix delete cache by prefix, it can be used for dynamic pages, like profile view, where for each profile separate cache is generated.
     * @return string
     */
    protected function cleanCache ($isDelAllWithPagePrefix = false)
    {
        $a = ['Page', 'Block'];
        $bRet = false;
        foreach ($a as $s) {
            $oCache = $this->{'_get' . $s . 'CacheObject'} ();
            $sKey = $this->{'_get'. $s . 'CacheKey'} ($isDelAllWithPagePrefix);

            if ($isDelAllWithPagePrefix)
                $bRet = $bRet && $oCache->removeAllByPrefix($sKey);
            else
                $bRet = $bRet && $oCache->delData($sKey);
        }
        return $bRet;
    }
}

/** @} */
