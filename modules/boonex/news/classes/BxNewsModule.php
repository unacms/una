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

require_once('BxNewsCalendar.php');
require_once('BxNewsCmts.php');
require_once('BxNewsVoting.php');
require_once('BxNewsSearchResult.php');
require_once('BxNewsData.php');

/**
 * News module by BoonEx
 *
 * This module is needed to manage site news.
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
 * @see BxNewsModule::servicePostBlock
 * BxDolService::call('news', 'post_block');
 * @note is needed for internal usage.
 *
 * Get edit block.
 * @see BxNewsModule::serviceEditBlock
 * BxDolService::call('news', 'edit_block', array($mixed));
 * @note is needed for internal usage.
 *
 * Get administration block.
 * @see BxNewsModule::serviceAdminBlock
 * BxDolService::call('news', 'admin_block', array($iStart, $iPerPage, $sFilterValue));
 * @note is needed for internal usage.
 *
 * Get block with all news ordered by the time of posting.
 * @see BxNewsModule::serviceArchiveBlock
 * BxDolService::call('news', 'archive_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get block with news marked as featured.
 * @see BxNewsModule::serviceFeaturedBlock
 * BxDolService::call('news', 'featured_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get block with news ordered by their rating.
 * @see BxNewsModule::serviceTopRatedBlock
 * BxDolService::call('news', 'top_rated_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get block with all news ordered by their popularity(number of views).
 * @see BxNewsModule::servicePopularBlock
 * BxDolService::call('news', 'popular_block', array($iStart, $iPerPage));
 * @note is needed for internal usage.
 *
 * Get the block with news content for view news page.
 * @see BxNewsModule::serviceViewBlock
 * BxDolService::call('news', 'view_block', array($sUri));
 * @note is needed for internal usage.
 *
 * Get the block with news comments for view news page.
 * @see BxNewsModule::serviceCommentBlock
 * BxDolService::call('news', 'comment_block', array($sUri));
 * @note is needed for internal usage.

 * Get the block with news votes for view news page.
 * @see BxNewsModule::serviceVoteBlock
 * BxDolService::call('news', 'vote_block', array($sUri));
 * @note is needed for internal usage.
 *
 * Get the block with news actions for view news page.
 * @see BxNewsModule::serviceActionBlock
 * BxDolService::call('news', 'action_block', array($sUri));
 * @note is needed for internal usage.
 *
 *
 * Alerts:
 * Alerts type/unit - 'news'
 * The following alerts are rised
 *
 *  post - news is added
 *      $iObjectId - news id
 *      $iSenderId - admin's id
 *
 *  edit - news was modified
 *      $iObjectId - news id
 *      $iSenderId - admin's id
 *
 *  featured - news was marked as featured
 *      $iObjectId - news id
 *      $iSenderId - admin's id
 *
 *  publish - news was published
 *      $iObjectId - news id
 *      $iSenderId - admin's id
 *
 *  unpublish - news was unpublished
 *      $iObjectId - news id
 *      $iSenderId - admin's id
 *
 *  delete - news was deleted
 *      $iObjectId - news id
 *      $iSenderId - admin's id
 *
 */
class BxNewsModule extends BxDolTextModule {
    /**
     * Constructor
     */
    function BxNewsModule($aModule) {
        parent::BxDolTextModule($aModule);

        //--- Define Membership Actions ---//
        defineMembershipActions(array('news delete'), 'ACTION_ID_');
    }

    /**
     * Service methods
     */
    function serviceNewsRss($iLength = 0) {
        return $this->actionRss($iLength);
    }

    /**
     * Action methods
     */
    function actionGetNews($sSampleType = 'all', $iStart = 0, $iPerPage = 0) {
        return $this->actionGetEntries($sSampleType, $iStart, $iPerPage);
    }

    /**
     * View list of latest news from mobile app
     */
    function actionMobileLatestNews() {

        bx_import('BxDolMobileTemplate');
        $oMobileTemplate = new BxDolMobileTemplate($this->_oConfig, $this->_oDb);
        $oMobileTemplate->pageStart();

        $sCaption = _t('_news_bcaption_latest');

        $aEntries = $this->_oDb->getEntries(array(
            'sample_type' => 'all',
            'sample_params' => '',
            'viewer_type' => $this->_oTextData->getViewerType(), 
            'start' => 0, 
            'count' => 10,
            'filter_value' => '',
        ));

        if (empty($aEntries)) {
            $oMobileTemplate->displayNoData($sCaption);
            return;
        }

        foreach ($aEntries as $aEntry) {
            $aVars = array (
                'content' => '<h2>' . $aEntry['caption'] . '</h2>' . getLocaleDate($aEntry['when_uts'], BX_DOL_LOCALE_DATE),
                'url' => bx_js_string(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'mobile_entry/' . $aEntry['id']),
            );
            echo $oMobileTemplate->parseHtmlByName('mobile_row.html', $aVars); 
        }

        $oMobileTemplate->pageCode($sCaption, false);
    }

    /**
     * News entry view from mobile app
     */
    function actionMobileEntry($iId) {
        
        bx_import('BxDolMobileTemplate');
        $oMobileTemplate = new BxDolMobileTemplate($this->_oConfig, $this->_oDb);
        $oMobileTemplate->pageStart();

        $aParams = array(
            'sample_type' => 'id', 
            'id' => (int)$iId,
        );
        $aEntry = $this->_oDb->getEntries($aParams);

        if (empty($aEntry)) {
            $oMobileTemplate->displayPageNotFound();
            return;
        }
    
        echo '<h1>' . $aEntry['caption'] . '</h1>';
        echo getLocaleDate($aEntry['when_uts'], BX_DOL_LOCALE_DATE);
        echo $aEntry['content'];

        $oMobileTemplate->pageCode($aEntry['caption']);
    }

    /**
     * Private methods.
     */
    function _createObjectCmts($iId) {
        return new BxNewsCmts($this->_oConfig->getCommentsSystemName(), $iId);
    }
    function _createObjectVoting($iId) {
        return new BxNewsVoting($this->_oConfig->getVotesSystemName(), $iId);
    }
    function _isDeleteAllowed($bPerform = false) {
        if(!isLogged())
            return false;

        if(isAdmin())
            return true;

        $aCheckResult = checkAction(getLoggedId(), ACTION_ID_NEWS_DELETE, $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }
}
?>
