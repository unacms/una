<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MapShow Display last sign up users on map
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMapShowDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
    
    public function getLngLatData($iLastId)
    {
        $CNF = &$this->_oConfig->CNF;
        $sIntervalInHour = getParam('bx_mapshow_initial_timeframe_users_shown_in_hours');
        $sSql = "";
        $aBindings = array();
        if ($iLastId == 0) {
            $aBindings['interval'] = $sIntervalInHour;
            $sSql = $CNF['FIELD_JOINED'] . " > DATE_SUB(NOW(), INTERVAL :interval HOUR) ";
        }
        else{
            $aBindings['id'] = $iLastId;
            $sSql = $CNF['FIELD_ID'] . " > :id";
        }
        
        return $this->getAll("SELECT " . $CNF['FIELD_LNG'] . ", " . $CNF['FIELD_LAT'] . ", " . $CNF['FIELD_ID'] . " FROM " . $CNF['TABLE_ENTRIES'] . " WHERE " . $sSql, $aBindings);
    }
    
    public function addIpInfo($iAccountId, $sIp, $sLng, $sLat)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'account_id' => $iAccountId,
            'ip' => $sIp,
            'lng' => $sLng,
            'lat' => $sLat
        );
        $this->query("INSERT INTO " . $CNF['TABLE_ENTRIES'] . " (" . $CNF['FIELD_ACCOUNT_ID'] . ", " . $CNF['FIELD_IP'] . ", " . $CNF['FIELD_LNG'] . ", " . $CNF['FIELD_LAT'] . ") VALUES (:account_id, :ip, :lng, :lat)", $aBindings);
    }
}

/** @} */
