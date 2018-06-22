<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Spaces module database queries
 */
class BxCnlDb extends BxBaseModGroupsDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
     
    public function getChannelIdByName($sName)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'name' => $sName,
        );
        return $this->getOne("SELECT `" . $CNF['FIELD_ID'] . "` FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_NAME'] . "` = :name", $aBindings);
    }
}

/** @} */
