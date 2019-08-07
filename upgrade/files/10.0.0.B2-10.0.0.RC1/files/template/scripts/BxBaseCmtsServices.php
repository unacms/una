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
        $sCmtUrl = $oCmts->serviceGetLink($iCmtId);
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
        $sCmtUrl = $oCmts->serviceGetLink($iCmtId);
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
        $sCmtUrl = $oCmts->serviceGetLink($iCmtId);
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
