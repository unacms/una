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

    public function getMenuItems ()
    {
        $aItems = parent::getMenuItems();
        foreach($aItems as $iKey => $aItem) {
            switch($aItem['name']) {
                case 'switch_language':
                    $aItems[$iKey]['title'] = _t('_sys_menu_item_title_switch_language_mask', $aItems[$iKey]['title'], genFlag());
                    break;

                case 'switch_template':
                	$aTemplates = get_templates_array(true, true);

                	$sTemplateName = $this->_oTemplate->getCode();                    
                    $sTemplateTitle = isset($aTemplates[$sTemplateName]) ? $aTemplates[$sTemplateName] : '';

                    $aItems[$iKey]['title'] = _t('_sys_menu_item_title_switch_template_mask', $aItems[$iKey]['title'], $sTemplateTitle);
                    break;
                case 'powered_by':
                    $aItems[$iKey]['alt'] = _t('_sys_txt_powered_by');
                    $aItems[$iKey]['bx_if:image']['content']['alt'] = _t('_sys_txt_powered_by');
                    break;
            }
            if (!isset($aItems[$iKey]['alt'])) {
                $aItems[$iKey]['alt'] = $aItems[$iKey]['title'];
                $aItems[$iKey]['bx_if:image']['content']['alt'] = $aItems[$iKey]['title'];
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
                $aTemplates = get_templates_array(true, true);
                if(count($aTemplates) <= 1)
                    $bResult = false;
                break;
        }

        return $bResult;
    }
}

/** @} */
