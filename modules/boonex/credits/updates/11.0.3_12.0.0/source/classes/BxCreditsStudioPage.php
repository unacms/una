<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_DOL_STUDIO_MOD_TYPE_BUNDLES', 'bundles');

class BxCreditsStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_credits';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems[BX_DOL_STUDIO_MOD_TYPE_BUNDLES] = array('name' => BX_DOL_STUDIO_MOD_TYPE_BUNDLES, 'icon' => 'archive', 'title' => '_bx_credits_menu_item_title_bundles');
    }

    protected function getSettings()
    {
        $sContent = '';
        if($this->_oModule->_oConfig->getAuthor() == 0)
            $sContent .= $this->_oModule->_oTemplate->getEmptyAuthor();

        $sContent .= parent::getSettings();

        return $sContent;
    }

    protected function getBundles()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_BUNDLES'], BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        $sContent = '';
        if($this->_oModule->_oConfig->getAuthor() == 0)
            $sContent .= $this->_oModule->_oTemplate->getEmptyAuthor();

        $sContent .= $this->_oModule->_oTemplate->getJsCode('studio');
        $sContent .= $oGrid->getCode();

        $this->_oModule->_oTemplate->addJs('studio');
        return $sContent;
    }
}

/** @} */
