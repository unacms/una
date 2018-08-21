<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 *
 * @{
 */

define('BX_DEF_MENU_CUSTOM_ITEM_MORE_AUTO', 'more-auto');

class BxBaseMenuCustom extends BxTemplMenu
{
    protected static $_sTmplContentCustomItem;

    protected $_sTmplNameCustomItemMore;
    protected $_sTmplNameCustomItemMorePopup;

    protected $_bAutoMore;
    
    protected $_aHtmlIds;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if(empty(self::$_sTmplContentCustomItem))
            self::$_sTmplContentCustomItem = $this->_oTemplate->getHtml('menu_custom_item.html');

        $this->_sTmplNameCustomItemMore = 'menu_custom_item_more.html';
        $this->_sTmplNameCustomItemMorePopup = 'menu_custom_item_more_popup.html';

        $this->_bAutoMore = false;

        $sPrefix = str_replace('_', '-', $this->_sObject);
        $this->_aHtmlIds = array(
            'more_auto_popup' => $sPrefix . '-ma-popup',
        );
    }

    public function getCode ()
    {
        $sResult = parent::getCode();

        if($this->_bAutoMore)
            $this->_oTemplate->addJs(array('BxDolMenuMoreAuto.js'));

        return $sResult;
    }

    public function getMenuItems()
    {
        $aResult = parent::getMenuItems();

        $sItem = BX_DEF_MENU_CUSTOM_ITEM_MORE_AUTO;
        if(!empty($this->_aObject['menu_items'][$sItem]) && is_array($this->_aObject['menu_items'][$sItem]) && (int)$this->_aObject['menu_items'][$sItem]['active'] == 1)
            $this->_bAutoMore = true;

        return $aResult;
    }

    protected function _getTemplateVars()
    {
        $aResult = array_merge(parent::_getTemplateVars(), array(
            'bx_if:show_more_auto_class' => array(
                'condition' => $this->_bAutoMore,
                'content' => array()
            ),
            'js_code' => $this->_bAutoMore ? $this->_getJsCodeMoreAuto() : ''
        ));

        return $aResult;
    }

    protected function _getJsCodeMoreAuto()
    {
        $aParams = array(
            'sObject' => $this->_sObject
        );
        return $this->_oTemplate->_wrapInTagJsCode("var oMenuMoreAuto = new BxDolMenuMoreAuto(" . json_encode($aParams) . ");");
    }

    protected function _getMenuItem ($aItem)
    {
    	if (isset($aItem['active']) && !$aItem['active'])
            return false;

        if (isset($aItem['visible_for_levels']) && !$this->_isVisible($aItem))
            return false;

    	$sMethod = '_getMenuItem' . str_replace(' ', '', ucwords(str_replace('-', ' ', $aItem['name'])));

    	if(!method_exists($this, $sMethod)) {
            $aItem = parent::_getMenuItem($aItem);
            if($aItem === false)
                return false;

            $sItem = $this->_getMenuItemDefault($aItem);
    	}
    	else
            $sItem = $this->$sMethod($aItem);

    	if(empty($sItem))
            return false;

        return array(
            'name' => $aItem['name'],
            'class' => $this->_isSelected($aItem) ? ' bx-menu-tab-active' : '',
            'item' => $sItem
        );
    }

    protected function _getMenuItemMoreAuto ($aItem)
    {
        $sPopup = $this->_aHtmlIds['more_auto_popup'];

        $aItem['onclick'] = "$(this).parents('li:first').find('#" . $sPopup . "').dolPopup({pointer: {el: $(this)}, moveToDocRoot: false});";

        $aItem = parent::_getMenuItem($aItem);
        if($aItem === false)
            return false;

        return $this->_oTemplate->parseHtmlByName($this->_getTmplNameItemMore(), array(
            'item' => $this->_getMenuItemDefault($aItem),
            'popup' => BxTemplFunctions::getInstance()->transBox($sPopup, $this->_oTemplate->parseHtmlByName($this->_getTmplNameItemMorePopup(), array(
                'content' => ''
            )), true)
        ));
    }

    protected function _getMenuItemDefault ($aItem)
    {
        if(!isset($aItem['class_wrp']))
            $aItem['class_wrp'] = '';

        if(!isset($aItem['class_link']))
            $aItem['class_link'] = '';

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentCustomItem(), $aItem);
    }

    protected function _getTmplNameItemMore()
    {
        return $this->_sTmplNameCustomItemMore;
    }

    protected function _getTmplNameItemMorePopup()
    {
        return $this->_sTmplNameCustomItemMorePopup;
    }

    protected function _getTmplContentCustomItem()
    {
        return self::$_sTmplContentCustomItem;
    }
}

/** @} */
