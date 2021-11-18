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
class BxBaseMenuDashboardManageTools extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
        $this->_bDisplayAddons = true;
    }
    
    protected function _getMenuIcon ($a)
    {
         $aTmp = parent::_getMenuIcon ($a);
         if (trim($aTmp[0]) == '')
             $aTmp[0] = 'user-cog';
         return $aTmp;
    }
    
    protected function _getTmplVarsAddon($mixedAddon, $aMenuItem)
    {
        $aValues = array(
            'counter1_value' => 0, 'counter1_caption' => _t('_sys_menu_dashboard_manage_tools_addon_counter1_caption_default'), 
            'counter2_value' => 0, 'counter2_caption' => _t('_sys_menu_dashboard_manage_tools_addon_counter2_caption_default'),
            'counter3_value' => 0, 'counter3_caption' => ''
        );
        if (is_array($mixedAddon)){
            $aValues = array_merge($aValues, $mixedAddon);
        }
        else{
            $aValues['counter1_value'] = $mixedAddon;
        }
        
        $aTmp = array(
            'bx_if:counter1' => array(
                'condition' => $aValues['counter1_value'] > 0,
                'content' => array(
                    'value' => $aValues['counter1_value'],
                    'caption' => $aValues['counter1_caption']
                )
            ),
            'bx_if:counter2' => array(
                'condition' => $aValues['counter2_value'] > 0,
                'content' => array(
                    'value' => $aValues['counter2_value'],
                    'caption' => $aValues['counter2_caption'],
                    'link' => $aMenuItem['link'] . '?order_field=reports&order_dir=desc'
                )
            ),
            'bx_if:counter3' => array(
                'condition' => $aValues['counter3_value'] > 0,
                'content' => array(
                    'total_value' => $aValues['counter3_value'],
                    'total_caption' => $aValues['counter3_caption']
                )
            ),
        );
        return array(
            'addon' => (trim($aValues['counter1_value']) != '' || trim($aValues['counter2_value']) != ''),
            'addonf' => $this->_oTemplate->parseHtmlByName('menu_item_dashboard_manage_tools_addon.html', $aTmp)
        );
    }
}

/** @} */
