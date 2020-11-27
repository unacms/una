<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_DOL_STUDIO_MOD_TYPE_CATEGORIES', 'categories');

class BxAdsStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_ads';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems[BX_DOL_STUDIO_MOD_TYPE_CATEGORIES] = array('name' => BX_DOL_STUDIO_MOD_TYPE_CATEGORIES, 'icon' => 'bars', 'title' => '_bx_ads_menu_item_title_categories');
    }

    protected function getCategories()
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_GRID_CATEGORIES'], BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        $this->_oModule->_oTemplate->addJs('studio');
        return $this->_oModule->_oTemplate->getJsCode('studio') . $oGrid->getCode();
    }
}

/** @} */
