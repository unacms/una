<?
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

bx_import('BxDolTextConfig');

class BxNewsConfig extends BxDolTextConfig {
    function BxNewsConfig($aModule) {
        parent::BxDolTextConfig($aModule);
    }
    function init(&$oDb) {
        parent::init($oDb);

        $this->_bAutoapprove = $this->_oDb->getParam('news_autoapprove') == 'on';
        $this->_bComments = $this->_oDb->getParam('news_comments') == 'on';
        $this->_sCommentsSystemName = "bx_news";
        $this->_bVotes = $this->_oDb->getParam('news_votes') == 'on';
        $this->_sVotesSystemName = "bx_news";
        $this->_sDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE_SHORT, BX_DOL_LOCALE_DB);
        $this->_sAnimationEffect = 'fade';
        $this->_iAnimationSpeed = 'slow';
        $this->_iIndexNumber = (int)$this->_oDb->getParam('news_index_number');
        $this->_iMemberNumber = (int)$this->_oDb->getParam('news_member_number');
        $this->_iSnippetLength = (int)$this->_oDb->getParam('news_snippet_length');
        $this->_iPerPage = (int)$this->_oDb->getParam('news_per_page');
        $this->_sSystemPrefix = 'news';
        $this->_aJsClasses = array('main' => 'BxNewsMain');
        $this->_aJsObjects = array('main' => 'oNewsMain');
        $this->_iRssLength = (int)$this->_oDb->getParam('news_rss_length');
    }
}
?>