<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAsstChatsCmts extends BxTemplCmts
{
    protected static $_sParamAllowDelete = 'allow_delete';

    protected $_oQueryAgents;
    
    protected $_oAI;
    protected $_iProfileIdAi;
    
    protected $_iAssistantId;
    protected $_sAssistantUrl;

    protected $_bAuto;

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

        $this->_oAI = BxDolAI::getInstance();
        $this->_iProfileIdAi = $this->_oAI->getProfileId();

        $this->_iAssistantId = 0;
        if(($iAssistantId = bx_get('aid')) !== false) {
            $this->_iAssistantId = bx_process_input($iAssistantId, BX_DATA_INT);
            $this->_aMarkers['assistant_id'] = $this->_iAssistantId;
        }
        $this->_sAssistantUrl = BX_DOL_URL_STUDIO . bx_append_url_params('agents.php', ['page' => 'assistants', 'spage' => 'chats', 'aid' => $this->_iAssistantId]);

        if(!$this->isParam(self::$_sParamAllowDelete))
            $this->setAllowDelete(true);

        $this->_bAuto = false;
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

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }

    public function getCommentsBlock($aBp = [], $aDp = [])
    {
        $aComments = parent::getCommentsBlock($aBp, array_merge($aDp, ['in_designbox' => false]));
        if(empty($aComments['content']))
            return MsgBox(_t('_error occured'));

        $aChat = $this->_oQueryAgents->getChatsBy(['sample' => 'id', 'id' => (int)$this->getId()]);
        if(empty($aChat) || !is_array($aChat))
            return MsgBox(_t('_error occured'));
        
        $aAssistant = $this->_oQueryAgents->getAssistantsBy(['sample' => 'id', 'id' => (int)$aChat['assistant_id']]);
        if(empty($aAssistant) || !is_array($aAssistant))
            return MsgBox(_t('_error occured'));

        return $aComments['content'];
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

    public function isAllowDelete()
    {
        return !$this->isParam(self::$_sParamAllowDelete) || (int)$this->getParam(self::$_sParamAllowDelete) != 0;
    }

    public function setAllowDelete($bAllow)
    {
        $this->setParam(self::$_sParamAllowDelete, $bAllow ? 1 : 0);
    }

    public function addAuto($aValues, $bUnsetForm = false)
    {
        $this->_bAuto = true;
        $mixedResult = $this->add($aValues);
        $this->_bAuto = false;

        if($bUnsetForm)
            $this->_unsetFormObject();

        return $mixedResult;
    }

    public function onPostAfter($iCmtId, $aDp = [])
    {
        $mixedResult = parent::onPostAfter($iCmtId, $aDp);
        if($this->_bAuto || $mixedResult === false) 
            return $mixedResult;

        $iObjId = (int)$this->getId();
        $aChat = $this->_oAI->getAssistantChatById($iObjId);
        if(empty($aChat) || !is_array($aChat))
            return $mixedResult;

        $aAssistant = $this->_oAI->getAssistantById($aChat['assistant_id']);
        if(empty($aAssistant) || !is_array($aAssistant))
            return $mixedResult;

        $aComment = $this->_oQuery->getCommentSimple($iObjId, $iCmtId);
        if(empty($aComment) || !is_array($aComment))
            return $mixedResult;
        
        $sMessage = $aComment['cmt_text'];
        $sAssistantId = $aAssistant['ai_asst_id'];
        $sThreadId = $aChat['ai_thread_id'];

        $oAIModel = $this->_oAI->getModelObject($aAssistant['model_id']);
        if(empty($sThreadId) && ($aResponseInit = $oAIModel->getResponseInit(BX_DOL_AI_ASSISTANT, $sMessage, ['assistant_id' => $sAssistantId])) !== false) {
            $sThreadId = $aResponseInit['params']['thread_id'];

            $this->_oAI->updateAssistantChatById($iObjId, [
                'ai_thread_id' => $sThreadId
            ]);
        }
        if(empty($sThreadId))
            return $mixedResult;

        if(($sResponse = $oAIModel->getResponse(BX_DOL_AI_ASSISTANT, $sMessage, ['thread_id' => $sThreadId, 'assistant_id' => $sAssistantId])) !== false) {
            $mixedResultAuto = $this->addAuto([
                'cmt_author_id' => $this->_iProfileIdAi,
                'cmt_parent_id' => 0,
                'cmt_text' => $sResponse
            ], true);

            if($mixedResultAuto !== false)
                $mixedResult['id'] .= ',' . $mixedResultAuto['id'];
        }

        return $mixedResult;
    }

    protected function _getActionsBox(&$aCmt, $aBp = [], $aDp = [])
    {
        if(!$this->isAllowDelete())
            return '';

        return $this->_oTemplate->parseLink('javascript:void(0)', _t('_sys_menu_item_title_cmts_item_delete'), [
            'class' => 'bx-btn bx-btn-small',
            'onclick' => $this->_sJsObjName . '.cmtRemove(this, ' . $aCmt['cmt_id'] . ')',
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
    
    protected function _getForm($sAction, $iId, $aDp = [])
    {
        $oForm = parent::_getForm($sAction, $iId, $aDp);

        $oForm->aInputs['cmt_text']['caption'] = '';
        $oForm->aInputs['cmt_text']['db']['pass'] = 'xss';

        if(isset($aDp['mode']) && $aDp['mode'] == 'compact')
            $oForm->aInputs['cmt_submit']['value'] = $this->_oTemplate->parseIcon('arrow-right');

        return $oForm;
    }

    protected function _prepareTextForOutput($s, $iCmtId = 0)
    {
        return nl2br(parent::_prepareTextForOutput($s, $iCmtId));
    }
}

/** @} */
