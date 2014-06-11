<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplStudioNavigation');

class BxDevNavigation extends BxTemplStudioNavigation {
    protected $oModule;
    protected $aParams;
    protected $aGridObjects = array(
        'menus' => 'mod_dev_nav_menus',
        'sets' => 'mod_dev_nav_sets',
        'items' => 'mod_dev_nav_items'
    );

    function __construct($aParams) {
        parent::__construct(isset($aParams['page']) ? $aParams['page'] : '');

        $this->aParams = $aParams;
        $this->sSubpageUrl = $this->aParams['url'] . '&nav_page=';

        bx_import('BxDolModule');
        $this->oModule = BxDolModule::getInstance('bx_developer');

        $this->oModule->_oTemplate->addStudioCss(array('navigation.css'));
    }
}

/** @} */
