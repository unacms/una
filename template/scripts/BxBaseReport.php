<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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

    protected $_sTmplNameCounter;
    protected $_sTmplNameDoReport;

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
			'show_counter' => true
        );

        $this->_sTmplNameCounter = 'report_counter.html';
        $this->_sTmplNameDoReport = 'report_do_report.html';
    }

    public function addCssJs($bDynamicMode = false)
    {
    	if($bDynamicMode || $this->_bCssJsAdded)
    		return;

    	$this->_oTemplate->addJs(array('BxDolReport.js'));
        $this->_oTemplate->addCss(array('report.css'));

        $this->_bCssJsAdded = true;
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
            'aHtmlIds' => $this->_aHtmlIds
        );
        $sCode = $this->_sJsObjName . " = new " . $this->_sJsObjClass . "(" . json_encode($aParams) . ");";

        if($bDynamicMode) {
			$sCode = "var " . $this->_sJsObjName . " = null; 
			$.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('BxDolReport.js'), BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
				bx_get_style('" . bx_js_string($this->_oTemplate->getCssUrl('report.css'), BX_ESCAPE_STR_APOS) . "');
				" . $sCode . "
        	}); ";
        }
        else
        	$sCode = "var " . $sCode;

        $this->addCssJs($bDynamicMode);
        return $this->_oTemplate->_wrapInTagJsCode($sCode);
    }

    public function getJsClick()
    {
        return $this->getJsObjectName() . '.report(this)';
    }

    public function getCounter($aParams = array())
    {
        $sJsObject = $this->getJsObjectName();

        $aReport = $this->_oQuery->getReport($this->getId());

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameCounter, array(
            'href' => 'javascript:void(0)',
            'title' => _t('_report_do_report_by'),
            'bx_repeat:attrs' => array(
                array('key' => 'id', 'value' => $this->_aHtmlIds['counter']),
                array('key' => 'class', 'value' => $this->_sStylePrefix . '-counter'),
                array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.toggleByPopup(this)')
            ),
            'content' => (int)$aReport['count'] > 0 ? $this->_getLabelCounter($aReport['count']) : ''
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

        $aParams['is_reported'] = $this->_oQuery->isPerformed($iObjectId, $iAuthorId) ? true : false;

        $sTmplName = 'report_element_' . (!empty($aParams['usage']) ? $aParams['usage'] : BX_DOL_REPORT_USAGE_DEFAULT) . '.html';
        return $this->_oTemplate->parseHtmlByName($sTmplName, array(
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
                    'counter' => $this->getCounter()
                )
            ),
            'script' => $this->getJsScript($bDynamicMode)
        ));
    }

    protected function _getDoReport($aParams = array())
    {
    	$bReported = isset($aParams['is_reported']) && $aParams['is_reported'] === true;
        $bShowDoReportAsButtonSmall = isset($aParams['show_do_report_as_button_small']) && $aParams['show_do_report_as_button_small'] == true;
        $bShowDoReportAsButton = !$bShowDoReportAsButtonSmall && isset($aParams['show_do_report_as_button']) && $aParams['show_do_report_as_button'] == true;
		$bDisabled = !$this->isAllowedReport() || $bReported;

        $sClass = '';
		if($bShowDoReportAsButton)
			$sClass = 'bx-btn';
		else if ($bShowDoReportAsButtonSmall)
			$sClass = 'bx-btn bx-btn-small';

		if($bDisabled)
			$sClass .= $bShowDoReportAsButton || $bShowDoReportAsButtonSmall ? ' bx-btn-disabled' : 'bx-report-disabled';

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameDoReport, array(
            'style_prefix' => $this->_sStylePrefix,
        	'html_id' => $this->_aHtmlIds['do_link'],
            'class' => $sClass,
            'title' => _t('_report_do_report'),
        	'bx_if:show_onclick' => array(
        		'condition' => !$bDisabled,
        		'content' => array(
        			'js_object' => $this->getJsObjectName()
        		)
        	),
            'do_report' => $this->_getLabelDoReport($aParams),
        ));
    }

    protected function _getLabelCounter($iCount)
    {
        return _t('_report_counter', $iCount);
    }

    protected function _getLabelDoReport($aParams = array())
    {
    	$bReported = isset($aParams['is_reported']) && $aParams['is_reported'] === true;
        return $this->_oTemplate->parseHtmlByName('report_do_report_label.html', array(
        	'bx_if:show_icon' => array(
        		'condition' => isset($aParams['show_do_report_icon']) && $aParams['show_do_report_icon'] == true,
        		'content' => array(
        			'name' => $this->_getIconDoReport($bReported)
        		)
        	),
        	'bx_if:show_text' => array(
        		'condition' => isset($aParams['show_do_report_label']) && $aParams['show_do_report_label'] == true,
        		'content' => array(
        			'text' => _t($this->_getTitleDoReport($bReported))
        		)
        	)
        ));
    }

    protected function _getReport()
    {
    	if (!$this->isEnabled())
           return array('code' => 1, 'msg' => _t('_report_err_not_enabled'));

	    if(!$this->isAllowedReport())
            return array('code' => 2, 'msg' => $this->msgErrAllowedReport());

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
	        $iAuthorId = $this->_getAuthorId();
	        $iAuthorNip = ip2long($this->_getAuthorIp());

        	if(!$this->isAllowedReport(true))
        		return array('code' => 2, 'msg' => $this->msgErrAllowedReport());

	        $bPerformed = $this->_oQuery->isPerformed($iObjectId, $iAuthorId);
	        if($bPerformed)
	        	return array('code' => 4, 'msg' => _t('_report_err_duplicate_report'));

			$sType = $oForm->getCleanValue('type');
	        if(!in_array($sType, $this->_aTypes)) 
	        	return array('code' => 5, 'msg' => _t('_report_err_wrong_type'));

			$sText = $oForm->getCleanValue('text');
            $sText = bx_process_input($sText, BX_DATA_TEXT_MULTILINE);
            $oForm->setSubmittedValue('text', $sText, $oForm->aFormAttrs['method']);

			$iId = (int)$oForm->insert(array('author_id' => $iAuthorId, 'author_nip' => $iAuthorNip, 'date' => time()));
			if($iId != 0 && $this->_oQuery->putReport($iObjectId)) {
				$this->_trigger();

        		$aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Reported', array(
        			'report_type' => $sType,
        			'report_text' => $sText,
        			'report_url' => $this->getBaseUrl(),
        		));
        		if($aTemplate)
        			sendMail(getParam('site_email'), $aTemplate['Subject'], $aTemplate['Body']);

		        $oZ = new BxDolAlerts($this->_sSystem, 'doReport', $iObjectId, $iAuthorId, array('report_id' => $iId, 'report_author_id' => $iAuthorId, 'object_author_id' => $iObjectAuthorId, 'type' => $sType, 'text' => $sText));
		        $oZ->alert();
		
		        $oZ = new BxDolAlerts('report', 'do', $iId, $iAuthorId, array('object_system' => $this->_sSystem, 'object_id' => $iObjectId, 'object_author_id' => $iObjectAuthorId, 'type' => $sType, 'text' => $sText));
		        $oZ->alert();

		        $aReport = $this->_oQuery->getReport($iObjectId);
		        return array(
		        	'eval' => $this->getJsObjectName() . '.onReport(oData, oElement)',
		        	'code' => 0,
		        	'id' => $iId, 
		        	'count' => $aReport['count'], 
		        	'countf' => (int)$aReport['count'] > 0 ? $this->_getLabelCounter($aReport['count']) : '',
		        	'label_icon' => $this->_getIconDoReport(!$bPerformed),
		        	'label_title' => _t($this->_getTitleDoReport(!$bPerformed)),
		        	'disabled' => !$bPerformed
		        );
			}

			return array('code' => 3, 'msg' => _t('_report_err_cannot_perform_action'));
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
}

/** @} */
