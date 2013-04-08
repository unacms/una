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

bx_import('BxDolTextModule');

require_once('BxArlCalendar.php');
require_once('BxArlCmts.php');
require_once('BxArlVoting.php');
require_once('BxArlSearchResult.php');
require_once('BxArlData.php');

/**
 * Articles module by BoonEx
 *
 * This module is needed to manage site articles.
 *
 *
 * Profile's Wall:
 * no spy events
 *
 *
 *
 * Spy:
 * no spy events
 *
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 *
 * Service methods:
 *
 * Get post block.
 * @see BxArlModule::servicePostBlock
 * BxDolService::call('articles', 'post_block');
 * @note is needed for internal usage.
 *
 * Get edit block.
 * @see BxArlModule::serviceEditBlock
 * BxDolService::call('articles', 'edit_block', array($mixed));
 * @note is needed for internal usage.
 *
 * Get administration block.
 * @see BxArlModule::serviceAdminBlock
 * BxDolService::call('articles', 'admin_block', array($iStart, $iPerPage, $sFilterValue));
 * @note is needed for internal usage.
 *
 * Get block with all articles ordered by the time of posting.
 * @see BxArlModule::serviceArchiveBlock
 * BxDolService::call('articles', 'archive_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get block with articles marked as featured.
 * @see BxArlModule::serviceFeaturedBlock
 * BxDolService::call('articles', 'featured_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get block with articles ordered by their rating.
 * @see BxArlModule::serviceTopRatedBlock
 * BxDolService::call('articles', 'top_rated_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get block with all articles ordered by their popularity(number of views).
 * @see BxArlModule::servicePopularBlock
 * BxDolService::call('articles', 'popular_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get the block with article content for view article page.
 * @see BxArlModule::serviceViewBlock
 * BxDolService::call('articles', 'view_block', array($sUri));
 * @note is needed for internal usage.
 *
 * Get the block with article comments for view article page.
 * @see BxArlModule::serviceCommentBlock
 * BxDolService::call('articles', 'comment_block', array($sUri));
 * @note is needed for internal usage.

 * Get the block with article votes for view article page.
 * @see BxArlModule::serviceVoteBlock
 * BxDolService::call('articles', 'vote_block', array($sUri));
 * @note is needed for internal usage.
 *
 * Get the block with article actions for view article page.
 * @see BxArlModule::serviceActionBlock
 * BxDolService::call('articles', 'action_block', array($sUri));
 * @note is needed for internal usage.
 *
 *
 * Alerts:
 * Alerts type/unit - 'articles'
 * The following alerts are rised
 *
 *  post - article is added
 *      $iObjectId - article id
 *      $iSenderId - admin's id
 *
 *  edit - article was modified
 *      $iObjectId - article id
 *      $iSenderId - admin's id
 *
 *  featured - article was marked as featured
 *      $iObjectId - article id
 *      $iSenderId - admin's id
 *
 *  publish - article was published
 *      $iObjectId - article id
 *      $iSenderId - admin's id
 *
 *  unpublish - article was unpublished
 *      $iObjectId - article id
 *      $iSenderId - admin's id
 *
 *  delete - article was deleted
 *      $iObjectId - article id
 *      $iSenderId - admin's id
 *
 */
class BxArlModule extends BxDolTextModule {
    /**
     * Constructor
     */
    function BxArlModule($aModule) {
        parent::BxDolTextModule($aModule);

        //--- Define Membership Actions ---//
        //defineMembershipActions(array('articles delete'), 'ACTION_ID_');
    }

    /**
     * Service methods
     */
    function serviceArticlesRss($iLength = 0) {
        return $this->actionRss($iLength);
    }

    /**
     * Action methods
     */
    function actionGetArticles($sSampleType = 'all', $iStart = 0, $iPerPage = 0) {
        return $this->actionGetEntries($sSampleType, $iStart, $iPerPage);
    }

    /**
     * Private methods.
     */
    function _createObjectCmts($iId) {
        return new BxArlCmts($this->_oConfig->getCommentsSystemName(), $iId);
    }
    function _createObjectVoting($iId) {
        return new BxArlVoting($this->_oConfig->getVotesSystemName(), $iId);
    }
    function _isDeleteAllowed($bPerform = false) {
        if(!isLogged())
            return false;

        if(isAdmin())
            return true;

        $aCheckResult = checkAction(getLoggedId(), ACTION_ID_ARTICLES_DELETE, $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }
}
?>
