<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Site main menu representation.
 */
class BxBaseMenuFooter extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode ()
    {
        return parent::getCode() . getVersionComment();
    }
    public function getMenuItems ()
    {
        $aItems = parent::getMenuItems();
        foreach($aItems as $iKey => $aItem) {
            switch($aItem['name']) {
                case 'switch_language':
                    $sTitle = _t('_sys_menu_item_title_switch_language_mask', $aItems[$iKey]['title'], genFlag());

                    $aItems[$iKey]['title'] = $sTitle;
                    $aItems[$iKey]['bx_if:title']['condition'] = (bool)$sTitle;
                    $aItems[$iKey]['bx_if:title']['content']['title'] = $sTitle;
                    break;

                case 'switch_template':
                    $aTemplates = get_templates_array(true, true);

                    $sTemplateName = $this->_oTemplate->getCode();                    
                    $sTemplateTitle = isset($aTemplates[$sTemplateName]) ? $aTemplates[$sTemplateName] : '';

                    $sTitle = _t('_sys_menu_item_title_switch_template_mask', $aItems[$iKey]['title'], $sTemplateTitle);

                    $aItems[$iKey]['title'] = $sTitle;
                    $aItems[$iKey]['bx_if:title']['condition'] = (bool)$sTitle;
                    $aItems[$iKey]['bx_if:title']['content']['title'] = $sTitle;
                    break;

                case 'copyright':
                    $aItems[$iKey]['title_attr'] = bx_html_attribute(_t('_copyright', date('Y')));
                    break;

                case 'powered_by':
                    $sTitleAttr = bx_html_attribute(_t('_sys_txt_powered_by'));

                    $aItems[$iKey]['title_attr'] = $sTitleAttr;
                    $aItems[$iKey]['bx_if:image']['content']['title_attr'] = $sTitleAttr;
                    break;
            }

            $bTitleAttr = isset($aItems[$iKey]['title_attr']);
            $bTitleAttrImage = isset($aItems[$iKey]['bx_if:image']['content']['title_attr']);
            if(!$bTitleAttr || !$bTitleAttrImage) {
                $sTitleAttr = bx_html_attribute(strip_tags($aItems[$iKey]['title']));

                if(!$bTitleAttr)
                    $aItems[$iKey]['title_attr'] = $sTitleAttr;

                if(!$bTitleAttrImage)
                    $aItems[$iKey]['bx_if:image']['content']['title_attr'] = $sTitleAttr;
            }
        }

        return $aItems;
    }

    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        $bResult = true;
        switch ($a['name']) {
            case 'switch_language':
                $aLanguages = BxDolLanguagesQuery::getInstance()->getLanguages(false, true);
                if(count($aLanguages) <= 1)
                    $bResult = false;
                break;

            case 'switch_template':
                $iTemplates = 0;
                $aTemplates = get_templates_array(true, false);
                foreach($aTemplates as $aTemplate) {
                    $aMixes = $this->_oQuery->getParamsMixes($aTemplate['name'], 1);
                    if(!empty($aMixes) && is_array($aMixes))
                        $iTemplates += count($aMixes);
                    else 
                        $iTemplates += 1;
                }
                if($iTemplates <= 1)
                    $bResult = false;
                break;
        }

        return $bResult;
    }
}

/** @} */
