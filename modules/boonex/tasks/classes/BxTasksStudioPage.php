<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;
    
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_tasks';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);
    }
}

/** @} */
