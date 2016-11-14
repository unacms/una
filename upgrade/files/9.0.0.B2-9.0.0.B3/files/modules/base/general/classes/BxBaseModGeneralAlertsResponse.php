<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModGeneralAlertsResponse extends BxDolAlertsResponse
{
    protected $MODULE;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    public function response($oAlert)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        if('system' == $oAlert->sUnit && 'save_setting' == $oAlert->sAction && isset($CNF['PARAM_SEARCHABLE_FIELDS']) && $CNF['PARAM_SEARCHABLE_FIELDS'] == $oAlert->aExtras['option'])
            $this->_oModule->_oDb->alterFulltextIndex();
    }
}

/** @} */
