<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxStripeConnectPageActivity extends BxTemplPage
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sModule = 'bx_stripe_connect';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if($oMenuSubmenu) {
            $sMenuSubmenu = 'sys_account_dashboard';
            $oMenuSubmenu->setObjectSubmenu($sMenuSubmenu, array('title' => _t('_sys_menu_item_title_account_dashboard'), 'link' => '', 'icon' => ''));

            BxDolMenu::getObjectInstance($sMenuSubmenu)->setSelected($this->_sModule, 'connected-activity');
        }
    }

    public function getCode ()
    {
        $sResult = parent::getCode();

        if(!isLogged() || !$this->isAvailablePage() || !$this->isVisiblePage())
            return $sResult;
        
        $iProfileId = bx_get_logged_profile_id();
        if(!$this->_oModule->hasAccount($iProfileId)) {
            $this->_oTemplate->displayMsg([
                'title' => _t('_bx_stripe_connect_page_block_title_warning'), 
                'content' => _t('_bx_stripe_connect_msg_create_account', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=payment-details'))
            ]);
            exit;
        }

        return $this->_oModule->_oTemplate->getJsCodeEmbeds($iProfileId) . $sResult;
    }
}

/** @} */
