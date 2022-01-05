<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolMenu
 */
class BxTemplVoteReactionsMenuDo extends BxBaseVoteReactionsMenuDo
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
    }
    
    protected function getMenuItemsRaw()
    {
        $sJsObject = $this->_oObject->getJsObjectName();
        $aReactions = $this->_oObject->getReactions(true);
        $sDefault = $this->_oObject->getDefault();

        $aMenuItems = array();        
        foreach($aReactions as $sName => $aReaction) {
            if($sName == $sDefault)
                continue;

            $sEmoji = $this->_oObject->getEmoji($sName);
            
            $aMenuItems[] = array(
                'id' => $sName, 
                'name' => $sName, 
                'class' => 'group inline-flex items-center border border-transparent rounded-md space-x-1 text-gray-500 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 px-1', 
                'link' => 'javascript:void(0)', 
                'onclick' => 'javascript:' . $this->_oObject->getJsClickDo($sName, $this->_iValue), 
                'target' => '_self', 
                'title' => _t($aReaction['title']), 
                'icon' => $sEmoji != '' ? $sEmoji : $this->_oObject->getIcon($sName), 
                'active' => 1
            );
        }

        return $aMenuItems;
    }
}

/** @} */
