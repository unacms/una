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
    protected $_sUrlPageAgents;
    protected $_iProfileIdAi;

    protected $_bAuto;
    
    protected $_oAI;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolStudioTemplate::getInstance();
        
        $this->_sTmplNameItemContent = 'agents_comment_content.html';
        $this->_bLiveUpdates = false;

        $this->_oQueryAgents = new BxDolStudioAgentsQuery();
        $this->_sUrlPageAgents = BX_DOL_URL_STUDIO . 'agents.php?page=automators';
        $this->_iProfileIdAi = BxDolAI::getInstance()->getProfileId();

        $this->_bAuto = false;
        
        $this->_oAI = BxDolAI::getInstance();
    }

    public function actionGetCmt ()
    {
        if(!$this->isEnabled())
            return echoJson([]);

        if($this->isViewAllowed() !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson([]);

        $mixedCmtId = bx_process_input(bx_get('Cmt'));
        $sCmtBrowse = ($sCmtBrowse = bx_get('CmtBrowse')) !== false ? bx_process_input($sCmtBrowse, BX_DATA_TEXT) : '';
        $sCmtDisplay = ($sCmtDisplay = bx_get('CmtDisplay')) !== false ? bx_process_input($sCmtDisplay, BX_DATA_TEXT) : '';

        $aCmtIds = strpos($mixedCmtId, ',') !== false ? explode(',', $mixedCmtId) : [$mixedCmtId];

        $sContent = '';
        foreach($aCmtIds as $iCmtId)
            $sContent .= $this->getComment((int)$iCmtId, ['type' => $sCmtBrowse], ['type' => $sCmtDisplay, 'dynamic_mode' => true]);

        $aCmt = $this->getCommentRow((int)reset($aCmtIds));
        echoJson([
            'parent_id' => $aCmt['cmt_parent_id'],
            'vparent_id' => $aCmt['cmt_parent_id'],
            'content' => $sContent
        ]);
    }
    
    public function actionApproveCode()
    {
        if(!$this->isEnabled())
            return echoJson([]);

        $iCmt = bx_process_input(bx_get('Cmt'), BX_DATA_INT);
        $aCmt = $this->getCommentRow($iCmt);
        if(empty($aCmt) || !is_array($aCmt))
            return echoJson([]);
        
        $iObjId = (int)$this->getId();
        
        $aAutomator = $this->_oAI->getAutomator($iObjId, true);
        $aAutomator['code'] = $aCmt['cmt_text'];

/*
 * TODO: Improve code evaluation.
 * 
        $oAlert = new BxDolAlerts('test', 'test', -1, -1, []);
        $sRv = $this->_oAI->evalCode($aAutomator, false, ['alert' => $oAlert]);
        if ($sRv != ''){
            return echoJson(['msg' => 'Error in code: ' . $sRv]);
        }
*/

        if(!$this->_oQueryAgents->updateAutomators(['code' => $aCmt['cmt_text'], 'status' => 'ready'], ['id' => (int)$this->getId()]))
            return echoJson([]);

        return echoJson(['redirect' => $this->_sUrlPageAgents]);
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
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
        $aComments = $this->_oQuery->getCommentsBy(['type' => 'latest', 'object_id' => (int)$this->getId(), 'start' => 0, 'per_page' => 1]);
        if(!empty($aComments) && is_array($aComments)) {
            $aLast = current($aComments);
            if($aLast['cmt_author_id'] != $this->_iProfileIdAi)
                return '';
        }

        return parent::getFormBoxPost($aBp, $aDp);
    }

    public function isAttachImageEnabled()
    {
        return false;
    }

    public function addAuto($aValues)
    {
        $this->_bAuto = true;
        $mixedResult = $this->add($aValues);

        $this->_bAuto = false;
        return $mixedResult;
    }

    public function onPostAfter($iCmtId, $aDp = [])
    {
        $mixedResult = parent::onPostAfter($iCmtId, $aDp);
        if($this->_bAuto || $mixedResult === false) 
            return $mixedResult;

        $iObjId = (int)$this->getId();
        $aAutomator = $this->_oAI->getAutomator($iObjId, true);

        $aComments = $this->_oQuery->getCommentsBy(['type' => 'object_id', 'object_id' => $iObjId]);
        if($aAutomator['type'] == BX_DOL_AI_AUTOMATOR_EVENT && !empty($aAutomator['params']['trigger']))
            $aComments[0]['cmt_text'] .= $aAutomator['params']['trigger'];

        $aMessages = [];
        foreach($aComments as $aComment)
            $aMessages[] = [
                'ai' => (int)$aComment['cmt_author_id'] == $this->_iProfileIdAi,
                'content' => $aComment['cmt_text']
            ];

        $oAIModel = $this->_oAI->getModelObject($aAutomator['model_id']);
        if(($sResponse = $oAIModel->getResponse($aAutomator['type'], $aMessages, $aAutomator['params'])) !== false) {
            $mixedResultAuto = $this->addAuto([
                'cmt_author_id' => $this->_iProfileIdAi,
                'cmt_parent_id' => 0,
                'cmt_text' => $sResponse
            ]);

            if($mixedResultAuto !== false)
                $mixedResult['id'] .= ',' . $mixedResultAuto['id'];
        }

        return $mixedResult;
    }

    protected function _getActionsBox(&$aCmt, $aBp = [], $aDp = [])
    {
        if((int)$aCmt['cmt_author_id'] != $this->_iProfileIdAi)
            return parent::_getActionsBox($aCmt, $aBp, array_merge($aDp, ['view_only' => true]));

        return $this->_oTemplate->parseLink('javascript:void(0)', _t('_sys_agents_automators_btn_approve'), [
            'class' => 'bx-btn bx-btn-small bx-btn-primary',
            'onclick' => $this->getPageJsObject() . '.approveCode(this, ' . $aCmt['cmt_id'] . ')',
        ]);
    }

    protected function _getCountersBox(&$aCmt, $aBp = [], $aDp = [])
    {
        return '';
    }

    protected function _getFormBox($sType, $aBp, $aDp)
    {
        return parent::_getFormBox($sType, $aBp, array_merge($aDp, ['min_post_form' => false]));
    }

    protected function _getTmplVarsText($aCmt)
    {
        $aResult = parent::_getTmplVarsText($aCmt);

        if((int)$aCmt['cmt_author_id'] == $this->_iProfileIdAi)
            $aResult['text'] = '<pre>' . $aResult['text'] . '</pre>';

        return $aResult;
    }
    
    protected function _getForm($sAction, $iId)
    {
        $aResult = parent::_getForm($sAction, $iId);

        $aResult->aInputs['cmt_text']['db']['pass'] = 'xss';
        return $aResult;
    }
    
    protected function _prepareTextForOutput($s, $iCmtId = 0)
    {
        return nl2br(parent::_prepareTextForOutput($s, $iCmtId));
    }
}

/** @} */
