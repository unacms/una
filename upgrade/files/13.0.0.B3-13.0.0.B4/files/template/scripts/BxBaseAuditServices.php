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
class BxBaseAuditServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceManageTools()
    {
        $oGrid = BxDolGrid::getObjectInstance('sys_audit_administration');
        if(!$oGrid)
            return '';

        return [
            'content' =>  $oGrid->getCode()
        ];
    }
    
    public function serviceGetMemberships()
    {
        $aLevels = BxDolAcl::getInstance()->getMemberships(false, false, true, false);
        unset($aLevels[MEMBERSHIP_ID_NON_MEMBER]);
        return $aLevels;
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
