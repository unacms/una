<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Help Tours Help Tours
 * @ingroup     UnaModules
 *
 * @{
 */

class BxHelpToursGridItems extends BxTemplGrid
{
    protected $_iTourId;
    protected $_aTourPageBlocks;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_help_tours';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);
    	if(!$oTemplate)
			$oTemplate = $this->_oModule->_oTemplate;

    	parent::__construct ($aOptions, $oTemplate);

    	$this->_iTourId = bx_get('tour');
        if ($this->_iTourId) {
            $this->_aQueryAppend['tour'] = $this->_iTourId;
            $this->_aTourPageBlocks = $this->_oModule->_oDb->getPageBlocksForHelpTour($this->_iTourId);
        }
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(!empty($this->_iTourId)) {
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tour` = ? ", $this->_iTourId);
            return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
        }
        return false;
    }

    protected function _getCellElement($mixedValue, $sKey, $aField, $aRow)
    {
        if (isset($this->_aTourPageBlocks[$aRow['element']])) $mixedValue = htmlspecialchars($this->_aTourPageBlocks[$aRow['element']]);
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    private function getAddEditForm($iEntry) {
        $aEntry = false;

        if ($iEntry) {
            $aEntry = $this->_oModule->_oDb->getTourItemDetails($iEntry);
            if (!$aEntry) {
                echoJson([]);
                exit;
            }
        }

        $oForm = $this->getAddEditFormObject($iEntry);

        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT.'grid.php?o=' . $this->_sObject . '&a='.($aEntry ? 'edit' : 'add');

        if ($aEntry) {
            $aEntry['element_helper'] = $aEntry['element'];
            if ($aEntry['element'] && !isset($this->_aTourPageBlocks[$aEntry['element']])) $aEntry['element_helper'] = 'custom';
        }
        $oForm->initChecker($aEntry);
        if (isset($oForm->aInputs['element_helper']['value']) && $oForm->aInputs['element_helper']['value'] == 'custom')
            unset($oForm->aInputs['element']['tr_attrs']);

        if ($oForm->isSubmittedAndValid()) {
            if ($aEntry) {
                $oForm->update($aEntry['id']);
                $iResult = $aEntry['id'];
            } else {
                $iResult = $oForm->insert();
            }

            if ($iResult) {
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iResult];
            } else {
                $aRes = ['msg' => "Error occured"]; // if record adding failed, display error message
            }
            echoJson($aRes);
        } else {
            $s = '
                <div class="bx-def-color-bg-block">' . $oForm->getCode(true) . '</div>                
                <script>                    
                    $(document).ready(function () {
                        $("#'.$oForm->aFormAttrs['id'].'").ajaxForm({
                            dataType: "json",
                            beforeSubmit: function (formData, jqForm, options) {
                                bx_loading($("#' . $oForm->aFormAttrs['id'] . '"), true);
                            },
                            success: function (data) {
                                $(".bx-popup-active:visible").dolPopupHide();
                                glGrids.' . $this->_sObject . '.processJson(data, "'.($aEntry ? 'edit' : 'add').'");
                            }
                        });
                    });
                </script>';

            echoJson(['popup' => [
                'html' => BxTemplStudioFunctions::getInstance()->popupBox('bx-tour-item-details-popup', _t('_bx_help_tours_cpt_tour_item_details'), $s),
                'options' => [
                    'onBeforeShow' => empty($aEntry) ? "$('#grid-popup-bx_help_tours_items-edit').remove();" : "$('#grid-popup-bx_help_tours_items-add').remove();",
                    'closeOnOuterClick' => false,
                ]]]
            );
        }
    }

    public function performActionAdd()
    {
        if(!$this->canAdd()) {
            echoJson([]);
            exit;
        }

        return $this->getAddEditForm(0);
    }

    public function performActionEdit()
    {
        $iEntry = !is_null($_REQUEST) && isset($_REQUEST['id']) ? intval($_REQUEST['id']) : intval($_REQUEST['ids'][0]);
        if(!$iEntry) {
            echoJson([]);
            exit;
        }

        return $this->getAddEditForm($iEntry);
    }

    private function getAddEditFormObject($iEntry) {
        $aArrowOptionsSrc = ['auto', 'auto-start', 'auto-end', 'top', 'top-start', 'top-end', 'bottom', 'bottom-start', 'bottom-end', 'right', 'right-start', 'right-end', 'left', 'left-start', 'left-end'];
        $aArrowOptions = ['' => _t('_bx_help_tours_arrow_option_none')];
        foreach ($aArrowOptionsSrc as $sOption) {
            $aArrowOptions[$sOption] = $sOption;
        }

        $aForm = [
            'form_attrs' => [
                'method' => 'post',
                'action' => '',
                'id' => 'bx-help-tour-item-details-form',
            ],
            'params' => [
                'db' => [
                    'submit_name' => 'submit',
                    'table' => 'bx_help_tours_items',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                ],
            ],
            'inputs' => [
                'id' => [
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => $iEntry,
                ],
                'tour' => [
                    'type' => 'hidden',
                    'name' => 'tour',
                    'value' => $this->_iTourId,
                    'db' => [
                        'pass' => 'Int',
                    ],
                ],
                'name' => [
                    'type' => 'text',
                    'name' => 'name',
                    'required' => true,
                    'caption' => _t('_bx_help_tours_form_field_name'),
                    'info' => _t('_bx_help_tours_form_field_name_info'),
                    'checker' => [
                        'func' => 'Avail',
                        'error' => _t('_bx_help_tours_form_field_name_error'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'element_helper' => [
                    'type' => 'select',
                    'name' => 'element_helper',
                    'caption' => _t('_bx_help_tours_form_field_element'),
                    'info' => _t('_bx_help_tours_form_field_element_info'),
                    'values' =>
                        ['' => _t('_bx_help_tours_form_field_option_none')] +
                        $this->_aTourPageBlocks +
                        ['custom' => _t('_bx_help_tours_form_field_option_custom')],
                    'attrs' => [
                        'onchange' => "
                            $('#bx-help-tours-input-element').val(this.value == 'custom' ? '' : this.value);
                            if (this.value == 'custom') $('#bx-form-element-element').fadeIn(); else $('#bx-form-element-element').fadeOut();                            
                         ",
                    ],
                ],
                'element' => [
                    'type' => 'text',
                    'name' => 'element',
                    'caption' => _t('_bx_help_tours_form_field_element_custom'),
                    'info' => _t('_bx_help_tours_form_field_element_custom_info'),
                    'attrs' => [
                        'id' => 'bx-help-tours-input-element',
                    ],
                    'tr_attrs' => [
                        'style' => 'display:none;',
                    ],
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ],
                'title' => [
                    'type' => 'text_translatable',
                    'name' => 'title',
                    'caption' => _t('_bx_help_tours_form_field_title'),
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'text' => [
                    'type' => 'textarea_translatable',
                    'name' => 'text',
                    'required' => true,
                    'caption' => _t('_bx_help_tours_form_field_text'),
                    'checker' => [
                        'func' => 'AvailTranslatable',
                        'params' => ['text'],
                        'error' => _t('_bx_help_tours_form_field_text_error'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                    'code' => true, //to avoid loosing HTML formatting
                ],
                'arrow' => [
                    'type' => 'select',
                    'name' => 'arrow',
                    'caption' => _t('_bx_help_tours_form_field_arrow'),
                    'values' => $aArrowOptions,
                    'value' => 'auto',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ],
                'actions' => [
                    'type' => 'input_set',
                    0 => [
                        'type' => 'submit',
                        'name' => 'submit',
                        'value' => _t('_Submit'),
                    ],
                    1 => [
                        'type' => 'button',
                        'name' => 'cancel',
                        'value' => _t('_Cancel'),
                        'attrs' => [
                            'style' => 'margin-left: 20px;',
                            'onclick' => '$(".bx-popup-active:visible").dolPopupHide();',
                        ],
                    ],
                ],
            ],
        ];
        return new BxTemplStudioFormView($aForm);
    }

    public function performActionDelete() {
        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        $oLanguage = BxDolStudioLanguagesUtils::getInstance();

        foreach ($aIds as $iEntry) {
            $aItem = $this->_oModule->_oDb->getTourItemDetails($iEntry);
            $oLanguage->deleteLanguageString($aItem['title']);
            $oLanguage->deleteLanguageString($aItem['text']);
            $this->_delete($iEntry);
        }

        echoJson(array(
            'grid' => $this->getCode(false),
        ));
    }

    public function _getActionPreview($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array()) {
        $sUrl = $this->_oModule->_oDb->getTourPageUrl($this->_iTourId);
        if (!$sUrl) return '';
        $sUrl = trim($sUrl, '/');

        $a['attr']['href'] = bx_absolute_url(bx_append_url_params(BxDolPermalinks::getInstance()->permalink($sUrl), ['help_tour_preview' => $this->_iTourId, 'help_tour_item' => $aRow['id']]));
        $a['attr']['target'] = '_blank';

        $sButton = $this->_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
        $sA = str_replace('button', 'a', $sButton);
        return $sA;
    }

    protected function canAdd()
    {
        return !empty($this->_iTourId);
    }

    protected function _getActionAdd($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->canAdd())
            $isDisabled = true;

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getFilterControls ()
    {
        $aTourOptions = $this->_oModule->_oDb->getHelpToursOptions();
        if ($aTourOptions) foreach ($aTourOptions as $iTour => $sPage) {
            $aPageTitle = $this->_oModule->_oDb->getPageTitleDetails($sPage);
            if ($aPageTitle)
                $aTourOptions[$iTour] = $aPageTitle['module_title'].': '._t($aPageTitle['title_system']);
        }

        $aInputTour = [
            'type' => 'select',
            'name' => 'tour',
            'value' => $this->_iTourId,
            'values' => ['' => _t('_sys_please_select')] + $aTourOptions,
            'attrs' => array(
                'id' => 'bx-grid-tour',
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeTour(this.value);',
            ),
        ];

        $oForm = new BxTemplStudioFormView(array());
        return $oForm->genRow($aInputTour).parent::_getFilterControls();
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addStudioCss('admin.css');
        $this->_oModule->_oTemplate->addStudioJs('grid_items.js');

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }

    function getJsObject()
    {
        return 'oBxHelpToursGridItems';
    }

    function getCode($isDisplayHeader = true)
    {
        return $this->_oModule->_oTemplate->parseHtmlByName('grid_items.html', [
            'module_url' => BX_DOL_URL_ROOT.$this->_oModule->_oConfig->getBaseUri(),
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'grid_object' => $this->_sObject,
            'tour' => $this->_iTourId,
            'page_url' => BX_DOL_URL_STUDIO.'module.php?name='.$this->MODULE.'&page=items',
        ]);
    }
}

/** @} */
