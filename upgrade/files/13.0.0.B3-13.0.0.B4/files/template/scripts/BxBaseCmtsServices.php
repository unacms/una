<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Comments.
 */
class BxBaseCmtsServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetBlockView($sSystem = '', $iObjectId = 0, $iCommentId = 0)
    {
        if(empty($sSystem) && ($sSystem = bx_get('sys')) !== false)
            $sSystem = bx_process_input($sSystem);

        if(empty($iObjectId) && ($iObjectId = bx_get('id')) !== false)
            $iObjectId = bx_process_input($iObjectId, BX_DATA_INT);

        if(empty($iCommentId) && ($iCommentId = bx_get('cmt_id')) !== false)
            $iCommentId = bx_process_input($iCommentId, BX_DATA_INT);

        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iObjectId, true);
        if(!$oCmts)
            return '';

        return $oCmts->getCommentBlock($iCommentId);
    }

    public function serviceAlertResponseSysCmtsImagesFileDeleted($oAlert)
    {
        $iUniqId = (int)$oAlert->aExtras['ghost']['content_id'];

        $aCmt = BxDolCmts::getGlobalInfo($iUniqId);
        if(empty($aCmt) || !is_array($aCmt))
            return;

        $oCmts = BxDolCmts::getObjectInstance($aCmt['system_name'], 0, false);
        if(!$oCmts)
            return;

        $oCmts->getQueryObject()->deleteImages($aCmt['system_id'], false, $oAlert->iObject);
    }

    public function serviceGetMenuItemAddonVote($sSystem, $iId, $iCmtId)
    {
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iId);

        $oVote = $oCmts->getVoteObject($iCmtId);
        if($oVote !== false)
            return $oVote->getCounter();

        return '';
    }

    public function serviceGetLiveUpdate($sSystem, $iContentId, $iProfileId, $iCount = 0)
    {
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iContentId);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        $sKey = $oCmts->getNotificationId();

        bx_import('BxDolSession');
        if((int)BxDolSession::getInstance()->getValue($sKey) == 1)
            return false;

        $iCountNew = $oCmts->getCommentsCount($iContentId, -1, BX_CMT_FILTER_OTHERS);
        if($iCountNew == $iCount)
            return false;

        return array(
            'count' => $iCountNew, // required
            'method' => $oCmts->getJsObjectName() . '.showLiveUpdate(oData)', // required
            'data' => array(
                'code' => $oCmts->getLiveUpdate($iCount, $iCountNew)
            ),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
        );
    }

    public function serviceGetLiveUpdates($sSystem, $iContentId, $iProfileId, $iCount = 0)
    {
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iContentId);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        $sKey = $oCmts->getNotificationId();

        bx_import('BxDolSession');
        if((int)BxDolSession::getInstance()->getValue($sKey) == 1)
            return false;

        $iCountNew = $oCmts->getCommentsCount($iContentId, -1, BX_CMT_FILTER_OTHERS);
        if($iCountNew == $iCount)
            return false;

        return array(
            'count' => $iCountNew, // required
            'method' => $oCmts->getJsObjectName() . '.showLiveUpdates(oData)', // required
            'data' => array(
                'code' => $oCmts->getLiveUpdates($iCount, $iCountNew)
            ),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
        );
    }
    
    public function serviceManageTools($sType = 'common')
    {
        $oGrid = BxDolGrid::getObjectInstance('sys_cmts_administration');
        if(!$oGrid)
            return '';

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('BxDolCmtsManageTools.js'));
        $oTemplate->addCss(array('cmts_manage_tools.css'));
        $oTemplate->addJsTranslation(array('_sys_grid_search'));
        return array(
            'content' =>  $oGrid->getCode()
        );
    }

    /**
     * Comment Added for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $bCache = true;

        $iCmtUniqId = $aEvent['object_id'];
        $oCmts = BxDolCmts::getObjectInstanceByUniqId($iCmtUniqId);
        if(!$oCmts)
            return false;

        $aCmt = BxDolCmtsQuery::getCommentExtendedByUniqId($iCmtUniqId);
        if(empty($aCmt) || !is_array($aCmt))
            return false;

        $iCmtId = (int)$aCmt['cmt_id'];

        $iUserId = bx_get_logged_profile_id();
        $iAuthorId = (int)$aCmt['cmt_author_id'];
        $iAuthorIdAbs = abs($iAuthorId);
        if($iAuthorId < 0 && ((is_numeric($aEvent['owner_id']) && $iAuthorIdAbs == (int)$aEvent['owner_id']) || (is_array($aEvent['owner_id']) && in_array($iAuthorIdAbs, $aEvent['owner_id']))) && $iAuthorIdAbs != $iUserId)
            return false;

        $sCmtUrl = $oCmts->getViewUrl($iCmtId);

        $sSample = '_cmt_txt_sample_comment_single_with_article';
        $sSampleWoArticle = '_cmt_txt_sample_comment_single';
        $sAction = '';
        $sActionCustom = '_cmt_txt_added_sample_custom';
        $aActionCustomMarkers = array(
            'sample' => $sSample,
            'sample_url' => $sCmtUrl,
            'psample' => strip_tags($oCmts->getObjectTitle()),
            'psample_url' => $oCmts->getBaseUrl(),
        );

        $iOwnerId = $iAuthorIdAbs;
        if(isset($aEvent['object_privacy_view']) && (int)$aEvent['object_privacy_view'] < 0)
            $iOwnerId = abs($aEvent['object_privacy_view']);

        //--- Votes
        $aVotes = array();
        if(($oVotes = $oCmts->getVoteObject($iCmtUniqId)) !== false)
            $aVotes = array(
                'system' => $oVotes->getSystemName(),
                'object_id' => $iCmtUniqId,
                'count' => $aCmt['votes']
            );
        
        //--- Reactions
        $aReactions = array();
        if(($oReactions = $oCmts->getReactionObject($iCmtUniqId)) !== false)
            $aReactions = array(
                'system' => $oReactions->getSystemName(),
                'object_id' => $iCmtUniqId,
                'count' => $aCmt['rvotes']
            );

        //--- Scores
        $aScores = array();
        if(($oScores = $oCmts->getScoreObject($iCmtUniqId)) !== false)
            $aScores = array(
                'system' => $oScores->getSystemName(),
                'object_id' => $iCmtUniqId,
                'score' => $aCmt['score']
            );

        //--- Reports
        $aReports = array();
        if(($oReports = $oCmts->getReportObject($iCmtUniqId)) !== false)
            $aReports = array(
                'system' => $oReports->getSystemName(),
                'object_id' => $iCmtUniqId,
                'count' => $aCmt['reports']
            );

        return array(
            '_cache' => $bCache,
            'owner_id' => $iOwnerId,
            'object_owner_id' => $iAuthorId,
            'icon' => 'comments',
            'sample' => $sSample,
            'sample_wo_article' => $sSampleWoArticle,
            'sample_action' => $sAction,
            'sample_action_custom' => array(
                'content' => $sActionCustom,
                'markers' => $aActionCustomMarkers
            ),
            'url' => $sCmtUrl,
            'content' => array(
                'sample' => $sSample,
                'sample_wo_article' => $sSampleWoArticle,
                'sample_action' => $sAction,
                'url' => $sCmtUrl,
                'title' => '',
                'text' => $oCmts->getViewText($aCmt),
                'images' => array(),
                'images_attach' => array(),
                'videos' => array(),
                'videos_attach' => array(),
                'files' => array(),
                'files_attach' => $oCmts->getAttachments($iCmtId)
            ), //a string to display or array to parse default template before displaying.
            'date' => $aCmt['cmt_time'],
            'views' => array(),
            'votes' => $aVotes,
            'reactions' => $aReactions,
            'scores' => $aScores,
            'reports' => $aReports,
            'comments' => array(),
            'title' => !empty($aCmt['cmt_text']) ? $aCmt['cmt_text'] : '', //may be empty.
            'description' => '' //may be empty.
        );
    }

    /**
     * Comment added for Notifications module
     */
    public function serviceGetNotificationsCommentAdded($aEvent)
    {
        $aCommentGi = BxDolCmts::getGlobalInfo($aEvent['subobject_id']);
        if(empty($aCommentGi) || !is_array($aCommentGi))
            return [];

        $iContentId = (int)$aEvent['object_id'];

        $oComment = BxDolCmts::getObjectInstance($aCommentGi['system_name'], $iContentId);
        if(!$oComment)
            return [];

        $aCommentSystem = $oComment->getSystemInfo();
        
        $oContentModule = BxDolModule::getInstance($aCommentSystem['module']);
        if(!$oContentModule)
            return [];

        $aContentInfo = $oContentModule->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return [];

        $CNF = &$oContentModule->_oConfig->CNF;
        if(empty($CNF['FIELD_ID']) || empty($CNF['FIELD_AUTHOR']) || !(isset($CNF['FIELD_TITLE']) || isset($CNF['FIELD_TEXT'])) || empty($CNF['URI_VIEW_ENTRY']))
            return [];

        $sEntryUrl = '{bx_url_root}' . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'subentry_sample' => $CNF['T']['txt_sample_comment_single'],
            'subentry_url' => '{bx_url_root}' . $oComment->getViewUrl((int)$aCommentGi['cmt_id'], false),
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    /**
     * Comment vote for Notifications module
     */
    public function serviceGetNotificationsVote($aEvent)
    {
        $iCmtIdUnique = (int)$aEvent['object_id'];

        $aCmtInfo = BxDolCmts::getGlobalInfo($iCmtIdUnique);
        if(empty($aCmtInfo) || !is_array($aCmtInfo))
            return array();

        $oCmts = BxDolCmts::getObjectInstance($aCmtInfo['system_name'], 0, false);
        if(!$oCmts || !$oCmts->isEnabled())
            return array();

        $oVote = $oCmts->getVoteObject($iCmtIdUnique);
        if(!$oVote)
            return array();

        $iCmtId = (int)$aCmtInfo['cmt_id'];
        $sCmtUrl = str_replace(BX_DOL_URL_ROOT, '{bx_url_root}', $oCmts->serviceGetLink($iCmtId));
        $sCmtCaption = strmaxtextlen($oCmts->serviceGetText($iCmtId), 20, '...');

        return array(
            'entry_sample' => $oCmts->getLanguageKey('txt_sample_single'),
            'entry_url' => $sCmtUrl,
            'entry_caption' => $sCmtCaption,
            'entry_author' => $aCmtInfo['author_id'],
            'subentry_sample' => $oCmts->getLanguageKey('txt_sample_vote_single'),
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    /**
     * Comment reaction for Notifications module
     */
    public function serviceGetNotificationsReaction($aEvent)
    {
    	$iCmtIdUnique = (int)$aEvent['object_id'];

        $aCmtInfo = BxDolCmts::getGlobalInfo($iCmtIdUnique);
        if(empty($aCmtInfo) || !is_array($aCmtInfo))
            return array();

        $oCmts = BxDolCmts::getObjectInstance($aCmtInfo['system_name'], 0, false);
        if(!$oCmts || !$oCmts->isEnabled())
            return array();

        $oReaction = $oCmts->getReactionObject($iCmtIdUnique);
        if(!$oReaction)
            return array();
        
        $aSubentry = $oReaction->getTrackBy(array('type' => 'id', 'id' => (int)$aEvent['subobject_id']));
        if(empty($aSubentry) || !is_array($aSubentry))
            return array();

        $aSubentrySampleParams = array();
        $aReaction = $oReaction->getReaction($aSubentry['reaction']);
        if(!empty($aReaction['title']))
            $aSubentrySampleParams[] = $aReaction['title'];
        else
            $aSubentrySampleParams[] = '_undefined';

        $iCmtId = (int)$aCmtInfo['cmt_id'];
        $sCmtUrl = str_replace(BX_DOL_URL_ROOT, '{bx_url_root}', $oCmts->serviceGetLink($iCmtId));
        $sCmtCaption = strmaxtextlen($oCmts->serviceGetText($iCmtId), 20, '...');

        return array(
            'entry_sample' => $oCmts->getLanguageKey('txt_sample_single'),
            'entry_url' => $sCmtUrl,
            'entry_caption' => $sCmtCaption,
            'entry_author' => $aCmtInfo['author_id'],
            'subentry_sample' => $oCmts->getLanguageKey('txt_sample_reaction_single'),
            'subentry_sample_params' => $aSubentrySampleParams,
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    /**
     * Comment score -> vote up for Notifications module
     */
    public function serviceGetNotificationsScoreUp($aEvent)
    {
    	return $this->_serviceGetNotificationsScore('up', $aEvent);
    }

    /**
     * Comment score -> vote down for Notifications module
     */
    public function serviceGetNotificationsScoreDown($aEvent)
    {
    	return $this->_serviceGetNotificationsScore('down', $aEvent);
    }

    protected function _serviceGetNotificationsScore($sType, $aEvent)
    {
        $iCmtIdUnique = (int)$aEvent['object_id'];

        $aCmtInfo = BxDolCmts::getGlobalInfo($iCmtIdUnique);
        if(empty($aCmtInfo) || !is_array($aCmtInfo))
            return array();

        $oCmts = BxDolCmts::getObjectInstance($aCmtInfo['system_name'], 0, false);
        if(!$oCmts || !$oCmts->isEnabled())
            return array();

        $oScore = $oCmts->getScoreObject($iCmtIdUnique);
        if(!$oScore)
            return array();

        $iCmtId = (int)$aCmtInfo['cmt_id'];
        $sCmtUrl = str_replace(BX_DOL_URL_ROOT, '{bx_url_root}', $oCmts->serviceGetLink($iCmtId));
        $sCmtCaption = strmaxtextlen($oCmts->serviceGetText($iCmtId), 20, '...');

        return array(
            'entry_sample' => $oCmts->getLanguageKey('txt_sample_single'),
            'entry_url' => $sCmtUrl,
            'entry_caption' => $sCmtCaption,
            'entry_author' => $aCmtInfo['author_id'],
            'subentry_sample' => $oCmts->getLanguageKey('txt_sample_score_' . $sType . '_single'),
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }
}

/** @} */
