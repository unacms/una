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

bx_import('BxDolTwigPageMain');

class BxGroupsPageMain extends BxDolTwigPageMain {

    function __construct($aObject, $oTemplate = false) {
        $this->sSearchResultClassName = 'BxGroupsSearchResult';
        $this->sFilterName = 'bx_groups_filter';
        $this->oMain = BxDolModule::getInstance('bx_groups');
        parent::__construct($aObject, $oTemplate);
    }

    function getBlockCode_LatestFeaturedGroup() {

        $aDataEntry = $this->oDb->getLatestFeaturedItem ();
        if (!$aDataEntry) {
            return MsgBox(_t('_Empty'));
        }

        $aAuthor = getProfileInfo($aDataEntry['author_id']);

        $sImageUrl = '';
        $sImageTitle = '';
        $a = array ('ID' => $aDataEntry['author_id'], 'Avatar' => $aDataEntry['thumb']);
        $aImage = BxDolService::call('photos', 'get_image', array($a, 'file'), 'Search');

        bx_groups_import('Voting');
        $oRating = new BxGroupsVoting ('bx_groups', $aDataEntry['id']);

        $aVars = array (
            'image_url' => !$aImage['no_image'] && $aImage['file'] ? $aImage['file'] : $this->oTemplate->getIconUrl('no-photo-110.png'),
            'image_title' => !$aImage['no_image'] && $aImage['title'] ? $aImage['title'] : '',
            'group_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'view/' . $aDataEntry['uri'],
            'group_title' => $aDataEntry['title'],
            'author_title' => _t('_From'),
            'author_username' => $aAuthor['NickName'],
            'author_url' => getProfileLink($aAuthor['ID']),
            'rating' => $oRating->isEnabled() ? $oRating->getJustVotingElement (true, $aDataEntry['id']) : '',
        );
        return $this->oTemplate->parseHtmlByName('latest_featured_group', $aVars);
    }

    function getBlockCode_Recent() {
        return $this->ajaxBrowse('recent', $this->oDb->getParam('bx_groups_perpage_main_recent'));
    }

    function getBlockCode_Menu() {
        bx_import('BxTemplMenu');
        $oMenu = BxTemplMenu::getObjectInstance('bx_groups');
        return $oMenu ? $oMenu->getCode () : '';
    }
}

?>
