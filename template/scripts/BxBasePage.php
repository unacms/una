<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
    protected $_oPageCacheObject = null;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /**
     * Get page code with automatic caching, adding necessary css/js files and system template vars.
     * @return string.
     */
    public function getCode ()
    {
        if (bx_get('dynamic') && ($iBlockId = (int)bx_get('pageBlock'))) {

            if (!$this->_isVisiblePage($this->_aObject)) {
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
            echo $this->_getBlockOnlyCode($iBlockId);
            exit;
        }

        if (!$this->_isVisiblePage($this->_aObject))
            return $this->_getPageAccessDeniedMsg ();

        $this->_addJsCss();

        $this->_addSysTemplateVars();

        $this->_selectMenu();

        if (!getParam('sys_page_cache_enable') || !$this->_aObject['cache_lifetime'])
            return $this->_getPageCode();

        $oCache = $this->_getPageCacheObject();
        $sKey = $this->_getPageCacheKey();

        $mixedRet = $oCache->getData($sKey, $this->_aObject['cache_lifetime']);

        if ($mixedRet !== null) {
            return $mixedRet;
        } else {
            $sPageCode = $this->_getPageCode();
            $oCache->setData($sKey, $sPageCode, $this->_aObject['cache_lifetime']);
        }

        bx_alert('system', 'page_output', 0, false, array(
            'page_name' => $this->_sObject,
            'page_object' => $this,
            'page_query' => $this->_oQuery,
            'page_code' => &$sPageCode,
        ));

        return $sPageCode;
    }

    /**
     * Get block title.
     * @return string
     */
    public function getBlockTitle ($aBlock)
    {
        return $this->_replaceMarkers(_t($aBlock['title']));
    }

    /**
     * Get page code only.
     * @return string
     */
    protected function _getPageCode ()
    {
    	$aHiddenOn = array(
			pow(2, BX_DB_HIDDEN_PHONE - 1) => 'bx-def-media-phone-hide',
			pow(2, BX_DB_HIDDEN_TABLET - 1) => 'bx-def-media-tablet-hide',
			pow(2, BX_DB_HIDDEN_DESKTOP - 1) => 'bx-def-media-desktop-hide'
		);

        $aVars = array ();
        $aBlocks = $this->_oQuery->getPageBlocks();
        foreach ($aBlocks as $sKey => $aCell) {
            $sCell = '';
            foreach ($aCell as $aBlock) {
                $sContentWithBox = $this->_getBlockCode($aBlock);

            	$sHiddenOn = '';
		    	if(!empty($aBlock['hidden_on']))
		    		foreach($aHiddenOn as $iHiddenOn => $sClass)
		    			if((int)$aBlock['hidden_on'] & $iHiddenOn)
		    				$sHiddenOn .= ' ' . $sClass;
    	
    	
                if ($sContentWithBox)
                    $sCell .= '<div class="bx-page-block-container bx-def-padding-sec-topbottom' . $sHiddenOn . '" id="bx-page-block-' . $aBlock['id'] . '">' . $sContentWithBox . '</div>';
            }
            $aVars[$sKey] = $sCell;
        }

        return $this->_oTemplate->parseHtmlByName($this->_aObject['template'], $aVars);
    }

    /**
     * Get one block code only.
     * @return string
     */
    protected function _getBlockOnlyCode ($iBlockId)
    {
        $aBlock = $this->_oQuery->getPageBlock((int)$iBlockId);
        return $this->_getBlockCode($aBlock);
    }

    /**
     * Get block code.
     * @return string
     */
    protected function _getBlockCode($aBlock)
    {
        $sContentWithBox = '';

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginPageBlock(_t($aBlock['title']), $aBlock['id']);

        if ($this->_isVisibleBlock($aBlock)) {

            $sFunc = '_getBlock' . ucfirst($aBlock['type']);
            $mixedContent = $this->$sFunc($aBlock);

            $sTitle = $this->getBlockTitle($aBlock);

            if (is_array($mixedContent) && !empty($mixedContent['content'])) {
				$sContentWithBox = DesignBoxContent(
                	isset($mixedContent['title']) ? $mixedContent['title'] : $sTitle,
                    $mixedContent['content'],
                    isset($mixedContent['designbox_id']) ? $mixedContent['designbox_id'] : $aBlock['designbox_id'],
                    isset($mixedContent['menu']) ? $mixedContent['menu'] : false
				);
            } 
            elseif (is_string($mixedContent) && !empty($mixedContent)) {                    
                $sContentWithBox = DesignBoxContent($sTitle, $mixedContent, $aBlock['designbox_id']);
            }
        }

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endPageBlock($aBlock['id'], $sContentWithBox ? false : true, false );

        return $sContentWithBox;
    }

    /**
     * Add necessary js and css files.
     */
    protected function _addJsCss()
    {
        $this->_oTemplate->addCss('page_layouts.css');
    }

    /**
     * Set system template variables, like page title, meta description, meta keywords and meta robots.
     */
    protected function _addSysTemplateVars ()
    {
        $sPageTitle = $this->_getPageTitle();
        if ($sPageTitle)
            BxDolTemplate::getInstance()->setPageHeader ($sPageTitle);

        $sMetaDesc = $this->_getPageMetaDesc();
        if ($sMetaDesc)
            BxDolTemplate::getInstance()->setPageDescription ($sMetaDesc);

        $sMetaKeywords = $this->_getPageMetaKeywords();
        if ($sMetaKeywords)
            BxDolTemplate::getInstance()->addPageKeywords ($sMetaKeywords);

        $sMetaRobots = $this->_getPageMetaRobots();
        if ($sMetaRobots)
            BxDolTemplate::getInstance()->setPageMetaRobots ($sMetaRobots);
    }

    /**
     * Select menu from page properties.
     */
    protected function _selectMenu ()
    {
        BxDolMenu::setSelectedGlobal ($this->_aObject['module'], $this->_aObject['uri']);
    }

    /**
     * Get content for 'raw' block type.
     * @return string
     */
    protected function _getBlockRaw ($aBlock)
    {
        return '<div class="bx-page-raw-container">' . $this->_replaceMarkers($aBlock['content']) . '</div>';
    }

    /**
     * Get content for 'html' block type.
     * @return string
     */
    protected function _getBlockHtml ($aBlock)
    {
        return '<div class="bx-page-html-container">' . $this->_replaceMarkers($aBlock['content']) . '</div>';
    }

    /**
     * Get content for 'lang' block type.
     * @return string
     */
    protected function _getBlockLang ($aBlock)
    {
        return '<div class="bx-page-lang-container">' . $this->_replaceMarkers(_t(trim($aBlock['content']))) . '</div>';
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
        if ($this->_oPageCacheObject != null) {
            return $this->_oPageCacheObject;
        } else {
            $sEngine = getParam('sys_page_cache_engine');
            $this->_oPageCacheObject = bx_instance ('BxDolCache' . $sEngine);
            if (!$this->_oPageCacheObject->isAvailable())
                $this->_oPageCacheObject = bx_instance ('BxDolCacheFile');
            return $this->_oPageCacheObject;
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
     * Additional cache key. In the case of dynamic page.
     * For example - profile page, where each profile has own page.
     * @return string
     */
    protected function _getPageCacheParams ()
    {
        return '';
    }

    /**
     * Clean page cache.
     * @param $isDelAllWithPagePrefix delete cache by prefix, it can be used for dynamic pages, like profile view, where for each profile separate cache is generated.
     * @return string
     */
    protected function cleanCache ($isDelAllWithPagePrefix = false)
    {
        $oCache = $this->_getPageCacheObject ();
        $sKey = $this->_getPageCacheKey($isDelAllWithPagePrefix);

        if ($isDelAllWithPagePrefix)
            return $oCache->removeAllByPrefix($sKey);
        else
            return $oCache->delData($sKey);
    }
}

/** @} */
