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

bx_import ('BxDolTwigPageMain');

class BxEventsPageMain extends BxDolTwigPageMain {

    function BxEventsPageMain(&$oEventsMain) {
        parent::BxDolTwigPageMain('bx_events_main', $oEventsMain);
        $this->sSearchResultClassName = 'BxEventsSearchResult';
        $this->sFilterName = 'bx_events_filter';
    }

    function getBlockCode_UpcomingPhoto() {

        $aEvent = $this->oDb->getUpcomingEvent (getParam('bx_events_main_upcoming_event_from_featured_only') ? true : false);
        if (!$aEvent) {
            return MsgBox(_t('_Empty'));
        }

        $aAuthor = getProfileInfo($aEvent['ResponsibleID']);

        $a = array ('ID' => $aEvent['ResponsibleID'], 'Avatar' => $aEvent['PrimPhoto']);
        $aImage = BxDolService::call('photos', 'get_image', array($a, 'file'), 'Search');

        bx_events_import('Voting');
        $oRating = new BxEventsVoting ('bx_events', (int)$aEvent['ID']);

        $aVars = array (
            'image_url' => !$aImage['no_image'] && $aImage['file'] ? $aImage['file'] : $this->oTemplate->getIconUrl('no-photo-110.png'),
            'image_title' => !$aImage['no_image'] && $aImage['title'] ? $aImage['title'] : '',

            'event_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'view/' . $aEvent['EntryUri'],
            'event_title' => $aEvent['Title'],
            'event_start' => getLocaleDate($aEvent['EventStart']),
            'event_start_in' => defineTimeInterval($aEvent['EventStart']),
            'author_title' => _t('_From'),
            'author_username' => $aAuthor['NickName'],
            'author_url' => getProfileLink($aAuthor['ID']),

            'rating' => $oRating->isEnabled() ? $oRating->getJustVotingElement (true, $aEvent['ID']) : '',
            'participants' => $aEvent['FansCount'],
            'country_city' => '<a href="' . $this->oConfig->getBaseUri() . 'browse/country/' . strtolower($aEvent['Country']) . '">' . _t($GLOBALS['aPreValues']['Country'][$aEvent['Country']]['LKey']) . '</a>' . (trim($aEvent['City']) ? ', '.$aEvent['City'] : ''),
            'place' => $aEvent['Place'],
            'flag_image' => genFlag($aEvent['Country']),
        );
        return $this->oTemplate->parseHtmlByName('main_event', $aVars);
    }

    function getBlockCode_UpcomingList() {
        return $this->ajaxBrowse('upcoming', $this->oDb->getParam('bx_events_perpage_main_upcoming'));
    }

    function getBlockCode_PastList() {
        return $this->ajaxBrowse('past', $this->oDb->getParam('bx_events_perpage_main_past'));
    }

    function getBlockCode_RecentlyAddedList() {
        return $this->ajaxBrowse('recent', $this->oDb->getParam('bx_events_perpage_main_recent'));
    }
}

?>
