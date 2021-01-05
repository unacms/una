<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 *
 * @{
 */

class BxBaseMenuCustom extends BxTemplMenuMoreAuto
{
    protected static $_sTmplContentDefault;
    protected static $_sTmplContentItemDefault;

    protected $_sTmplContent;
    protected $_sTmplContentItem;
    
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if(empty(self::$_sTmplContentDefault))
            self::$_sTmplContentDefault = $this->_oTemplate->getHtml($aObject['template']);
        $this->_sTmplContent = self::$_sTmplContentDefault;

        if(empty(self::$_sTmplContentItemDefault))
            self::$_sTmplContentItemDefault = $this->_oTemplate->getHtml('menu_custom_item.html');
        $this->_sTmplContentItem = self::$_sTmplContentItemDefault;

        $this->_sTmplNameItemMore = 'menu_custom_item_more.html';
    }

    public function setTemplateById ($iTemplateId)
    {
        $sTemplate = $this->_aObject['template'];

        switch($iTemplateId) {
            case BX_DB_MENU_TEMPLATE_TABS:
                $iTemplateId = BX_MENU_TEMPLATE_CUSTOM_HOR;
                break;

            case BX_DB_MENU_TEMPLATE_POPUP:
                $iTemplateId = BX_MENU_TEMPLATE_CUSTOM_VER;
                break;
        }

        parent::setTemplateById ($iTemplateId);

        if($sTemplate != $this->_aObject['template'])
            $this->_sTmplContent = $this->_oTemplate->getHtml($this->_aObject['template']);
    }

    protected function _getCode($sTmplName, $aTmplVars)
    {
        if($sTmplName != $this->_aObject['template'])
            return parent::_getCode($sTmplName, $aTmplVars);

        return $this->_oTemplate->parseHtmlByContent($this->_sTmplContent, $aTmplVars);
    }

    protected function _getMenuItem ($aItem)
    {
    	if (isset($aItem['active']) && !$aItem['active'])
            return false;

        if (isset($aItem['visible_for_levels']) && !$this->_isVisible($aItem))
            return false;

    	$sMethod = '_getMenuItem' . str_replace(' ', '', ucwords(str_replace('-', ' ', $aItem['name'])));
        $bMethod = method_exists($this, $sMethod);

        $mixedItem = false;
        if($bMethod)
            $mixedItem = $this->$sMethod($aItem);

    	if($mixedItem === true || !$bMethod) {
            $aItem = parent::_getMenuItem($aItem);
            if($aItem === false)
                return false;

            $mixedItem = $this->_getMenuItemDefault($aItem);
    	}

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

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentItemMore(), array(
            'item' => $this->_getMenuItemDefault($aItem),
            'popup' => BxTemplFunctions::getInstance()->transBox($this->_aHtmlIds['more_auto_popup'], $this->_oTemplate->parseHtmlByContent($this->_getTmplContentItemMorePopup(), array(
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

    protected function _getTmplContentItem()
    {
        return $this->_sTmplContentItem;
    }
}

/** @} */
