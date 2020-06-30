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
    protected $_aStatuses = array(
        1 => 'viewed',
        2 => 'replied',
    );

    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getPrevEntry ($iClassId)
    {        
        return $this->_getNextPrevEntry($iClassId, 'DESC');
    }

    public function getNextEntry ($iClassId)
    {        
        return $this->_getNextPrevEntry($iClassId, 'ASC');
    }

    protected function _getNextPrevEntry ($iClassId, $sSorting = 'DESC')
    {        
        $aClass = $this->getRow("SELECT `c`.`id`, `c`.`order`, `m`.`order` as `order_module` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` AS `m` ON (`m`.`id` = `c`.`module_id`) WHERE `c`.`id` = :class", array('class' => $iClassId));
        $sQuery = "SELECT `c`.`*`, `m`.`module_title` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `" . $this->_oConfig->CNF['TABLE_MODULES'] . "` AS `m` ON (`m`.`id` = `c`.`module_id`) WHERE `m`.`order` <= :order_module AND `c`.`order` <= :order AND `c`.`id` != :id ORDER BY `m`.`order` DESC, `c`.`order` DESC LIMIT 1";
        
        $a = $this->getRow($sQuery, array(
            'order_module' => $aClass['order_module'], 
            'order' => $aClass['order'],
            'id' => $aClass['id'],
        ));

        return $a;
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

    public function isClassCompleted($iClassId, $iStudentProfileId)
    {
        if (!($aContentInfo = $this->getContentInfoById($iClassId)))
            return false;

        return $this->getClassStatus($iClassId, $iStudentProfileId, (int)$aContentInfo['completed_when']);
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
}

/** @} */
