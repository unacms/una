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

bx_import('BxDolViews');
bx_import('BxDolAlerts');
bx_import('BxDolModule');
bx_import('BxDolPaginate');
//bx_import('BxDolCategories');
bx_import('BxDolRssFactory');
//bx_import('BxDolSubscription');
//bx_import('BxTemplTagsModule');
//bx_import('BxTemplCategoriesModule');

require_once(BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php');

class BxDolTextModule extends BxDolModule {
    var $_oTextData;
    var $_oPrivacy;

    function BxDolTextModule($aModule) {
        parent::BxDolModule($aModule);

        $this->_oConfig->init($this->_oDb);
        $this->_oTemplate->init($this);

        $sClassPrefix = $this->_oConfig->getClassPrefix();

        $sClassName = $sClassPrefix . 'Privacy';
        $this->_oPrivacy = class_exists($sClassName) ? new $sClassName($this) : null;

        $sClassName = $sClassPrefix . 'Data';
        $this->_oTextData = class_exists($sClassName) ? new $sClassName($this) : null;
    }

    function getCalendar($iYear = 0, $iMonth = 0) {
        $sClassName = $this->_oConfig->getClassPrefix() . 'Calendar';
        return new $sClassName($iYear, $iMonth, $this->_oDb, $this->_oConfig);
    }

    function getSearchResult() {
        $sClassName = $this->_oConfig->getClassPrefix() . 'SearchResult';
        return new $sClassName($this);
    }

    function getSearchContent($aRestrictions, $sUri, $iPage = 1, $iPerPage = 0) {
        $iPerPage = !empty($iPerPage) ? $iPerPage : $this->_oConfig->getPerPage();

        $sClassName = $this->_oConfig->getClassPrefix() . 'SearchResult';
        $oSearchResult = new $sClassName($this);
        foreach($aRestrictions as $sKey => $mixedValue)
            if(is_array($mixedValue))
                $oSearchResult->aCurrent['restriction'][$sKey] = $mixedValue;
            else if(is_string($mixedValue))
                $oSearchResult->aCurrent['restriction'][$sKey]['value'] = $mixedValue;
        $oSearchResult->aCurrent['paginate']['forcePage'] = $iPage;
        $oSearchResult->aCurrent['paginate']['perPage'] = $iPerPage;
        $sCode = $oSearchResult->displayResultBlock();

        if(!empty($sCode)) {
            $oPaginate = new BxDolPaginate(array(
                'page_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $sUri . '{page}/{per_page}',
                'count' => $oSearchResult->aCurrent['paginate']['totalNum'],
                'per_page' => $iPerPage,
                'page' => $iPage,
                'per_page_changer' => true,
                'page_reloader' => false,
                'on_change_page' => '',
                'on_change_per_page' => '',
            ));
            $sCode .= $oPaginate->getPaginate();
        }

        return $sCode;
    }
    function getCategoryContent($sCategory, $iPage = 1, $iPerPage = 0) {
        $sCategory = uri2title(process_db_input($sCategory, BX_TAGS_STRIP));
        $iPage = (int)$iPage;
        $iPerPage = (int)$iPerPage;

        $sCategoryDisplay = $GLOBALS['MySQL']->unescape($sCategory);
        return array(
            $sCategoryDisplay,
            $this->getSearchContent(array('category' => $sCategory), 'category/' . title2uri($sCategoryDisplay) . '/', $iPage, $iPerPage)
        );
    }
    function getTagContent($sTag = '', $iPage = 1, $iPerPage = 0) {
        $sTag = uri2title(process_db_input($sTag, BX_TAGS_STRIP));
        $iPage = (int)$iPage;
        $iPerPage = (int)$iPerPage;

        $sTagDisplay = $GLOBALS['MySQL']->unescape($sTag);
        return array(
            $sTagDisplay,
            $this->getSearchContent(array('tag' => $sTag), 'tag/' . title2uri($sTagDisplay) . '/', $iPage, $iPerPage)
        );
    }
    function getCalendarContent($iYear, $iMonth, $iDay, $iPage, $iPerPage) {
        $iYear = (int)$iYear;
        $iMonth = (int)$iMonth;
        $iDay = (int)$iDay;
        $iPage = (int)$iPage;
        $iPerPage = (int)$iPerPage;

        return $this->getSearchContent(
            array(
                'calendar-min' => array('value' => "UNIX_TIMESTAMP('" . $iYear . "-" . $iMonth . "-" . $iDay . " 00:00:00')", 'field' => 'when', 'operator' => '>=', 'no_quote_value' => true),
                'calendar-max' => array('value' => "UNIX_TIMESTAMP('" . $iYear . "-" . $iMonth . "-" . $iDay . " 23:59:59')", 'field' => 'when', 'operator' => '<=', 'no_quote_value' => true),
            ),
            'calendar/' . $iYear . '/' . $iMonth . '/' . $iDay . '/',
            $iPage,
            $iPerPage
        );
    }

    /**
     * Admin Settings Methods
     */
    function getSettingsForm($mixedResult) {
        $sUri = $this->_oConfig->getUri();

        $iId = (int)$this->_oDb->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name`='" . ucwords(str_replace('_', ' ', $sUri)) . "'");
        if(empty($iId))
            return MsgBox('_' . $sUri . '_msg_no_results');

        $oSettings = new BxDolAdminSettings($iId, BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'admin');
        $sResult = $oSettings->getForm();

        if($mixedResult !== true && !empty($mixedResult))
            $sResult = $mixedResult . $sResult;

        return $sResult;
    }
    function setSettings($aData) {
        $sUri = $this->_oConfig->getUri();

        $iId = (int)$this->_oDb->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name`='" . ucwords(str_replace('_', ' ', $sUri)) . "'");
        if(empty($iId))
           return MsgBox(_t('_' . $sUri . '_msg_no_results'));

        $oSettings = new BxDolAdminSettings($iId);
        return $oSettings->saveChanges($_POST);
    }

    /**
     * Service methods
     */
    function servicePostBlock() {
        $aVariables = array(
           'include_css' => $this->_oTemplate->addCss(array('post.css'), true),
           'post_form' => $this->_oTextData->getPostForm()
        );
        return $this->_oTemplate->parseHtmlByName('post.html', $aVariables);
    }
    function serviceEditBlock($mixed) {
        if(is_string($mixed))
           $aEntry = $this->_oDb->getEntries(array('sample_type' => 'uri', 'uri' => $mixed));
        else if(is_int($mixed))
           $aEntry = $this->_oDb->getEntries(array('sample_type' => 'id', 'id' => $mixed));

        $aVariables = array(
           'include_css' => $this->_oTemplate->addCss(array('post.css'), true),
           'post_form' => $this->_oTextData->getEditForm($aEntry)
        );
        return $this->_oTemplate->parseHtmlByName('post.html', $aVariables);
    }
    function serviceViewBlock($sUri) {
        $aParams = is_numeric($sUri) ? array('sample_type' => 'id', 'id' => $sUri) : array('sample_type' => 'uri', 'uri' => $sUri);
        $aEntry = $this->_oDb->getEntries($aParams);

        $sModuleUri = $this->_oConfig->getUri();
        $oView = new BxDolViews('bx_' . $sModuleUri, $aEntry['id']);
        $oView->makeView();

        $this->_oTemplate->setPageTitle($aEntry['caption']);
        $GLOBALS['oTopMenu']->setCustomSubHeader($aEntry['caption']);
        $GLOBALS['oTopMenu']->setCustomBreadcrumbs(array(
            _t('_' . $sModuleUri . '_top_menu_item') => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'index/',
            $aEntry['caption'] => '')
        );
        return $this->_oTemplate->displayList(array(
           'sample_type' => 'view',
           'viewer_type' => $this->_oTextData->getViewerType(),
           'uri' => $aEntry['uri']
        ));
    }
    function serviceCommentBlock($sUri) {
        $aParams = is_numeric($sUri) ? array('sample_type' => 'id', 'id' => $sUri) : array('sample_type' => 'uri', 'uri' => $sUri);
        $aEntry = $this->_oDb->getEntries($aParams);
        if(!$this->_oConfig->isCommentsEnabled() || !$this->_isCommentsAllowed($aEntry))
            return '';

        $sModuleUri = $this->_oConfig->getUri();
        if($aEntry['status'] != BX_TD_STATUS_ACTIVE)
            return MsgBox(_t('_' . $sModuleUri . '_msg_no_results'));

        $this->_oTemplate->addCss(array('cmts.css'));
        $oComments = $this->_createObjectCmts($aEntry['id']);
        return $oComments->getCommentsFirst('comment');
    }
    function serviceVoteBlock($sUri) {
        $aParams = is_numeric($sUri) ? array('sample_type' => 'id', 'id' => $sUri) : array('sample_type' => 'uri', 'uri' => $sUri);
        $aEntry = $this->_oDb->getEntries($aParams);
        if(!$this->_oConfig->isVotesEnabled() || !$this->_isVotesAllowed($aEntry))
            return '';

        $sModuleUri = $this->_oConfig->getUri();
        if($aEntry['status'] != BX_TD_STATUS_ACTIVE)
            return MsgBox(_t('_' . $sModuleUri . '_msg_no_results'));

        $oVotes = $this->_createObjectVoting($aEntry['id']);
        return $oVotes->getBigVoting();
    }
    function serviceActionBlock($sUri) {
        $aParams = is_numeric($sUri) ? array('sample_type' => 'id', 'id' => $sUri) : array('sample_type' => 'uri', 'uri' => $sUri);
        $aEntry = $this->_oDb->getEntries($aParams);

        $sModuleUri = $this->_oConfig->getUri();
        if($aEntry['status'] != BX_TD_STATUS_ACTIVE)
            return MsgBox(_t('_' . $sModuleUri . '_msg_no_results'));

        $sModuleUri = $this->_oConfig->getUri();
        $oSubscription = new BxDolSubscription();
        $aButton = $oSubscription->getButton($this->getUserId(), 'bx_' . $sModuleUri, '', $aEntry['id']);

        $aReplacement['sbs_' . $sModuleUri . '_title'] = $aButton['title'];
        $aReplacement['sbs_' . $sModuleUri . '_script'] = $aButton['script'];

        if($this->_isDeleteAllowed()) {
            $this->_oTemplate->addJsTranslation(array('_' . $sModuleUri . '_msg_success_delete', '_' . $sModuleUri . '_msg_failed_delete'));

            $aReplacement['del_' . $sModuleUri . '_title'] = _t('_' . $sModuleUri . '_actions_delete');
            $aReplacement['del_' . $sModuleUri . '_script'] = $this->_oConfig->getJsObject() . '.deleteEntry(' . $aEntry['id'] . ')';
        }
        else
            $aReplacement['del_' . $sModuleUri . '_title'] = '';

        return $oSubscription->getData() . $GLOBALS['oFunctions']->genObjectsActions($aReplacement, 'bx_' . $this->_oConfig->getUri());
    }
    function serviceAdminBlock($iStart = 0, $iPerPage = 0, $sFilterValue = '') {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getPerPage();

        return $this->_oTemplate->displayAdminBlock(array(
           'sample_type' => 'admin',
           'viewer_type' => $this->_oTextData->getViewerType(),
           'start' => $iStart,
           'count' => $iPerPage,
           'admin_panel' => true,
           'filter_value' => $sFilterValue,
           'search_result_object' => $this->getSearchResult()
        ));
    }
    function serviceArchiveBlockIndex($iStart = 0, $iPerPage = 0, $bShowEmpty = true) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getIndexNumber();

        return $this->serviceArchiveBlock($iStart, $iPerPage, $bShowEmpty);
    }
    function serviceArchiveBlockMember($iStart = 0, $iPerPage = 0, $bShowEmpty = true) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getMemberNumber();

        return $this->serviceArchiveBlock($iStart, $iPerPage, $bShowEmpty);
    }
    function serviceArchiveBlock($iStart = 0, $iPerPage = 0, $bShowEmpty = true) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getPerPage();

        return $this->_oTemplate->displayBlock(array(
           'sample_type' => 'archive',
           'viewer_type' => $this->_oTextData->getViewerType(),
           'start' => $iStart,
           'count' => $iPerPage,
           'show_empty' => $bShowEmpty
        ));
    }
    function serviceFeaturedBlockIndex($iStart = 0, $iPerPage = 0, $bShowEmpty = true) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getIndexNumber();

        return $this->serviceFeaturedBlock($iStart, $iPerPage, $bShowEmpty);
    }
    function serviceFeaturedBlockMember($iStart = 0, $iPerPage = 0, $bShowEmpty = true) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getMemberNumber();

        return $this->serviceFeaturedBlock($iStart, $iPerPage, $bShowEmpty);
    }
    function serviceFeaturedBlock($iStart = 0, $iPerPage = 0, $bShowEmpty = true) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getPerPage();

        return $this->_oTemplate->displayBlock(array(
           'sample_type' => 'featured',
           'viewer_type' => $this->_oTextData->getViewerType(),
           'start' => $iStart,
           'count' => $iPerPage,
           'show_empty' => $bShowEmpty
        ));
    }
    function serviceTopRatedBlock($iStart = 0, $iPerPage = 0) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getPerPage();

        return $this->_oTemplate->displayBlock(array(
           'sample_type' => 'top_rated',
           'viewer_type' => $this->_oTextData->getViewerType(),
           'start' => $iStart,
           'count' => $iPerPage
        ));
    }
    function servicePopularBlock($iStart = 0, $iPerPage = 0) {
        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getPerPage();

        return $this->_oTemplate->displayBlock(array(
           'sample_type' => 'popular',
           'viewer_type' => $this->_oTextData->getViewerType(),
           'start' => $iStart,
           'count' => $iPerPage
        ));
    }
    function serviceGetSubscriptionParams($sUnit, $sAction, $iObjectId) {
        $sUnit = str_replace('bx_', '_', $sUnit);
        if(empty($sAction))
            $sAction = 'main';

        $aItem = $this->_oDb->getEntries(array('sample_type' => 'id', 'id' => $iObjectId));

        return array(
            'template' => array(
                'Subscription' => _t($sUnit . '_sbs_' . $sAction, $aItem['caption']),
                'ViewLink' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri()  . 'view/' . $aItem['uri']
            )
        );
    }

    /**
     * Action methods
     */
    function actionRss($iLength = 0) {
        $iLength = $iLength != 0 ? $iLength : (int)$this->_oConfig->getRssLength();

        $aEntries = $this->_oDb->getEntries(array(
            'sample_type' => 'archive',
            'viewer_type' => $this->_oTextData->getViewerType(),
            'start' => 0,
            'count' => $iLength
        ));

        $aRssData = array();
        $sRssViewUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/';
        foreach($aEntries as $aEntry) {
            if(empty($aEntry['caption'])) continue;

            $aRssData[$aEntry['id']] = array(
               'UnitID' => $aEntry['id'],
               'OwnerID' => $aEntry['id'],
               'UnitTitle' => $aEntry['caption'],
               'UnitLink' => $sRssViewUrl . $aEntry['uri'],
               'UnitDesc' => $aEntry['content'],
               'UnitDateTimeUTS' => $aEntry['when_uts'],
               'UnitIcon' => ''
            );
        }

        $oRss = new BxDolRssFactory();
        return $oRss->GenRssByData($aRssData, _t('_news_rss_caption'), $this->_oConfig->getBaseUri() . 'act_rss/');
    }
    function actiongetEntries($sSampleType = 'all', $iStart = 0, $iPerPage = 0) {
        check_logged();

        if(empty($iPerPage))
            $iPerPage = $this->_oConfig->getPerPage();

        return $this->_oTemplate->displayList(array(
            'sample_type' => $sSampleType,
            'sample_params' => isset($_POST['params']) ? unserialize(urldecode($_POST['params'])) : '',
            'viewer_type' => $this->_oTextData->getViewerType(),
            'start' => $iStart,
            'count' => $iPerPage,
            'filter_value' => isset($_POST['filter_value']) ? process_db_input($_POST['filter_value'], BX_TAGS_STRIP) : ''
        ));
    }
    function actionMarkFeatured() {
        $iId = (int)$_POST['id'];

        return !empty($iId) ? $this->_actFeatured($iId) : false;
    }
    function actionPublish() {
        $iId = (int)$_POST['id'];

        return !empty($iId) ? $this->_actPublish($iId) : false;
    }
    function actionDelete() {
        $iId = (int)$_POST['id'];

        return !empty($iId) ? $this->_actDelete($iId) : false;
    }
    function actionArchive($iStart = 0, $iPerPage = 0) {
        $sUri = $this->_oConfig->getUri();

        $aParams = array(
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_archive'),
                'block' => _t('_' . $sUri . '_bcaption_archive')
            ),
            'content' => array(
                'page_main_code' => $this->serviceArchiveBlock((int)$iStart, (int)$iPerPage)
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionPopular() {
        $sUri = $this->_oConfig->getUri();

        $aParams = array(
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_popular'),
                'block' => _t('_' . $sUri . '_bcaption_popular')
            ),
            'content' => array(
                'page_main_code' => $this->servicePopularBlock()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionTop() {
        $sUri = $this->_oConfig->getUri();

        $aParams = array(
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_top_rated'),
                'block' => _t('_' . $sUri . '_bcaption_top_rated')
            ),
            'content' => array(
                'page_main_code' => $this->serviceTopRatedBlock()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionCalendar($iYear = 0, $iMonth = 0, $iDay = 0, $iStart = 1, $iPerPage = 0) {
        $sUri = $this->_oConfig->getUri();

        $iArgs = func_num_args();
        if($iArgs == 1 && is_string($iYear))
            $iArgs = 0;

        $sContent = '';
        switch($iArgs) {
            case 0:
            case 2:
                $oCalendar = $this->getCalendar((int)$iYear, (int)$iMonth);
                $sContent = $oCalendar->display();
                break;
            case 3:
            case 5:
                $sContent = $this->getCalendarContent((int)$iYear, (int)$iMonth, (int)$iDay, (int)$iStart, (int)$iPerPage);
                $sContent = strlen($sContent) > 0 ? $sContent : _t('_' . $sUri . '_msg_no_results');
                break;
        }

        $sBlockTitle = !$iDay
            ? _t('_' . $sUri . '_bcaption_calendar')
            : _t('_' . $sUri . '_bcaption_calendar_browse') . ': ' . getLocaleDate(
                strtotime("{$iYear}-{$iMonth}-{$iDay}"), BX_DOL_LOCALE_DATE_SHORT);

        $sPageTitle = !$iDay
            ?  _t('_' . $sUri . '_pcaption_calendar')
            : $sBlockTitle;

        $aParams = array(
            'title' => array(
                'page' =>  $sPageTitle,
                'block' => $sBlockTitle,
            ),
            'content' => array(
                'page_main_code' => $sContent
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionCategories() {
		$sUri = $this->_oConfig->getUri();
		$oCategories = new BxTemplCategoriesModule(array('type' => 'bx_' . $sUri), _t('_categ_users'), BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'category');

		$aParams = array(
			'index' => 1,
			'title' => array(
				'page' => _t('_' . $sUri . '_pcaption_categories')
			),
			'content' => array(
				'page_main_code' => $oCategories->getCode()
			)
		);
		$this->_oTemplate->getPageCode($aParams);
	}
    function actionCategory($sCategory = '', $iPage = 1, $iPerPage = 0) {
        $sUri = $this->_oConfig->getUri();
        $sBaseUri = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();

        $sContent = MsgBox(_t('_' . $sUri . '_msg_no_results'));
        if(!empty($sCategory))
            list($sCategoryDisplay, $sContent) = $this->getCategoryContent($sCategory, $iPage, $iPerPage);

        $aParams = array(
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_category', $sCategoryDisplay),
                'block' => _t('_' . $sUri . '_bcaption_category', $sCategoryDisplay)
            ),
            'breadcrumb' => array(
                _t('_' . $sUri . '_top_menu_item') => $sBaseUri . 'home/',
                _t('_' . $sUri . '_categories_top_menu_sitem') => $sBaseUri . 'categories/',
                $sCategoryDisplay => ''
            ),
            'content' => array(
                'page_main_code' => $sContent
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionTags() {
        $sUri = $this->_oConfig->getUri();
        $oTags = new BxTemplTagsModule(array('type' => 'bx_' . $sUri, 'orderby' => 'popular'), _t('_' . $sUri . '_bcaption_all_tags'), BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'tags');

        $aParams = array(
            'index' => 1,
            'css' => array('view.css'),
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_tags')
            ),
            'content' => array(
                'page_main_code' => $oTags->getCode()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionTag($sTag = '', $iPage = 1, $iPerPage = 0) {
        $sUri = $this->_oConfig->getUri();
        $sBaseUri = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();

        $sContent = MsgBox(_t('_' . $sUri . '_msg_no_results'));
        if(!empty($sTag))
            list($sTagDisplay, $sContent) = $this->getTagContent($sTag, $iPage, $iPerPage);

        $aParams = array(
            'css' => array('view.css'),
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_tag', $sTagDisplay),
                'block' => _t('_' . $sUri . '_bcaption_tag', $sTagDisplay)
            ),
            'breadcrumb' => array(
                _t('_' . $sUri . '_top_menu_item') => $sBaseUri . 'home/',
                _t('_' . $sUri . '_tags_top_menu_sitem') => $sBaseUri . 'tags/',
                $sTagDisplay => ''
            ),
            'content' => array(
                'page_main_code' => $sContent
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionFeatured() {
        $sUri = $this->_oConfig->getUri();

        $aParams = array(
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_featured'),
                'block' => _t('_' . $sUri . '_bcaption_featured')
            ),
            'content' => array(
                'page_main_code' => $this->serviceFeaturedBlock()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionIndex() {
        $sUri = $this->_oConfig->getUri();
        $oPage = bx_instance($this->_oConfig->getClassPrefix() . 'PageMain', array($this), $this->_aModule);

        $aParams = array(
            'index' => 1,
            'css' => array('view.css', 'cmts.css'),
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_home')
            ),
            'content' => array(
                'page_main_code' => $oPage->getCode()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionView($sName) {
        $sUri = $this->_oConfig->getUri();
        $oPage = bx_instance($this->_oConfig->getClassPrefix() . 'PageView', array($sName, $this), $this->_aModule);

        $aParams = array(
            'index' => 1,
            'js' => array('main.js'),
            'css' => array('view.css', 'cmts.css'),
            'content' => array(
                'page_main_code' => $this->_oTemplate->getViewJs(true) . $oPage->getCode()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }
    function actionAdmin($sName = '') {
        $GLOBALS['iAdminPage'] = 1;
        require_once(BX_DIRECTORY_PATH_INC . 'admin_design.inc.php');

        $sUri = $this->_oConfig->getUri();

        check_logged();
        if(!@isAdmin()) {
            send_headers_page_changed();
            login_form("", 1);
            exit;
        }

        //--- Process actions ---//
        $mixedResultSettings = '';
        if(isset($_POST['save']) && isset($_POST['cat'])) {
            $mixedResultSettings = $this->setSettings($_POST);
        }

        if(isset($_POST[$sUri . '-publish']))
            $this->_actPublish($_POST[$sUri . '-ids'], true);
        else if(isset($_POST[$sUri . '-unpublish']))
            $this->_actPublish($_POST[$sUri . '-ids'], false);
        else if(isset($_POST[$sUri . '-featured']))
            $this->_actFeatured($_POST[$sUri . '-ids'], true);
        else if(isset($_POST[$sUri . '-unfeatured']))
            $this->_actFeatured($_POST[$sUri . '-ids'], false);
        else if(isset($_POST[$sUri . '-delete']))
            $this->_actDelete($_POST[$sUri . '-ids']);
        //--- Process actions ---//

        //--- Get New/Edit form ---//
        $sPostForm = '';
        if(!empty($sName))
            $sPostForm = $this->serviceEditBlock(process_db_input($sName, BX_TAGS_STRIP));
        else if(isset($_POST['id']))
            $sPostForm = $this->serviceEditBlock((int)$_POST['id']);
        else
            $sPostForm = $this->servicePostBlock();
        //--- Get New/Edit form ---//

        $sFilterValue = '';
        if(isset($_GET[$sUri . '-filter']))
            $sFilterValue = process_db_input($_GET[$sUri . '-filter'], BX_TAGS_STRIP);

        $sContent = DesignBoxAdmin(_t('_' . $sUri . '_bcaption_settings'), $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $this->getSettingsForm($mixedResultSettings))));
        $sContent .= DesignBoxAdmin(_t('_' . $sUri . '_bcaption_post'), $sPostForm);
        $sContent .= DesignBoxAdmin(_t('_' . $sUri . '_bcaption_all'), $this->serviceAdminBlock(0, 0, $sFilterValue));

        $aParams = array(
            'title' => array(
                'page' => _t('_' . $sUri . '_pcaption_admin')
            ),
            'content' => array(
                'page_main_code' => $sContent
            )
        );
        $this->_oTemplate->getPageCodeAdmin($aParams);
    }

    /**
     * Common methods
     */
    function _actFeatured($aIds, $bPositive = true) {
        if(!isAdmin())
            return false;

        if(is_int($aIds) || is_string($aIds))
            $aIds = array((int)$aIds);

        $bResult = $this->_oDb->updateEntry($aIds, array('featured' => ($bPositive ? 1 : 0)));
        if($bResult)
            foreach($aIds as $iId) {
                //--- Entry -> Featured for Alerts Engine ---//
                bx_import('BxDolAlerts');
                $oAlert = new BxDolAlerts('bx_' . $this->_oConfig->getUri(), 'featured', $iId, BxDolTextData::getAuthorId());
                $oAlert->alert();
                //--- Entry -> Featured for Alerts Engine ---//
            }

        return $bResult;
    }
    function _actPublish($aIds, $bPositive = true) {
        if(!isAdmin())
            return false;

        if(is_int($aIds) || is_string($aIds))
            $aIds = array((int)$aIds);

        $bResult = $this->_oDb->updateEntry($aIds, array('status' => ($bPositive ? BX_TD_STATUS_ACTIVE : BX_TD_STATUS_INACTIVE)));
        if($bResult)
            foreach($aIds as $iId) {
                //--- Entry -> Publish/Unpublish for Alerts Engine ---//
                $oAlert = new BxDolAlerts('bx_' . $this->_oConfig->getUri(), ($bPositive ? 'publish' : 'unpublish'), $iId, BxDolTextData::getAuthorId());
                $oAlert->alert();
                //--- Entry -> Publish/Unpublish for Alerts Engine ---//

                //--- Reparse Global Tags ---//
                $oTags = new BxDolTags();
                $oTags->reparseObjTags('bx_' . $this->_oConfig->getUri(), $iId);
                //--- Reparse Global Tags ---//

                //--- Reparse Global Categories ---//
                $oCategories = new BxDolCategories();
                $oCategories->reparseObjTags('bx_' . $this->_oConfig->getUri(), $iId);
                //--- Reparse Global Categories ---//
            }

        return $bResult;
    }
    function _actDelete($aIds) {
        if(!$this->_isDeleteAllowed(true))
            return false;

        if(is_int($aIds) || is_string($aIds))
            $aIds = array((int)$aIds);

        $bResult = $this->_oDb->deleteEntries($aIds);
        if($bResult) {
            $oTags = new BxDolTags();
            $oCategories = new BxDolCategories();
            $oSubscription = new BxDolSubscription();

            foreach($aIds as $iId) {
                //--- Entry -> Delete for Alerts Engine ---//
                $oAlert = new BxDolAlerts('bx_' . $this->_oConfig->getUri(), 'delete', $iId, BxDolTextData::getAuthorId());
                $oAlert->alert();
                //--- Entry -> Delete for Alerts Engine ---//

                //--- Reparse Global Tags ---//
                $oTags->reparseObjTags('bx_' . $this->_oConfig->getUri(), $iId);
                //--- Reparse Global Tags ---//

                //--- Reparse Global Categories ---//
                $oCategories->reparseObjTags('bx_' . $this->_oConfig->getUri(), $iId);
                //--- Reparse Global Categories ---//

                //--- Remove all subscriptions ---//
                $oSubscription->unsubscribe(array('type' => 'object_id', 'unit' => 'bx_' . $this->_oConfig->getUri(), 'object_id' => $iId));
                //--- Remove all subscriptions ---//
            }
        }
        return $bResult;
    }
    function _isCommentsAllowed(&$aEntry) {
        return true;
    }
    function _isVotesAllowed(&$aEntry) {
        return true;
    }
}
?>
