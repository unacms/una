<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEventsCronPruning extends BxBaseModGroupsCronPruning
{
    public function __construct()
    {
        $this->_sModule = 'bx_events';

        parent::__construct();
    }
    
    function processing()
    {
        parent::processing();

        $CNF = &$this->_oModule->_oConfig->CNF;

        $sModule = 'bx_timeline';
        $sMethod = 'get_events_by_descriptor';
        if(($iInterval = (int)getParam($CNF['PARAM_DELETE_FROM_TIMELINE_AFTER'])) != 0 && bx_is_srv($sModule, $sMethod)) {
            $iDateTo = time() - 3600 * $iInterval;
            $aEntries = $this->_oModule->_oDb->getEntriesBy(['type' => 'past', 'date_from' => $iDateTo - 86400, 'date_to' => $iDateTo]);
            foreach($aEntries as $aEntry) {
                $aEvent = bx_srv($sModule, $sMethod, [$this->_sModule, 'added', $aEntry[$CNF['FIELD_ID']]]);

                if(empty($aEvent) || !is_array($aEvent))
                    continue;

                bx_srv($sModule, 'delete_entity', [$aEvent['id']]);
            }
        }
    }
}

/** @} */
