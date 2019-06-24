<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 *
 * @{
 */

define('BX_DEF_MENU_ITEM_MORE_AUTO', 'more-auto');

class BxBaseMenuMoreAuto extends BxTemplMenu
{
    protected $_sTmplNameItemMore;
    protected $_sTmplNameItemMorePopup;

    protected $_bMoreAuto;
    protected $_iMoreAutoItemsStatic;
    protected $_sJsObjectMoreAuto;

    protected $_aHtmlIds;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sTmplNameItemMore = 'menu_item_more.html';
        $this->_sTmplNameItemMorePopup = 'menu_item_more_popup.html';

        $this->_bMoreAuto = null;
        $this->_iMoreAutoItemsStatic = 1;
        $this->_sJsObjectMoreAuto = 'oMenuMoreAuto' . bx_gen_method_name($this->_sObject);

        $sPrefix = str_replace('_', '-', $this->_sObject);
        $this->_aHtmlIds = array(
            'more_auto_popup' => $sPrefix . '-ma-popup',
        );
    }

    public function getCode()
    {
        $sResult = parent::getCode();

        if($this->_isMoreAuto())
            $this->_oTemplate->addJs(array('BxDolMenuMoreAuto.js'));

        return $sResult;
    }

    protected function _isMoreAuto()
    {
        if(is_bool($this->_bMoreAuto))
            return $this->_bMoreAuto;

        $aResult = $this->getMenuItems();

        $sItem = BX_DEF_MENU_ITEM_MORE_AUTO;
        if(!empty($this->_aObject['menu_items'][$sItem]) && is_array($this->_aObject['menu_items'][$sItem]) && (int)$this->_aObject['menu_items'][$sItem]['active'] == 1)
            $this->_bMoreAuto = true;
        else
            $this->_bMoreAuto = false;

        return $this->_bMoreAuto;
    }

    protected function _getTemplateVars()
    {
        $bMoreAuto = $this->_isMoreAuto();

        $aResult = array_merge(parent::_getTemplateVars(), array(
            'bx_if:show_more_auto_class' => array(
                'condition' => $bMoreAuto,
                'content' => array()
            ),
            'js_code' => $bMoreAuto ? $this->_getJsCodeMoreAuto() : ''
        ));

        return $aResult;
    }

    protected function _getJsCodeMoreAuto()
    {
        $aParams = array(
            'sObject' => $this->_sObject,
            'iItemsStatic' => $this->_iMoreAutoItemsStatic,
            'aHtmlIds' => $this->_aHtmlIds
        );
        return $this->_oTemplate->_wrapInTagJsCode("if(!" . $this->_sJsObjectMoreAuto . ") var " . $this->_sJsObjectMoreAuto . " = new BxDolMenuMoreAuto(" . json_encode($aParams) . "); " . $this->_sJsObjectMoreAuto . ".init();");
    }
    
    protected function _getMenuItem ($aItem)
    {
        $aItem['popup'] = '';

        if($aItem['name'] != BX_DEF_MENU_ITEM_MORE_AUTO)
            return parent::_getMenuItem($aItem);

        $aItem['onclick'] = $this->_sJsObjectMoreAuto . '.more(this);';
        $aItem['popup'] = BxTemplFunctions::getInstance()->transBox($this->_aHtmlIds['more_auto_popup'], $this->_oTemplate->parseHtmlByName($this->_getTmplNameItemMorePopup(), array(
            'content' => ''
        )), true);

        return parent::_getMenuItem($aItem);
    }

    protected function _getTmplNameItemMore()
    {
        return $this->_sTmplNameItemMore;
    }

    protected function _getTmplNameItemMorePopup()
    {
        return $this->_sTmplNameItemMorePopup;
    }
}

/** @} */
