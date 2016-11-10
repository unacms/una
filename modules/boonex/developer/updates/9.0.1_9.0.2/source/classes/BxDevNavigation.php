<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Developer Developer
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDevNavigation extends BxTemplStudioNavigation
{
    protected $oModule;
    protected $aParams;
    protected $aGridObjects;

    function __construct($aParams)
    {
        parent::__construct(isset($aParams['page']) ? $aParams['page'] : '');

        $this->aParams = $aParams;
        $this->sSubpageUrl = $this->aParams['url'] . '&nav_page=';

        $this->oModule = BxDolModule::getInstance('bx_developer');

        $this->aGridObjects = array(
	        'menus' => $this->oModule->_oConfig->getObject('grid_nav_menus'),
	        'sets' => $this->oModule->_oConfig->getObject('grid_nav_sets'),
	        'items' => $this->oModule->_oConfig->getObject('grid_nav_items')
	    );

        $this->oModule->_oTemplate->addStudioCss(array('navigation.css'));
    }
}

/** @} */
