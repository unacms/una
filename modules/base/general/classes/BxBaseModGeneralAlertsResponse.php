<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
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

        if (isset($CNF['PARAM_SEARCHABLE_FIELDS']) && $CNF['PARAM_SEARCHABLE_FIELDS'] == $oAlert->aExtras['option'] && 'system' == $oAlert->sUnit && 'save_setting' == $oAlert->sAction)
            $this->_oModule->_oDb->alterFulltextIndex();
    }
}

/** @} */
