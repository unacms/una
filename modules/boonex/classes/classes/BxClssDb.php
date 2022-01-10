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
    /**
     * Map of IDs from `bx_classes_completed_when` pre-defined values 
     * to fields in `bx_classes_statuses` table
     */
    protected $_aStatuses = array(
        0 => 'completed',
        1 => 'viewed',
        2 => 'replied',
    );

    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getPrevEntry ($iClassId)
    {        
        return $this->_getNextPrevEntry($iClassId, 'DESC', '<=');
    }

    public function getNextEntry ($iClassId)
    {        
        return $this->_getNextPrevEntry($iClassId, 'ASC', '>=');
    }

    protected function _getNextPrevEntry ($iClassId, $sSorting = 'DESC', $sOp = '<=')
    {        
        $aClass = $this->getRow("SELECT `c`.`id`, `c`.`order`, `m`.`order` as `order_module`, `allow_view_to` as `context_profile_id` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` AS `m` ON (`m`.`id` = `c`.`module_id`) WHERE `c`.`id` = :class", array('class' => $iClassId));

        $sQuery = "SELECT `c`.*, `m`.`module_title` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` AS `m` ON (`m`.`id` = `c`.`module_id`) WHERE `m`.`order`*1000000 + `c`.`order` $sOp :order_module*1000000 + :order AND `c`.`id` != :id AND `allow_view_to` = :context_profile ORDER BY `m`.`order`*1000000 + `c`.`order` $sSorting LIMIT 1";

        $a = $this->getRow($sQuery, array(
            'order_module' => $aClass['order_module'], 
            'order' => $aClass['order'],
            'id' => $aClass['id'],
            'context_profile' => $aClass['context_profile_id'],
        ));

        return $a;
    }

    public function getEntriesByModule ($iModuleId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `module_id` = ? ORDER BY `order`", $iModuleId);
        return $this->getAll($sQuery);
    }

    public function getEntriesModulesByContext ($iProfileConextId, $bAsPairs = false)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` WHERE `profile_id` = ? ORDER BY `order`", $iProfileConextId);
        if ($bAsPairs)
            return $this->getPairs($sQuery, 'id', 'module_title');
        else
            return $this->getAll($sQuery);
    }

    public function getClassModuleTitleById ($iModuleId)
    {
        $sQuery = $this->prepare ("SELECT `module_title` FROM `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` WHERE `id` = ?", $iModuleId);
        return $this->getOne($sQuery);
    }

    public function updateModulesOrder ($iProfileConextId, $aModulesOrder)
    {
        $iAffected = 0;
        foreach ($aModulesOrder as $iOrder => $iModuleId) {
            $iAffected += ($this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` SET `order` = :order WHERE `profile_id` = :profile_id AND `id` = :module_id", array(
                'order' => $iOrder,
                'profile_id' => $iProfileConextId,
                'module_id' => $iModuleId,
            )) ? 1 : 0);
        }        
        return $iAffected;
    }

    public function getModuleMaxOrder ($iProfileConextId)
    {
        return $this->getOne("SELECT `order` + 1 FROM `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` WHERE `profile_id` = :profile_context ORDER BY `order` DESC LIMIT 1", array(
            'profile_context' => $iProfileConextId,
        ));
    }

    public function getModule ($iProfileConextId, $iModuleId)
    {
        return $this->getRow("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` WHERE `profile_id` = :profile_context AND `id` = :id LIMIT 1", array(
            'profile_context' => $iProfileConextId,
            'id' => $iModuleId,
        ));
    }

    public function deleteModule ($iProfileConextId, $iModuleId)
    {
        return $this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` WHERE `profile_id` = :profile_context AND `id` = :id", array(
            'profile_context' => $iProfileConextId,
            'id' => $iModuleId,
        ));
    }

    public function getClassMaxOrder ($iProfileConextId, $iModuleId)
    {
        return $this->getOne("SELECT `order` + 1 FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `allow_view_to` = :profile_context AND `module_id` = :module ORDER BY `order` DESC LIMIT 1", array(
            'profile_context' => -$iProfileConextId,
            'module' => $iModuleId,
        ));
    }

    public function updateClassesOrder($iProfileConextId, $iModuleId, $aClassesOrder)
    {
        $iAffected = 0;
        foreach ($aClassesOrder as $iOrder => $iClassId) {
            $iAffected += ($this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `order` = :order, `module_id` = :module_id WHERE `allow_view_to` = :profile_context AND `id` = :class_id", array(
                'order' => $iOrder,
                'profile_context' => -$iProfileConextId,
                'module_id' => $iModuleId,
                'class_id' => $iClassId,
            )) ? 1 : 0);
        }        
        return $iAffected;
    }

    public function isClassCompleted($iClassId, $iStudentProfileId)
    {
        return $this->getClassStatus($iClassId, $iStudentProfileId, 'completed');
    }

    public function getClassStatus($iClassId, $iStudentProfileId, $mixedStatus)
    {
        if (is_numeric($mixedStatus) && is_int($mixedStatus) && isset($this->_aStatuses[$mixedStatus]))
            $mixedStatus = $this->_aStatuses[$mixedStatus];
        elseif (!in_array($mixedStatus, $this->_aStatuses, true))
            return false;

        return $this->getOne("SELECT `$mixedStatus` FROM `bx_classes_statuses` WHERE `class_id` = :class AND `student_profile_id` = :student", array(
            'class' => $iClassId,
            'student' => $iStudentProfileId,
        ));
    }

    public function updateClassStatus($iClassId, $iStudentProfileId, $sStatus)
    {
        if (!in_array($sStatus, $this->_aStatuses))
            return false;

        if ($this->getOne("SELECT `id` FROM `bx_classes_statuses` WHERE `class_id` = :class AND `student_profile_id` = :student", array(
            'class' => $iClassId,
            'student' => $iStudentProfileId,
        ))) {
            return $this->query("UPDATE `bx_classes_statuses` SET `$sStatus` = :ts WHERE `class_id` = :class AND `student_profile_id` = :student", array(
                'ts' => time(),
                'class' => $iClassId,
                'student' => $iStudentProfileId,
            ));
        }
        else {
            return $this->query("INSERT INTO `bx_classes_statuses` SET `class_id` = :class, `student_profile_id` = :student, `$sStatus` = :ts", array(
                'ts' => time(),
                'class' => $iClassId,
                'student' => $iStudentProfileId,
            ));
        }
    }

    public function getStudentsInClass($aContentInfo, $iStart = 0, $iLimit = 1000)
    {
        if (!$aContentInfo)
            return array();

        if ($aContentInfo['allow_view_to'] < 0 && $oProfileContext = BxDolProfile::getInstance(abs($aContentInfo['allow_view_to']))) {
            $oModule = BxDolModule::getInstance($oProfileContext->getModule());

            if ($oModule && isset($oModule->_oConfig->CNF['OBJECT_CONNECTIONS'])) {
                if (!($o = BxDolConnection::getObjectInstance($oModule->_oConfig->CNF['OBJECT_CONNECTIONS'])))
                    return array();

                // TODO: remake to use SQL parts
                if (BX_CONNECTIONS_TYPE_MUTUAL == $o->getType())
                    $a = $o->getConnectedContent($oProfileContext->id(), true, $iStart, $iLimit);
                else
                    $a = $o->getConnectedContent($oProfileContext->id(), false, $iStart, $iLimit);
            }
        }

        if ($a && $aContentInfo['allow_view_to'] < 0)
            $a = $this->_removeCourseAdminsFromProfilesArray(abs($aContentInfo['allow_view_to']), $a); // TODO: remake to add condition to query instead of postfiltering

        return $a;
    }

    public function getStudentsInClassNotCompleted($aContentInfo, $iStart = 0, $iLimit = 1000)
    {
        $aAll = $this->getStudentsInClass($aContentInfo, $iStart, $iLimit);
        $aCompleted = $this->getStudentsInClassCompleted($aContentInfo, $iStart, $iLimit);
        return array_diff($aAll, $aCompleted);;
    }

    public function getStudentsInClassCompleted($aContentInfo, $iStart = 0, $iLimit = 1000)
    {
        if (!$aContentInfo)
            return array();

        $a = $this->getColumn("SELECT `student_profile_id` FROM `bx_classes_statuses` INNER JOIN `sys_profiles` AS `p` ON(`p`.`id` = `student_profile_id` AND `p`.`status` = 'active') WHERE `class_id` = :class AND `completed` != 0 LIMIT :start, :limit", array(
            'class' => $aContentInfo['id'],
            'start' => (int)$iStart,
            'limit' => (int)$iLimit,
        ));
        if (!$a)
            return array();

        if ($aContentInfo['allow_view_to'] < 0)
            $a = $this->_removeCourseAdminsFromProfilesArray(abs($aContentInfo['allow_view_to']), $a); // TODO: remake to add condition to query instead of postfiltering

        return $a;
    }

    protected function _removeCourseAdminsFromProfilesArray($iProfileConextId, $a)
    {
        if ($oProfileContext = BxDolProfile::getInstance($iProfileConextId)) {
            $oModule = BxDolModule::getInstance($oProfileContext->getModule());
            if ($oModule && method_exists($oModule->_oDb, 'getAdmins')) {
                $aAdmins = $oModule->_oDb->getAdmins($oProfileContext->id(), 0, 1000);
                $a = array_diff($a, $aAdmins);
            }
        }
        return $a;
    }
}

/** @} */
