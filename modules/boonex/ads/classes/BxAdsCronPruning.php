<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxAdsCronPruning extends BxDolCron
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        $this->_sModule = 'bx_ads';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct();
    }

    function processing()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sStatus = 'hidden';
        $aEntries = $this->_oModule->_oDb->getEntriesBy(array('type' => 'expired', 'days' => (int)getParam($CNF['PARAM_LIFETIME'])));
        foreach($aEntries as $aEntry) {
            if(!$this->_oModule->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => $sStatus), array($CNF['FIELD_ID'] => $aEntry[$CNF['FIELD_ID']])))
                continue;

            $aEntry[$CNF['FIELD_STATUS']] = $sStatus;

            $this->_oModule->alertAfterEdit($aEntry);
        }
    }
}

/** @} */
