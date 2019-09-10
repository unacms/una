<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseFormView extends BxDolForm
{
    protected static $_isToggleJsAdded = false;

    protected static $_isCssJsAdded = false;
    protected static $_isCssJsUiAdded = false;
    protected static $_isCssJsMinicolorsAdded = false;
    protected static $_isCssJsTimepickerAdded = false;
    protected static $_isCssJsAddedViewMode = false;

    /**
     * Enable or disable error message displaying
     */
    protected $bEnableErrorIcon = true;

    /**
     * HTML Code of this form
     */
    protected $sCode;

    /**
     * Code which will be added to the beginning of the form.
     * For example, hidden inputs.
     * For internal use only
     */
    protected $_sCodeAdd = '';

    /**
     * for internal use only
     */
    protected $_isSectionOpened = false;

    /**
     * Default divider for several inputs
     */
    protected $_sDivider = '<span class="bx-def-margin-left"></span>';

    /**
     * Alternative divider for several inputs
     */
    protected $_sDividerAlt = '<br />';

    /**
     * Form is added dynamically.
     */
    protected $_bDynamicMode = false;

    /**
     * Form is submitted dynamically (using Ajax Submit).
     */
    protected $_bAjaxMode = false;
    
    /**
     * Use absolute Action URL which is needed in Ajax Mode.
     */
    protected $_bAbsoluteActionUrl = false;

    /**
     * Form is displayed in view mode.
     */
    protected $_bViewMode = false;

    /**
     * Function name for generation close form section HTML.
     */
    protected $_sSectionClose = 'getCloseSection';

    /**
     * Function name for generation open form section HTML.
     */
    protected $_sSectionOpen = 'getOpenSection';

    /**
     * JS files list for form
     */
    protected $_aJs = array();

    /**
     * CSS files list for form
     */
    protected $_aCss = array();
    
    /**
     * Constructor
     *
     * @param array $aInfo Form contents
     *
     * $aInfo['params'] = array(
     *     'remove_form' => true|false,
     * );
     *
     * @return BxBaseFormView
     */
    function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_bAjaxMode = isset($this->aParams['ajax_mode']) && $this->aParams['ajax_mode'];
        $this->_bViewMode = isset($this->aParams['view_mode']) && $this->aParams['view_mode'];

        if($this->_bViewMode) {
            $this->_sSectionClose = 'getCloseSectionViewMode';
            $this->_sSectionOpen = 'getOpenSectionViewMode';
        }
    }

    function setAjaxMode($bAjaxMode)
    {
        $this->_bAjaxMode = (bool)$bAjaxMode;
    }

    function isAjaxMode()
    {
        return $this->_bAjaxMode;
    }

    function setAbsoluteActionUrl($sUrl)
    {
        if(empty($sUrl))
            return;

        $this->aFormAttrs['action'] = $sUrl;

        $this->_bAbsoluteActionUrl = true;
    }

    /**
     * Return Form code
     * @param $bDynamicMode - set it to true if form is added via JS/AJAX call, for example form in AJAX popup.
     * @return string
     */
    function getCode($bDynamicMode = false)
    {
        if (!$bDynamicMode && bx_is_dynamic_request())
            $bDynamicMode = true;

        $this->_bDynamicMode = $bDynamicMode;
        $this->aFormAttrs = $this->_replaceMarkers($this->aFormAttrs);
        $this->sCode = $this->genForm();

        $this->addCssJs ();
        $sDynamicCssJs = $this->_processCssJs();
        return $sDynamicCssJs . $this->sCode;
    }

    /**
     * Generate the whole form
     *
     * @return string
     */
    function genForm()
    {
        $this->_sCodeAdd = '';

        $sTable = $this->genRows();

        $sHtmlBefore = isset($this->aParams['html_before']) ? $this->aParams['html_before'] : '';
        $sHtmlAfter = isset($this->aParams['html_after']) ? $this->aParams['html_after'] : '';

        if (!empty($this->aParams['remove_form']) || (isset($this->aParams['view_mode']) && $this->aParams['view_mode'])) {
            $sForm = <<<BLAH
                    $sHtmlBefore
                    {$this->_sCodeAdd}
                    <div class="bx-form-advanced-wrapper {$this->id}_wrapper">
                        $sTable
                    </div>
                    $sHtmlAfter
BLAH;
        } else {
            $sFormAttrs = bx_convert_array2attrs($this->aFormAttrs, 'bx-form-advanced');

            $sAjaxFormJs = '';
            if($this->_bAjaxMode)
            	$sAjaxFormJs = <<<BLAH
	            	$("form#{$this->id}").ajaxForm({ 
			            dataType: "json",
			            beforeSubmit: function (formData, jqForm, options) {
			                bx_loading($("form#{$this->id}"), true);
			            },
			            success: function (oData) {
			            	bx_loading($("form#{$this->id}"), false);
			
			                processJsonData(oData);
			            }
			        });
BLAH;

            $sForm = <<<BLAH
                $sHtmlBefore
                <form $sFormAttrs>
                    {$this->_sCodeAdd}
                    <div class="bx-form-advanced-wrapper {$this->id}_wrapper">
                        $sTable
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        $(this).addWebForms();
                    });
                    $sAjaxFormJs
                </script>
                $sHtmlAfter
BLAH;
        }

        return $sForm;
    }

    /**
     * Generate Table HTML code
     *
     * @return string
     */
    function genRows()
    {
        // add CSRF token if it's needed.
        if (!(isset($this->aParams['view_mode']) && $this->aParams['view_mode']) && getParam('sys_security_form_token_enable') == 'on' && (!isset($this->aParams['csrf']['disable']) || (isset($this->aParams['csrf']['disable']) && $this->aParams['csrf']['disable'] !== true)) && ($mixedCsrfToken = BxDolForm::getCsrfToken()) !== false) {
            $this->aInputs['csrf_token'] = array(
                'type' => 'hidden',
                'name' => 'csrf_token',
                'value' => $mixedCsrfToken,
                'db' => array ('pass' => 'Xss'),
                'visible_for_levels' => PHP_INT_MAX,
            );
        }

        // add 'Ajax Mode' flag
        if($this->_bAjaxMode)
            $this->aInputs['ajax_mode'] = array(
                'type' => 'hidden',
                'name' => 'ajax_mode',
                'value' => 1,
                'db' => array ('pass' => 'Int'),
                'visible_for_levels' => PHP_INT_MAX,
            );

        // add 'Absolute Action Url' flag
        if($this->_bAbsoluteActionUrl)
            $this->aInputs['absolute_action_url'] = array(
                'type' => 'hidden',
                'name' => 'absolute_action_url',
                'value' => 1,
                'db' => array ('pass' => 'Int'),
                'visible_for_levels' => PHP_INT_MAX,
            );

        // check if we need to generate open section clause
        $sOpenSection = '';
        foreach ($this->aInputs as $aInput) {
            if (isset($aInput['type']) && 'hidden' == $aInput['type'])
                continue;
            if (isset($aInput['type']) && 'block_header' != $aInput['type'])
                $sOpenSection = $this->{$this->_sSectionOpen}();
            break;
        }

        // generate rows contents
        $sCont = '';
        $sContHeader = '';
        $sContFields = '';
        $sFuncGenRow = isset($this->aParams['view_mode']) && $this->aParams['view_mode'] ? 'genViewRow' : 'genRow';
        foreach ($this->aInputs as $aInput) {
            if (!isset($aInput['visible_for_levels']) || self::isVisible($aInput)) {
                if ('block_header' == $aInput['type']) {
                    // don't show section with no fields or with empty fields
                    if ($sContHeader) {                        
                        if ($sContFields)
                            $sCont .= $sContHeader . $sContFields;
                        else
                            $sContHeader = '';
                        $sContFields = '';
                    } 
                    else {
                        $sCont .= $sContFields;
                        $sContFields = '';
                    }
                    $sContHeader = $this->$sFuncGenRow($aInput);
                } else {
                    $sContFields .= $this->$sFuncGenRow($aInput);
                }
            }
        }
        $sCont .= $sContHeader . $sContFields;

        $sCloseSection = $this->{$this->_sSectionClose}();

        return $sOpenSection . $sCont . $sCloseSection;
    }

    /**
     * Generate single Table Row
     *
     * @param  array  $aInput
     * @return string
     */
    function genRow(&$aInput)
    {
        if (!isset($aInput['type']))
            $aInput['type'] = false;

        if (!empty($aInput['name'])) {
            $sCustomMethod = 'genCustomRow' . $this->_genMethodName($aInput['name']);
            if (method_exists($this, $sCustomMethod))
                return $this->$sCustomMethod($aInput);
        }

        switch ($aInput['type']) {

            case 'block_header':
                $sRow = $this->genRowBlockHeader($aInput);
            break;

            case 'block_end':
                $sRow = $this->genBlockEnd($aInput);
            break;

            case 'hidden':
                // do not generate row for hidden inputs
                $sRow = '';
                $this->_sCodeAdd .= $this->genInput($aInput);
            break;

            case 'select_box':
                $sRow = $this->genRowCustom($aInput, 'genInputSelectBox');
            break;

            case 'files':
                $sRow = $this->genRowCustom($aInput, 'genInputFiles');
            break;

            case 'switcher':
            case 'checkbox':
                $sRow = $this->genRowStandard($aInput, true);
            break;

            default:
                $sRow = $this->genRowStandard($aInput);
        }

        return $sRow;
    }

    /**
     * Generate single Table Row for view mode
     *
     * @param  array  $aInput
     * @return string
     */
    function genViewRow(&$aInput)
    {
        if (!isset($aInput['type']))
            $aInput['type'] = false;

        if (!empty($aInput['name'])) {
            $sCustomMethod = 'genCustomRow' . $this->_genMethodName($aInput['name']);
            if (method_exists($this, $sCustomMethod))
                return $this->$sCustomMethod($aInput);
        }

        switch ($aInput['type']) {

            case 'block_header':
                $sRow = $this->genRowBlockHeader($aInput);
            break;

            case 'block_end':
                $sRow = $this->genBlockEnd($aInput);
            break;

            default:
                $sRow = $this->genViewRowWrapped($aInput);
        }

        return $sRow;
    }

    /**
     * Generate complete wrapped row for view mode form
     *
     * @param  array  $aInput
     * @return string
     */
    function genViewRowWrapped(&$aInput)
    {
        $sValue = $this->genViewRowValue($aInput);
        if (null === $sValue)
            return '';

        $sCaption = isset($aInput['caption']) ? bx_process_output($aInput['caption']) : '';

        return <<<EOS
            <div class="bx-form-row-view-wrapper bx-form-row-view-wrapper-{$aInput['type']} bx-def-padding-sec-top">
                <div class="bx-form-row-view-caption">{$sCaption}:</div>
                <div class="bx-form-row-view-value">{$sValue}</div>
            </div>
EOS;
    }

    /**
     * Generate value for view mode row
     *
     * @param  array  $aInput
     * @return string
     */
    function genViewRowValue(&$aInput)
    {
        if (!empty($aInput['name'])) {
            $sCustomMethod = 'genCustomViewRowValue' . $this->_genMethodName($aInput['name']);
            if (method_exists($this, $sCustomMethod))
                return $this->$sCustomMethod($aInput);
        }

        switch ($aInput['type']) {

            case 'hidden':
                $sValue = null;
            break;

            case 'select':
            case 'radio_set':
                $sValue = $this->genViewRowValueForSelect($aInput);
            break;

            case 'datepicker':
                $sValue = null;
                if (empty($aInput['value']) || !$aInput['value'] || '0000-00-00' == $aInput['value'])
                    break;

                $sValue = BxTemplFunctions::getInstance()->{is_numeric($aInput['value']) ? 'timeForJs' : 'timeForJsFullDate'}($aInput['value'], isset($aInput['date_format']) ? $aInput['date_format'] : BX_FORMAT_DATE, true);
            break;

            case 'date_time':
            case 'datetime':
                $sValue = null;
                if(empty($aInput['value']) || !$aInput['value'] || '0000-00-00 00:00:00' == $aInput['value'] || '0000-00-00 00:00' == $aInput['value'])
                    break;

                $sValue = BxTemplFunctions::getInstance()->{is_numeric($aInput['value']) ? 'timeForJs' : 'timeForJsFullDate'}($aInput['value'], isset($aInput['date_format']) ? $aInput['date_format'] : BX_FORMAT_DATE_TIME, true);
            break;

            case 'checkbox_set':
            case 'select_multiple':
                $sValue = null;
                if (!empty($aInput['value']) && is_array($aInput['value'])) {
                    $sValue = '';
                    foreach ($aInput['value'] as $sVal)
                        $sValue .= $aInput['values'][$sVal] . ', ';
                    $sValue = trim ($sValue, ', ');
                }
            break;

            case 'checkbox':
            case 'switcher':
                $sValue = isset($aInput['checked']) && $aInput['checked'] ? _t('_sys_form_checkbox_value_on') : _t('_sys_form_checkbox_value_off');
            break;

            case 'textarea':
                if (isset($aInput['value']) && '' !== $aInput['value']) 
                    $sValue = (isset($aInput['html']) && $aInput['html']) || (isset($aInput['code']) && $aInput['code']) ? $aInput['value'] : bx_process_output($aInput['value'], BX_DATA_TEXT_MULTILINE);
                else
                    $sValue = null;
            break;

            case 'text':
                if(isset($aInput['value']) && '' !== $aInput['value'])
                    $sValue = bx_linkify(bx_process_output($aInput['value']));
                else 
                    $sValue = null;
                break;

            default:
                $sValue = isset($aInput['value']) && '' !== $aInput['value'] ? bx_process_output($aInput['value']) : null;
        }

        return $sValue;
    }

    function genViewRowValueForSelect(&$aInput)
    {
        if (!isset($aInput['value']) || !$aInput['value'])
            return null;
        $s = isset($aInput['value']) && isset($aInput['values'][$aInput['value']]) ? $aInput['values'][$aInput['value']] : null;
        if (isset($aInput['values_list_name'])  && ($oCategory = BxDolCategory::getObjectInstanceByFormAndList($this->aFormAttrs['name'], $aInput['values_list_name'])))
            return $oCategory->getCategoryLink($s, $aInput['value']);
        return $s;
    }

    protected function genCustomRowBirthday(&$aInput)
    {
        if(!$this->_bViewMode)
            return $this->genRowStandard($aInput);

        $aInput['caption_src'] = '_sys_form_input_age';
        $aInput['caption'] = _t($aInput['caption_src']);
        return $this->genViewRowWrapped($aInput);
    }

    protected function genCustomViewRowValueBirthday(&$aInput)
    {
        if(!isset($aInput['value']) || !$aInput['value'] || in_array($aInput['value'], array('0000-00-00', '0000-00-00 00:00:00')))
            return null;

        $sValue = $aInput['value'];

        $iPosSpace = strpos($sValue, ' ');
        if($iPosSpace !== false)
            $sValue = trim(substr($sValue, 0, $iPosSpace));

        $aDate = explode('-', $sValue);

        $iCdYear = (int)date('Y');
        $iCdMonth = (int)date('n');
        $iCdDay = (int)date('j');

        $iResult = $iCdYear - (int)$aDate[0];
        if($iCdMonth < (int)$aDate[1] || ($iCdMonth == (int)$aDate[1] && $iCdDay < (int)$aDate[2]))
            $iResult -= 1;           

        return $iResult;
    }

    /**
     * Generate standard row
     *
     * @param  array  $aInput
     * @return string
     */
    function genRowStandard(&$aInput, $isOneLine = false)
    {
        $sCaption = isset($aInput['caption']) ? bx_process_output($aInput['caption'], BX_DATA_HTML) : '';

        $sRequired = !empty($aInput['required']) ? '<span class="bx-form-required">*</span> ' : '';

        $sClassAdd = !empty($aInput['error']) ? ' bx-form-error' : '';
        $sInfoIcon = !empty($aInput['info']) ? $this->genInfoIcon($aInput['info']) : '';

        $sErrorIcon = $this->genErrorIcon(empty($aInput['error']) ? '' : $aInput['error']);

        $sClassWrapper = 'bx-form-element-wrapper';
        if($isOneLine)
            $sClassWrapper .= ' ' . $sClassWrapper . '-oneline';
        $sClassWrapper .= ' bx-def-margin-top';

        if (isset($aInput['name']))
            $aInput['tr_attrs']['id'] = "bx-form-element-" . $aInput['name'];
        $sTrAttrs = bx_convert_array2attrs(isset($aInput['tr_attrs']) && is_array($aInput['tr_attrs']) ? $aInput['tr_attrs'] : array(), $sClassWrapper);

        $sClassOneLineCaption = '';
        $sClassOneLineValue = '';
        if ($isOneLine) {
            $sClassOneLineCaption = ' bx-form-caption-oneline bx-form-caption-oneline-' . $aInput['type'] . ' bx-def-margin-sec-left';
            $sClassOneLineValue = ' bx-form-value-oneline bx-form-value-oneline-' . $aInput['type'];
            $aInput['attrs']['id'] = $this->getInputId($aInput);
            if ($sCaption)
                $sCaption = '<label for="' . $aInput['attrs']['id'] . '">' . $sCaption . '</label>';
        }

        $sInput = $this->genInput($aInput);
        if(isset($aInput['error_updated']) && $aInput['error_updated'] === true)
            $sErrorIcon = $this->genErrorIcon(empty($aInput['error']) ? '' : $aInput['error']);

        $sCaptionCode = $sCaption ? '<div class="bx-form-caption' . $sClassOneLineCaption . '">' . $sCaption . $sRequired . '</div>' : '';
        $sInputCode = $this->genWrapperInput($aInput, $sInput);

        if (empty($sInfoIcon)) $sInfoIcon = '';
        if (empty($sInputCode)) $sInputCode = '';
        if (empty($sErrorIcon)) $sErrorIcon = '';

        $sValueCode = '<div class="bx-form-value bx-clearfix' . $sClassAdd . $sClassOneLineValue . '">' . $sInputCode . '</div>';

        if ($isOneLine)
            $sCode = $sValueCode . $sCaptionCode;
        else
            $sCode = $sCaptionCode . $sValueCode;

        return '<div ' . $sTrAttrs . '><div class="bx-form-element">' . $sCode . '</div>' . $sInfoIcon . $sErrorIcon . '</div>';
    }


    function genWrapperInput($aInput, $sContent)
    {
        $sAttr = isset($aInput['attrs_wrapper']) && is_array($aInput['attrs_wrapper']) ? bx_convert_array2attrs($aInput['attrs_wrapper']) : '';

        return "<div class=\"bx-form-input-wrapper bx-form-input-wrapper-{$aInput['type']}" . ((isset($aInput['html']) && $aInput['html'] && $this->isHtmlEditor($aInput['html'], $aInput)) ? ' bx-form-input-wrapper-html' : '') . "\" $sAttr>$sContent</div>";
    }

    /**
     * Generate custom row
     *
     * @param  array  $aInput
     * @param  string $sCustomMethod custom method to generate code for input
     * @return string
     */
    function genRowCustom(&$aInput, $sCustomMethod)
    {
        $sCaption = isset($aInput['caption']) ? bx_process_output($aInput['caption']) : '';

        $sRequired = !empty($aInput['required']) ? '<span class="bx-form-required">*</span> ' : '';

        $sClassAdd = !empty($aInput['error']) ? ' bx-form-error' : '';
        $sInfoIcon = !empty($aInput['info']) ? $this->genInfoIcon($aInput['info']) : '';

        $sErrorIcon = $this->genErrorIcon(empty($aInput['error']) ? '' : $aInput['error']);
        $sInput = $this->$sCustomMethod($aInput, $sInfoIcon, $sErrorIcon);

        if (isset($aInput['name']))
            $aInput['tr_attrs']['id'] = "bx-form-element-" . $aInput['name'];
        $sTrAttrs = bx_convert_array2attrs(empty($aInput['tr_attrs']) ? array() : $aInput['tr_attrs'], "bx-form-element-wrapper bx-def-margin-top");

        $sCaptionCode = '';
        if ($sCaption)
            $sCaptionCode = '<div class="bx-form-caption">' . $sCaption . $sRequired . '</div>';

        $sCode = <<<BLAH
                <div $sTrAttrs>
                    $sCaptionCode
                    <div class="bx-form-value$sClassAdd">
                        <div class="bx-clear"></div>
                            $sInput
                        <div class="bx-clear"></div>
                    </div>
                </div>
BLAH;

        return $sCode;
    }

    /**
     * Generate Block Headers row
     *
     * @param  array  $aInput
     * @return string
     */
    function genRowBlockHeader(&$aInput)
    {
        $aAttrs = empty($aInput['attrs']) ? '' : $aInput['attrs'];

        // if there is no caption - show divider only

        if (empty($aInput['caption'])) {
            $sCode = $this->{$this->_sSectionClose}();
            $sCode .= $this->{$this->_sSectionOpen}($aAttrs);
            return $sCode;
        }

        // if section is collapsed by default, add necessary code

        $sClassAddCollapsable = 'bx-form-collapsable';
        if (isset($aInput['collapsed']) and $aInput['collapsed'])
            $sClassAddCollapsable .= ' bx-form-collapsed bx-form-section-hidden';

        // display section with caption

        $sCode = $this->{$this->_sSectionClose}();

        if (empty($aAttrs))
            $aAttrs = array('class' => 'bx-form-collapsable ' . $sClassAddCollapsable);
        else
            $aAttrs['class'] .= ' bx-form-collapsable ' . $sClassAddCollapsable;


        if (isset($this->aParams['view_mode']) && $this->aParams['view_mode'])
            $sLegend = '<legend class="bx-def-padding-left bx-def-padding-sec-right bx-def-font-grayed bx-def-font-h3">' . bx_process_output($aInput['caption']) . (!empty($aInput['info']) ? '<br /><span>' . bx_process_output($aInput['info']) . '</span>' : '') . '</legend>';
        else
            $sLegend = '<legend class="bx-def-padding-left bx-def-padding-sec-right bx-def-font-grayed bx-def-font-h3"><a href="javascript:void(0);">' . bx_process_output($aInput['caption']) . '</a>' . (!empty($aInput['info']) ? '<br /><span>' . bx_process_output($aInput['info']) . '</span>' : '') . '</legend>';

        $sCode .= $this->{$this->_sSectionOpen}($aAttrs, $sLegend);

        return $sCode;
    }

    function genBlockEnd()
    {
        $aNextTbodyAdd = false; // need to have some default
        $sCode = '';
        $sCode .= $this->{$this->_sSectionClose}();
        $sCode .= $this->{$this->_sSectionOpen}($aNextTbodyAdd);
        return $sCode;
    }

    /**
     * Generate HTML Input Element
     *
     * @param  array  $aInput
     * @return string Output HTML Code
     */
    function genInput(&$aInput)
    {
        if (!empty($aInput['name'])) {
            $sCustomMethod = 'genCustomInput' . $this->_genMethodName($aInput['name']);
            if (method_exists($this, $sCustomMethod))
                return $this->$sCustomMethod($aInput);
        }

        switch ($aInput['type']) {

            // standard inputs (and non-standard, interpreted as standard)
            case 'text':
            case 'datepicker':
            case 'date_time':
            case 'datetime':
            case 'number':
            case 'time':
            case 'checkbox':
            case 'radio':
            case 'image':
            case 'password':
            case 'slider':
            case 'doublerange':
            case 'hidden':
                $sInput = $this->genInputStandard($aInput);
            break;

            case 'file':
            	$sInput = $this->genInputFile($aInput);
            	break;

            case 'rgb':
            case 'rgba':
            	$sClass = 'bx-form-input-' . $aInput['type'];
            	$aInput['attrs']['class'] = !empty($aInput['attrs']['class']) ? $aInput['attrs']['class'] . ' ' . $sClass : $sClass; 

            	$aInput['type'] = 'text';
                $sInput = $this->genInputStandard($aInput);
            break;

            case 'switcher':
                $sInput = $this->genInputSwitcher($aInput);
            break;

            case 'button':
            case 'reset':
            case 'submit':
                $sInput = $this->genInputButton($aInput);
            break;

            case 'textarea':
                $sInput = $this->genInputTextarea($aInput);
            break;

            case 'select':
                $sInput = $this->genInputSelect($aInput);
            break;

            case 'select_multiple':
                $sInput = $this->genInputSelectMultiple($aInput);
            break;

            case 'checkbox_set':
                $sInput = $this->genInputCheckboxSet($aInput);
            break;

            case 'radio_set':
                $sInput = $this->genInputRadioSet($aInput);
            break;

            case 'input_set': // numeric array of inputs
                $sInput = '';
                $sDivider = isset($aInput['dv']) ? $aInput['dv'] : ' ';
                foreach ($aInput as $iKey => $aSubInput) {
                    if (!is_int($iKey) or !$aSubInput)
                        continue; // parse only integer keys and existing values

                    $sInput .= $this->genInput($aSubInput); // recursive call
                    $sInput .= $sDivider;
                }
                $sInput .= '<div class="bx-clear"></div>';
            break;

            case 'custom':
                $sInput = isset($aInput['content']) ? $aInput['content'] : '';
            break;

            case 'captcha':
                $sInput = $this->genInputCaptcha($aInput);
            break;

            case 'location':
                $sInput = $this->genInputLocation($aInput);
            break;

            case 'value':
                $sInput = isset($aInput['value']) ? $aInput['value'] : '';
            break;

            default:
                //unknown control type
                $sInput = 'Unknown control type';
        }

        // create input label
        $sInput .= $this->genLabel($aInput);

        return $sInput;
    }

    /**
     * Generate new Input Element id
     *
     * @param  array  $aInput
     * @return string
     */
    function getInputId(&$aInput)
    {
        if (isset($aInput['id']))
            return $aInput['id'];

        $sName = md5($aInput['name']);

        $sID = $this->id . '_input_' . $sName;

        if ( // multiple elements cause identical id's
            (
                (
                    $aInput['type'] == 'checkbox' and
                    substr($aInput['name'], -2) == '[]' // it is multiple element
                ) or
                $aInput['type'] == 'radio' // it is always multiple (i think so... hm)
            ) and
            isset($aInput['value']) // if we can make difference
        ) {
            $sValue = md5($aInput['value']);

            // add value
            $sID .= '_' . $sValue;
        }

        $sID = trim($sID, '_');

        $aInput['id'] = $sID; // just for repeated calls

        return $sID;
    }

    /**
     * Generate standard Input Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputStandard(&$aInput)
    {
        // clone attributes for system use ;)
        $aAttrs = empty($aInput['attrs']) ? array() : $aInput['attrs'];

        // add default className to attributes
        $aAttrs['type'] = $aInput['type'];
        if ('datetime' == $aAttrs['type'])
            $aAttrs['type'] = 'date_time';

        switch($aAttrs['type']) {
            case 'date_time':
                $this->addCssJsUi ();
                $this->addCssJsTimepicker ();
                break;
            case 'slider':
            case 'doublerange':
            case 'datepicker':
                $this->addCssJsUi ();
                break;
            case 'text':
                if (isset($aAttrs['class']) && false !== strpos($aAttrs['class'], 'bx-form-input-rgb'))
                    $this->addCssJsMinicolors ();
                break;
        }

        if ('doublerange' == $aAttrs['type'] && (!isset($aInput['value']) || !$aInput['value']) && !empty($aAttrs['min']) && !empty($aAttrs['max'])) {
            $aInput['value'] = $aAttrs['min'] . '-' . $aAttrs['max'];
        }

        if (isset($aInput['name'])) $aAttrs['name'] = $aInput['name'];
        if (isset($aInput['value'])) $aAttrs['value'] = $aInput['value'];
        if (isset($aInput['db']['pass']) && ('DateUtc' == $aInput['db']['pass'] || 'DateTimeUtc' == $aInput['db']['pass'])) $aAttrs['data-utc'] = 1;

        // for inputs with labels generate id
        if (isset($aInput['label']))
            $aAttrs['id'] = $this->getInputId($aInput);

        // for checkboxes
        if (isset($aInput['checked']) and $aInput['checked'])
            $aAttrs['checked'] = 'checked';

        $sAttrs = bx_convert_array2attrs($aAttrs, "bx-def-font-inputs bx-form-input-{$aInput['type']}");

        return  "<input $sAttrs />\n";
    }

    /**
     * Generate Switcher Input Element (based on checkbox)
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputSwitcher(&$aInput)
    {
        $aInput['type'] = 'checkbox';
        $sCheckbox = $this->genInputStandard($aInput);
        $aInput['type'] = 'switcher';

        $sClass = 'off';
        if (isset($aInput['checked']) and $aInput['checked'])
            $sClass = 'on';

        return '
            <div class="bx-switcher-cont ' . $sClass . '">' . $sCheckbox . '
                <div class="bx-switcher-canvas">
                    <div class="bx-switcher-on"><i class="sys-icon check"></i></div>
                    <div class="bx-switcher-off"><i class="sys-icon times"></i></div>
                    <div class="bx-switcher-handler">&nbsp;</div>
                </div>
            </div>';
    }

    /**
     * Generate Button Input Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputButton(&$aInput)
    {
        // clone attributes for system use ;)
        $aAttrs = empty($aInput['attrs']) ? array() : $aInput['attrs'];

        // add default className to attributes
        $aAttrs['type'] = $aInput['type'];
        if (isset($aInput['value']))
            $aAttrs['value'] = $aInput['value'];

        if (isset($aInput['name'])) $aAttrs['name'] = $aInput['name'];

        $sAttrs = bx_convert_array2attrs($aAttrs, "bx-def-font-inputs bx-form-input-{$aInput['type']} bx-btn" . ('submit' == $aInput['type'] ? ' bx-btn-primary' : ''));

        return  "<button $sAttrs>" . $aInput['value'] . "</button>\n";
    }

    /**
     * Generate Textarea Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputTextarea(&$aInput)
    {
        // clone attributes for system use ;)
        $aAttrs = empty($aInput['attrs']) ? array() : $aInput['attrs'];

        $aAttrs['name'] = $aInput['name'];

        // for inputs with labels generate id
        if (isset($aInput['label']))
            $aAttrs['id'] = $this->getInputId($aInput);

        $sAttrs = bx_convert_array2attrs($aAttrs, "bx-def-font-inputs bx-form-input-{$aInput['type']}" . ((isset($aInput['html']) and $aInput['html'] and $this->addHtmlEditor($aInput['html'], $aInput)) ? ' bx-form-input-html' : ''));

        $sValue = isset($aInput['value']) ? bx_process_output((isset($aInput['html']) && $aInput['html']) || (isset($aInput['code']) && $aInput['code']) ? $aInput['value'] : strip_tags($aInput['value'])) : '';

        return "<textarea $sAttrs>$sValue</textarea>";
    }

    function isHtmlEditor($iViewMode, &$aInput)
    {
		return BxDolEditor::getObjectInstance(false, $this->oTemplate) !== false;
    }

    function addHtmlEditor($iViewMode, &$aInput)
    {
        $oEditor = BxDolEditor::getObjectInstance(false, $this->oTemplate);
        if (!$oEditor)
            return false;

        $this->_sCodeAdd .= $oEditor->attachEditor ('#' . $this->aFormAttrs['id'] . ' [name='.$aInput['name'].']', $iViewMode, $this->_bDynamicMode);

        return true;
    }

    /**
     * Generate Select Box Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputSelectBox(&$aInput, $sInfo = '', $sError = '')
    {
        $aNewInput = $aInput;

        $aNewInput['type'] = 'select';
        $aNewInput['name'] .= '[]';

        $sInput = $this->genInput($aNewInput);
        return <<<BLAH
                <div class="bx-form-input-wrapper input-wrapper-{$aInput['type']}">
                   $sInput
                </div>
                $sInfo
                $sError
BLAH;

    }

    /**
     * Generate Browse File Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputFile(&$aInput)
    {
    	$sOnChange = "$(this).parents('.bx-form-input-wrapper-file:first').find('.bx-fif-value').html($(this).val());";
    	if(!empty($aInput['attrs']['onchange']))
    		$aInput['attrs']['onchange'] = $sOnChange . $aInput['attrs']['onchange'];
    	else
    		$aInput['attrs']['onchange'] = $sOnChange;

    	$sInput = $this->genInputStandard($aInput);
    	return '<label class="bx-btn">' . $sInput . '<span class="bx-fif-label">' . _t('_sys_form_txt_select_file') . '</span></label><span class="bx-fif-value bx-def-margin-thd-left"></span>';
    }

    /**
     * Generate Select Box Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputFiles(&$aInput, $sInfo = '', $sError = '')
    {
        $sUniqId = genRndPwd (8, false);
        $sUploaders = '';
        $oUploader = null;
        foreach ($aInput['uploaders'] as $sUploaderObject) {
            $oUploader = BxDolUploader::getObjectInstance($sUploaderObject, $aInput['storage_object'], $sUniqId, $this->oTemplate);
            if (!$oUploader)
                continue;

            $sGhostTemplate = $this->genGhostTemplate($aInput);

            $aAttrs = !empty($aInput['attrs']) ? $aInput['attrs'] : array();
            if (empty($aInput['attrs']['disabled']))
                $aAttrs = array_merge($aAttrs, array('onclick' => $oUploader->getNameJsInstanceUploader() . '.showUploaderForm();'));
                
            $aParams = array(
                'button_title' => bx_js_string($oUploader->getUploaderButtonTitle(isset($aInput['upload_buttons_titles']) ? $aInput['upload_buttons_titles'] : false)),
                'content_id' => isset($aInput['content_id']) ? $aInput['content_id'] : '',
                'storage_private' => isset($aInput['storage_private']) ? $aInput['storage_private'] : '1',
                'attrs' => bx_convert_array2attrs($aAttrs),
                'btn_class' => !empty($aInput['attrs']['disabled']) ? 'bx-btn-disabled' : '',
            );
            if (isset($aInput['images_transcoder']) && $aInput['images_transcoder'])
                $aParams['images_transcoder'] = bx_js_string($aInput['images_transcoder']);

            $sUploaders .= $oUploader->getUploaderButton($sGhostTemplate, isset($aInput['multiple']) ? $aInput['multiple'] : true, $aParams, $this->_bDynamicMode);
        }

        return $this->oTemplate->parseHtmlByName('form_field_uploader.html', array(
            'uploaders_buttons' => $sUploaders,
            'info' => $sInfo,
            'error' => $sError,
            'id_container_errors' => $oUploader ? $oUploader->getIdContainerErrors() : '',
            'id_container_result' => $oUploader ? $oUploader->getIdContainerResult() : '',
            'uploader_instance_name' => $oUploader ? $oUploader->getNameJsInstanceUploader() : '',
            'is_init_ghosts' => isset($aInput['init_ghosts']) && !$aInput['init_ghosts'] ? 0 : 1,
        ));
    }

    protected function genGhostTemplate(&$aInput)
    {
        $sGhostTemplate = false;
        if (isset($aInput['ghost_template']) && is_object($aInput['ghost_template'])) { // form is not submitted and ghost template is BxDolFormNested object

            $oFormNested = $aInput['ghost_template'];
            if ($oFormNested instanceof BxDolFormNested)
                $sGhostTemplate = $oFormNested->genForm();

        } elseif (isset($aInput['ghost_template']) && is_array($aInput['ghost_template']) && isset($aInput['ghost_template']['inputs'])) { // form is not submitted and ghost template is form array

            $oFormNested = new BxDolFormNested($aInput['name'], $aInput['ghost_template'], $this->aParams['db']['submit_name'], $this->oTemplate);
            $sGhostTemplate = $oFormNested->getCode();

        } elseif (isset($aInput['ghost_template']) && is_array($aInput['ghost_template']) && $aInput['ghost_template']) { // form is submitted and ghost template is array of BxDolFormNested objects

            $sGhostTemplate = array ();
            foreach ($aInput['ghost_template'] as $iFileId => $oFormNested)
                if (is_object($oFormNested) && $oFormNested instanceof BxDolFormNested)
                    $sGhostTemplate[$iFileId] = $oFormNested->genForm();

        } elseif (isset($aInput['ghost_template']) && is_string($aInput['ghost_template'])) { // ghost template is just string template, without nested form

            $sGhostTemplate = $aInput['ghost_template'];

        }

        return $sGhostTemplate;
    }

    protected function genCustomInputUsernamesSuggestions ($aInput)
    {
        $this->addCssJsUi();

        $sVals = '';
        if(!empty($aInput['value']) && is_array($aInput['value'])) {
            foreach($aInput['value'] as $sVal) {
                if(!$sVal || !($oProfile = BxDolProfile::getInstance($sVal)))
                    continue;

               $sVals .= '<b class="val bx-def-color-bg-hl bx-def-round-corners">' . $oProfile->getUnit(0, array('template' => 'unit_wo_info')) . $oProfile->getDisplayName() . '<input type="hidden" name="' . $aInput['name'] . '[]" value="' . $sVal . '" /></b>';
            }
            $sVals = trim($sVals, ',');
        }

        $bDisabled = isset($aInput['attrs']['disabled']) && $aInput['attrs']['disabled'] == 'disabled';

        $sId = $aInput['name'] . time() . mt_rand(0, 100);
        $sClass = 'bx-form-input-autotoken bx-def-font-inputs bx-form-input-text';
        if($bDisabled)
            $sClass .= ' bx-form-input-disabled';
        if(!empty($aInput['attrs']['class'])) {
            $sClass .= ' ' . $aInput['attrs']['class'];
            unset($aInput['attrs']['class']);
        }

        $aAttrs = array('value' => '', 'autocomplete' => 'off', 'autocapitalize' => 'off', 'autocorrect' => 'off');
        if(isset($aInput['attrs']) && is_array($aInput['attrs']))
            $aAttrs = array_merge($aAttrs, $aInput['attrs']);

        return $this->oTemplate->parseHtmlByName('form_field_custom_suggestions.html', array(
            'id' => $sId,
            'name' => $aInput['name'],
            'class' => $sClass,
            'vals' => $sVals,
            'bx_if:input' => array(
                'condition' => !$bDisabled,
                'content' => array(
                    'attrs' => bx_convert_array2attrs($aAttrs),
                )
            ),
            'bx_if:init' => array(
                'condition' => !$bDisabled,
                'content' => array(
                    'id' => $sId,
                    'name' => $aInput['name'],
                    'url_get_recipients' => $aInput['ajax_get_suggestions'],
                    'b_img' => isset($aInput['custom']['b_img']) ? (int)$aInput['custom']['b_img'] : 1,
                    'only_once' => isset($aInput['custom']['only_once']) ? 1 : 0,
                    'on_select' => isset($aInput['custom']['on_select']) ? $aInput['custom']['on_select']: 'null',
                    'placeholder' => bx_html_attribute(isset($aInput['placeholder']) ? $aInput['placeholder'] : _t('_sys_form_paceholder_profiles_suggestions'), BX_ESCAPE_STR_QUOTE),
                )
            )
        ));
    }

    protected function genCustomViewRowValueLabels ($aInput)
    {        
        if (empty($aInput['value']) || !is_array($aInput['value']) || !($oMetatags = BxDolMetatags::getObjectInstance($aInput['meta_object'])) || !$oMetatags->keywordsIsEnabled())
            return null;

        $s = ''; 
        foreach ($aInput['value'] as $sLabel)
            $s .= '<a href="' . $oMetatags->keywordsGetHashTagUrl($sLabel, $aInput['content_id']) . '"><b class="val bx-def-color-bg-hl bx-def-round-corners">' . trim($sLabel) . '</b></a>';

        return $this->oTemplate->parseHtmlByName('form_field_labels_view.html', array(
            'values' => $s,
        ));
    }

    protected function genCustomInputLabels ($aInput)
    {
        $aValues = array();
        $oLabel = BxDolLabel::getInstance();
        $this->_getLabelOptions(0, $oLabel, $aValues);

        $aInput['type'] = 'select_multiple';
        $aInput['values'] = $aValues;

        return $this->genInputSelectMultiple($aInput);
    }

    protected function _getLabelOptions($iParent, &$oLabel, &$aValues)
    {
        $aLabels = $oLabel->getLabels(array('type' => 'parent', 'parent' => $iParent));
        foreach($aLabels as $aLabel) {
            $aValues[] = array('key' => $aLabel['value'], 'value' => str_repeat('&nbsp;&nbsp;&nbsp;', (int)$aLabel['level']) . ' ' . $aLabel['value']);

            $this->_getLabelOptions($aLabel['id'], $oLabel, $aValues);
        }
    }

    /*
    protected function genCustomInputLabels ($aInput)
    {
        // generate currently selected labels
        $sValue = '';
        if (!empty($aInput['value']) && is_array($aInput['value'])) {
            sort($aInput['value'], SORT_LOCALE_STRING);
            foreach ($aInput['value'] as $sVal) {
               $sValue .= '<b class="val bx-def-color-bg-hl bx-def-round-corners">' . trim($sVal) . '<input type="hidden" name="' . $aInput['name'] . '[]" value="' . trim($sVal) . '" /></b>';
            }
            $sValue = trim($sValue, ',');
        }

        // generate "following" labels
        $aFollowingLabels = bx_srv('bx_channels', 'get_following_channels_names', array(bx_get_logged_profile_id()));
        if (!is_array($aFollowingLabels))
            $aFollowingLabels = array();
        $aFollowingLabels = array_intersect($aFollowingLabels, $aInput['values']);
        $aFollowingLabels = array_diff($aFollowingLabels, !empty($aInput['value']) && is_array($aInput['value']) ? $aInput['value'] : array());
        $aValues = array_diff($aInput['values'], 
            !empty($aInput['value']) && is_array($aInput['value']) ? $aInput['value'] : array(), 
            !empty($aFollowingLabels) && is_array($aFollowingLabels) ? $aFollowingLabels : array());

        $sValues = '';
        if (!empty($aFollowingLabels)) {
            sort($aFollowingLabels, SORT_LOCALE_STRING);
            foreach ($aFollowingLabels as $sVal) {
               $sValues .= '<b class="val bx-def-color-bg-hl bx-def-round-corners">' . trim($sVal) . '</b>';
            }
            if (!empty($aValues))
                $sValues .= '<a href="#" class="bx-form-input-autotoken-labels-show-more">' . _t('_sys_show_more') . '</a><i class="bx-form-input-autotoken-labels-more">';
        }

        // generate all possible labels 
        if (!empty($aValues)) {
            sort($aValues, SORT_LOCALE_STRING);
            foreach ($aValues as $sVal) {
               $sValues .= '<b class="val bx-def-color-bg-hl bx-def-round-corners">' . trim($sVal) . '</b>';
            }
            $sValues = trim($sValues, ',');
        }

        if (!empty($aFollowingLabels) && !empty($aValues))
            $sValues .= '</i>';

        return $this->oTemplate->parseHtmlByName('form_field_labels.html', array(
            'id' => $aInput['name'] . time() . mt_rand(0, 100),
            'name' => $aInput['name'],
            'value' => $sValue,
            'values' => $sValues,
        ));
    }
     */
    
    /**
     * Generate Select Element	
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputSelect(&$aInput)
    {
        $sCurValue = isset($aInput['value']) ? $aInput['value'] : '';
        return $this->_genInputSelect($aInput, false, $sCurValue, '_isSelected');
    }

    /**
     * Generate Multiple Select Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputSelectMultiple(&$aInput)
    {
        $aCurValues = array();
        if (isset($aInput['value']) && $aInput['value'])
            $aCurValues = is_array($aInput['value']) ? $aInput['value'] : array();
        return $this->_genInputSelect($aInput, true, $aCurValues, '_isSelectedMultiple');
    }

    /**
     * Generate Checkbox Set Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputCheckboxSet(&$aInput)
    {
        $aCurValues = array();
        if (isset($aInput['value']) && $aInput['value'])
            $aCurValues = is_array($aInput['value']) ? $aInput['value'] : array();
        return $this->_genInputsSet($aInput, 'checkbox', $aCurValues, '_isSelectedMultiple', '[]');
    }
    /**
     * Generate Radiobuttons Set Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputRadioSet(&$aInput)
    {
        $sCurValue = isset($aInput['value']) ? $aInput['value'] : '';
        return $this->_genInputsSet($aInput, 'radio', $sCurValue, '_isSelected');
    }

    function _isSelected ($sValue, $sCurValue)
    {
        return ((string)$sValue === (string)$sCurValue);
    }

    function _isSelectedMultiple ($sValue, $aCurValues)
    {
        return in_array($sValue, $aCurValues);
    }

    function _genInputSelect(&$aInput, $isMultiple, $mixedCurrentVal, $sIsSelectedFunc)
    {
        $aAttrs = empty($aInput['attrs']) ? array() : $aInput['attrs'];

        $aAttrs['name'] = $aInput['name'];
        if ($isMultiple) {
            $aAttrs['name'] .= '[]';
            $aAttrs['multiple'] = 'multiple';
        }

        // for inputs with labels generate id
        if (isset($aInput['label']))
            $aAttrs['id'] = $this->getInputId($aInput);

        $sAttrs = bx_convert_array2attrs($aAttrs, "bx-def-font-inputs bx-form-input-{$aInput['type']}");

        // generate options
        $sOptions = '';
        if (isset($aInput['values']) and is_array($aInput['values'])) {
            foreach ($aInput['values'] as $sValue => $sTitle) {
                $sAttrsOpt = "";
                if(is_array($sTitle)) {
                    if(isset($sTitle['type'])) {
                        switch($sTitle['type']) {
                            case 'group_header':
                                $sTitle = bx_process_output($sTitle['value']);
                                $sOptions .= <<<BLAH
                                       <optgroup label="$sTitle">
BLAH;
                                break;
                            case 'group_end':
                                $sOptions .= <<<BLAH
                                       </optgroup>
BLAH;
                                break;
                        }
                        continue;
                    }

                    $aAttrsOpt = array();
                    if(!empty($sTitle['attrs']) && is_array($sTitle['attrs']))
                        $aAttrsOpt = $sTitle['attrs'];

                    if(isset($sTitle['class']))
                        $aAttrsOpt['class'] = !empty($aAttrsOpt['class']) ? $aAttrsOpt['class'] . ' ' . $sTitle['class'] : $sTitle['class'];

                    if(isset($sTitle['style']))
                        $aAttrsOpt['style'] = !empty($aAttrsOpt['style']) ? $aAttrsOpt['style'] . ' ' . $sTitle['style'] : $sTitle['style'];

                    $sAttrsOpt = !empty($aAttrsOpt) ? bx_convert_array2attrs($aAttrsOpt) : '';

                    $sValue = $sTitle['key'];
                    $sTitle = $sTitle['value'];
                }
                $sValueC = bx_html_attribute($sValue);
                $sTitleC = bx_process_output($sTitle);

                $sSelected = $this->$sIsSelectedFunc($sValue, $mixedCurrentVal) ? 'selected="selected"' : '';

                $sOptions .= <<<BLAH
                   <option value="$sValueC" $sAttrsOpt $sSelected>$sTitleC</option>
BLAH;

            }
        }

        // generate element
        $sCode = <<<BLAH
            <select $sAttrs>
                $sOptions
            </select>
BLAH;

        if(!empty($aInput['content']))
            $sCode .= $aInput['content'];

        return $sCode;
    }

    function _genInputsSet(&$aInput, $sType, $mixedCurrentVal, $sIsCheckedFunc, $sNameAppend = '')
    {
        $aAttrs = empty($aInput['attrs']) || 'radio_set' == $aInput['type'] || 'checkbox_set' == $aInput['type'] ? array() : $aInput['attrs'];

        $aAttrs['name']  = $aInput['name'];

        // for inputs with labels generate id
        if (isset($aInput['label']))
            $aAttrs['id'] = $this->getInputId($aInput);

        $sAttrs = bx_convert_array2attrs($aAttrs, "bx-form-input-{$aInput['type']}");

        // generate options
        $sDivider = isset($aInput['dv']) ? $aInput['dv'] : $this->_sDivider;

        $sOptions = '';

        if (isset($aInput['values']) && is_array($aInput['values'])) {
            if (count($aInput['values']) > 3 && $sDivider == $this->_sDivider)
                $sDivider = $this->_sDividerAlt;
            
            // generate complex input using simple standard inputs
            foreach ($aInput['values'] as $sValue => $sLabel) {
                if(is_array($sLabel)) {
                    $sValue = $sLabel['key'];
                    $sLabel = $sLabel['value'];
                }

                // create new simple input
                $aNewInput = array(
                    'type'    => $sType,
                    'name'    => $aInput['name'] . $sNameAppend,
                    'value'   => $sValue,
                    'checked' => $this->$sIsCheckedFunc($sValue, $mixedCurrentVal),
                    'label'   => $sLabel,
                    'attrs'   => !empty($aInput['attrs']) && ('radio_set' == $aInput['type'] || 'checkbox_set' == $aInput['type']) ? $aInput['attrs'] : false,
                );

                $sNewInput  = $this->genInput($aNewInput);

                // attach new input to complex
                $sOptions .= ($sNewInput . $sDivider);
            }
        }

        // generate element
        $sCode = <<<BLAH
            <div $sAttrs>
                $sOptions
            </div>
BLAH;

        return $sCode;
    }

    function genInputLocation(&$aInput)
    {
        $isManualInput = (int)(isset($aInput['manual_input']) && $aInput['manual_input']);
        $sIdStatus = $this->getInputId($aInput) . '_status';
        $sIdInput = $this->getInputId($aInput) . '_location';
        $sProto = (0 === strncmp('https', BX_DOL_URL_ROOT, 5)) ? 'https' : 'http';

        $aVars = array (
            'key' => trim(getParam('sys_maps_api_key')),
            'lang' => bx_lang_name(),
            'name' => $aInput['name'],
            'id_status' => $sIdStatus,
            'id_input' => $sIdInput,
            'manual_input' => $isManualInput,            
            'bx_if:manual_input' => array(
                'condition' => $isManualInput,
                'content' => array(),
            ),
            'bx_if:auto_input' => array(
                'condition' => !$isManualInput,
                'content' => array(
                    'id_status' => $sIdStatus,
                    'location_string' => _t('_sys_location_undefined'),
                ),
            ),            
        );
        $aLocationIndexes = array ('lat', 'lng', 'country', 'state', 'city', 'zip', 'street', 'street_number');
        foreach ($aLocationIndexes as $sKey)
            $aVars[$sKey] = $this->getLocationVal($aInput, $sKey);
        if ($aVars['country']) {
            $aCountries = BxDolFormQuery::getDataItems('Country');
            $s = ($aVars['street_number'] ? $aVars['street_number'] . ', ' : '') . ($aVars['street'] ? $aVars['street'] . ', ' : '') . ($aVars['city'] ? $aVars['city'] . ', ' : '') . ($aVars['state'] ? $aVars['state'] . ', ' : '') . $aCountries[$aVars['country']];
            $aVars['bx_if:auto_input']['content']['location_string'] = $s;
        }

        if ($isManualInput) {
            $aInput['type'] = 'text';
            $aInput['attrs']['id'] = $sIdInput;
            $aVars['input'] = $this->genInputStandard($aInput);
        } 
        else {
            if ($this->getLocationVal($aInput, 'lat') && $this->getLocationVal($aInput, 'lng'))
                $aInput['checked'] = true;
            else
                $aInput['checked'] = $this->getCleanValue($aInput['name'] . '_lat') && $this->getCleanValue($aInput['name'] . '_lng') ? 1 : 0;
            $aVars['input'] = $this->genInputSwitcher($aInput);
        }

        return $this->oTemplate->parseHtmlByName('form_field_location.html', $aVars);
    }

    public function setLocationVal ($aInput, $sIndex, $sVal)
    {
        $s = $aInput['name'] . '_' . $sIndex;
        $this->_aSpecificValues[$s] = $sVal;
    }

    protected function getLocationVal ($aInput, $sIndex) 
    {
        $s = $aInput['name'] . '_' . $sIndex;
        if (isset($this->_aSpecificValues[$s]))
            return $this->_aSpecificValues[$s];
        return $this->getCleanValue($s);
    }
    
    function genInputCaptcha(&$aInput)
    {
        $aAttrs = empty($aInput['attrs']) ? array() : $aInput['attrs'];

        // for inputs with labels generate id
        if (isset($aInput['label']))
            $aAttrs['id'] = $this->getInputId($aInput);

        $sAttrs = bx_convert_array2attrs($aAttrs, "bx-form-input-{$aInput['type']}");

        $oCaptcha = BxDolCaptcha::getObjectInstance();

        return "<div $sAttrs>" . ($oCaptcha ? $oCaptcha->display($this->_bDynamicMode) : _t('_sys_txt_captcha_not_available')) . "</div>";
    }

    /**
     * Generate Label Element
     *
     * @param  string $sLabel   Text of the Label
     * @param  string $sInputID Dependant Input Element ID
     * @return string HTML code
     */
    function genLabel(&$aInput)
    {
        if (!isset($aInput['label']) or empty($aInput['label']))
            return '';

        $sLabel   = $aInput['label'];
        $sInputID = $this->getInputId($aInput);

        $sRet = '<label for="' . $sInputID . '">' . bx_process_output($sLabel) . '</label>';

        return $sRet;
    }

    function genInfoIcon($sInfo)
    {
        return '<div class="bx-form-info bx-def-font-grayed bx-def-font-small">' . bx_process_output($sInfo, BX_DATA_HTML) . '</div>';
    }

    function genErrorIcon( $sError = '' )
    {
        if ($this->bEnableErrorIcon) {
            $sStyle = '';
            if (!$sError)
                $sStyle = 'style="display:none;"';
            return '<div class="bx-form-warn" ' . $sStyle . '>' . $sError . '</div>';
        }
    }

    function getOpenSection($aAttrs = array(), $sLegend = '')
    {
        if (!$this->_isSectionOpened) {

            if (!$aAttrs || !is_array($aAttrs))
                $aAttrs = array();

            if ($sLegend)
                $sClassesAdd = "bx-form-section-header";
            else
                $sClassesAdd = "bx-form-section-divider";

            $sAttrs = bx_convert_array2attrs($aAttrs, "bx-form-section bx-def-padding-sec-top bx-def-border-top " . $sClassesAdd);

            $this->_isSectionOpened = true;

            return "<!-- form header content begins -->\n <div class=\"bx-form-section-wrapper bx-def-margin-top\"> <div $sAttrs> $sLegend <div class=\"bx-form-section-content bx-def-padding-top bx-def-padding-bottom" . ($sLegend ? ' bx-def-padding-left bx-def-padding-right' : '') . "\">\n";

        } else {

            return '';
        }
    }

    function getCloseSection()
    {
        if ($this->_isSectionOpened) {

            $this->_isSectionOpened = false;
            return "</div> </div> </div> \n<!-- form header content ends -->\n";

        } else {

            return '';
        }
    }

    function getOpenSectionViewMode($aAttrs = array(), $sLegend = '')
    {
        if (!$this->_isSectionOpened) {

            if (!$aAttrs || !is_array($aAttrs))
                $aAttrs = array();

            if ($sLegend)
                $sClassesAdd = "bx-form-section-header";
            else
                $sClassesAdd = "bx-form-section-divider";

            $sAttrs = bx_convert_array2attrs($aAttrs, "bx-form-section bx-form-view-section bx-def-padding-sec-top bx-def-border-top " . $sClassesAdd);

            $this->_isSectionOpened = true;

            return "<!-- form header content begins -->\n <div class=\"bx-form-section-wrapper bx-def-margin-top\"> <div $sAttrs> $sLegend <div class=\"bx-form-section-content bx-def-padding-top\">\n";

        } else {

            return '';
        }
    }

    function getCloseSectionViewMode()
    {
        if ($this->_isSectionOpened) {

            $this->_isSectionOpened = false;
            return "</div> </div> </div> \n<!-- form header content ends -->\n";

        } else {

            return '';
        }
    }

    static function getJsUiLangs ()
    {
        return array ('af' => 1, 'ar-DZ' => 1, 'ar' => 1, 'az' => 1, 'be' => 1, 'bg' => 1, 'bs' => 1, 'ca' => 1, 'cs' => 1, 'cy-GB' => 1, 'da' => 1, 'de' => 1, 'el' => 1, 'en-AU' => 1, 'en-GB' => 1, 'en-NZ' => 1, 'en' => 1, 'eo' => 1, 'es' => 1, 'et' => 1, 'eu' => 1, 'fa' => 1, 'fi' => 1, 'fo' => 1, 'fr-CA' => 1, 'fr-CH' => 1, 'fr' => 1, 'gl' => 1, 'he' => 1, 'hi' => 1, 'hr' => 1, 'hu' => 1, 'hy' => 1, 'id' => 1, 'is' => 1, 'it' => 1, 'ja' => 1, 'ka' => 1, 'kk' => 1, 'km' => 1, 'ko' => 1, 'ky' => 1, 'lb' => 1, 'lt' => 1, 'lv' => 1, 'mk' => 1, 'ml' => 1, 'ms' => 1, 'nb' => 1, 'nl-BE' => 1, 'nl' => 1, 'nn' => 1, 'no' => 1, 'pl' => 1, 'pt-BR' => 1, 'pt' => 1, 'rm' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sl' => 1, 'sq' => 1, 'sr-SR' => 1, 'sr' => 1, 'sv' => 1, 'ta' => 1, 'th' => 1, 'tj' => 1, 'tr' => 1, 'uk' => 1, 'vi' => 1, 'zh-CN' => 1, 'zh-HK' => 1, 'zh-TW' => 1);
    }
    
    function addCssJsUi ()
    {
        if (self::$_isCssJsUiAdded)
            return;

        $aUiLangs = $this->getJsUiLangs ();

        $sUiLang = BxDolLanguages::getInstance()->detectLanguageFromArray ($aUiLangs);

        $this->_addJs(array(
            'jquery-ui/jquery-ui.custom.min.js',
            'jquery-ui/i18n/jquery.ui.datepicker-' . $sUiLang . '.min.js',
        ), "'undefined' === typeof($.datepicker)");
        
        $this->_addCss('jquery-ui/jquery-ui.css');

        self::$_isCssJsUiAdded = true;
    }

    function addCssJsTimepicker ()
    {
        if (self::$_isCssJsTimepickerAdded)
            return; 

        $aCalendarLangs = array ('af' => 1, 'am' => 1, 'bg' => 1, 'ca' => 1, 'cs' => 1, 'da' => 1, 'de' => 1, 'el' => 1, 'es' => 1, 'et' => 1, 'eu' => 1, 'fa' => 1, 'fi' => 1, 'fr' => 1, 'gl' => 1, 'he' => 1, 'hr' => 1, 'hu' => 1, 'id' => 1, 'it' => 1, 'ja' => 1, 'ko' => 1, 'lt' => 1, 'lv' => 1, 'mk' => 1, 'nl' => 1, 'no' => 1, 'pl' => 1, 'pt-BR' => 1, 'pt' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sl' => 1, 'sr-RS' => 1, 'sr-YU' => 1, 'sv' => 1, 'th' => 1, 'tr' => 1, 'uk' => 1, 'vi' => 1, 'zh-CN' => 1, 'zh-TW' => 1);

        $sCalendarLang = BxDolLanguages::getInstance()->detectLanguageFromArray ($aCalendarLangs);            
        $this->_addCss('timepicker-addon/jquery-ui-timepicker-addon.css');
        
        $this->_addJs(array(
            'timepicker-addon/jquery-ui-timepicker-addon.min.js',
            'timepicker-addon/jquery-ui-sliderAccess.js',
            'timepicker-addon/i18n/jquery-ui-timepicker-' . $sCalendarLang . '.js',
            'jquery.ui.touch-punch.min.js',
        ), "'undefined' === typeof($.fn.datetimepicker)");

        self::$_isCssJsTimepickerAdded = true;
    }

    function addCssJsMinicolors ()
    {
        if (self::$_isCssJsMinicolorsAdded)
            return;       

        $this->_addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'jquery-minicolors/|jquery.minicolors.css');
        $this->_addJs('jquery-minicolors/jquery.minicolors.min.js', "'undefined' === typeof($.minicolors)");

        self::$_isCssJsMinicolorsAdded = true;
    }

    function addCssJsViewMode ()
    {
        if (self::$_isCssJsAddedViewMode)
            return;

        $this->_addCss('forms.css');

        self::$_isCssJsAddedViewMode = true;
    }

    function addCssJsCore ()
    {
        if (self::$_isCssJsAdded)
            return;

        $this->_addCss('forms.css');
        $this->_addJs('jquery.webForms.js', "'undefined' === typeof($.fn.addWebForms)");

        self::$_isCssJsAdded = true;
    }

    function addCssJs ()
    {
        if (isset($this->aParams['view_mode']) && $this->aParams['view_mode']) {

            $this->addCssJsViewMode ();

        } else { 

            $this->addCssJsCore();            
        }
    }

    protected function _processCssJs()
    {
        $sRet = '';
        if ($this->_bDynamicMode) {
            $sRet .= $this->oTemplate->addCss($this->_aCss, true);


            $sRet .= "\n<script>\n";
            $sRet .= "\nglJsLoadOnaddWebForms = [];\n";
            foreach ($this->_aJs as $sCondition => $aJs) {
                $sJs = $this->oTemplate->addJs($aJs, true);
                if (!$sJs)
                    continue;
                
                $sRet .= "if ($sCondition)\n";
                if (preg_match_all("/src=\"(.*?)\"/", $sJs, $aMatches))
                    $sRet .= "\tglJsLoadOnaddWebForms = glJsLoadOnaddWebForms.concat(" . json_encode($aMatches[1]) . ");\n";
            }
            $sRet .= "\n</script>\n";
        }
        else {
            $this->oTemplate->addCss($this->_aCss);
            
            $aJs = array();
            foreach ($this->_aJs as $sCondition => $a)
                $aJs = array_merge($aJs, $a);
            $this->oTemplate->addJs($aJs);
        }
        return $sRet;
    }

    protected function _addJs($mixed, $sJsCondition)
    {
        if (!is_array($mixed))
            $mixed = array($mixed);
        $this->_aJs[$sJsCondition] = $mixed;
    }

    protected function _addCss($mixed)
    {
        if (!is_array($mixed))
            $mixed = array($mixed);
        foreach ($mixed as $s)
            $this->_aCss[$s] = $s;
    }
}

/** @} */
