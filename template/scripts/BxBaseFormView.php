<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolForm');

class BxBaseFormView extends BxDolForm
{
    protected static $_isToggleJsAdded = false;

    protected static $_isCssJsAdded = false;
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
     * Function name for generation close form section HTML.
     */
    protected $_sSectionClose = 'getCloseSection';

    /**
     * Function name for generation open form section HTML.
     */
    protected $_sSectionOpen = 'getOpenSection';

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

        if (isset($this->aParams['view_mode']) && $this->aParams['view_mode']) {
            $this->_sSectionClose = 'getCloseSectionViewMode';
            $this->_sSectionOpen = 'getOpenSectionViewMode';
        }
    }

    /**
     * Return Form code
     * @param $bDynamicMode - set it to true if form is added via JS/AJAX call, for example form in AJAX popup.
     * @return string
     */
    function getCode($bDynamicMode = false)
    {
        $this->_bDynamicMode = $bDynamicMode;
        $this->addCssJs ();
        $this->aFormAttrs = $this->_replaceMarkers($this->aFormAttrs);
        return ($this->sCode = $this->genForm());
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
        $sFuncGenRow = isset($this->aParams['view_mode']) && $this->aParams['view_mode'] ? 'genViewRow' : 'genRow';
        foreach ($this->aInputs as $aInput)
            if (!isset($aInput['visible_for_levels']) || $this->_isVisible($aInput))
                $sCont .= $this->$sFuncGenRow($aInput);

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
                <div class="bx-form-row-view-caption bx-def-margin-sec-right">{$sCaption}:</div>
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
        switch ($aInput['type']) {

            case 'hidden':
                $sValue = null;
            break;

            case 'select':
            case 'radio_set':
                $sValue = isset($aInput['value']) && isset($aInput['values'][$aInput['value']]) ? $aInput['values'][$aInput['value']] : null;
            break;

            case 'datepicker':
                $iTime = bx_process_input ($aInput['value'], BX_DATA_DATE_TS, false, false);
                $sValue = bx_time_js ($iTime, BX_FORMAT_DATE);
            break;
            case 'date_time':
            case 'datetime':
                $iTime = bx_process_input ($aInput['value'], BX_DATA_DATETIME_TS, false, false);
                $sValue = bx_time_js ($iTime, BX_FORMAT_DATE_TIME);
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
                $sValue = isset($aInput['value']) ? $aInput['value'] : null;
            break;

            default:
                $sValue = isset($aInput['value']) ? bx_process_output($aInput['value']) : null;
        }

        return $sValue;
    }

    /**
     * Generate standard row
     *
     * @param  array  $aInput
     * @return string
     */
    function genRowStandard(&$aInput, $isOneLine = false)
    {
        $sCaption = isset($aInput['caption']) ? bx_process_output($aInput['caption']) : '';

        $sRequired = !empty($aInput['required']) ? '<span class="bx-form-required">*</span> ' : '';

        $sClassAdd = !empty($aInput['error']) ? ' bx-form-error' : '';
        $sInfoIcon = !empty($aInput['info']) ? $this->genInfoIcon($aInput['info']) : '';

        $sErrorIcon = $this->genErrorIcon(empty($aInput['error']) ? '' : $aInput['error']);

        if (isset($aInput['name']))
            $aInput['tr_attrs']['id'] = "bx-form-element-" . $aInput['name'];
        $sTrAttrs = bx_convert_array2attrs(isset($aInput['tr_attrs']) && is_array($aInput['tr_attrs']) ? $aInput['tr_attrs'] : array(), "bx-form-element-wrapper bx-def-margin-top");

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

        $sCaptionCode = '';
        if ($sCaption)
            $sCaptionCode = '<div class="bx-form-caption' . $sClassOneLineCaption . '">' . $sCaption . $sRequired . '</div>';
        else
            $sInput .= $sRequired;

        $sInputCode = $this->genWrapperInput($aInput, $sInput);


        if (empty($sInputCodeExtra)) $sInputCodeExtra = '';
        if (empty($sInfoIcon)) $sInfoIcon = '';
        if (empty($sInputCode)) $sInputCode = '';
        if (empty($sErrorIcon)) $sErrorIcon = '';

        $sValueCode = '
                    <div class="bx-form-value bx-clearfix' . $sClassAdd . $sClassOneLineValue . '">
                        ' . $sInputCode . '
                        ' . ($isOneLine ? '' : $sInfoIcon . $sErrorIcon) . '
                        ' . $sInputCodeExtra . '
                    </div>';

        if ($isOneLine)
            $sCode = $sValueCode . $sCaptionCode . '<div class="bx-clear"></div>' . $sInfoIcon . $sErrorIcon;
        else
            $sCode = $sCaptionCode . $sValueCode;

        return "<div $sTrAttrs>" . $sCode . "</div>";
    }


    function genWrapperInput($aInput, $sContent)
    {
        $sAttr = isset($aInput['attrs_wrapper']) && is_array($aInput['attrs_wrapper']) ? bx_convert_array2attrs($aInput['attrs_wrapper']) : '';

        $sCode = <<<BLAH
                <div class="bx-form-input-wrapper bx-form-input-wrapper-{$aInput['type']}" $sAttr>
                    $sContent
                </div>
BLAH;

        return $sCode;
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
            $aAttrs['class'] = 'bx-form-collapsable ' . $sClassAddCollapsable;
        else
            $aAttrs['class'] .= ' bx-form-collapsable ' . $sClassAddCollapsable;


        if (isset($this->aParams['view_mode']) && $this->aParams['view_mode'])
            $sLegend = '<legend class="bx-def-padding-sec-right bx-def-font-grayed bx-def-font-h3">' . bx_process_output($aInput['caption']) . '</legend>';
        else
            $sLegend = '<legend class="bx-def-padding-left bx-def-padding-sec-right bx-def-font-grayed bx-def-font-h3"><a href="javascript:void(0);">' . bx_process_output($aInput['caption']) . '</a></legend>';

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
            case 'checkbox':
            case 'radio':
            case 'file':
            case 'image':
            case 'password':
            case 'slider':
            case 'doublerange':
            case 'hidden':
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

            case 'value':
                $sInput = $aInput['value'];
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

        $sPattern = 'a-z0-9';

        $sName = preg_replace("/[^$sPattern]/i", '_', $aInput['name']);

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
            $sValue = preg_replace("/[^$sPattern]/i", '_', $aInput['value']);

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

        if (isset($aInput['name'])) $aAttrs['name'] = $aInput['name'];
        if (isset($aInput['value'])) $aAttrs['value'] = $aInput['value'];



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

        $sValue = isset($aInput['value']) ? bx_process_output($aInput['value']) : '';

        return "<textarea $sAttrs>$sValue</textarea>";
    }

    function addHtmlEditor($iViewMode, &$aInput)
    {
        bx_import('BxDolEditor');
        $oEditor = BxDolEditor::getObjectInstance();
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
     * Generate Select Box Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputFiles(&$aInput, $sInfo = '', $sError = '')
    {
        bx_import('BxDolUploader');

        $sUniqId = genRndPwd (8, false);
        $sUploaders = '';
        $oUploader = null;
        foreach ($aInput['uploaders'] as $sUploaderObject) {
            $oUploader = BxDolUploader::getObjectInstance($sUploaderObject, $aInput['storage_object'], $sUniqId);
            if (!$oUploader)
                continue;

            $sGhostTemplate = false;
            if (isset($aInput['ghost_template']) && is_object($aInput['ghost_template'])) { // form is not submitted and ghost template is BxDolFormNested object

                $oFormNested = $aInput['ghost_template'];
                if ($oFormNested instanceof BxDolFormNested)
                    $sGhostTemplate = $oFormNested->getCode();

            } elseif (isset($aInput['ghost_template']) && is_array($aInput['ghost_template']) && isset($aInput['ghost_template']['inputs'])) { // form is not submitted and ghost template is form array

                bx_import('BxDolFormNested');
                $oFormNested = new BxDolFormNested($aInput['name'], $aInput['ghost_template'], $this->aParams['db']['submit_name'], $this->oTemplate);
                $sGhostTemplate = $oFormNested->getCode();

            } elseif (isset($aInput['ghost_template']) && is_array($aInput['ghost_template']) && $aInput['ghost_template']) { // form is submitted and ghost template is array of BxDolFormNested objects

                bx_import('BxDolFormNested');
                $sGhostTemplate = array ();
                foreach ($aInput['ghost_template'] as $iFileId => $oFormNested)
                    if (is_object($oFormNested) && $oFormNested instanceof BxDolFormNested)
                        $sGhostTemplate[$iFileId] = $oFormNested->getCode();

            } elseif (isset($aInput['ghost_template']) && is_string($aInput['ghost_template'])) { // ghost template is just string template, without nested form

                $sGhostTemplate = $aInput['ghost_template'];

            }

            $aParams = array(
                'button_title' => bx_js_string($oUploader->getUploaderButtonTitle(isset($aInput['upload_buttons_titles']) ? $aInput['upload_buttons_titles'] : false)),
                'content_id' => isset($aInput['content_id']) ? $aInput['content_id'] : '',
            );
            if (isset($aInput['images_transcoder']) && $aInput['images_transcoder'])
                $aParams['images_transcoder'] = bx_js_string($aInput['images_transcoder']);

            $sUploaders .= $oUploader->getUploaderButton($sGhostTemplate, isset($aInput['multiple']) ? $aInput['multiple'] : true, $aParams);
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
                $sClassC = $sStyleC = "";
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

                    $sClassC = isset($sTitle['class']) ? " class=\"" . $sTitle['class'] . "\"" : "";
                    $sStyleC = isset($sTitle['style']) ? " style=\"" . $sTitle['style'] . "\"" : "";
                    $sValue = $sTitle['key'];
                    $sTitle = $sTitle['value'];
                }
                $sValueC = bx_html_attribute($sValue);
                $sTitleC = bx_process_output($sTitle);

                $sSelected = $this->$sIsSelectedFunc($sValue, $mixedCurrentVal) ? 'selected="selected"' : '';

                $sOptions .= <<<BLAH
                   <option value="$sValueC"$sClassC$sStyleC $sSelected>$sTitleC</option>
BLAH;

            }
        }

        // generate element
        $sCode = <<<BLAH
            <select $sAttrs>
                $sOptions
            </select>
BLAH;

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

        if (isset($aInput['values']) and is_array($aInput['values'])) {
            if (count($aInput['values']) > 3 && $sDivider == $this->_sDivider)
                $sDivider = $this->_sDividerAlt;
            // generate complex input using simple standard inputs
            foreach ($aInput['values'] as $sValue => $sLabel) {
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

    function genInputCaptcha(&$aInput)
    {
        $aAttrs = empty($aInput['attrs']) ? array() : $aInput['attrs'];

        // for inputs with labels generate id
        if (isset($aInput['label']))
            $aAttrs['id'] = $this->getInputId($aInput);

        $sAttrs = bx_convert_array2attrs($aAttrs, "bx-form-input-{$aInput['type']}");

        bx_import('BxDolCaptcha');
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
        return '<div class="bx-form-info bx-def-font-grayed bx-def-font-small">' . bx_process_output($sInfo) . '</div>';
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

            return "<!-- form header content begins -->\n <div class=\"bx-form-section-wrapper bx-def-margin-top\"> <fieldset $sAttrs> $sLegend <div class=\"bx-form-section-content bx-def-padding-top bx-def-padding-bottom" . ($sLegend ? ' bx-def-padding-left bx-def-padding-right' : '') . "\">\n";

        } else {

            return '';
        }
    }

    function getCloseSection()
    {
        if ($this->_isSectionOpened) {

            $this->_isSectionOpened = false;
            return "</div> </fieldset> </div> \n<!-- form header content ends -->\n";

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

            return "<!-- form header content begins -->\n <div class=\"bx-form-section-wrapper bx-def-margin-top\"> <fieldset $sAttrs> $sLegend <div class=\"bx-form-section-content bx-def-padding-top\">\n";

        } else {

            return '';
        }
    }

    function getCloseSectionViewMode()
    {
        if ($this->_isSectionOpened) {

            $this->_isSectionOpened = false;
            return "</div> </fieldset> </div> \n<!-- form header content ends -->\n";

        } else {

            return '';
        }
    }

    function addCssJs ()
    {
        if (isset($this->aParams['view_mode']) && $this->aParams['view_mode']) {

            if (self::$_isCssJsAddedViewMode)
                return;

            $this->oTemplate->addCss('forms.css');

            self::$_isCssJsAddedViewMode = true;

        } else {

            if (self::$_isCssJsAdded)
                return;

            $aCss = array(
                'forms.css',

                'jquery-ui/jquery-ui.css',

                'timepicker-addon/jquery-ui-timepicker-addon.css',
            );

            $aUiLangs = array ('af' => 1, 'ar-DZ' => 1, 'ar' => 1, 'az' => 1, 'be' => 1, 'bg' => 1, 'bs' => 1, 'ca' => 1, 'cs' => 1, 'cy-GB' => 1, 'da' => 1, 'de' => 1, 'el' => 1, 'en-AU' => 1, 'en-GB' => 1, 'en-NZ' => 1, 'en' => 1, 'eo' => 1, 'es' => 1, 'et' => 1, 'eu' => 1, 'fa' => 1, 'fi' => 1, 'fo' => 1, 'fr-CA' => 1, 'fr-CH' => 1, 'fr' => 1, 'gl' => 1, 'he' => 1, 'hi' => 1, 'hr' => 1, 'hu' => 1, 'hy' => 1, 'id' => 1, 'is' => 1, 'it' => 1, 'ja' => 1, 'ka' => 1, 'kk' => 1, 'km' => 1, 'ko' => 1, 'ky' => 1, 'lb' => 1, 'lt' => 1, 'lv' => 1, 'mk' => 1, 'ml' => 1, 'ms' => 1, 'nb' => 1, 'nl-BE' => 1, 'nl' => 1, 'nn' => 1, 'no' => 1, 'pl' => 1, 'pt-BR' => 1, 'pt' => 1, 'rm' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sl' => 1, 'sq' => 1, 'sr-SR' => 1, 'sr' => 1, 'sv' => 1, 'ta' => 1, 'th' => 1, 'tj' => 1, 'tr' => 1, 'uk' => 1, 'vi' => 1, 'zh-CN' => 1, 'zh-HK' => 1, 'zh-TW' => 1);

            $aCalendarLangs = array ('af' => 1, 'am' => 1, 'bg' => 1, 'ca' => 1, 'cs' => 1, 'da' => 1, 'de' => 1, 'el' => 1, 'es' => 1, 'et' => 1, 'eu' => 1, 'fi' => 1, 'fr' => 1, 'gl' => 1, 'he' => 1, 'hr' => 1, 'hu' => 1, 'id' => 1, 'it' => 1, 'ja' => 1, 'ko' => 1, 'lt' => 1, 'nl' => 1, 'no' => 1, 'pl' => 1, 'pt-BR' => 1, 'pt' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sr-RS' => 1, 'sr-YU' => 1, 'sv' => 1, 'th' => 1, 'tr' => 1, 'uk' => 1, 'vi' => 1, 'zh-CN' => 1, 'zh-TW' => 1);

            bx_import('BxDolLanguages');
            $sCalendarLang = BxDolLanguages::getInstance()->detectLanguageFromArray ($aCalendarLangs);
            $sUiLang = BxDolLanguages::getInstance()->detectLanguageFromArray ($aUiLangs);

            $aJs = array(
                'jquery.webForms.js',

                'jquery-ui/jquery.ui.core.min.js',
                'jquery-ui/jquery.ui.widget.min.js',
                'jquery-ui/jquery.ui.mouse.min.js',
                'jquery-ui/jquery.ui.position.min.js',
                'jquery-ui/jquery.ui.slider.min.js',
                'jquery-ui/jquery.ui.datepicker.min.js',
                'jquery-ui/i18n/jquery.ui.datepicker-' . $sUiLang . '.js',

                'timepicker-addon/jquery-ui-timepicker-addon.min.js',
                'timepicker-addon/jquery-ui-sliderAccess.js',
                'timepicker-addon/i18n/jquery-ui-timepicker-' . $sCalendarLang . '.js',
            );

            foreach ($this->aInputs as $aInput) {
                if (!isset($aInput['type']) || 'files' != $aInput['type'] || !isset($aInput['uploaders']))
                    continue;

                bx_import('BxDolUploader');
                foreach ($aInput['uploaders'] as $sUploaderObject) {
                    $oUploader = BxDolUploader::getObjectInstance($sUploaderObject, $aInput['storage_object'], '');
                    if ($oUploader)
                        $oUploader->addCssJs();
                }
            }
                    

            $this->oTemplate->addJs($aJs);
            $this->oTemplate->addCss($aCss);

            self::$_isCssJsAdded = true;

        }
    }
}

/** @} */
