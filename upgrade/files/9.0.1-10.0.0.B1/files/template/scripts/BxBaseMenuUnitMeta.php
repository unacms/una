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
class BxBaseMenuUnitMeta extends BxTemplMenuCustom
{
    protected $_sStylePrefix;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_sStylePrefix = 'sys-meta';
    }

    public function getUnitMetaItemLink($sContent, $aAttrs = array())
    {
        return $this->getUnitMetaItem('a', $sContent, $aAttrs);
    }

    public function getUnitMetaItemText($sContent, $aAttrs = array())
    {
        return $this->getUnitMetaItem('span', $sContent, $aAttrs);
    }

    public function getUnitMetaItemButton($sContent, $aAttrs = array())
    {
        return $this->getUnitMetaItem('button', $sContent, $aAttrs);
    }

    public function getUnitMetaItemButtonSmall($sContent, $aAttrs = array())
    {
        return $this->getUnitMetaItem('sbutton', $sContent, $aAttrs);
    }

    public function getUnitMetaItemNl($sContent = '')
    {
        return $this->getUnitMetaItem('nl', $sContent);
    }

    public function getUnitMetaItemCustom($sContent)
    {
        return $this->getUnitMetaItem('custom', $sContent);
    }

    public function getUnitMetaItem($sName, $sContent, $aAttrs = array(), $sTemplate = 'unit_meta_item.html')
    {
        if(empty($sContent) && $sName != 'nl')
            return '';

        if(!is_array($aAttrs))
            $aAttrs = array();

        $aTags = array('span', 'a', 'button', 'sbutton', 'custom', 'nl');

        $sTmplVarsClass = ''; 
        if(!empty($aAttrs['class'])) {
            $sTmplVarsClass = $aAttrs['class'];
            unset($aAttrs['class']);
        }

        $aTmplVarsAttrs = array();
        foreach($aAttrs as $sKey => $sValue)
            $aTmplVarsAttrs[] = array('key' => $sKey, 'value' => bx_html_attribute($sValue));

        $aTmplVars = array();
        foreach($aTags as $sTag) {
            $aTmplVarsTag = array();
            $bTmplVarsTag = $sTag == $sName;
            if($bTmplVarsTag)
                $aTmplVarsTag = array(
                    'style_prefix' => $this->_sStylePrefix,
                    'class' => $sTmplVarsClass,
                    'content' => $sContent,
                    'bx_repeat:attrs' => $aTmplVarsAttrs
                );

            $aTmplVars['bx_if:' . $sTag] = array(
            	'condition' => $bTmplVarsTag,
                'content' => $aTmplVarsTag
            );
        }

        return $this->_oTemplate->parseHtmlByName($sTemplate, $aTmplVars);
    }
    
    protected function _getMenuItemNl($aItem)
    {
        return $this->getUnitMetaItemNl();
    }

    protected function _getMenuItemDefault($aItem)
    {
        $sResult = '';

        if(!empty($aItem['link']))
            $sResult = $this->getUnitMetaItemLink($aItem['title'], array(
                'href' => $aItem['link']
            ));
        else if(!empty($aItem['onclick']))
            $sResult = $this->getUnitMetaItemButtonSmall($aItem['title'], array(
            	'onclick' => $aItem['onclick']
            ));
        else 
            $sResult = $this->getUnitMetaItemText($aItem['title']);
        
        return $sResult;
    }
}

/** @} */
