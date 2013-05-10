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

bx_import('BxDolModuleConfig');

class BxDolTextConfig extends BxDolModuleConfig {
    var $_oDb;
    var $_bAutoapprove;
    var $_bComments;
    var $_sCommentsSystemName;
    var $_bVotes;
    var $_sVotesSystemName;
    var $_sViewsSystemName;
    var $_sSubscriptionsSystemName;
    var $_sActionsViewSystemName;
    var $_sCategoriesSystemName;
    var $_sTagsSystemName;
    var $_sAlertsSystemName;
    var $_sSearchSystemName;
    var $_sDateFormat;
    var $_sAnimationEffect;
    var $_iAnimationSpeed;
    var $_iIndexNumber;
    var $_iMemberNumber;
    var $_iSnippetLength;
    var $_iPerPage;
    var $_sSystemPrefix;
    var $_aJsClasses;
    var $_aJsObjects;
    var $_iRssLength;

    function BxDolTextConfig(&$aModule) {
        parent::BxDolModuleConfig($aModule);
    }
    function init(&$oDb) {
        $this->_oDb = &$oDb;
    }
    function isAutoapprove() {
        return $this->_bAutoapprove;
    }
    function isCommentsEnabled() {
        return $this->_bComments;
    }
    function getCommentsSystemName() {
        return $this->_sCommentsSystemName;
    }
    function isVotesEnabled() {
        return $this->_bVotes;
    }
    function getVotesSystemName() {
        return $this->_sVotesSystemName;
    }
    function getViewsSystemName() {
        return $this->_sViewsSystemName;
    }
    function getSubscriptionsSystemName() {
        return $this->_sSubscriptionsSystemName;
    }
    function getActionsViewSystemName() {
        return $this->_sActionsViewSystemName;
    }
    function getCategoriesSystemName() {
        return $this->_sCategoriesSystemName;
    }
    function getTagsSystemName() {
        return $this->_sTagsSystemName;
    }
    function getAlertsSystemName() {
        return $this->_sAlertsSystemName;
    }
    function getSearchSystemName() {
        return $this->_sSearchSystemName;
    }
    function getDateFormat() {
        return $this->_sDateFormat;
    }
    function getAnimationEffect() {
        return $this->_sAnimationEffect;
    }
    function getAnimationSpeed() {
        return $this->_iAnimationSpeed;
    }
    function getIndexNumber() {
        return $this->_iIndexNumber;
    }
    function getMemberNumber() {
        return $this->_iMemberNumber;
    }
    function getSnippetLength() {
        return $this->_iSnippetLength;
    }
    function getPerPage() {
        return $this->_iPerPage;
    }
    function getSystemPrefix() {
        return $this->_sSystemPrefix;
    }
    function getJsClass($sType = 'main') {
        if(empty($sType))
            return $this->_aJsClasses;

        return $this->_aJsClasses[$sType];
    }
    function getJsObject($sType = 'main') {
        if(empty($sType))
            return $this->_aJsObjects;

        return $this->_aJsObjects[$sType];
    }
    function getRssLength() {
        return $this->_iRssLength;
    }
}
?>
