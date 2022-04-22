<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuInteractive extends BxTemplMenu
{
    protected $_oTemplate;
    protected $_bShowDivider;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if(empty($this->_aObject['menu_id']))
            $this->_aObject['menu_id'] = $this->_sObject;

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_bShowDivider = true;
    }

    /**
     * Get menu code.
     * @return string
     */
    public function getCode ()
    {
        $sCode = parent::getCode();
        if($sCode !== false)
            $sCode = $this->_oTemplate->parseHtmlByContent($sCode, array(
                'id' => $this->_aObject['menu_id']
            ));

        return $sCode;
    }

    /**
     * Get menu items array, which are ready to pass to template.
     * @return array
     */
    public function getMenuItems ()
    {
        $aRet = array();
        if (!isset($this->_aObject['menu_items']))
            $this->_aObject['menu_items'] = $this->getMenuItemsRaw();

        foreach ($this->_aObject['menu_items'] as $a) {
            if (isset($a['active']) && !$a['active'])
                continue;

            if (isset($a['visible_for_levels']) && !$this->_isVisible($a))
                continue;

            $a = $this->_replaceMarkers($a);

            list ($sIcon, $sIconUrl, $sIconA, $sIconHtml) = $this->_getMenuIcon($a);

            $a['class'] = 'bx-menu-item-inter';
            $a['class_wrp_act'] = 'bx-menu-inter-hidden';
            $a['class_wrp_pas'] = '';
            if($this->_isSelected($a)) {
                $a['class_wrp_act'] = '';
                $a['class_wrp_pas'] = 'bx-menu-inter-hidden';
            }

            $a['link'] = isset($a['link']) ? $this->_oPermalinks->permalink($a['link']) : 'javascript:void(0);';
            $a['title'] = _t($a['title']);
            $a['title_attr'] = isset($a['title_attr']) ? bx_html_attribute($a['title_attr']) : '';

            $a['attrs'] = $this->_getMenuAttrs($a);

            $a['bx_if:show_divider'] = array (
            	'condition' => $this->_bShowDivider,
                'content' => array(),
            );
            $a['bx_if:image'] = array (
                'condition' => (bool)$sIconUrl,
                'content' => array('icon_url' => $sIconUrl),
            );
            $a['bx_if:icon'] = array (
                'condition' => (bool)$sIcon,
                'content' => array('icon' => $sIcon),
            );
            $a['bx_if:icon-a'] = array (
                'condition' => (bool)$sIconA,
                'content' => array('icon-a' => $sIconA),
            );
            $a['bx_if:icon-html'] = array (
                'condition' => (bool)$sIconHtml,
                'content' => array('icon' => $sIconHtml),
            );

            $aRet[] = $a;
        }

        return $aRet;
    }

    /**
     * Add css/js files which are needed for menu display and functionality.
     */
    protected function _addJsCss()
    {
        $this->_oTemplate->addCss('menu.css');
    }

}

/** @} */
