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
    protected static $_sPrefixLoad = '#-#';
    protected static $_sPrefixRetrieve = '|-|';
    protected static $_sParamAllowDelete = 'allow_delete';

    protected $_oQueryAgents;
    
    protected $_oAI;
    protected $_iProfileIdAi;
    
    protected $_iAssistantId;
    protected $_sAssistantUrl;

    protected $_bAuto;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        $this->_sTableImages = 'sys_agents_assistants_chats_files';

        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolStudioTemplate::getInstance();

        $this->_sFormObject = 'sys_agents_comment';
        $this->_sFormDisplayPost = 'sys_agents_comment_post';
        $this->_sFormDisplayEdit = 'sys_agents_comment_edit';

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

    public function getComment($mixedCmt, $aBp = [], $aDp = [])
    {
        $aCmt = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;
        if(!$aCmt)
            return '';

        $iObjId = (int)$this->getId();
        $iCmtId = (int)$aCmt['cmt_id'];
        if($this->_isShowAnswerLoad($aCmt))
            $this->_oQuery->updateComments(['cmt_text' => str_replace(self::$_sPrefixLoad, self::$_sPrefixRetrieve, $aCmt['cmt_text'])], ['cmt_id' => $iCmtId]);
        else if($this->_isShowAnswerRetrieve($aCmt) && ($mixedAiData = $this->_getAiData()) !== false) {
            list($iModelId, $sAssistantId, $sThreadId) = $mixedAiData;

            $aCmtRequest = $this->getCommentRow((int)str_replace(self::$_sPrefixRetrieve, '', $aCmt['cmt_text']));
            if(!empty($aCmtRequest) && is_array($aCmtRequest)) {
                if(($oAIModel = $this->_oAI->getModelObject($iModelId)) !== false) {
                    $oMessage = new BxDolAIMessageArray('hb');
                    if(!empty($aCmtRequest['cmt_text']))
                        $oMessage->addText($aCmtRequest['cmt_text']);

                    if($this->isAttachImageEnabled()) {
                        $aFiles = $this->_oQuery->getFiles($this->_aSystem['system_id'], $aCmtRequest['cmt_id']);
                        if(!empty($aFiles) && is_array($aFiles)) {
                            $oStorage = BxDolStorage::getObjectInstance($this->getStorageObjectName());
                            $oTranscoder = BxDolTranscoderImage::getObjectInstance($this->getTranscoderPreviewName());

                            foreach($aFiles as $aFile) {
                                $sFileUrl = $oStorage->getFileUrlById($aFile['image_id']);
                                if(!$sFileUrl)
                                    continue;

                                //--- Add image as part of message's content.
                                if($oTranscoder && $oTranscoder->isMimeTypeSupported($aFile['mime_type'])) {
                                    $oMessage->addImageUrl($sFileUrl);
                                    continue;
                                }

                                //--- Add non-image file as attchment.
                                if(($sFileContent = bx_file_get_contents($sFileUrl))) {
                                    $aFile = $oAIModel->callFiles([
                                        'content' => $sFileContent, 
                                        'name' => $aFile['file_name'], 
                                        'mime' => $aFile['mime_type']
                                    ]);
                                    if($aFile !== false)
                                        $oMessage->addAttachments($aFile['id']);
                                }
                            }
                        }
                    }

                    if(empty($sThreadId) && ($aResponseInit = $oAIModel->getResponseInit(BX_DOL_AI_ASSISTANT, $oMessage, ['assistant_id' => $sAssistantId])) !== false) {
                        $sThreadId = $aResponseInit['params']['thread_id'];

                        $this->_oAI->updateAssistantChatById($iObjId, [
                            'ai_thread_id' => $sThreadId
                        ]);
                    }

                    if(!empty($sThreadId)) {
                        $sResponse = $oAIModel->getResponse(BX_DOL_AI_ASSISTANT, $oMessage, [
                            'thread_id' => $sThreadId, 
                            'assistant_id' => $sAssistantId
                        ]);

                        if($sResponse === false && $oAIModel->isError())
                            $sResponse = $oAIModel->getErrorMessage();

                        if($sResponse) {
                            $this->_oQuery->updateComments(['cmt_text' => $sResponse], ['cmt_id' => $iCmtId]);

                            $aCmt['cmt_text'] = $sResponse;
                        }
                    }
                }
            }
        }

        return parent::getComment($aCmt, $aBp, array_merge($aDp, ['class_comment' => $this->_sStylePrefix . '-agents']));
    }

    public function isAttachImageEnabled()
    {
        return true;
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

        $mixedResultAuto = $this->addAuto([
            'cmt_author_id' => $this->_iProfileIdAi,
            'cmt_parent_id' => 0,
            'cmt_text' => self::$_sPrefixLoad . $iCmtId
        ], true);

        if($mixedResultAuto !== false)
            $mixedResult['id'] .= ',' . $mixedResultAuto['id'];

        return $mixedResult;
    }

    public function registerTranscoders()
    {
        parent::registerTranscoders();

        $aTranscoders = [
            $this->getTranscoderPreviewName()
        ];

        BxDolTranscoderImage::registerHandlersArray($aTranscoders);
    }

    public function unregisterTranscoders()
    {
        parent::unregisterTranscoders();

        $aTranscoders = [
            $this->getTranscoderPreviewName()
        ];

        BxDolTranscoderImage::unregisterHandlersArray($aTranscoders);
        BxDolTranscoderImage::cleanupObjectsArray($aTranscoders);
    }

    protected function _getActionsBox(&$aCmt, $aBp = [], $aDp = [])
    {
        if(!$this->isAllowDelete())
            return '';

        return $this->_oTemplate->parseHtmlByName('agents_comment_actions_assistant.html', [
            'js_object' => $this->_sJsObjName,
            'id' => $aCmt['cmt_id']
        ]);
    }

    protected function _getCountersBox(&$aCmt, $aBp = [], $aDp = [])
    {
        return '';
    }

    protected function _getFormBox($sType, $aBp, $aDp)
    {
        return parent::_getFormBox($sType, $aBp, array_merge($aDp, [
            'min_post_form' => false, 
            'class_body' => $this->_sStylePrefix . '-body-agents'
        ]));
    }
    
    protected function _getForm($sAction, $iId, $aDp = [])
    {
        $oForm = parent::_getForm($sAction, $iId, $aDp);

        if(isset($oForm->aInputs['cmt_text'])) {
            $oForm->aInputs['cmt_text']['caption'] = '';
            $oForm->aInputs['cmt_text']['db']['pass'] = 'xss';
        }           

        if(isset($oForm->aInputs['cmt_submit']))
            $oForm->aInputs['cmt_submit']['value'] = $this->_oTemplate->parseIcon('arrow-right');

        return $oForm;
    }

    protected function _getFormObject($sAction = BX_CMT_ACTION_POST)
    {
        $oResult = parent::_getFormObject($sAction);
        if(!isset($oResult->aInputs['cmt_image']))
            return $oResult;

        $oResult->aInputs['cmt_image'] = array_merge($oResult->aInputs['cmt_image'], [
            'storage_object' => 'sys_agents_assistants_chats_files',
            'images_transcoder' => 'sys_agents_assistants_chats_files_preview',
            'upload_buttons_titles' => ['HTML5' => 'paperclip']
        ]);

        return $oResult;
    }

    protected function _getTmplVarsText($aCmt)
    {
        $bLoad = $this->_isShowAnswerLoad($aCmt);
        $bRetrieve = $this->_isShowAnswerRetrieve($aCmt);

        $iId = (int)$aCmt['cmt_id'];
        $sText = $aCmt['cmt_text'];
        if(!$bLoad && !$bRetrieve)
            $sText = nl2br($this->_prepareTextForOutput($sText, $iId));

        if($bLoad)
            $sText = $this->_oTemplate->parseHtmlByName('agents_comment_loading.html', [
                'style_prefix' => $this->_sStylePrefix,
                'js_object' => $this->_sJsObjName,
                'html_id' => 'cmt' . $iId . '-loading',
                'id' => $iId
            ]);
        
        if($bRetrieve)
            $sText = _t('_sys_agents_assistants_chats_err_no_response');

        return [
            'style_prefix' => $this->_sStylePrefix,
            'text' => $sText
        ];
    }

    protected function _getAiData()
    {
        $aChat = $this->_oAI->getAssistantChatById((int)$this->getId());
        if(empty($aChat) || !is_array($aChat))
            return false;

        $aAssistant = $this->_oAI->getAssistantById($aChat['assistant_id']);
        if(empty($aAssistant) || !is_array($aAssistant))
            return false;

        return [$aAssistant['model_id'], $aAssistant['ai_asst_id'], $aChat['ai_thread_id']];
    }

    protected function _isShowAnswerLoad($aCmt)
    {
        return $aCmt['cmt_author_id'] == $this->_iProfileIdAi && strpos($aCmt['cmt_text'], self::$_sPrefixLoad) !== false;
    }
    
    protected function _isShowAnswerRetrieve($aCmt)
    {
        return $aCmt['cmt_author_id'] == $this->_iProfileIdAi && strpos($aCmt['cmt_text'], self::$_sPrefixRetrieve) !== false;
    }
}

/** @} */
