<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAutomatorsCmts extends BxTemplCmts
{
    protected $_oQueryAgents;
    protected $_iProfileAi;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
        
        $this->_oQueryAgents = new BxDolStudioAgentsQuery();

        $this->_iProfileAi = (int)getParam('sys_profile_bot'); 
    }

    public function getCommentsBlock($aBp = [], $aDp = [])
    {
        $aComments = parent::getCommentsBlock($aBp, ['in_designbox' => false]);
        if(empty($aComments['content']))
            return MsgBox(_t('_error occured'));

        $aAutomator = $this->_oQueryAgents->getAutomatorsBy(['sample' => 'id', 'id' => (int)$this->getId()]);
        if(empty($aAutomator) || !is_array($aAutomator))
            return MsgBox(_t('_error occured'));

        $aTmplVarsEvent = [];
        $bTmplVarsEvent = $aAutomator['type'] == 'event';
        if($bTmplVarsEvent)
            $aTmplVarsEvent = [
                'unit' => $aAutomator['alert_unit'],
                'action' => $aAutomator['alert_action']
            ];
        
        $aTmplVarsScheduler = [];
        $bTmplVarsScheduler = $aAutomator['type'] == 'scheduler';
        if($bTmplVarsScheduler) {
            $aParams = [];
            if(!empty($aAutomator['params']))
                $aParams = json_decode($aAutomator['params'], true);

            $aTmplVarsScheduler = [
                'time' => $aParams['scheduler_time'],
            ];
        }

        $sAutomator = $this->_oTemplate->parseHtmlByName('agents_automator_info.html', [
            'type' => _t('_sys_agents_automators_field_type_' . $aAutomator['type']),
            'bx_if:show_event' => [
                'condition' => $bTmplVarsEvent,
                'content' => $aTmplVarsEvent
            ],
            'bx_if:show_scheduler' => [
                'condition' => $bTmplVarsScheduler,
                'content' => $aTmplVarsScheduler
            ],
            'code' => $aAutomator['code']
        ]);

        return [
            $sAutomator,
            $aComments['content']
        ];
    }

    public function getFormBoxPost($aBp = [], $aDp = [])
    {
        $aComments = $this->_oQuery->getCommentsBy(['type' => 'latest', 'start' => 0, 'per_page' => 1]);
        if(!empty($aComments) && is_array($aComments)) {
            $aLast = current($aComments);
            if($aLast['cmt_author_id'] != $this->_iProfileAi)
                return '';
        }

        return parent::getFormBoxPost($aBp, $aDp);
    }

    public function isAttachImageEnabled()
    {
        return false;
    }

    public function onPostAfter($iCmtId, $aDp = [])
    {
        $mixedResult = parent::onPostAfter($iCmtId, $aDp);
        if($mixedResult !== false) {
            $iObjId = (int)$this->getId();

            $aCmt = $this->_oQuery->getCommentSimple($iObjId, $iCmtId);
            $aAutomator = $this->_oQueryAgents->getAutomatorsBy(['sample' => 'id_full', 'id' => $iObjId]);

            //TODO: Send to AI $aCmt['cmt_text']
        }

        return $mixedResult;
    }

    protected function _getActionsBox(&$aCmt, $aBp = [], $aDp = [])
    {
        return parent::_getActionsBox($aCmt, $aBp, array_merge($aDp, ['view_only' => true]));
    }
    
    protected function _getFormBox($sType, $aBp, $aDp)
    {
        return parent::_getFormBox($sType, $aBp, array_merge($aDp, ['min_post_form' => false]));
    }
}

/** @} */
