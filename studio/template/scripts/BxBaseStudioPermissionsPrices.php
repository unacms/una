<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioPermissionsPrices');
bx_import('BxTemplStudioFormView');

class BxBaseStudioPermissionsPrices extends BxDolStudioPermissionsPrices {
    function __construct($aOptions, $oTemplate = false) {
        parent::__construct($aOptions, $oTemplate);

        $this->_aOptions['actions_single']['edit']['attr']['title'] = _t('_adm_prm_btn_price_edit');
        $this->_aOptions['actions_single']['delete']['attr']['title'] = _t('_adm_prm_btn_price_delete');
    }

    public function performActionAdd() {
        $sAction = 'add';

        $sFilter = bx_get('filter');
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($this->iLevel, $sFilter) = explode($this->sParamsDivider, $sFilter);

        if((int)$this->iLevel == 0)
            $this->iLevel = (int)bx_get('IDLevel');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-prm-price-add',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_acl_level_prices',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'IDLevel' => array(
                    'type' => 'hidden',
                    'name' => 'IDLevel',
                    'value' => $this->iLevel,
            		'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'Days' => array(
                    'type' => 'text',
                    'name' => 'Days',
                    'caption' => _t('_adm_prm_txt_price_add_days'),
                    'info' => _t('_adm_prm_dsc_price_add_days'),
                    'value' => '',
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'Price' => array(
                    'type' => 'text',
                    'name' => 'Price',
                    'caption' => _t('_adm_prm_txt_price_add_price'),
                    'info' => '',
                    'value' => '',
                	'db' => array (
                        'pass' => 'Float',
                    ),
                ),
                'controls' => array(
                    'name' => 'controls', 
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_prm_btn_price_add'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_prm_btn_price_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aPrice = array();
            $iPrice = $this->oDb->getPrices(array('type' => 'by_level_id_duration', 'level_id' => $oForm->getCleanValue('IDLevel'), 'days' => $oForm->getCleanValue('Days')), $aPrice);
            if($iPrice != 0) {
                $this->_echoResultJson(array('msg' => _t('_adm_prm_err_price_duplicate')), true);
                return;
            }

            $iId = (int)$oForm->insert(array('Order' => $this->oDb->getPriceOrderMax($this->iLevel) + 1));
            if($iId != 0) {
                $this->oDb->updateLevels($this->iLevel, array('Purchasable' => 'yes'));
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            }
            else
                $aRes = array('msg' => _t('_adm_prm_err_price_create'));

            $this->_echoResultJson($aRes, true);
        }
        else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-prm-price-add-popup', _t('_adm_prm_txt_price_add_popup'), $this->_oTemplate->parseHtmlByName('prm_add_price.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    public function performActionEdit() {
        $sAction = 'edit';

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) {
                $this->_echoResultJson(array());
                exit;
            }

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $aPrice = array();
        $iPrice = $this->oDb->getPrices(array('type' => 'by_id', 'value' => $iId), $aPrice);
        if($iPrice != 1 || empty($aPrice)) {
            $this->_echoResultJson(array());
            exit;
        }

        if((int)$this->iLevel == 0)
            $this->iLevel = (int)$aPrice['level_id'];

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-prm-price-edit',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_acl_level_prices',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
            	'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => $iId,
            		'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'IDLevel' => array(
                    'type' => 'hidden',
                    'name' => 'IDLevel',
                    'value' => $aPrice['level_id'],
            		'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'Days' => array(
                    'type' => 'text',
                    'name' => 'Days',
                    'caption' => _t('_adm_prm_txt_price_add_days'),
                    'info' => _t('_adm_prm_dsc_price_add_days'),
                    'value' => $aPrice['days'],
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'Price' => array(
                    'type' => 'text',
                    'name' => 'Price',
                    'caption' => _t('_adm_prm_txt_price_add_price'),
                    'info' => '',
                    'value' => round($aPrice['price'], 2),
                	'db' => array (
                        'pass' => 'Float',
                    ),
                ),
                'controls' => array(
                    'name' => 'controls', 
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_prm_btn_price_save'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_prm_btn_price_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $iId = $oForm->getCleanValue('id');

            $aPrice = array();
            $iPrice = $this->oDb->getPrices(array('type' => 'by_level_id_duration', 'level_id' => $oForm->getCleanValue('IDLevel'), 'days' => $oForm->getCleanValue('Days')), $aPrice);
            if($iPrice != 0 && !empty($aPrice) && $aPrice['id'] != $iId) {
                $this->_echoResultJson(array('msg' => _t('_adm_prm_err_price_duplicate')), true);
                return;
            }

            if((int)$oForm->update($iId) > 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_adm_prm_err_price_update'));

            $this->_echoResultJson($aRes, true);
        }
        else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-prm-price-edit-popup', _t('_adm_prm_txt_price_edit_popup', $aPrice['days']), $this->_oTemplate->parseHtmlByName('prm_add_price.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    function performActionDelete() {
        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $aPrice = array();
        $this->oDb->getPrices(array('type' => 'by_id', 'value' => $aIds[0]), $aPrice, false);

        parent::performActionDelete();

        $aPrices = array();
        $iPrices = $this->oDb->getPrices(array('type' => 'by_level_id', 'value' => $aPrice['level_id']), $aPrices);
        if($iPrices == 0)
            $this->oDb->updateLevels($this->iLevel, array('Purchasable' => 'no'));
    }

    function getJsObject() {
        return 'oBxDolStudioPermissionsPrices';
    }

    function getCode($isDisplayHeader = true) {
        return $this->_oTemplate->parseHtmlByName('prm_prices.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }

    protected function _addJsCss() {
        parent::_addJsCss();
        $this->_oTemplate->addJs(array('jquery.form.js', 'permissions_prices.js'));

        bx_import('BxTemplStudioFormView');
        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }
    protected function _getCellPrice ($mixedValue, $sKey, $aField, $aRow) {
        return parent::_getCellDefault (_t_format_currency($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellDays ($mixedValue, $sKey, $aField, $aRow) {
        return parent::_getCellDefault (_t('_sys_x_days', $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionAdd($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array()) {
        if($this->iLevel == 0)
            $isDisabled = true;

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getFilterControls () {
        parent::_getFilterControls();

        $sContent = "";

        bx_import('BxTemplStudioFormView');
        $oForm = new BxTemplStudioFormView(array());

        $aInputLevels = array(
            'type' => 'select',
            'name' => 'level',
            'attrs' => array(
                'id' => 'bx-grid-level-' . $this->_sObject,
            	'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeLevel()'
            ),
            'value' => 'id-' . $this->iLevel,
            'values' => array()
        );

        $aLevels = $aCounter = array();
        $this->oDb->getLevels(array('type' => 'all'), $aLevels, false);
        $this->oDb->getPrices(array('type' => 'counter_by_levels'), $aCounter, false);
        foreach($aLevels as $aLevel)
            $aInputLevels['values']['id-' . $aLevel['id']] = _t($aLevel['name']) . " (" . (isset($aCounter[$aLevel['id']]) ? $aCounter[$aLevel['id']] : "0") . ")";

        asort($aInputLevels['values']);
        $aInputLevels['values'] = array_merge(array('id-0' => _t('_adm_prm_txt_select_level')), $aInputLevels['values']);

        $sContent .= $oForm->genRow($aInputLevels);
        if($this->iLevel == 0)
            return $sContent;

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject
            )
        );
        $sContent .= $oForm->genRow($aInputSearch);

        return $sContent;
    }
}
/** @} */