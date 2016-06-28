<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModProfileAlertsResponse extends BxDolAlertsResponse
{
    protected $MODULE;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    public function response($oAlert)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        // update picture field id when file is deleted
        if ($CNF['OBJECT_STORAGE'] == $oAlert->sUnit && 'file_deleted' == $oAlert->sAction) {
            $this->_oModule->_oDb->resetContentPictureByFileId($oAlert->iObject, $CNF['FIELD_PICTURE']);
        }
        if ($CNF['OBJECT_STORAGE_COVER'] == $oAlert->sUnit && 'file_deleted' == $oAlert->sAction) {
            $this->_oModule->_oDb->resetContentPictureByFileId($oAlert->iObject, $CNF['FIELD_COVER']);
        }        

        // re-translate timeline alert for timeline in this module for posts made by other profiles
        if ('bx_timeline' == $oAlert->sUnit && 'post_common' == $oAlert->sAction && ($oGroupProfile = BxDolProfile::getInstance($oAlert->aExtras['object_author_id'])) && $oGroupProfile->getModule() == $this->_oModule->getName() && $oGroupProfile->id() != $oAlert->iSender) {            
            $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
            bx_alert($this->_oModule->getName(), 'timeline_post_common', $aContentInfo[$CNF['FIELD_ID']], $oGroupProfile->id(), array('content' => $aContentInfo, 'group_profile' => $oGroupProfile->id(), 'profile' => $oAlert->iSender, 'notification_subobject_id' => $oAlert->iSender, 'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]));
        }
 
        if ($this->MODULE != $oAlert->sUnit)
            return;

        // timeline events to override permissions
        switch ($oAlert->sAction) {
        case 'timeline_view':
            $this->processTimelineView($oAlert, $oAlert->iObject, true);
            break;

        case 'timeline_comment':
        case 'timeline_report':
        case 'timeline_vote':
            $this->processTimelineEventsBoolResult($oAlert, $oAlert->iObject);
            break;

        case 'timeline_post':
            $this->processTimelineEventsCheckResult($oAlert, $oAlert->iObject);
            break;

        case 'timeline_delete':
            $this->processTimelineEventsCheckResult($oAlert, $oAlert->iObject, 'checkAllowedEdit');
            break;

        case 'timeline_share':
            $this->processTimelineShare($oAlert, $oAlert->iObject);
            break;
        }

    }

    protected function processTimelineView ($oAlert, $iGroupProfileId, $bDisableOwnerActions = false)
    {
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (CHECK_ACTION_RESULT_ALLOWED !== $this->_oModule->checkAllowedView($aContentInfo)) {
            $oAlert->aExtras['override_content'] = MsgBox(_t('_sys_access_denied_to_private_content'));
        }

        if ($bDisableOwnerActions) {
            foreach ($oAlert->aExtras['menu'] as $i => $r) {
                if ('timeline-view-owner' == $r['id'] || 'timeline-view-other'  == $r['id'])
                    unset($oAlert->aExtras['menu'][$i]);
            }
        }
    }

    protected function processTimelineEventsCheckResult ($oAlert, $iGroupProfileId, $sFunc = 'checkAllowedPost')
    {
        if ($oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (CHECK_ACTION_RESULT_ALLOWED === ($s = $this->_oModule->$sFunc($aContentInfo))) {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        } 
        else {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            $oAlert->aExtras['check_result'][CHECK_ACTION_MESSAGE] = $s;
        }
    }

    protected function processTimelineEventsBoolResult ($oAlert, $iGroupProfileId, $sFunc = 'checkAllowedPost')
    {
        if (isAdmin() || !$oAlert->aExtras['result'])
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        $oAlert->aExtras['result'] = (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->$sFunc($aContentInfo)) ? true : false;
    }

    protected function processTimelineShare ($oAlert, $iGroupProfileId)
    {
        if ($oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (BX_DOL_PG_ALL == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]) {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        }
        else {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            $oAlert->aExtras['check_result'][CHECK_ACTION_MESSAGE] = _t('_sys_access_denied_to_private_content');
        }
    }
}

/** @} */
