<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Help Tours Help Tours
 * @ingroup     UnaModules
 *
 * @{
 */

class BxHelpToursStudioPage extends BxTemplStudioModule
{
	protected $_sModule;
	protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_help_tours';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

		$this->aMenuItems = array(
            'tours' => array('name' => 'tours', 'icon' => 'info', 'title' => '_bx_help_tours_page_tours'),
            'items' => array('name' => 'items', 'icon' => 'list', 'title' => '_bx_help_tours_page_items'),
        );
    }

	protected function getTours()
    {
		bx_import('BxTemplGrid');
        $oGrid = BxDolGrid::getObjectInstance('bx_help_tours_tours', BxDolStudioTemplate::getInstance());
        if (!$oGrid) return 'The module have to be reinstalled';
        return $oGrid->getCode();
    }

    protected function getItems()
    {
        bx_import('BxTemplGrid');
        $oGrid = BxDolGrid::getObjectInstance('bx_help_tours_items', BxDolStudioTemplate::getInstance());
        if (!$oGrid) return 'The module have to be reinstalled';
        return $oGrid->getCode();
    }
}

/** @} */
