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
    protected $_iContentId;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_market';

        parent::__construct($aObject, $oTemplate);
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

        $oMenu = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_SNIPPET_MORE']);
        $oMenu->setContentId($this->_iContentId);
    	return $oMenu->isVisible();
    }
}

/** @} */
