<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

class BxClssStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
	protected $_oModule;
    
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_classes';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);
    }
}

/** @} */
