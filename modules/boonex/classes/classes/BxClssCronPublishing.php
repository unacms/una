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

class BxClssCronPublishing extends BxDolCron
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

    	$this->_sModule = 'bx_classes';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    function processing()
    {
        $mixedIds = $this->_oModule->_oDb->publishEntries();
        if($mixedIds === false)
            return;

        foreach($mixedIds as $iId)
            $this->_oModule->onPublished($iId);
    }
}

/** @} */
