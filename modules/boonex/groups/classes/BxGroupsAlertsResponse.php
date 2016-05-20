<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * alerts handler
 */
class BxGroupsAlertsResponse extends BxDolAlertsResponse
{
    protected $_oModule;

    public function response($oAlert)
    {
        $this->_oModule = BxDolModule::getInstance('bx_groups');
 
        if ('bx_groups_fans' == $oAlert->sUnit && 'connection_added' == $oAlert->sAction) {
            $this->_oModule->serviceAddMutualConnection($oAlert->aExtras['content'], $oAlert->aExtras['initiator']);
        }

        if ('profile' == $oAlert->sUnit && 'delete' == $oAlert->sAction) {
            $this->_oModule->serviceDeleteProfileFromFansAndAdmins($oAlert->iObject);
            $this->_oModule->serviceReassignEntitiesByAuthor($oAlert->iObject);
        }

        if ('bx_groups' != $oAlert->sUnit)
            return;

        switch ($oAlert->sAction) {

            case 'timeline_view':
                $this->processTimelineView($oAlert, $oAlert->iObject);
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

    protected function processTimelineView ($oAlert, $iGroupProfileId)
    {
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (CHECK_ACTION_RESULT_ALLOWED !== $this->_oModule->checkAllowedView($aContentInfo)) {
            $oAlert->aExtras['override_content'] = MsgBox(_t('_sys_access_denied_to_private_content'));
        }

        foreach ($oAlert->aExtras['menu'] as $i => $r) {
            if ('timeline-view-owner' == $r['id'] || 'timeline-view-other'  == $r['id'])
                unset($oAlert->aExtras['menu'][$i]);
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
        if (!$oAlert->aExtras['result'])
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
            break;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            break;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (BX_DOL_PG_ALL == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']] || 'c' == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]) {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        }
        else {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            $oAlert->aExtras['check_result'][CHECK_ACTION_MESSAGE] = _t('_sys_access_denied_to_private_content');
        }
    }
}

/** @} */
