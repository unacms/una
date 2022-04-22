<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioMenu extends BxDolStudioMenu
{
    protected $_bInlineIcons;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bInlineIcons = in_array($this->_aObject['template'], array(
            'menu_side.html', 
            'menu_top_toolbar.html', 
            'menu_launcher_browser.html',
            'page_breadcrumb.html'
        ));
    }

    public function setInlineIcons($bInlineIcons)
    {
        $this->_bInlineIcons = $bInlineIcons;
    }

    protected function _getMenuItem ($aItem)
    {
        $aItem = parent::_getMenuItem($aItem);
        if($aItem === false)
            return $aItem;

        $aItem['class'] = isset($aItem['class']) ? $aItem['class'] : '';
        if($this->_bInlineIcons && $aItem['bx_if:image']['condition'] && strpos($aItem['icon'], '.svg') !== false) {
            $aItem = array_merge($aItem, array(
                'bx_if:image' => array (
                    'condition' => false,
                    'content' => array(),
                ),
                'bx_if:image_inline' => array (
                    'condition' => true,
                    'content' => array(
                        'image' => file_get_contents($this->_oTemplate->getIconPath($aItem['icon']))
                    ),
                ),
            ));
        }

        return $aItem;
    }

    /**
     * Check if menu items is selected.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isSelected ($a)
    {
        return isset($a['selected']) && $a['selected'] === true;
    }
}

/** @} */
