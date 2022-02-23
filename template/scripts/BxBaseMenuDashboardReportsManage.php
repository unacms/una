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
class BxBaseMenuDashboardReportsManage extends BxTemplMenuCustom
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);
        
        $this->_sTmplContentItem = $this->_oTemplate->getHtml('menu_custom_item_addon.html');
        
        $this->_aObject['menu_id'] = 'sys-dashboard-reports-manage-menu';

        $this->_bShowDivider = false;
        $this->_bDisplayAddons = true;
    }

    
    protected function getMenuItemsRaw ()
    {
        $aResult = array();
        $aUrl = bx_get_base_url_inline();
        
        $aSystems = BxDolReport::getSystems();
        
        $sSelected = bx_get('object');
        
        if ($sSelected == ''){
            $sSelected = reset($aSystems)['name'];
        }
       
        foreach($aSystems as $aSystem){
            $sName = $aSystem['name'];
            $aResult[$sName] = array(
                'name' => $sName,
                'title' => _t('_' . $sName),
                'link' => bx_append_url_params($aUrl[0], array_merge($aUrl[1], array('object' => $sName))),
                'active' => true,
                'addon' =>  serialize([
                    'module' => 'system',
                    'method' => 'get_reports_count',
                    'params' => [
                        'module' => $sName,
                        'status' => BX_DOL_REPORT_STASUS_NEW
                    ],
                    'class' => 'TemplDashboardServices'
                    ]),
                'selected' => $sSelected == $sName ? true : false
            );   
    	}
        
        if(!empty($aResult) && is_array($aResult)){
            $aResult['more-auto'] = array(
                'module' => 'system', 
                'id' => 'more-auto', 
                'name' => 'more-auto',
                'active' => true,
                'title' => '_sys_menu_item_title_va_more_auto', 
                'href' => 'javascript:void(0)', 
                'icon' => 'ellipsis-v',
            );
        }
        
        return $aResult;
    }
    
    protected function _isSelected ($a)
    {
        if (isset($a['selected']))
            return $a['selected'];
        return false;
    }
}

/** @} */
