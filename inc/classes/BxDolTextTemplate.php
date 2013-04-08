<?php

// TODO: decide later what to do with text* classes and module, it looks like they will stay and text modules will be still based on it, but some refactoring is needed


/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolModuleTemplate');

class BxDolTextTemplate extends BxDolModuleTemplate {
    var $_oModule;

    var $oPaginate;
    var $sCssPrefix;

    function BxDolTextTemplate(&$oConfig, &$oDb) {
        parent::BxDolModuleTemplate($oConfig, $oDb);
        $this->_aTemplates = array('comments');

        $this->_oModule = null;
        $this->oPaginate = null;
        $this->sCssPrefix = '';
    }
    function init(&$oModule) {
        $this->_oModule = $oModule;

        /*
        $this->oPaginate = new BxDolPaginate(array(
            'page_url' => 'javascript:void(0);',
            'start' => 0,
            'per_page' => $this->_oConfig->getPerPage(),
            'per_page_step' => 2,
            'per_page_interval' => 3,
            'on_change_page' => $this->_oConfig->getJsObject() . '.changePage({start}, {per_page})'
        ));
        */
    }
    function displayAdminBlock($aParams) {
        $oSearchResult = $aParams['search_result_object'];
        unset($aParams['search_result_object']);

        $sModuleUri = $this->_oConfig->getUri();
        $aButtons = array(
            $sModuleUri . '-publish' => _t('_' . $sModuleUri . '_lcaption_publish'),
            $sModuleUri . '-unpublish' => _t('_' . $sModuleUri . '_lcaption_unpublish'),
            $sModuleUri . '-featured' => _t('_' . $sModuleUri . '_lcaption_featured'),
            $sModuleUri . '-unfeatured' => _t('_' . $sModuleUri . '_lcaption_unfeatured'),
            $sModuleUri . '-delete' => _t('_' . $sModuleUri . '_lcaption_delete')
        );

        $aResult = array(
            'include_css' => $this->addCss(array('view.css', 'cmts.css'), true),
            'include_js_content' => $this->getViewJs(),
            'filter' => $oSearchResult->showAdminFilterPanel($this->_oDb->unescape($aParams['filter_value']), $sModuleUri . '-filter-txt', $sModuleUri . '-filter-chb', $sModuleUri . '-filter'),
            'content' => $this->displayList($aParams),
            'control' => $oSearchResult->showAdminActionsPanel($this->sCssPrefix . '-view-admin', $aButtons, $sModuleUri . '-ids')
        );

        return $this->addJs(array('main.js'), true) . $this->parseHtmlByName('admin.html', $aResult);
    }
    function displayBlock($aParams) {
        $bShowEmpty = isset($aParams['show_empty']) ? $aParams['show_empty'] : true;

        $aResult = array(
            'include_js_content' => $this->getViewJs(),
            'content' => $this->displayList($aParams),
        );

        if(!$bShowEmpty && empty($aResult['content']))
            return "";

        $this->addJs(array('main.js'));
        $this->addCss(array('view.css'));
        return $this->parseHtmlByName('view.html', $aResult);
    }
    function displayList($aParams) {
        $sSampleType = $aParams['sample_type'];
        $iViewerType = $aParams['viewer_type'];
        $iStart = isset($aParams['start']) ? (int)$aParams['start'] : -1;
        $iPerPage = isset($aParams['count']) ? (int)$aParams['count'] : -1;
        $bShowEmpty = isset($aParams['show_empty']) ? $aParams['show_empty'] : true;
        $bAdminPanel = $iViewerType == BX_TD_VIEWER_TYPE_ADMIN && ((isset($aParams['admin_panel']) && $aParams['admin_panel']) || $sSampleType == 'admin');

        $sModuleUri = $this->_oConfig->getUri();
        $aEntries = $this->_oDb->getEntries($aParams);
        if(empty($aEntries))
            return $bShowEmpty ? MsgBox(_t('_' . $sModuleUri . '_msg_no_results')) : "";

        $oTags = new BxDolTags();
        $oCategories = new BxDolCategories();

        //--- Language translations ---//
        $sLKLinkPublish = _t('_' . $sModuleUri . '_lcaption_publish');
        $sLKLinkEdit = _t('_' . $sModuleUri . '_lcaption_edit');
        $sLKLinkDelete = _t('_' . $sModuleUri . '_lcaption_delete');

        $sBaseUri = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();
        $sJsMainObject = $this->_oConfig->getJsObject();

        $aResult['sample'] = $sSampleType;
        $aResult['bx_repeat:entries'] = array();
        foreach($aEntries as $aEntry) {
            $sVotes = "";

            if($this->_oConfig->isVotesEnabled()) {
                $oVotes = $this->_oModule->_createObjectVoting($aEntry['id']);
                $sVotes = $oVotes->getJustVotingElement(0, $aEntry['id']);
            }

            $aTags = $oTags->explodeTags($aEntry['tags']);
            $aCategories = $oCategories->explodeTags($aEntry['categories']);

            $aTagItems = array();
            foreach($aTags as $sTag) {
                $sTag = trim($sTag);
                $aTagItems[] = array('href' => $sBaseUri . 'tag/' . title2uri($sTag), 'title' => $sTag);
            }

            $aCategoryItems = array();
            foreach($aCategories as $sCategory) {
                $sCategory = trim($sCategory);
                $aCategoryItems[] = array('href' => $sBaseUri . 'category/' . title2uri($sCategory), 'title' => $sCategory);
            }

            $aResult['bx_repeat:entries'][] = array(
                'id' => $this->_oConfig->getSystemPrefix() . $aEntry['id'],
                'caption' => str_replace("$", "&#36;", $aEntry['caption']),
                'class' => !in_array($sSampleType, array('view')) ? ' ' . $this->sCssPrefix . '-text-snippet' : '',
                'date' => getLocaleDate($aEntry['when_uts'], BX_DOL_LOCALE_DATE),
                'comments' => (int)$aEntry['cmts_count'],
                'bx_repeat:categories' => $aCategoryItems,
                'bx_repeat:tags' => $aTagItems,
                'content' => str_replace("$", "&#36;", $aEntry['content']),
                'link' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aEntry['uri'],
                'voting' => $sVotes,
                'bx_if:checkbox' => array(
                    'condition' => $bAdminPanel,
                    'content' => array(
                        'id' => $aEntry['id']
                    ),
                ),
                'bx_if:status' => array(
                    'condition' => $iViewerType == BX_TD_VIEWER_TYPE_ADMIN,
                    'content' => array(
                        'status' => _t('_' . $sModuleUri . '_status_' . $aEntry['status'])
                    ),
                ),
                'bx_if:featured' => array(
                    'condition' => $iViewerType == BX_TD_VIEWER_TYPE_ADMIN && (int)$aEntry['featured'] == 1,
                    'content' => array(),
                ),
                'bx_if:edit_link' => array (
                    'condition' => $iViewerType == BX_TD_VIEWER_TYPE_ADMIN,
                    'content' => array(
                        'edit_link_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'admin/' . $aEntry['uri'],
                        'edit_link_caption' => $sLKLinkEdit,
                    )
                )
            );
        };

        $aResult['paginate'] = '';
        if(!in_array($sSampleType, array('id', 'uri', 'view', 'search_unit'))) {
            if(!empty($sSampleType))
                $this->_updatePaginate($aParams);

            $aResult['paginate'] = $this->oPaginate->getPaginate($iStart, $iPerPage);
        }

        $aResult['loading'] = LoadingBox($sModuleUri . '-' . $sSampleType . '-loading');

        $sRes = $this->parseHtmlByName('list.html', $aResult);
        return $sRes;
    }
    function getViewJs($bWrap = false) {
        $sJsMainClass = $this->_oConfig->getJsClass();
        $sJsMainObject = $this->_oConfig->getJsObject();
        ob_start();
?>
        var <?=$sJsMainObject; ?> = new <?=$sJsMainClass; ?>({
            sSystem: '<?=$this->_oConfig->getSystemPrefix(); ?>',
            sActionUrl: '<?=BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
            sObjName: '<?=$sJsMainObject; ?>',
            sAnimationEffect: '<?=$this->_oConfig->getAnimationEffect(); ?>',
            iAnimationSpeed: '<?=$this->_oConfig->getAnimationSpeed(); ?>'
        });
<?
        $sContent = ob_get_clean();
        return $bWrap ? $this->_wrapInTagJsCode($sContent) : $sContent;
    }
    function getPageCode(&$aParams) {
    	check_logged();

	    $this->setPageNameIndex(isset($aParams['index']) ? (int)$aParams['index'] : 0);
	    $this->setPageParams(array(
	        'css_name' => isset($aParams['css']) ? $aParams['css'] : '',
	        'js_name' => isset($aParams['js']) ? $aParams['js'] : '',
            'extra_js' => isset($aParams['extra_js']) ? $aParams['extra_js'] : '',
	        'header' => isset($aParams['title']['page']) ? $aParams['title']['page'] : '',
	        'header_text' => isset($aParams['title']['block']) ? $aParams['title']['block'] : '',
	    ));

        if(isset($aParams['content']))
            foreach($aParams['content'] as $sKey => $sValue)
                $this->setPageContent($sKey, $sValue);

        if(isset($aParams['breadcrumb']))
            $GLOBALS['oTopMenu']->setCustomBreadcrumbs($aParams['breadcrumb']);

        PageCode($this);
    }
    function getPageCodeAdmin(&$aParams) {
        bx_import('BxDolStudioTemplate');
        $oTemplate = BxDolStudioTemplate::getInstance();
        
        $oTemplate->setPageNameIndex(isset($aParams['index']) ? (int)$aParams['index'] : 9);
        $oTemplate->setPageParams(array(
            'css_name' => isset($aParams['css']) ? $aParams['css'] : '',
            'js_name' => isset($aParams['js']) ? $aParams['js'] : '',
            'header' => isset($aParams['title']['page']) ? $aParams['title']['page'] : '',
        ));

        if(isset($aParams['content']))
            foreach($aParams['content'] as $sKey => $sValue)
                $oTemplate->setPageContent($sKey, $sValue);

        $oTemplate->getPageCode();
    }

    protected function _updatePaginate($aParams) {
        switch($aParams['sample_type']) {
            default:
                $this->oPaginate->setCount($this->_oDb->getCount($aParams));
                $this->oPaginate->setOnChangePage($this->_oConfig->getJsObject() . '.changePage({start}, {per_page}, \'' . $aParams['sample_type'] . '\')');
        }
    }
}
?>
