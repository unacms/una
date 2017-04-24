<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Shopify Shopify
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * 'Shopify Submenu' menu.
 */
class BxShopifyMenuSubmenu extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sModule = 'bx_shopify';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $aSettings = $this->_oModule->getSettings();
        if(!empty($aSettings))
            $this->addMarkers(array(
    			'domain' => $aSettings['domain']
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
        	case 'shopify-dashboard':
                $sCheckFuncName = 'isAllowedViewDashboard';
				$aCheckFuncParams = array();
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
