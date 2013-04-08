<?php
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

bx_import('BxDolTwigSearchResult');

class BxGroupsSearchResult extends BxDolTwigSearchResult {

    var $aCurrent = array(
        'name' => 'bx_groups',
        'title' => '_bx_groups_page_title_browse',
        'table' => 'bx_groups_main',
        'ownFields' => array('id', 'title', 'uri', 'created', 'author_id', 'thumb', 'rate', 'fans_count', 'country', 'city'),
        'searchFields' => array('title', 'desc', 'tags', 'categories'),
/*
        'join' => array(
            'profile' => array(
                    'type' => 'left',
                    'table' => 'Profiles',
                    'mainField' => 'author_id',
                    'onField' => 'ID',
                    'joinFields' => array('NickName'),
            ),
        ),
*/
        'restriction' => array(
            'activeStatus' => array('value' => 'approved', 'field'=>'status', 'operator'=>'='),
            'owner' => array('value' => '', 'field' => 'author_id', 'operator' => '='),
            'tag' => array('value' => '', 'field' => 'tags', 'operator' => 'against'),
            'category' => array('value' => '', 'field' => 'Category', 'operator' => '=', 'table' => 'sys_categories'),
            'category_type' => array('value' => '', 'field' => 'Type', 'operator' => '=', 'table' => 'sys_categories'),
            'public' => array('value' => '', 'field' => 'allow_view_group_to', 'operator' => '='),
        ),
        'paginate' => array('perPage' => 14, 'page' => 1, 'totalNum' => 0, 'totalPages' => 1),
        'sorting' => 'last',
        'rss' => array(
            'title' => '',
            'link' => '',
            'image' => '',
            'profile' => 0,
            'fields' => array (
                'Link' => '',
                'Title' => 'title',
                'DateTimeUTS' => 'created',
                'Desc' => 'desc',
                'Photo' => '',
            ),
        ),
        'ident' => 'id'
    );


    function BxGroupsSearchResult($sMode = '', $sValue = '', $sValue2 = '', $sValue3 = '') {

        switch ($sMode) {

            case 'pending':
                if (false !== bx_get('bx_groups_filter'))
                    $this->aCurrent['restriction']['keyword'] = array('value' => process_db_input(bx_get('bx_groups_filter'), BX_TAGS_STRIP), 'field' => '','operator' => 'against');
                $this->aCurrent['restriction']['activeStatus']['value'] = 'pending';
                $this->sBrowseUrl = "administration";
                $this->aCurrent['title'] = _t('_bx_groups_page_title_pending_approval');
                unset($this->aCurrent['rss']);
            break;

            case 'my_pending':
                $oMain = $this->getMain();
                $this->aCurrent['restriction']['owner']['value'] = $oMain->_iProfileId;
                $this->aCurrent['restriction']['activeStatus']['value'] = 'pending';
                $this->sBrowseUrl = "browse/user/" . getNickName($oMain->_iProfileId);
                $this->aCurrent['title'] = _t('_bx_groups_page_title_pending_approval');
                unset($this->aCurrent['rss']);
            break;

            case 'search':
                if ($sValue)
                    $this->aCurrent['restriction']['keyword'] = array('value' => $sValue,'field' => '','operator' => 'against');

                if ($sValue2) {

                    $this->aCurrent['join']['category'] = array(
                        'type' => 'inner',
                        'table' => 'sys_categories',
                        'mainField' => 'id',
                        'onField' => 'ID',
                        'joinFields' => '',
                    );

                    $this->aCurrent['restriction']['category_type']['value'] = $this->aCurrent['name']; 
                    $this->aCurrent['restriction']['category']['value'] = $sValue2;
                    if (is_array($sValue2)) {
                        $this->aCurrent['restriction']['category']['operator'] = 'in';
                    }
                }

                $sValue = $GLOBALS['MySQL']->unescape($sValue);
                $sValue2 = $GLOBALS['MySQL']->unescape($sValue2);
                $this->sBrowseUrl = "search/$sValue/" . (is_array($sValue2) ? implode(',',$sValue2) : $sValue2);
                $this->aCurrent['title'] = _t('_bx_groups_page_title_search_results') . ' ' . (is_array($sValue2) ? implode(', ',$sValue2) : $sValue2) . ' ' . $sValue;
                unset($this->aCurrent['rss']);
                break;

            case 'user':
                $iProfileId = $GLOBALS['oBxGroupsModule']->_oDb->getProfileIdByNickName ($sValue, false);
                $GLOBALS['oTopMenu']->setCurrentProfileID($iProfileId); // select profile subtab, instead of module tab
                if (!$iProfileId)
                    $this->isError = true;
                else
                    $this->aCurrent['restriction']['owner']['value'] = $iProfileId;
                $sValue = $GLOBALS['MySQL']->unescape($sValue);
                $this->sBrowseUrl = "browse/user/$sValue";
                $this->aCurrent['title'] = ucfirst(strtolower($sValue)) . _t('_bx_groups_page_title_browse_by_author');
                if (bx_get('rss')) {
                    $aData = getProfileInfo($iProfileId);
                    if ($aData['Avatar']) {
                        $a = array ('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
                        $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                        if (!$aImage['no_image'])
                            $this->aCurrent['rss']['image'] = $aImage['file'];
                    }
                }
                break;

            case 'joined':
                $iProfileId = $GLOBALS['oBxGroupsModule']->_oDb->getProfileIdByNickName ($sValue, false);
                $GLOBALS['oTopMenu']->setCurrentProfileID($iProfileId); // select profile subtab, instead of module tab

                if (!$iProfileId) {

                    $this->isError = true;

                } else {

                    $this->aCurrent['join']['fans'] = array(
                        'type' => 'inner',
                        'table' => 'bx_groups_fans',
                        'mainField' => 'id',
                        'onField' => 'id_entry',
                        'joinFields' => array('id_profile'),
                    );
                    $this->aCurrent['restriction']['fans'] = array(
                        'value' => $iProfileId,
                        'field' => 'id_profile',
                        'operator' => '=',
                        'table' => 'bx_groups_fans',
                    );
                    $this->aCurrent['restriction']['confirmed_fans'] = array(
                        'value' => 1,
                        'field' => 'confirmed',
                        'operator' => '=',
                        'table' => 'bx_groups_fans',
                    );
                }

                $sValue = $GLOBALS['MySQL']->unescape($sValue);
                $this->sBrowseUrl = "browse/joined/$sValue";
                $this->aCurrent['title'] = ucfirst(strtolower($sValue)) . _t('_bx_groups_page_title_browse_by_author_joined_groups');

                if (bx_get('rss')) {
                    $aData = getProfileInfo($iProfileId);
                    if ($aData['Avatar']) {
                        $a = array ('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
                        $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                        if (!$aImage['no_image'])
                            $this->aCurrent['rss']['image'] = $aImage['file'];
                    }
                }
                break;

            case 'admin':
                $this->aCurrent['restriction']['owner']['value'] = 0;
                $this->sBrowseUrl = "browse/admin";
                $this->aCurrent['title'] = _t('_bx_groups_page_title_admin_groups');
                break;

            case 'category':
                $this->aCurrent['join']['category'] = array(
                    'type' => 'inner',
                    'table' => 'sys_categories',
                    'mainField' => 'id',
                    'onField' => 'ID',
                    'joinFields' => '',
                );
                $this->aCurrent['restriction']['category_type']['value'] = $this->aCurrent['name'];
                $this->aCurrent['restriction']['category']['value'] = $sValue;
                $sValue = $GLOBALS['MySQL']->unescape($sValue);
                $this->sBrowseUrl = "browse/category/" . title2uri($sValue);
                $this->aCurrent['title'] = _t('_bx_groups_page_title_browse_by_category') . ' ' . $sValue;
                break;

            case 'tag':
                $this->aCurrent['restriction']['tag']['value'] = $sValue;
                $sValue = $GLOBALS['MySQL']->unescape($sValue);
                $this->sBrowseUrl = "browse/tag/" . title2uri($sValue);
                $this->aCurrent['title'] = _t('_bx_groups_page_title_browse_by_tag') . ' ' . $sValue;
                break;

            case 'recent':
                $this->sBrowseUrl = 'browse/recent';
                $this->aCurrent['title'] = _t('_bx_groups_page_title_browse_recent');
                break;

            case 'top':
                $this->sBrowseUrl = 'browse/top';
                $this->aCurrent['sorting'] = 'top';
                $this->aCurrent['title'] = _t('_bx_groups_page_title_browse_top_rated');
                break;

            case 'popular':
                $this->sBrowseUrl = 'browse/popular';
                $this->aCurrent['sorting'] = 'popular';
                $this->aCurrent['title'] = _t('_bx_groups_page_title_browse_popular');
                break;

            case 'featured':
            $this->aCurrent['restriction']['featured'] = array('value' => 1, 'field' => 'featured', 'operator' => '=');
                $this->sBrowseUrl = 'browse/featured';
                $this->aCurrent['title'] = _t('_bx_groups_page_title_browse_featured');
                break;

            case 'calendar':
                $this->aCurrent['restriction']['calendar-min'] = array('value' => "UNIX_TIMESTAMP('{$sValue}-{$sValue2}-{$sValue3} 00:00:00')", 'field' => 'created', 'operator' => '>=', 'no_quote_value' => true);
                $this->aCurrent['restriction']['calendar-max'] = array('value' => "UNIX_TIMESTAMP('{$sValue}-{$sValue2}-{$sValue3} 23:59:59')", 'field' => 'created', 'operator' => '<=', 'no_quote_value' => true);
                $this->sEventsBrowseUrl = "browse/calendar/{$sValue}/{$sValue2}/{$sValue3}";
                $this->aCurrent['title'] = _t('_bx_groups_page_title_browse_by_day')
                    . getLocaleDate( strtotime("{$sValue}-{$sValue2}-{$sValue3}"), BX_DOL_LOCALE_DATE_SHORT);
                break;

            case '':
                $this->sBrowseUrl = 'browse/';
                $this->aCurrent['title'] = _t('_bx_groups');
                unset($this->aCurrent['rss']);
                break;

            default:
                $this->isError = true;
        }

        $oMain = $this->getMain();

        $this->aCurrent['paginate']['perPage'] = $oMain->_oDb->getParam('bx_groups_perpage_browse');

        if (isset($this->aCurrent['rss']))
            $this->aCurrent['rss']['link'] = BX_DOL_URL_ROOT . $oMain->_oConfig->getBaseUri() . $this->sBrowseUrl;

        if (bx_get('rss')) {
            $this->aCurrent['ownFields'][] = 'desc';
            $this->aCurrent['ownFields'][] = 'created';
            $this->aCurrent['paginate']['perPage'] = $oMain->_oDb->getParam('bx_groups_max_rss_num');
        }

        bx_import('Voting', $oMain->_aModule);
        $oVotingView = new BxGroupsVoting ('bx_groups', 0);
        $this->oVotingView = $oVotingView->isEnabled() ? $oVotingView : null;

        $this->sFilterName = 'bx_groups_filter';

        parent::BxDolTwigSearchResult();
    }

    function getAlterOrder() {
        if ($this->aCurrent['sorting'] == 'last') {
            $aSql = array();
            $aSql['order'] = " ORDER BY `bx_groups_main`.`created` DESC";
            return $aSql;
        } elseif ($this->aCurrent['sorting'] == 'top') {
            $aSql = array();
            $aSql['order'] = " ORDER BY `bx_groups_main`.`rate` DESC, `bx_groups_main`.`rate_count` DESC";
            return $aSql;
        } elseif ($this->aCurrent['sorting'] == 'popular') {
            $aSql = array();
            $aSql['order'] = " ORDER BY `bx_groups_main`.`views` DESC";
            return $aSql;
        }
        return array();
    }

    function displayResultBlock () {        
        $s = parent::displayResultBlock ();
        if ($s) {
            $oMain = $this->getMain();
            BxDolTemplate::getInstance()->addDynamicLocation($oMain->_oConfig->getHomePath(), $oMain->_oConfig->getHomeUrl());
            BxDolTemplate::getInstance()->addCss('unit.css');
            bx_import('BxTemplFunctions');
            return BxTemplFunctions::getInstance()->centerContent ($s, '.bx_groups_unit');
        }
        return '';
    }

    function getMain() {
        return BxDolModule::getInstance('bx_groups');
    }

    function getRssUnitLink (&$a) {
        $oMain = $this->getMain();
        return BX_DOL_URL_ROOT . $oMain->_oConfig->getBaseUri() . 'view/' . $a['uri'];
    }

    function _getPseud () {
        return array(
            'id' => 'id',
            'title' => 'title',
            'uri' => 'uri',
            'created' => 'created',
            'author_id' => 'author_id',
            'NickName' => 'NickName',
            'thumb' => 'thumb',
            'price_range' => 'price_range',
        );
    }
}

?>
