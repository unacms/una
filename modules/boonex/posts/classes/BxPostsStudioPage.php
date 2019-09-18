<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */
define('BX_POSTS_STUDIO_TEMPL_TYPE_CATEGORIES', 'categories');

class BxPostsStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
	protected $_oModule;
    
    function __construct($sModule = "", $sPage = "")
    {
    	$this->_sModule = 'bx_posts';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $sPage);
        
        $this->aMenuItems[BX_POSTS_STUDIO_TEMPL_TYPE_CATEGORIES] = array('name' => BX_POSTS_STUDIO_TEMPL_TYPE_CATEGORIES, 'title' => '_bx_posts_lmi_cpt_categories', 'icon' => 'bars');
    }
    
    protected function getCategories($sMix = '')
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_GRID_CATEGORIES'], BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }
}

/** @} */
