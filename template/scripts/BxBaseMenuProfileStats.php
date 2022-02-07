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
class BxBaseMenuProfileStats extends BxTemplMenuAccountNotifications
{
    protected $_iMenuItemsMin;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bDisplayAddons = true;
        $this->_iMenuItemsMin = 10;
    }

    public function getMenuItems ()
    {
        $aItems = parent::getMenuItems();
        if(empty($aItems) || !is_array($aItems))
            return $aItems;

        $iMaxNum = count($aItems);
        if($this->_iMenuItemsMin > $iMaxNum)
            $this->_iMenuItemsMin = $iMaxNum;

        for($i = $this->_iMenuItemsMin; $i < $iMaxNum; $i++)
            $aItems[$i]['class_add'] .= ' bx-mi-aux bx-mi-hidden';

        $aItems[] = array(
            'class_add' => ' bx-psmi-show-more',
            'name' => 'show_more',
            'title' => '<span class="bx-mi-sm">' . _t('_sys_show_more') . '</span><span class="bx-mi-sl" style="display:none">' . _t('_sys_show_less') . '</span>',
            'link' => 'javascript:void(0)',
            'onclick' => "bx_menu_show_more(this, '.bx-menu-profile-stats')",
            'attrs' => '',
            'bx_if:image' => [
                'condition' => false,
                'content' => ['icon_url' => ''],
            ],
            'bx_if:image_inline' => [
                'condition' => false,
                'content' => ['image' => ''],
            ],
            'bx_if:icon' => [
                'condition' => false,
                'content' => ['icon' => ''],
            ],
            'bx_if:icon-html' => [
                'condition' => false,
                'content' => ['icon-a' => ''],
            ],
            'bx_if:icon-a' => [
                'condition' => false,
                'content' => ['icon-a' => ''],
            ],
            'bx_if:addon' => [
                'condition' => false,
                'content' => []
            ]
        );

        return $aItems;
    }

    protected function getMenuItemsRaw ()
    {
        $aItems = $this->_oQuery->getMenuItemsBy(array(
            'type' => 'set_name', 
            'set_name' => $this->_aObject['set_name']
        ));

        $aDuplicates = $this->_oQuery->getMenuItemsBy(array(
            'type' => 'set_name_duplicates', 
            'set_name' => $this->_aObject['set_name']
        ));

        $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return array();

        $sModule = $oProfile->getModule();

        $aResult = array();
        foreach($aItems as $aItem) {
            if(in_array($aItem['name'], $aDuplicates) && $aItem['module'] != $sModule)
                continue;
            
            $aResult[$aItem['name']] = $aItem;
        }

        return $aResult;
    }
}

/** @} */
