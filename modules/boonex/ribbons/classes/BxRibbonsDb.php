<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxRibbonsDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
    
    public function clearRibbonsForProfile($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'profileid' => $iProfileId,
        );
        $this->query("DELETE FROM `" . $CNF['TABLE_BINDING'] . "` WHERE `" . $CNF['FIELD_PROFILE_ID'] . "` = :profileid", $aBindings);
    }
    
    public function addRibbonToProfile($iProfileId, $iRibbonId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'profileid' => $iProfileId,
            'ribbonid' => $iRibbonId
        );
        $this->query("INSERT INTO `" . $CNF['TABLE_BINDING'] . "` (`" . $CNF['FIELD_PROFILE_ID'] . "`, `" . $CNF['FIELD_RIBBON_ID'] . "`) VALUES (:profileid, :ribbonid)", $aBindings);
    }
    
    public function getRibbonsForProfile($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'profileid' => $iProfileId,
        );
        return $this->getAll("SELECT * FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_ID'] . "` IN (SELECT `" . $CNF['FIELD_RIBBON_ID'] . "` FROM `" . $CNF['TABLE_BINDING'] . "` WHERE `" . $CNF['FIELD_PROFILE_ID'] . "` = :profileid) AND `" . $CNF['FIELD_STATUS'] . "` = 'active'", $aBindings);
    }
     
    public function getAllActive()
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->getAll("SELECT * FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_STATUS'] . "` = 'active'");
    }
}

/** @} */
