<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxClssDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getEntriesByModule ($iModuleId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `module_id` = ? ORDER BY `order`", $iModuleId);
        return $this->getAll($sQuery);
    }

    public function getEntriesModulesByContext ($iProfileId, $bAsPairs = false)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` WHERE `profile_id` = ? ORDER BY `order`", $iProfileId);
        if ($bAsPairs)
            return $this->getPairs($sQuery, 'id', 'module_title');
        else
            return $this->getAll($sQuery);
    }

    public function updateModulesOrder ($iProfileId, $aModulesOrder)
    {
        $iAffected = 0;
        foreach ($aModulesOrder as $iOrder => $iModuleId) {
            $iAffected += ($this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` SET `order` = :order WHERE `profile_id` = :profile_id AND `id` = :module_id", array(
                'order' => $iOrder,
                'profile_id' => $iProfileId,
                'module_id' => $iModuleId,
            )) ? 1 : 0);
        }        
        return $iAffected;
    }

    public function updateClassesOrder($iProfileId, $iModuleId, $aClassesOrder)
    {
        $iAffected = 0;
        foreach ($aClassesOrder as $iOrder => $iClassId) {
            $iAffected += ($this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `order` = :order, `module_id` = :module_id WHERE `allow_view_to` = :profile_id AND `id` = :class_id", array(
                'order' => $iOrder,
                'profile_id' => -$iProfileId,
                'module_id' => $iModuleId,
                'class_id' => $iClassId,
            )) ? 1 : 0);
        }        
        return $iAffected;
    }
}

/** @} */
