<?php

    $oDb = $this->oDb;

    $aCmtsIds = $oDb->getAll("SELECT `id`, `system_id` FROM `sys_cmts_ids` WHERE 1");
    $aCmtsSystems = $oDb->getColumn("SELECT `ID` FROM `sys_objects_cmts` WHERE 1");
    foreach($aCmtsIds as $aCmtsId) {
        if(in_array($aCmtsId['system_id'], $aCmtsSystems))
            continue;

        $oDb->query("DELETE FROM `sys_cmts_ids` WHERE `id`=:id", array(
            'id' => $aCmtsId['id']
        ));
    }

    $oDb->getAll("OPTIMIZE TABLE `sys_cmts_ids`");

    return true;
