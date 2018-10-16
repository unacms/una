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
    protected static $_sTmplContentItem;

    protected $_sTmplNameCustomItemMore;
    protected $_sTmplNameCustomItemMorePopup;

    protected $_bMoreAuto;
    protected $_sJsObjectMoreAuto;

    protected $_aHtmlIds;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if(empty(self::$_sTmplContentItem))
            self::$_sTmplContentItem = $this->_oTemplate->getHtml('menu_custom_item.html');

        $this->_sTmplNameCustomItemMore = 'menu_custom_item_more.html';
        $this->_sTmplNameCustomItemMorePopup = 'menu_custom_item_more_popup.html';

        $this->_bMoreAuto = false;
        $this->_sJsObjectMoreAuto = 'oMenuMoreAuto' . bx_gen_method_name($this->_sObject);

        $sPrefix = str_replace('_', '-', $this->_sObject);
        $this->_aHtmlIds = array(
            'more_auto_popup' => $sPrefix . '-ma-popup',
        );
    }

    public function getCode ()
    {
        $sResult = parent::getCode();

        if($this->_bMoreAuto)
            $this->_oTemplate->addJs(array('BxDolMenuMoreAuto.js'));

        return $sResult;
    }

    public function getMenuItems()
    {
        $aResult = parent::getMenuItems();

        $sItem = BX_DEF_MENU_CUSTOM_ITEM_MORE_AUTO;
        if(!empty($this->_aObject['menu_items'][$sItem]) && is_array($this->_aObject['menu_items'][$sItem]) && (int)$this->_aObject['menu_items'][$sItem]['active'] == 1)
            $this->_bMoreAuto = true;

        return $aResult;
    }

    protected function _getTemplateVars()
    {
        $aResult = array_merge(parent::_getTemplateVars(), array(
            'bx_if:show_more_auto_class' => array(
                'condition' => $this->_bMoreAuto,
                'content' => array()
            ),
            'js_code' => $this->_bMoreAuto ? $this->_getJsCodeMoreAuto() : ''
        ));

        return $aResult;
    }

    protected function _getJsCodeMoreAuto()
    {
        $aParams = array(
            'sObject' => $this->_sObject,
            'aHtmlIds' => $this->_aHtmlIds
        );
        return $this->_oTemplate->_wrapInTagJsCode("if(!" . $this->_sJsObjectMoreAuto . ") var " . $this->_sJsObjectMoreAuto . " = new BxDolMenuMoreAuto(" . json_encode($aParams) . "); " . $this->_sJsObjectMoreAuto . ".init();");
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

            $mixedItem = $this->_getMenuItemDefault($aItem);
    	}
    	else
            $mixedItem = $this->$sMethod($aItem);

    	if(empty($mixedItem))
            return false;

        $sItem = $sClass = '';
        if(is_array($mixedItem)) 
            list($sItem, $sClass) = $mixedItem;
        else
            $sItem = $mixedItem;

        if(!empty($sClass))
            $sClass = ' ' . $sClass;
        if($this->_isSelected($aItem))
            $sClass .= ' bx-menu-tab-active';

        return array(
            'name' => $aItem['name'],
            'class' => $sClass,
            'item' => $sItem
        );
    }

    protected function _getMenuItemMoreAuto ($aItem)
    {
        $aItem['onclick'] = $this->_sJsObjectMoreAuto . '.more(this);';

        $aItem = parent::_getMenuItem($aItem);
        if($aItem === false)
            return false;

        return $this->_oTemplate->parseHtmlByName($this->_getTmplNameItemMore(), array(
            'item' => $this->_getMenuItemDefault($aItem),
            'popup' => BxTemplFunctions::getInstance()->transBox($this->_aHtmlIds['more_auto_popup'], $this->_oTemplate->parseHtmlByName($this->_getTmplNameItemMorePopup(), array(
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

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentItem(), $aItem);
    }

    protected function _getTmplNameItemMore()
    {
        return $this->_sTmplNameCustomItemMore;
    }

    protected function _getTmplNameItemMorePopup()
    {
        return $this->_sTmplNameCustomItemMorePopup;
    }

    protected function _getTmplContentItem()
    {
        return self::$_sTmplContentItem;
    }
}

/** @} */
