<?php
require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "utils.inc.php");


/*
$oDb = BxDolDb::getInstance();

$iCount = 0;
$aCmtsIds = $oDb->getAll("SELECT * FROM `sys_cmts_ids` WHERE 1");
$aCmtsSystems = $oDb->getColumn("SELECT `ID` FROM `sys_objects_cmts` WHERE 1");
foreach($aCmtsIds as $aCmtsId) {
    if(in_array($aCmtsId['system_id'], $aCmtsSystems))
        continue;

    $iCount += (int)$oDb->query("DELETE FROM `sys_cmts_ids` WHERE `id`=:id", array(
        'id' => $aCmtsId['id']
    ));
}
    
echo "Updated: " . $iCount;
*/

/*
$oDb = BxDolDb::getInstance();
$aSystems = $oDb->getPairs("SELECT `ID`, `Table` FROM `sys_objects_cmts` WHERE 1", "ID", "Table");

$iUpdated = 0;
$aComments = $oDb->getAll("SELECT * FROM `sys_cmts_ids` WHERE `author_id`='0'");
foreach($aComments as $aComment) {
    if(!isset($aSystems[$aComment['system_id']]))
        continue;

    $iUpdated += $oDb->query("UPDATE `sys_cmts_ids` SET `author_id`=:author_id WHERE `id`=:id LIMIT 1", array(
        'id' => (int)$aComment['id'],
        'author_id' => (int)$oDb->getOne("SELECT `cmt_author_id` FROM `" . $aSystems[$aComment['system_id']] . "` WHERE `cmt_id`=:cmt_id", array(
            'cmt_id' => (int)$aComment['cmt_id']
        ))
    )) !== false ? 1 : 0;
}

echo "Updated: " . $iUpdated;
 */