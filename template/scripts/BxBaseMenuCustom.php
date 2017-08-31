<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 *
 * @{
 */

class BxBaseMenuCustom extends BxTemplMenu
{
    protected static $_sTmplContentCustomItem;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if(empty(self::$_sTmplContentCustomItem))
            self::$_sTmplContentCustomItem = $this->_oTemplate->getHtml('menu_custom_item.html');
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

			$sItem = $this->_oTemplate->parseHtmlByContent(self::$_sTmplContentCustomItem, $aItem);
    	}
    	else
    		$sItem = $this->$sMethod($aItem);

    	if(empty($sItem))
    		return false;

		return array(
			'class' => $this->_isSelected($aItem) ? 'bx-menu-tab-active' : '',
			'item' => $sItem
		);
    }
}

/** @} */
