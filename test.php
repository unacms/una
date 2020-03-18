<?php
require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "utils.inc.php");

$oPayments = BxDolPayments::getInstance();
$oProvider = $oPayments->getProvider('chargebee_v3', 10);
if($oProvider) {
    //$aResult = $oProvider->getAddon('bx_profiler');
    $aResult = $oProvider->getAddons();
    var_dump($aResult);
}

/*
$oDb = BxDolDb::getInstance();

$aCmts = $oDb->getAll("SELECT * FROM `bx_forum_cmts` WHERE `cmt_level`>'0' AND `cmt_vparent_id`='0'");
foreach($aCmts as $aCmt) {
    if((int)$aCmt['cmt_level'] == 1) {
        $oDb->query("UPDATE `bx_forum_cmts` SET `cmt_vparent_id`=`cmt_parent_id` WHERE `cmt_id`=:cmt_id LIMIT 1", array('cmt_id' => (int)$aCmt['cmt_id']));
        continue;
    }

    $iParent = (int)$aCmt['cmt_parent_id'];
    while(true) {
        $aParent = $oDb->getRow("SELECT * FROM `bx_forum_cmts` WHERE `cmt_id`=:cmt_id LIMIT 1", array('cmt_id' => $iParent));
        if((int)$aParent['cmt_level'] == 0)
            break;

        $iParent = $aParent['cmt_parent_id'];
    }

    $oDb->query("UPDATE `bx_forum_cmts` SET `cmt_vparent_id`=:cmt_parent_id WHERE `cmt_id`=:cmt_id LIMIT 1", array(
        'cmt_parent_id' => $iParent, 
        'cmt_id' => (int)$aCmt['cmt_id']
    ));
}

exit;
 
 */

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