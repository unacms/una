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

class BxTimelineCmts extends BxTemplCmts
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct($sSystem, $iId, $iInit);

        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_aSystem['trigger_field_privacy_view'] = 'object_privacy_view';
    }
    
    public function onPostAfter($iCmtId, $aDp = [])
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $mixedResult = parent::onPostAfter($iCmtId, $aDp);
        if($mixedResult !== false)
            $this->_oModule->_oDb->updateEvent([$CNF['FIELD_REACTED'] => time()], [$CNF['FIELD_ID'] => $this->getId()]);

        return $mixedResult;
    }
}

/** @} */
