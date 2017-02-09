<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarketMenuSnippetActions extends BxBaseModTextMenu
{
    protected $_sModule;
    protected $_oModule;

    protected $_iContentId;
    
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sModule = 'bx_market';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
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
            case 'snippet-more':
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

        $oMenu = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_POPUP']);
        $oMenu->setContentId($this->_iContentId);
    	return $oMenu->isVisible();
    }
}

/** @} */
