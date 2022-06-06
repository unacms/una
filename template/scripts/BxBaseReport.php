<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolReport
 */
class BxBaseReport extends BxDolReport
{
    protected $_bCssJsAdded;

    protected $_sJsObjClass;
    protected $_sJsObjName;
    protected $_sStylePrefix;

    protected $_aHtmlIds;

    protected $_aElementDefaults;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_bCssJsAdded = false;

        $this->_sJsObjClass = 'BxDolReport';
        $this->_sJsObjName = 'oReport' . bx_gen_method_name($sSystem, array('_' , '-')) . $iId;
        $this->_sStylePrefix = 'bx-report';

        $sHtmlId = str_replace(array('_' , ' '), array('-', '-'), $sSystem) . '-' . $iId;
        $this->_aHtmlIds = array(
            'main' => 'bx-report-' . $sHtmlId,
            'counter' => 'bx-report-counter-' . $sHtmlId,
            'do_link' => 'bx-report-do-link-' . $sHtmlId,
            'do_popup' => 'bx-report-do-popup-' . $sHtmlId,
            'do_form' => 'bx-report-do-form-' . $sHtmlId,
            'by_popup' => 'bx-report-by-popup-' . $sHtmlId
        );

        $this->_aElementDefaults = array(
            'show_do_report_as_button' => false,
            'show_do_report_as_button_small' => false,
            'show_do_report_icon' => true,
            'show_do_report_label' => false,
            'show_counter' => true,
            'show_counter_only' => true
        );

        $this->_sTmplContentElementBlock = $this->_oTemplate->getHtml('report_element_block.html');
        $this->_sTmplContentElementInline = $this->_oTemplate->getHtml('report_element_inline.html');
        $this->_sTmplContentDoAction = $this->_oTemplate->getHtml('report_do_report.html');
        $this->_sTmplContentDoActionLabel = $this->_oTemplate->getHtml('report_do_report_label.html');
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjName;
    }

    public function getJsScript($bDynamicMode = false)
    {
        $aParams = array(
            'sObjName' => $this->_sJsObjName,
            'sSystem' => $this->getSystemName(),
            'iAuthorId' => $this->_getAuthorId(),
            'iObjId' => $this->getId(),
            'sRootUrl' => BX_DOL_URL_ROOT,
            'sStylePrefix' => $this->_sStylePrefix,
            'aHtmlIds' => $this->_aHtmlIds,
            'sUnreportConfirm' => bx_js_string(_t('_report_do_unreport_confirm'))
        );
        $sCode = "var " . $this->_sJsObjName . " = new " . $this->_sJsObjClass . "(" . json_encode($aParams) . ");";

        return $this->_oTemplate->_wrapInTagJsCode($sCode);
    }

    public function getJsClick()
    {
        return $this->getJsObjectName() . '.report(this)';
    }

    public function getCounter($aParams = [])
    {
        $aParams = array_merge($this->_aElementDefaults, $aParams);

        $bShowDoReportAsButtonSmall = isset($aParams['show_do_report_as_button_small']) && $aParams['show_do_report_as_button_small'] == true;
        $bShowDoReportAsButton = !$bShowDoReportAsButtonSmall && isset($aParams['show_do_report_as_button']) && $aParams['show_do_report_as_button'] == true;

        $aReport = $this->_oQuery->getReport($this->getId());

        $sClass = 'sys-action-counter';
        if(isset($aParams['show_counter_only']) && (bool)$aParams['show_counter_only'] === true)
            $sClass .= ' sys-ac-only';

        $sClass .= ' ' . $this->_sStylePrefix . '-counter';
        if($bShowDoReportAsButtonSmall)
            $sClass .= ' bx-btn-small-height';
        if($bShowDoReportAsButton)
            $sClass .= ' bx-btn-height';

        return $this->_oTemplate->parseLink('javascript:void(0)',  (int)$aReport['count'] > 0 ? $this->_getCounterLabel($aReport['count']) : '', array(
            'id' => $this->_aHtmlIds['counter'],
            'class' => $sClass, 
            'title' => _t('_report_do_report_by'),
            'onclick' => 'javascript:' . $this->getJsObjectName() . '.toggleByPopup(this)' 
        ));
    }

    public function getElementBlock($aParams = array())
    {
        $aParams['usage'] = BX_DOL_REPORT_USAGE_BLOCK;

        return $this->getElement($aParams);
    }

    public function getElementInline($aParams = array())
    {
        $aParams['usage'] = BX_DOL_REPORT_USAGE_INLINE;

        return $this->getElement($aParams);
    }

    public function getElement($aParams = array())
    {
    	$aParams = array_merge($this->_aElementDefaults, $aParams);
    	$bDynamicMode = isset($aParams['dynamic_mode']) && $aParams['dynamic_mode'] === true;

        $bShowDoReportAsButtonSmall = isset($aParams['show_do_report_as_button_small']) && $aParams['show_do_report_as_button_small'] == true;
        $bShowDoReportAsButton = !$bShowDoReportAsButtonSmall && isset($aParams['show_do_report_as_button']) && $aParams['show_do_report_as_button'] == true;
        $bShowCounter = isset($aParams['show_counter']) && $aParams['show_counter'] === true && $this->isAllowedReportView();

        $iObjectId = $this->getId();
        $iAuthorId = $this->_getAuthorId();
        $aReport = $this->_oQuery->getReport($iObjectId);

        if(!$this->isAllowedReport() && (!$this->isAllowedReportView() || (int)$aReport['count'] == 0))
            return '';

        $aParams['is_reported'] = $this->isPerformed($iObjectId, $iAuthorId) ? true : false;

        $sTmplName = $this->{'_getTmplContentElement' . bx_gen_method_name(!empty($aParams['usage']) ? $aParams['usage'] : BX_DOL_REPORT_USAGE_DEFAULT)}();
        return $this->_oTemplate->parseHtmlByContent($sTmplName, array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $this->_aHtmlIds['main'],
            'class' => $this->_sStylePrefix . ($bShowDoReportAsButton ? '-button' : '') . ($bShowDoReportAsButtonSmall ? '-button-small' : ''),
            'count' => $aReport['count'],
            'do_report' => $this->_getDoReport($aParams),
            'bx_if:show_counter' => array(
                'condition' => $bShowCounter,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'bx_if:show_hidden' => array(
                        'condition' => (int)$aReport['count'] == 0,
                        'content' => array()
                    ),
                    'counter' => $this->getCounter(array_merge($aParams, [
                        'show_counter_only' => false
                    ]))
                )
            ),
            'script' => $this->getJsScript($bDynamicMode)
        ));
    }
    
    public function getReportedByWithComments($sObjectNotes)
    {
        $aTmplReports = array();
        $aTypes = BxDolForm::getDataItems('sys_report_types');

        $aReports = $this->_oQuery->getPerformedBy($this->getId());
        foreach($aReports as $aReport) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $this->_getAuthorInfo($aReport['author_id']);

            $sText = bx_process_output($aReport['text'], BX_DATA_TEXT_MULTILINE);
            
            $oCmtsNotes = BxDolCmts::getObjectInstance($sObjectNotes, -$aReport['id'], true, $this->_oTemplate);
            $aCmtsNotes = $oCmtsNotes->getCommentsBlock(array(), array('in_designbox' => false));
            $iCmtsNotesCount = $oCmtsNotes->getCommentsCount();
           
            $aTmplReports[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'user_name' => $sUserName,
                'id' => $aReport['id'],
                'user_url' => $sUserUrl,
            	'type' => $aTypes[$aReport['type']],
                'comments_count' => _t('_report_comments_count', $iCmtsNotesCount),
                'comments' => $aCmtsNotes['content'],
                'date' => bx_time_js($aReport['date']),
            	'bx_if:show_text' => array(
                    'condition' => strlen($sText) > 0,
                    'content' => array(
                        'text' => $sText
                    )
            	)
            );
        }

        if(empty($aTmplReports))
            return '';

        return $this->_oTemplate->parseHtmlByName('report_by_list_with_comments.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplReports
        ));
    }

    protected function _getDoReport($aParams = array())
    {
    	$bReported = isset($aParams['is_reported']) && $aParams['is_reported'] === true;
        $bShowDoReportAsButtonSmall = isset($aParams['show_do_report_as_button_small']) && $aParams['show_do_report_as_button_small'] == true;
        $bShowDoReportAsButton = !$bShowDoReportAsButtonSmall && isset($aParams['show_do_report_as_button']) && $aParams['show_do_report_as_button'] == true;
        $bDisabled = !$this->isAllowedReport() || ($bReported && !$this->isUndo());

        $sClass = '';
        if($bShowDoReportAsButton)
            $sClass = 'bx-btn';
        else if ($bShowDoReportAsButtonSmall)
            $sClass = 'bx-btn bx-btn-small';

        if($bDisabled)
            $sClass .= $bShowDoReportAsButton || $bShowDoReportAsButtonSmall ? ' bx-btn-disabled' : 'bx-report-disabled';

        if($bReported)
            $sClass .= ' bx-report-reported';

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentDoAction(), array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $this->_aHtmlIds['do_link'],
            'class' => $sClass,
            'title' => bx_html_attribute(_t($this->_getTitleDoReport($bReported))),
        	'bx_if:show_onclick' => array(
                    'condition' => !$bDisabled,
                    'content' => array(
                        'js_object' => $this->getJsObjectName()
                    )
        	),
            'do_report' => $this->_getLabelDoReport($aParams),
        ));
    }

    protected function _getCounterLabel($iCount)
    {
        return _t('_report_counter', $iCount);
    }

    protected function _getLabelDoReport($aParams = array())
    {
    	$bReported = isset($aParams['is_reported']) && $aParams['is_reported'] === true;
        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentDoActionLabel(), array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:show_icon' => array(
                'condition' => isset($aParams['show_do_report_icon']) && $aParams['show_do_report_icon'] == true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'name' => $this->_getIconDoReport($bReported)
                )
            ),
            'bx_if:show_text' => array(
                'condition' => isset($aParams['show_do_report_label']) && $aParams['show_do_report_label'] == true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'text' => _t($this->_getTitleDoReport($bReported))
                )
            )
        ));
    }

    protected function _getReport()
    {
        if (!$this->isEnabled())
            return array('code' => 1, 'message' => _t('_report_err_not_enabled'));

        if(!$this->isAllowedReport())
            return array('code' => 2, 'message' => $this->msgErrAllowedReport());

        $iAuthorId = $this->_getAuthorId();
        $iAuthorNip = bx_get_ip_hash($this->_getAuthorIp());

        $iObjectId = $this->_iId;
        $iObjectAuthorId = $this->_oQuery->getObjectAuthorId($iObjectId);

        $bPerformed = $this->isPerformed($iObjectId, $iAuthorId);
        if($bPerformed) {
            if(!$this->isUndo())
                return array('code' => 4, 'message' => _t('_report_err_duplicate_report'));

            if(($iId = $this->_oQuery->putReport($iObjectId, $iAuthorId, true)) !== false) {
                $this->_trigger();

                bx_alert($this->_sSystem, 'undoReport', $iObjectId, $iAuthorId, array('report_id' => $iId, 'report_author_id' => $iAuthorId, 'object_author_id' => $iObjectAuthorId));
                bx_alert('report', 'undo', $iId, $iAuthorId, array('object_system' => $this->_sSystem, 'object_id' => $iObjectId, 'object_author_id' => $iObjectAuthorId));

                return $this->_returnReportData($iObjectId, $iId, !$bPerformed);
            }
        }
        
        
        $oForm = $this->_getFormObject();
        $oForm->setId($this->_aHtmlIds['do_form']);
        $oForm->setName($this->_aHtmlIds['do_form']);
        $oForm->aParams['db']['table'] = $this->_aSystem['table_track'];
        $oForm->aInputs['sys']['value'] = $this->_sSystem;
        $oForm->aInputs['object_id']['value'] = $this->_iId;
        $oForm->aInputs['action']['value'] = 'Report';

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iObjectId = $oForm->getCleanValue('object_id');
            $iObjectAuthorId = $this->_oQuery->getObjectAuthorId($iObjectId);

            if(!$this->isAllowedReport(true))
                return array('code' => 2, 'message' => $this->msgErrAllowedReport());               

            $sType = $oForm->getCleanValue('type');
            if(!in_array($sType, $this->_aTypes)) 
                return array('code' => 5, 'message' => _t('_report_err_wrong_type'));

            $sText = $oForm->getCleanValue('text');
            $sText = bx_process_input($sText, BX_DATA_TEXT_MULTILINE);
            $oForm->setSubmittedValue('text', $sText, $oForm->aFormAttrs['method']);

            $iId = (int)$oForm->insert(array('author_id' => $iAuthorId, 'author_nip' => $iAuthorNip, 'date' => time()));
            if($iId != 0 && $this->_oQuery->putReport($iObjectId, $iAuthorId)) {
                if(!empty($this->_sObjectCmts) && ($oCmts = BxDolCmts::getObjectInstance($this->_sObjectCmts, $this->getId()))) {
                    $sCmtText = '_report_comment';
                    if(!empty($sText))
                        $sCmtText = '_report_comment_with_note';

                    $aTypes = BxDolForm::getDataItems('sys_report_types');

                    $oCmts->add(array(
                        'cmt_author_id' => $iAuthorId,
                        'cmt_parent_id' => 0,
                        'cmt_text' => _t($sCmtText, $aTypes[$sType], $sText)
                    ));
                }

                $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Reported', array(
                    'report_type' => $sType,
                    'report_text' => $sText,
                    'report_url' => $this->getBaseUrl(),
                ));
                if($aTemplate)
                    sendMail(getParam('site_email'), $aTemplate['Subject'], $aTemplate['Body']);

                $mixedParams = $this->_prepareAuditParams($iObjectId, array('type' => $sType, 'text' => $sText));
                if($mixedParams) {
                    $sActionName = $mixedParams['action_name'];
                    $iAuditObjectId = $mixedParams['object_id'];
                    unset($mixedParams['action_name']);
                    unset($mixedParams['object_id']);
                    bx_audit($iAuditObjectId, $this->_sSystem, $sActionName, $mixedParams);
                }

                $aReport = $this->_oQuery->getReport($iObjectId);
                
                $iBlockContentAfter = (int)getParam('sys_security_block_content_after_n_reports');
                if ($iBlockContentAfter > 0 && $aReport['count'] >= $iBlockContentAfter){
                    $oModule = BxDolModule::getInstance($this->_aSystem['module_name']);
                    if($oModule)
                        $oModule->_oDb->updateStatusAdmin($iObjectId, false);
                }

                $this->_trigger();

                bx_alert($this->_sSystem, 'doReport', $iObjectId, $iAuthorId, array('report_id' => $iId, 'report_author_id' => $iAuthorId, 'object_author_id' => $iObjectAuthorId, 'type' => $sType, 'text' => $sText));
                bx_alert('report', 'do', $iId, $iAuthorId, array('object_system' => $this->_sSystem, 'object_id' => $iObjectId, 'object_author_id' => $iObjectAuthorId, 'type' => $sType, 'text' => $sText));

                return $this->_returnReportData($iObjectId, $iId, !$bPerformed, $aReport);
            }

            return array('code' => 3, 'message' => _t('_report_err_cannot_perform_action'));
        }

        $sPopupId = $this->_aHtmlIds['do_popup'];
        $sPopupContent = BxTemplFunctions::getInstance()->transBox($sPopupId, $this->_oTemplate->parseHtmlByName('report_do_report_form.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'js_object' => $this->getJsObjectName(),
            'form' => $oForm->getCode(),
            'form_id' => $oForm->id,
        )));

        return array('popup' => $sPopupContent, 'popup_id' => $sPopupId);
    }

    protected function _getReportedBy()
    {
        $aTmplReports = array();
        $aTypes = BxDolForm::getDataItems('sys_report_types');

        $aReports = $this->_oQuery->getPerformedBy($this->getId());
        foreach($aReports as $aReport) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $this->_getAuthorInfo($aReport['author_id']);

            $sText = bx_process_output($aReport['text'], BX_DATA_TEXT_MULTILINE);

            $aTmplReports[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'user_unit' => $sUserUnit,
            	'type' => $aTypes[$aReport['type']],
            	'bx_if:show_text' => array(
                    'condition' => strlen($sText) > 0,
                    'content' => array(
                        'text' => $sText
                    )
            	)
            );
        }

        if(empty($aTmplReports))
            $aTmplReports = MsgBox(_t('_Empty'));

        return $this->_oTemplate->parseHtmlByName('report_by_list.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplReports
        ));
    }

    protected function _returnReportData($iObjectId, $iReportId, $bPerformed, $aData = array())
    {
        $bUndo = $this->isUndo();
        if(empty($aData) || !is_array($aData))
            $aData = $this->_oQuery->getReport($iObjectId);

        return array(
            'eval' => $this->getJsObjectName() . '.onReport(oData, oElement)',
            'code' => 0,
            'id' => $iReportId, 
            'count' => $aData['count'], 
            'countf' => (int)$aData['count'] > 0 ? $this->_getCounterLabel($aData['count']) : '',
            'label_icon' => $this->_getIconDoReport($bPerformed),
            'label_title' => _t($this->_getTitleDoReport($bPerformed)),
            'disabled' => $bPerformed && !$bUndo,
        );
    }

    private function _prepareAuditParams($iObjectId, $aData)
    {
        $sModule = $this->_sSystem;
        $sKeyObjectContentInfo = 'OBJECT_CONTENT_INFO';
        $sActionName = '_sys_audit_action_report';
        
        if ($this->_sSystem == 'sys_cmts'){
            $aCommentData = BxDolCmtsQuery::getInfoByUniqId($iObjectId);
            $oComment = BxDolCmts::getObjectInstance($aCommentData['system_name'], $aCommentData['cmt_object_id']);
            $aComment = $oComment->getCommentSimple($aCommentData['cmt_id']);
            $aSystem = $oComment->getSystemInfo();
            
            $sModule = $aSystem['module'];
            $aData['comment_id'] = $aCommentData['cmt_id'];
            $aData['comment_author_id'] = $aComment['cmt_author_id'];
            $aData['comment_text'] = $aComment['cmt_text'];
            $iObjectId = $aCommentData['cmt_object_id'];
            
            $sKeyObjectContentInfo = 'OBJECT_CMTS_CONTENT_INFO';
            $sActionName = '_sys_audit_action_report_comment';
        }

        $oModule = BxDolModule::getInstance($sModule);
        if ($oModule) {
            $CNF = $oModule->_oConfig->CNF;

            $aContentInfo = BxDolRequest::serviceExists($sModule, 'get_all') ? BxDolService::call($sModule, 'get_all', array(array('type' => 'id', 'id' => $iObjectId))) : array();
                    
            return [
                'content_title' => !empty($CNF['FIELD_TITLE'])  ? $aContentInfo[$CNF['FIELD_TITLE']] : '',
                'content_info_object' => !empty($CNF[$sKeyObjectContentInfo]) ? $CNF[$sKeyObjectContentInfo] : '',
                'data' => $aData,
                'action_name' => $sActionName, 
                'object_id' => $iObjectId 
            ];
        }
        
        return false;
    }
}

/** @} */
