<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxForumMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($aObject, $oTemplate);

        $this->addMarkers(array(
        	'js_object' => $this->_oModule->_oConfig->getJsObject('entry') 
        ));
    }
    
    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        $sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
            case 'more':
            	$sCheckFuncName = '_isVisibleMore';
            	break;
        }

        if(!$sCheckFuncName || !method_exists($this, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this, $sCheckFuncName), $aCheckFuncParams) === true;
    }

    protected function _isVisibleMore()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oMenu = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE']);
        $oMenu->setContentId($this->_iContentId);
    	return $oMenu->isVisible();
    }
}

/** @} */
