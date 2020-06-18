<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reviews Reviews
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReviewsStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
	protected $_oModule;
    
    function __construct($sModule = "", $sPage = "")
    {
    	$this->_sModule = 'bx_reviews';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $sPage);
    }
}

/** @} */
