<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Grid representation.
 * @see BxDolGrid
 */
class BxBaseGrid extends BxDolGrid
{
    protected $_oTemplate;
    protected $_aPopupOptions = false;
    protected $_aQueryAppend = false;
    protected $_aConfirmMessages = false;
    protected $_isDisplayPopupOnTextOverflow = true;

    public function __construct ($aOptions, $oTemplate)
    {
        parent::__construct ($aOptions);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    public function performActionDisplay()
    {
        require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

        $this->_echoResultJson(array (
            'grid' => $this->getCode(false),
        ));
    }

    public function performActionReorder()
    {
        $this->_replaceMarkers ();

        $aOrder = bx_get($this->_sObject . '_row');
        $iOrder = 0;
        foreach ($aOrder as $mixedId)
            $this->_updateOrder($mixedId, ++$iOrder);

        $this->_echoResultJson(array());
    }

    public function performActionDelete()
    {
        $this->_replaceMarkers ();

        $iAffected = 0;
        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        foreach ($aIds as $mixedId)
            $iAffected += $this->_delete($mixedId) ? 1 : 0;

        echo $this->_echoResultJson(array_merge(
            array(
                'grid' => $this->getCode(false),
            ),
            $iAffected ? array() : array('msg' => _t("_sys_grid_delete_failed"))
        ));
    }

    public function performActionEnable()
    {
        $this->_replaceMarkers ();

        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $aAffectedIds = array ();
        foreach ($aIds as $mixedId)
            if ($this->_enable($mixedId, (int)bx_get('checked')))
                $aAffectedIds[] = $mixedId;

        $sAction = (int)bx_get('checked') ? 'enable' : 'disable';
        echo $this->_echoResultJson(array(
            $sAction => $aAffectedIds,
        ));
    }

    public function getCode ($isDisplayHeader = true)
    {
        $this->_replaceMarkers ();

        if ($isDisplayHeader && empty($this->_aOptions['paginate_url'])) {
            // reset page query params if grid is just initialized and it uses AJAX paginate
            $this->resetQueryParams();
        }

        $sPaginate = '';
        $aData = array();

        $sIdWrapper = 'bx-grid-wrap-' . $this->_sObject;
        $sIdContainer = 'bx-grid-cont-' . $this->_sObject;
        $sIdTable = 'bx-grid-table-' . $this->_sObject;

        $sFilter = bx_unicode_urldecode(bx_process_input(bx_get('filter')));
        $sOrderField = bx_unicode_urldecode(bx_process_input(bx_get('order_field')));
        $sOrderDir = 0 == strcasecmp('desc', bx_get('order_dir')) ? 'DESC' : 'ASC';

        if ($this->_aOptions['paginate_get_start'])
            $iStart = (int)bx_get($this->_aOptions['paginate_get_start']);
        else
            $iStart = 0;

        if ($this->_aOptions['paginate_get_per_page'] && (int)bx_get($this->_aOptions['paginate_get_per_page']) > 0)
            $iPerPage = (int)bx_get($this->_aOptions['paginate_get_per_page']);
        elseif ($this->_aOptions['paginate_per_page'])
            $iPerPage = (int)$this->_aOptions['paginate_per_page'];
        else
            $iPerPage = 10;

        if ($this->_aOptions['paginate_get_start']) {

            $aData = $this->_getData ($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage + 1);

            $sPageUrl = false;
            if (!empty($this->_aOptions['paginate_url'])) {

                $sPageUrl = $this->_aOptions['paginate_url'];

                $aParamsAppend = array();
                if ($sFilter) {
                    $aParamsAppend['filter'] = bx_process_input(bx_get('filter'));
                }
                if ($sOrderField) {
                    $aParamsAppend['order_field'] = bx_process_input(bx_get('order_field'));
                    $aParamsAppend['order_dir'] = bx_process_input(bx_get('order_dir'));
                }
                if ($aParamsAppend)
                    $sPageUrl = bx_append_url_params($sPageUrl, $aParamsAppend);
            }

            $aPaginateParams = array(
                'start' => $iStart,
                'per_page' => $iPerPage,
                'page_url' =>  $sPageUrl ? $sPageUrl : "javascript:glGrids." . $this->_sObject . ".reload('{start}'); void(0);",
            );

            $oPaginate = new BxTemplPaginate($aPaginateParams, $this->_oTemplate);
            $oPaginate->setNumFromDataArray($aData);

            if (isset($this->_aOptions['paginate_simple']) && false !== $this->_aOptions['paginate_simple'])
                $sPaginate = $oPaginate->getSimplePaginate($this->_aOptions['paginate_simple']);
            else
                $sPaginate = $oPaginate->getPaginate();

        } else {

            $aData = $this->_getData ($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);

        }

        $sPopupOptions = '{}';
        if (!empty($this->_aPopupOptions) && is_array($this->_aPopupOptions))
            $sPopupOptions = json_encode($this->_aPopupOptions);

        $aQueryAppend = array_merge(is_array($this->_aQueryAppend) ? $this->_aQueryAppend : array(), is_array($this->_aMarkers) ? $this->_aMarkers : array());
        $sQueryAppend = '{}';
        if (!empty($aQueryAppend) && is_array($aQueryAppend))
            $sQueryAppend = json_encode($aQueryAppend);

        $sConfirmMessages = '{}';
        if (!empty($this->_aConfirmMessages) && is_array($this->_aConfirmMessages))
            $sConfirmMessages = json_encode($this->_aConfirmMessages);

        $aVars = array (
            'object' => $this->_sObject,
            'id_table' => $sIdTable,
            'id_cont' => $sIdContainer,
            'id_wrap' => $sIdWrapper,
            'sortable' => empty($this->_aOptions['field_order']) ? 0 : 1,
            'sorting' => empty($this->_aOptions['sorting_fields']) ? 0 : 1,
            'sorting_field' => $sOrderField,
            'sorting_dir' => $sOrderDir,
            'bx_repeat:row_header' => $this->_getRowHeader (),
            'bx_repeat:rows_data' => $this->_getRowsDataDesign ($aData),
            'paginate' => $sPaginate,
            'paginate_get_start' => $this->_aOptions['paginate_get_start'],
            'paginate_get_per_page' => $this->_aOptions['paginate_get_per_page'],
            'start' => $iStart,
            'per_page' => $iPerPage,
            'filter' => bx_js_string($sFilter, BX_ESCAPE_STR_APOS),
            'order_field' => bx_js_string($sOrderField, BX_ESCAPE_STR_APOS),
            'order_dir' => bx_js_string($sOrderDir, BX_ESCAPE_STR_APOS),
            'popup_options' => $sPopupOptions,
            'query_append' => $sQueryAppend,
            'confirm_messages' => $sConfirmMessages,
            'columns' => count($this->_aOptions['fields']),
            'bx_if:actions_bulk' => array (
                'condition' => !empty($this->_aOptions['actions_bulk']),
                'content' => array(
                    'actions_bulk' => $this->_getActions ('bulk'),
                ),
            ),
            'bx_if:display_header' => array (
                'condition' => $isDisplayHeader,
                'content' => array (
                    'bx_if:actions_independent' => array (
                        'condition' => !empty($this->_aOptions['actions_independent']),
                        'content' => array(
                            'actions_independent' => $this->_getActions ('independent'),
                        ),
                    ),
                    'bx_if:filter' => array (
                        'condition' => !empty($this->_aOptions['filter_fields']) || !empty($this->_aOptions['filter_fields_translatable']),
                        'content' => array(
                            'controls' => $this->_getFilterControls(),
                        ),
                    ),
                ),
            ),
        );

        $this->_addJsCss();

        return $this->_oTemplate->parseHtmlByName('grid.html', $aVars);
    }

    /**
     * Reset query params, like filter and page number
     */
    public function resetQueryParams()
    {
        $aKeys = array('filter', 'order_field', 'order_dir', $this->_aOptions['paginate_get_start'], $this->_aOptions['paginate_get_per_page']);
        foreach ($aKeys as $sKey) {
            unset($_GET[$sKey]);
            unset($_POST[$sKey]);
        }
    }

    protected function _getRowHeader ()
    {
        $aRet = array();
        foreach ($this->_aOptions['fields'] as $sKey => $a) {

            $sMethod = '_getCellHeaderDefault';
            $sCustomMethod = '_getCellHeader' . $this->_genMethodName($sKey);
            if (method_exists($this, $sCustomMethod))
                $sMethod = $sCustomMethod;

            $aRet[] = array('header_cell' => $this->$sMethod($sKey, $a));
        }
        return $aRet;
    }

    protected function _getCellHeaderDefault ($sKey, $aField)
    {
        $sHeader = bx_process_output($aField['title']);

        if (!empty($this->_aOptions['sorting_fields']) && is_array($this->_aOptions['sorting_fields']) && in_array($sKey, $this->_aOptions['sorting_fields'])) {
            $sHeader = '<a href="javascript:void(0);" class="bx-grid-sort-handle">' . $sHeader . '</a><span class="bx-grid-sort-indi"></span>';
            $aField['attr_head']['bx_grid_sort_head'] = $sKey;
        }

        $sAttr = $this->_convertAttrs(
            $aField, 'attr_head',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top bx-grid-header-' . $sKey, // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false // add default styles
        );

        return $this->_getCellHeaderWrapper ($sKey, $aField, $sHeader, $sAttr);
    }

    protected function _getCellHeaderCheckbox ($sKey, $aField)
    {
        $sAttr = $this->_convertAttrs(
            $aField, 'attr_head',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top bx-grid-header-' . $sKey, // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false // add default styles
        );
        return $this->_getCellHeaderWrapper ($sKey, $aField, ' <input type="checkbox" id="'. $this->_sObject . '_check_all" name="'. $this->_sObject . '_check_all" onclick="$(\'input[name='. $this->_sObject . '_check]:not([disabled])\').attr(\'checked\', this.checked)" /> ', $sAttr);
    }

    protected function _getCellHeaderWrapper ($sKey, $aField, $sHeader, $sAttr)
    {
        return '<th ' . $sAttr . '>' . $sHeader . '</th>';
    }

    /**
     * Check if the whole row is disabled.
     * When row is disabled - checkbox is not selectable, actions aren't clickable and text is grayed out.
     * By default all rows aren't disabled.
     * @param $aRow row array
     * @return boolean
     */
    protected function _isRowDisabled($aRow)
    {
        if (isset($aRow[$this->_aOptions['field_active']]) && !$this->_switcherState2Checked($aRow[$this->_aOptions['field_active']]))
            return true;
        return false;
    }

    /**
     * Determine how actions are disabled when whole row is disabled.
     * @param $aRow row array
     * @return null - disable/enable actions when row is disabled/enabled, true - actions are always disabled, false - actions are always enabled
     */
    protected function _getActionsDisabledBehavior($aRow)
    {
        return null;
    }

    /**
     * Check if the checkbox is disabled.
     * @param $aRow row array
     * @return boolean
     */
    protected function _isCheckboxDisabled($aRow)
    {
        return $this->_isRowDisabled($aRow);
    }

    /**
     * Is checkbox checked by default ?
     * By default no one checkbox is selected.
     * @return boolean
     */
    protected function _isCheckboxSelected($mixedValue, $sKey, $aField, $aRow)
    {
        return false;
    }

    /**
     * Is switcher on by default ?
     * By default no one switcher is on.
     * @return boolean
     */
    protected function _isSwitcherOn($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_switcherState2Checked($mixedValue);
    }

    /**
     * Convert switcher checked status to the actual value ?
     * @return boolean
     */
    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 1 : 0;
    }

    /**
     * Convert switcher value to checked(boolean) value ?
     * @return boolean
     */
    protected function _switcherState2Checked($mixedState)
    {
        return $mixedState ? true : false;
    }

    protected function _getRowsDataDesign (&$aData)
    {
        $aGrid = array();
        if (empty($aData)) {

            $aGrid[] = array(
                'row' => '<td class="bx-def-padding-sec-bottom bx-def-padding-sec-top" colspan="' . count($this->_aOptions['fields']) . '">' . _t('_Empty') . '</td>',
                'id_row' => 0,
                'row_class' => 'bx-grid-table-row-empty',
            );

        } else {

            foreach ($aData as $aRow) {
                $sRow = '';
                foreach ($this->_aOptions['fields'] as $sKey => $aField)
                    $sRow .= $this->_getCellDesign($sKey, $aField, $aRow);

                $aGrid[] = array(
                    'row' => $sRow,
                    'id_row' => $this->_sObject . '_row_' . $aRow[$this->_aOptions['field_id']],
                    'row_class' => $this->_isRowDisabled($aRow) ? 'bx-grid-table-row-disabled bx-def-font-grayed' : '',
                );
            }
        }
        return $aGrid;
    }

    protected function _getCellDesign($sKey, $aField, $aRow)
    {
        $sMethod = '_getCellDefault';
        if ($this->_aOptions['field_order'] == $sKey)
            $sMethod = '_getCellOrder';

        $sCustomMethod = '_getCell' . $this->_genMethodName($sKey);
        if (method_exists($this, $sCustomMethod))
            $sMethod = $sCustomMethod;

        $mixedValue = $this->_getCellData($sKey, $aField, $aRow);

        if ($aField['translatable'])
            $mixedValue = _t($mixedValue);

        $mixedValue = $this->_limitMaxLength($mixedValue, $sKey, $aField, $aRow, $this->_isDisplayPopupOnTextOverflow);

        return $this->$sMethod($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellCheckbox ($mixedValue, $sKey, $aField, $aRow)
    {
        $sAttr = $this->_convertAttrs(
            $aField, 'attr_cell',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top', // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false  // add default styles
        );
        $sDisabled = ($this->_isCheckboxDisabled($aRow) ? 'disabled="disabled"' : '');
        $sSelected = ($this->_isCheckboxSelected($mixedValue, $sKey, $aField, $aRow) ? 'checked="checked"' : '');
        $sVal = $aRow[$this->_aOptions['field_id']];
        return '<td ' . $sAttr . '><input type="checkbox" name="'. $this->_sObject . '_check" value="' . $sVal . '" ' . $sDisabled . ' ' . $sSelected . '/></td>';
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        $sAttr = $this->_convertAttrs(
            $aField, 'attr_cell',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top', // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false  // add default styles
        );

        $oForm = new BxTemplFormView(array(), $this->_oTemplate);
        $oForm->addCssJs();
        $aInput = array(
            'type' => 'switcher',
            'name' => $this->_sObject . '_switch_' . $aRow[$this->_aOptions['field_id']],
            'caption' => '',
            'attrs' => array (
                'bx_grid_action_single' => 'enable',
                'bx_grid_action_confirm' => '',
                'bx_grid_action_data' => $aRow[$this->_aOptions['field_id']],
            ),
            'value' => $aRow[$this->_aOptions['field_id']],
            'checked' => $this->_isSwitcherOn(isset($aRow[$this->_aOptions['field_active']]) ? $aRow[$this->_aOptions['field_active']] : false, $sKey, $aField, $aRow),
        );
        $sSwitcher = $oForm->genInput($aInput);
        return '<td ' . $sAttr . '>' . $sSwitcher . '</td>';
    }

    protected function _getCellOrder ($mixedValue, $sKey, $aField, $aRow)
    {
        $sAttr = $this->_convertAttrs(
            $aField, 'attr_cell',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top', // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false  // add default styles
        );
        return '<td ' . $sAttr . '><div id="' . $this->_sObject . '_cell_' . $aRow[$this->_aOptions['field_id']] . '" class="bx-grid-drag-handle"><i class="sys-icon align-justify"></i></div></td>';
    }

    protected function _getCellActions ($mixedValue, $sKey, $aField, $aRow)
    {
        $sAttr = $this->_convertAttrs(
            $aField, 'attr_cell',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top', // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false  // add default styles
        );

        $mixedDisabledBehavior = $this->_getActionsDisabledBehavior($aRow);
        $sActions = $this->_getActions('single', $aRow[$this->_aOptions['field_id']], false, null === $mixedDisabledBehavior ? $this->_isRowDisabled($aRow) : $mixedDisabledBehavior, null !== $mixedDisabledBehavior, $aRow);

        return '<td ' . $sAttr . '><div class="bx-grid-cell-single-actions-wrapper">' . $sActions . '</div></td>';
    }

    protected function _getCellDefault ($mixedValue, $sKey, $aField, $aRow)
    {
        $sAttr = $this->_convertAttrs(
            $aField, 'attr_cell',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top', // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false // add default styles
        );
        return '<td ' . $sAttr . '>' . $mixedValue . '</td>';
    }

    protected function _getActions ($sType, $sActionData = false, $isSmall = false, $isDisabled = false, $isPermanentState = false, $aRow = array())
    {
        $sActionsType = 'actions_' . $sType;
        if (empty($this->_aOptions[$sActionsType]) || !is_array($this->_aOptions[$sActionsType]))
            return '';
        $sRet = '';
        foreach ($this->_aOptions[$sActionsType] as $sKey => $a) {
            $sFunc = '_getAction' . $this->_genMethodName($sKey);
            if (!method_exists($this, $sFunc))
                $sFunc = empty($a) ? '_getActionDivider' : '_getActionDefault';

            if (!isset($a['attr']['bx_grid_action'])) {
                $a['attr']['bx_grid_action_' . $sType] = $sKey;
                if (!empty($sActionData))
                    $a['attr']['bx_grid_action_data'] = $sActionData;
                if (isset($a['confirm']))
                    $a['attr']['bx_grid_action_confirm'] = $a['confirm'] ? 1 : 0;
                $a['attr']['bx_grid_action_reset_paginate'] = false === strpos($sKey, 'delete') ? 0 : 1; // reset paginate after deleting row 
            }

            if ($isPermanentState && !isset($a['attr']['bx_grid_permanent_state']))
                $a['attr']['bx_grid_permanent_state'] = 1;

            $sRet .= $this->$sFunc($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
        }
        return $sRet;
    }

    protected function _getActionDefault ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if ($a['icon_only'] && empty($a['attr']['title']) && !empty($a['title']))
            $a['attr']['title'] = $a['title'];

        $sAttr = $this->_convertAttrs(
            $a, 'attr',
            'bx-btn bx-def-margin-sec-left' . ($isSmall ? ' bx-btn-small' : '') . ($isDisabled ? ' bx-btn-disabled' : '') // add default classes
        );

        $sIcon = '';
        $sImage = '';
        if (!empty($a['icon'])) {
            if (false === strpos($a['icon'], '.'))
                $sIcon = '<i class="sys-icon ' . $a['icon'] . '"></i>';
            elseif ($sIconUrl = $this->_oTemplate->getIconUrl($a['icon']))
                $sImage = '<i style="background-image:url(' . $sIconUrl . ');"></i>';
        }
        return '<button ' . $sAttr . '>' . $sIcon . $sImage . ($a['icon_only'] ? '' : $a['title']) . '</button>';
    }

    protected function _getActionDivider ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return '<div class="bx-grid-actions-divider bx-def-margin-sec-left"> | </div>';
    }

    protected function _getFilterControls ()
    {
        $oForm = new BxTemplStudioFormView(array());

        $aInput = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject
            )
        );

        $this->_oTemplate->addCss('forms.css');
        return $oForm->genRow($aInput);
    }

    protected function _limitMaxLength ($mixedValue, $sKey, $aField, $aRow, $isDisplayPopupOnTextOverflow, $bReturnString = true)
    {
        if ($aField['chars_limit'] > 0)
            $mixedValue = BxTemplFunctions::getInstance()->getStringWithLimitedLength($mixedValue, $aField['chars_limit'], $isDisplayPopupOnTextOverflow, $bReturnString);
        return $mixedValue;
    }

    protected function _convertAttrs ($aField, $sAttrName, $sClasses = false, $sStyles = false)
    {
        return bx_convert_array2attrs(
            isset($aField[$sAttrName]) && is_array($aField[$sAttrName]) ? $aField[$sAttrName] : array(),
            $sClasses,
            $sStyles
        );
    }

    protected function _updateOrder($mixedId, $iOrder)
    {
        $oDb = BxDolDb::getInstance();
        $sTable = $this->_aOptions['table'];
        $sFieldId = $this->_aOptions['field_id'];
        $sFieldOrder = $this->_aOptions['field_order'];
        $sQuery = $oDb->prepare("UPDATE `{$sTable}` SET `{$sFieldOrder}` = ? WHERE `{$sFieldId}` = ?", $iOrder, $mixedId);
        return $oDb->query($sQuery);
    }

    protected function _delete ($mixedId)
    {
        $oDb = BxDolDb::getInstance();
        $sTable = $this->_aOptions['table'];
        $sFieldId = $this->_aOptions['field_id'];
        $sQuery = $oDb->prepare("DELETE FROM `{$sTable}` WHERE `{$sFieldId}` = ?", $mixedId);
        return $oDb->query($sQuery);
    }

    protected function _enable ($mixedId, $isChecked)
    {
        $oDb = BxDolDb::getInstance();
        $sTable = $this->_aOptions['table'];
        $sFieldId = $this->_aOptions['field_id'];
        $sFieldActive = $this->_aOptions['field_active'];
        $sQuery = $oDb->prepare("UPDATE `{$sTable}` SET `$sFieldActive` = ? WHERE `{$sFieldId}` = ?", $this->_switcherChecked2State($isChecked), $mixedId);
        return $oDb->query($sQuery);
    }

    protected function _addJsCss()
    {
        if ($this->_aOptions['field_order']) {
            $this->_oTemplate->addJs(array(
                'jquery-ui/jquery.ui.core.min.js',
                'jquery-ui/jquery.ui.widget.min.js',
                'jquery-ui/jquery.ui.mouse.min.js',
                'jquery-ui/jquery.ui.sortable.min.js',
            ));
        }

        $this->_oTemplate->addJs('BxDolGrid.js');
        $this->_oTemplate->addCss('grid.css');

        $this->_oTemplate->addJsTranslation('_sys_grid_confirmation');
    }

    protected function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false)
    {
        header('Content-type: text/html; charset=utf-8');

        $s = json_encode($a);
        if ($isAutoWrapForFormFileSubmit && !empty($_FILES))
            $s = '<textarea>' . $s . '</textarea>'; // http://jquery.malsup.com/form/#file-upload
        echo $s;
    }
}

/** @} */
