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
    protected $_bMenuSide;
    protected $_bInlineIcons;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bMenuSide = $this->_aObject['template'] == 'menu_side.html';

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
            $sImage = '';
            if(($sIconPath = $this->_oTemplate->getIconPath($aItem['icon'])))
                $sImage = file_get_contents($sIconPath);
            else 
                $sImage = bx_file_get_contents($aItem['icon']);

            if($sImage)            
                $aItem = array_merge($aItem, [
                    'bx_if:image' => [
                        'condition' => false,
                        'content' => [],
                    ],
                    'bx_if:image_inline' => [
                        'condition' => true,
                        'content' => [
                            'image' => $sImage
                        ],
                    ],
                ]);
        }

        if($this->_bMenuSide) {
            $aItem['bx_if:show_icon'] = [
                'condition' => $aItem['bx_if:icon']['condition'] || $aItem['bx_if:image']['condition'] || $aItem['bx_if:image_inline']['condition'],
                'content' => []
            ];

            $aItem['bx_if:show_icon_bg'] = [
                'condition' => (isset($aItem['icon_bg']) && $aItem['icon_bg'] === true) || strpos($aItem['icon'], '.') === false,
                'content' => []
            ];
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
