<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

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
class BxArlModule extends BxDolTextModule
{
    /**
     * Constructor
     */
    function BxArlModule($aModule)
    {
        parent::BxDolTextModule($aModule);

        //--- Define Membership Actions ---//
        defineMembershipActions(array('articles delete'), 'ACTION_ID_');
    }

    /**
     * Service methods
     */
    function serviceArticlesRss($iLength = 0)
    {
        return $this->actionRss($iLength);
    }

    /**
     * Action methods
     */
    function actionGetArticles($sSampleType = 'all', $iStart = 0, $iPerPage = 0)
    {
        return $this->actionGetEntries($sSampleType, $iStart, $iPerPage);
    }

    /**
     * Private methods.
     */
    function _createObjectCalendar($iYear, $iMonth)
    {
        return new BxArlCalendar($iYear, $iMonth, $this->_oDb, $this->_oConfig);
    }
    function _createObjectCmts($iId)
    {
        return new BxArlCmts($this->_oConfig->getCommentsSystemName(), $iId);
    }
    function _createObjectVoting($iId)
    {
        return new BxArlVoting($this->_oConfig->getVotesSystemName(), $iId);
    }
    function _isDeleteAllowed($bPerform = false)
    {
        if(!isLogged())
            return false;

        if(isAdmin())
            return true;

        $aCheckResult = checkAction(getLoggedId(), ACTION_ID_ARTICLES_DELETE, $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }
}
