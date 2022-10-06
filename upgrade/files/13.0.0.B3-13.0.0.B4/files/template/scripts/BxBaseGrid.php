<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
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
    protected $_aQueryAppend = [];
    protected $_aQueryAppendExclude = false; // an array of keys which shouldn't be pathed in http requests, but can be stored (used) in 'Query Append' array.
    protected $_aQueryReset = false;
    protected $_aConfirmMessages = false;
    protected $_bSelectAll = false;
    protected $_isDisplayPopupOnTextOverflow = true;

    public function __construct ($aOptions, $oTemplate)
    {
        parent::__construct ($aOptions);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_aPopupOptions = [];

        $this->_aQueryAppend = [
            $this->_oTemplate->getCodeKey() => $this->_oTemplate->getCode()
        ];
        $this->_aQueryAppendExclude = [];

        $this->_aQueryReset = [
            $this->_aOptions['filter_get'], 
            $this->_aOptions['order_get_field'], 
            $this->_aOptions['order_get_dir'], 
            $this->_aOptions['paginate_get_start'], 
            $this->_aOptions['paginate_get_per_page']
        ];

        $this->_aConfirmMessages = [];
    }

    public function performActionDisplay()
    {
        require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

        echoJson(array(
            'grid' => $this->getCode(false), 
            'total_count' => $this->_iTotalCount,
            'total_count_f' => $this->_getCounter()
        ));
    }

    public function performActionReorder()
    {
        $this->_replaceMarkers ();

        $aOrder = bx_get($this->_sObject . '_row');
        $iOrder = 0;
        foreach ($aOrder as $mixedId)
            $this->_updateOrder($mixedId, ++$iOrder);

        echoJson(array());
    }

    public function performActionDelete()
    {
        $this->_replaceMarkers ();

        $iAffected = 0;
        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        foreach ($aIds as $mixedId)
            $iAffected += $this->_delete($mixedId) ? 1 : 0;

        echo echoJson(array_merge(
            array(
                'grid' => $this->getCode(false),
            ),
            $iAffected ? array() : array('msg' => _t("_sys_grid_delete_failed"))
        ));
    }

    public function performActionEnable($mixedChecked = null)
    {
        $this->_replaceMarkers ();

        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        $iChecked = (int)($mixedChecked !== null ? $mixedChecked : bx_get('checked'));
        
        $aAffectedIds = array ();
        foreach ($aIds as $mixedId)
            if ($this->_enable($mixedId, $iChecked))
                $aAffectedIds[] = preg_match("/^[\d\w]+$/", $mixedId) ? $mixedId : (int)$mixedId;

        $sAction = $iChecked ? 'enable' : 'disable';
        echo echoJson(array($sAction => $aAffectedIds));
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

        $sFilter = bx_unicode_urldecode(bx_process_input(bx_get($this->_aOptions['filter_get'])));
        $sOrderField = bx_unicode_urldecode(bx_process_input(bx_get($this->_aOptions['order_get_field'])));
        $sOrderDir = 0 === strcasecmp('desc', bx_get($this->_aOptions['order_get_dir'])) ? 'DESC' : 'ASC';

        $iStart = 0;
        if($this->_aOptions['paginate_get_start'] && ($iStartGet = (int)bx_get($this->_aOptions['paginate_get_start'])) >= 0)
            $iStart = $iStartGet;

        $iPerPage = 10;
        if($this->_aOptions['paginate_get_per_page'] && ($iPerPageGet = (int)bx_get($this->_aOptions['paginate_get_per_page'])) > 0)
            $iPerPage = $iPerPageGet;
        else if($this->_aOptions['paginate_per_page'])
            $iPerPage = (int)$this->_aOptions['paginate_per_page'];            

        if ($this->_aOptions['paginate_get_start']) {

            $aData = $this->_getData ($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage + 1);

            $sPageUrl = false;
            if (!empty($this->_aOptions['paginate_url'])) {

                $sPageUrl = $this->_aOptions['paginate_url'];

                $aParamsAppend = array();
                if ($sFilter) {
                    $aParamsAppend['filter'] = bx_process_input(bx_get($this->_aOptions['filter_get']));
                }
                if ($sOrderField) {
                    $aParamsAppend['order_field'] = bx_process_input(bx_get($this->_aOptions['order_get_field']));
                    $aParamsAppend['order_dir'] = bx_process_input(bx_get($this->_aOptions['order_get_dir']));
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

        if((empty($aData) || !is_array($aData)) && isset($this->_aBrowseParams['empty_message']) && !(bool)$this->_aBrowseParams['empty_message'])
            return '';
        
        $sPopupOptions = '{}';
        if (!empty($this->_aPopupOptions) && is_array($this->_aPopupOptions))
            $sPopupOptions = json_encode($this->_aPopupOptions);

        $aQueryAppend = array_merge(is_array($this->_aQueryAppend) ? $this->_aQueryAppend : array(), is_array($this->_aMarkers) ? $this->_aMarkers : array());
        if(!empty($this->_aQueryAppendExclude) && is_array($this->_aQueryAppendExclude))
            $aQueryAppend = array_diff_key($aQueryAppend, array_flip($this->_aQueryAppendExclude));

        $sQueryAppend = '{}';
        if (!empty($aQueryAppend) && is_array($aQueryAppend))
            $sQueryAppend = json_encode($aQueryAppend);

        $sConfirmMessages = '{}';
        if (!empty($this->_aConfirmMessages) && is_array($this->_aConfirmMessages))
            $sConfirmMessages = json_encode($this->_aConfirmMessages);

        $iColumns = count($this->_aOptions['fields']);
        $aVarsHead = $this->_getRowHead();

        $sClassHeader = '';
        if($this->_aOptions['show_total_count'] == 0)
            $sClassHeader .= ' bx-gh-no-counter';

        $aVars = array (
            'object' => $this->_sObject,
            'csrf_token' => BxDolForm::getCsrfToken(),
            'id_table' => $sIdTable,
            'id_cont' => $sIdContainer,
            'id_wrap' => $sIdWrapper,
            'class_table_wrapper' => $this->_aOptions['responsive'] ? 'bx-grid-table-wrapper-responsive' : '',
            'sortable' => empty($this->_aOptions['field_order']) ? 0 : 1,
            'sorting' => empty($this->_aOptions['sorting_fields']) ? 0 : 1,
            'sorting_field' => $sOrderField,
            'sorting_dir' => $sOrderDir,
        	'bx_if:display_head' => array(
        		'condition' => !empty($aVarsHead),
        		'content' => array(
        			'bx_repeat:row_header' => $aVarsHead,
        			'columns' => $iColumns,
        		)
        	),
            'bx_repeat:rows_data' => $this->_getRowsDataDesign ($aData),
            'paginate_get_start' => $this->_aOptions['paginate_get_start'],
            'paginate_get_per_page' => $this->_aOptions['paginate_get_per_page'],
            'start' => $iStart,
            'per_page' => $iPerPage,
            'filter' => bx_js_string($sFilter, BX_ESCAPE_STR_APOS),
            'filter_get' => bx_js_string($this->_aOptions['filter_get']),
            'order_field' => bx_js_string($sOrderField, BX_ESCAPE_STR_APOS),
            'order_dir' => bx_js_string($sOrderDir, BX_ESCAPE_STR_APOS),
            'order_get_field' => bx_js_string($this->_aOptions['order_get_field']),
            'order_get_dir' => bx_js_string($this->_aOptions['order_get_dir']),
            'popup_options' => $sPopupOptions,
            'query_append' => $sQueryAppend,
            'confirm_messages' => $sConfirmMessages,
            'columns' => $iColumns,
            'bx_if:display_footer' => array(
        		'condition' => !empty($this->_aOptions['actions_bulk']) || !empty($sPaginate),
        		'content' => array(
		            'bx_if:actions_bulk' => array (
		                'condition' => !empty($this->_aOptions['actions_bulk']),
		                'content' => array(
		                    'actions_bulk' => $this->_getActions ('bulk'),
		                ),
		            ),
		            'paginate' => $sPaginate,
				)
			),
            'bx_if:display_header' => array (
                'condition' => $isDisplayHeader,
                'content' => array (
                    'class' => $sClassHeader,
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
                    'bx_if:counter' => array (
                        'condition' => $this->_aOptions['show_total_count'] == 1,
                        'content' => array(
                            'counter' => $this->_getCounter(),
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
        foreach ($this->_aQueryReset as $sKey) {
            unset($_GET[$sKey]);
            unset($_POST[$sKey]);
        }
    }

    protected function _getRowHead ()
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
    	$aAttr = array(
    		'type' => 'checkbox',
    		'id' => $this->_sObject . '_check_all',
    		'name' => $this->_sObject . '_check_all',
    		'onclick' => "$('input[name=" . $this->_sObject . "_check]:not([disabled])').attr('checked', this.checked)"
    	);
    	if($this->_bSelectAll)
    		$aAttr['checked'] = 'checked';

    	$aField['attr'] = isset($aField['attr']) && is_array($aField['attr']) ? array_merge($aAttr, $aField['attr']) : $aAttr;

        $sAttrHead = $this->_convertAttrs(
            $aField, 'attr_head',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top bx-grid-header-' . $sKey, // add default classes
            isset($aField['width']) ? 'width:' . $aField['width'] : false // add default styles
        );
        return $this->_getCellHeaderWrapper ($sKey, $aField, ' <input ' . $this->_convertAttrs($aField, 'attr') . ' /> ', $sAttrHead);
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
        return $this->_bSelectAll;
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

        if(empty($aData))
            $aGrid[] = array(
                'id_row' => 0,
                'row_class' => 'bx-grid-table-row-empty',
                'row' => '<td class="bx-def-padding-sec-topbottom" colspan="' . count($this->_aOptions['fields']) . '">' . MsgBox(_t('_Empty')) . '</td>'
            );
        else
            foreach ($aData as $aRow)
                $aGrid[] = array(
                    'id_row' => $this->_getRowId($aRow),
                    'row_class' => $this->_isRowDisabled($aRow) ? 'bx-grid-table-row-disabled bx-def-font-grayed' : '',
                    'row' => $this->_getRowDesign($aRow)
                );

        return $aGrid;
    }

    protected function _getRowId($mixedRow)
    {
        if(is_string($mixedRow))
            $sId = rand(1, getrandmax());
        else
            $sId = $mixedRow[$this->_aOptions['field_id']];

        return $this->_sObject . '_row_' . $sId;
    }

    protected function _getRowDesign($mixedRow)
    {
        $sRow = '';
        if(is_string($mixedRow))
            $sRow = '<td class="bx-def-padding-sec-topbottom bx-def-font-bold" colspan="' . count($this->_aOptions['fields']) . '">' . $mixedRow . '</td>';
        else
            foreach($this->_aOptions['fields'] as $sKey => $aField)
                $sRow .= $this->_getCellDesign($sKey, $aField, $mixedRow);

        return $sRow;
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

        return '<td ' . $sAttr . '><div class="bx-grid-cell-single-actions-wrapper bx-def-margin-thd-neg">' . $sActions . '</div></td>';
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
            if(!$a['active'] || (!$a['title'] && !$a['icon']))
                continue;

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
            'bx-btn bx-def-margin-thd' . ($isSmall ? ' bx-btn-small' : '') . ($isDisabled ? ' bx-btn-disabled' : '') // add default classes
        );

        $sIcon = '';
        $sImage = '';
        if (!empty($a['icon'])) {
            if (false === strpos($a['icon'], '.'))
                $sIcon = '<i class="sys-icon ' . $a['icon'] . '"></i>';
            elseif ($sIconUrl = $this->_oTemplate->getIconUrl($a['icon']))
                $sImage = '<img style="background-image:url(' . $sIconUrl . ');" src="' . $this->_oTemplate->getIconUrl('spacer.gif') .'" />';
        }
        return '<button ' . $sAttr . '>' . $sIcon . $sImage . ($a['icon_only'] || empty($a['title']) ? '' : '<u>' . $a['title'] . '</u>') . '</button>';
    }

    protected function _getActionDivider ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return '<div class="bx-grid-actions-divider bx-def-margin-sec-left"> | </div>';
    }

    protected function _getFilterControls ()
    {
        $oForm = new BxTemplFormView(array());

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

    protected function _getCounter()
    {
        return _t('_sys_grid_total_count', $this->_iTotalCount);
    }

    protected function _limitMaxLength ($mixedValue, $sKey, $aField, $aRow, $isDisplayPopupOnTextOverflow, $bReturnString = true)
    {
        if ($aField['chars_limit'] > 0)
            $mixedValue = BxTemplFunctions::getInstance()->getStringWithLimitedLength($mixedValue, $aField['chars_limit'], $isDisplayPopupOnTextOverflow, $bReturnString);
        return $mixedValue;
    }

    protected function _convertAttrs ($aField, $sAttrName, $sClasses = false, $sStyles = false)
    {
        if (!empty($aField['hidden_on'])) {
            $aHiddenOn = array(
                pow(2, BX_DB_HIDDEN_PHONE - 1) => 'bx-def-media-phone-hide',
                pow(2, BX_DB_HIDDEN_TABLET - 1) => 'bx-def-media-tablet-hide',
                pow(2, BX_DB_HIDDEN_DESKTOP - 1) => 'bx-def-media-desktop-hide',
                pow(2, BX_DB_HIDDEN_MOBILE - 1) => 'bx-def-mobile-app-hide'
            );
            foreach ($aHiddenOn as $iHiddenOn => $sClass)
                if ((int)$aField['hidden_on'] & $iHiddenOn)
                    $sClasses .= ' ' . $sClass;
        }
            
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
                'jquery-ui/jquery-ui.custom.min.js',
                'URI.min.js',
            ));
        }

        $this->_oTemplate->addJs('BxDolGrid.js');
        $this->_oTemplate->addCss('grid.css');

        $this->_oTemplate->addJsTranslation('_sys_grid_confirmation');
    }
}

/** @} */
