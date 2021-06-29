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
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bDisplayAddons = true;
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
