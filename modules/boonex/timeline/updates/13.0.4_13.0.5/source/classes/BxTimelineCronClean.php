<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineCronClean extends BxDolCron
{
    protected $_sModule;
    protected $_oModule;

    protected $_iLimitRead;

    public function __construct()
    {
    	$this->_sModule = 'bx_timeline';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct();

        $this->_iLimitRead = 1000;
    }

    public function processing()
    {
        if($this->_iLimitRead > 0)
            $this->_oModule->_oDb->cleanRead($this->_iLimitRead);
    }
}

/** @} */
