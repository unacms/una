<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

class BxStrmCronRecordings extends BxDolCron
{
    protected $_sModule;

    public function __construct()
    {
        parent::__construct();

    	$this->_sModule = 'bx_stream';
    }

    function processing()
    {
    	$oModule = BxDolModule::getInstance($this->_sModule);

        $a = $oModule->_oDb->getPendingRecordings();
        foreach($a as $r) {
            $aContentInfo = $oModule->_oDb->getContentInfoById($r['content_id']);
            $oModule->getStreamEngine()->processRecordings($r['id'], $aContentInfo, $oModule, $r['tries']);
        }
    }
}

/** @} */

