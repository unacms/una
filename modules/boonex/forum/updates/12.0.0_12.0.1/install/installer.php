<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function update($aParams)
    {
        $sOutputFileName = 'bx_forum_update_1200_1201';
        if(!file_exists(BX_DIRECTORY_PATH_LOGS . $sOutputFileName . '.log'))
            $this->bx_storage_update($sOutputFileName);

        return parent::update($aParams);
    }

    function bx_storage_update($sOutputFileName)
    {
        $oDb = BxDolDb::getInstance();
        $oFile = BxDolFile::getInstance();

        // FIX FILES FOR FORUM COMMENTS
        $iCmtsSystemId = $oDb->getOne("SELECT `ID` FROM `sys_objects_cmts` WHERE `Name`='bx_forum'");
        $iTsLastUpdate = filemtime(BX_DIRECTORY_PATH_MODULES . 'boonex/forum/install/config.php');
        $aCmtsFilesIds = $oDb->getColumn("SELECT `image_id` FROM `sys_cmts_images2entries` 
            INNER JOIN `bx_forum_covers` AS `c` ON (`c`.`id` = `image_id`) 
            LEFT JOIN `bx_forum_files` AS `f` ON (`f`.`id` = `image_id`) 
            WHERE `system_id` = :system_id AND (`f`.`added` IS NULL OR `f`.`added` < :last_update)", array(
            'system_id' => $iCmtsSystemId,
            'last_update' => $iTsLastUpdate,
        )); // select all files records from 'covers' table with cheking if they don't exists in 'files' table or in 'files' table upload date is older than forum update time

        $iMaxIdCovers = $oDb->getOne("SELECT `id` FROM `bx_forum_covers` ORDER BY `id` DESC LIMIT 1");
        $iMaxIdFiles = $oDb->getOne("SELECT `id` FROM `bx_forum_files` ORDER BY `id` DESC LIMIT 1");
        if ($iMaxIdCovers >= $iMaxIdFiles)
            $oDb->query("ALTER TABLE `bx_forum_files` AUTO_INCREMENT = :inc", array('inc' => $iMaxIdCovers + 100)); // we need to update autoincrement to make sure new IDs don't overlap old IDs since they from different tables

        $iUpdated = 0;
        $sUpdated = '';
        foreach($aCmtsFilesIds as $iFileId) {
            //--- Update DB data.
            $aFile = $oDb->getRow("SELECT * FROM `bx_forum_covers` WHERE `id`=:id", array('id' => $iFileId));
            if(empty($aFile) || !is_array($aFile))
                continue;
        
            unset($aFile['id']);
            if(!$oDb->query("INSERT INTO `bx_forum_files` SET " . $oDb->arrayToSQL($aFile)))
                continue;
            $iFileIdNew = $oDb->lastId();
        
            $oDb->query("DELETE FROM `bx_forum_covers` WHERE `id`=:id", array('id' => $iFileId));
            
            $oDb->query("UPDATE `sys_cmts_images2entries` SET `image_id`=:image_id_new WHERE `system_id`=:system_id AND `image_id`=:image_id_old", array(
                'system_id' => $iCmtsSystemId,
                'image_id_old' => $iFileId,
                'image_id_new' => $iFileIdNew,
            ));
         
            $iUpdated++;
            $sUpdated .= "[ OK ] $iFileId => $iFileIdNew, Name: {$aFile['file_name']} \n";
        }
        
        $sResult = "";
        $sResult .= "Forum comments attachments: " . count($aCmtsFilesIds) . "\n";
        $sResult .= "Updated: " . $iUpdated . "\n";
        $sResult .= $sUpdated . "\n";
        bx_log($sOutputFileName, $sResult);

        // FIX FILES FOR FORUM TOPICS
        $aFileIdsSrc = $oDb->getColumn("SELECT `c`.`id` FROM `bx_forum_covers` as `c` LEFT JOIN `bx_forum_discussions` as `d` ON (`d`.`thumb` = `c`.`id`) WHERE `d`.`id` IS NULL");

        $sLog = "";
        $iUpdated = $this->bx_storage_move($aFileIdsSrc, 'bx_forum_covers', 'bx_forum_files', $sLog);

        $sResult = "";
        $sResult .= "Forum topic attachments: " . count($aFileIdsSrc) . "\n";
        $sResult .= "Updated: " . $iUpdated . "\n";
        $sResult .= $sLog . "\n";

        bx_log($sOutputFileName, $sResult);
    }

    function bx_storage_move($aFileIdsSrc, $sStorageSrc, $sStorageDst, &$sLog = null, $fCallback = null)
    {
        $oStorageSrc = BxDolStorage::getObjectInstance($sStorageSrc);
        $oStorageDst = BxDolStorage::getObjectInstance($sStorageDst);

        $iUpdated = 0;
        foreach ($aFileIdsSrc as $iFileIdSrc) {

            // get file info array and check if file exists
            if (!($aFileSrc = $oStorageSrc->getFile($iFileIdSrc))) {
                if ($sLog !== null) $sLog .= "[FAIL] $iFileIdSrc - file wasn't found \n";
                continue;
            }

            // store file in dst engine
            if ($iFileIdNew = $oStorageDst->storeFileFromStorage(array('id' => $iFileIdSrc, 'storage' => $sStorageSrc), $aFileSrc['private'], $aFileSrc['profile_id'])) {

                // get ghost array for src file
                $aFileGhostSrc = $oStorageSrc->getGhost($iFileIdSrc);

                // delete src file
                $bRet = $oStorageSrc->deleteFile($iFileIdSrc);

                $bGhostUpdated = false;
                $bGhostDeleted = false;
                if ($aFileGhostSrc) { 
                    // if ghost file exists for src file then update ghost content id for dst file
                    $bGhostUpdated = $oStorageDst->updateGhostsContentId($iFileIdNew, $aFileSrc['profile_id'], $aFileGhostSrc['content_id']);
                } else {
                    // if ghost file doesn't exist for src file, then delete ghost file for dst file
                    $bGhostDeleted = $oStorageDst->afterUploadCleanup($iFileIdNew, $aFileSrc['profile_id']);
                }

                // update associated data
                $bAssiciatedDataUpdated = $fCallback !== null ? $fCallback($iFileIdSrc, $iFileIdNew) : false;

                if ($sLog !== null) $sLog .= "[ OK ] $iFileIdSrc -> $iFileIdNew (src file deleted:$bRet, dst file ghost updated:$bGhostUpdated, dst ghost file deleted:$bGhostDeleted, associated data updated:$bAssiciatedDataUpdated) \n";
                ++$iUpdated;
            }
            else {
                if ($sLog !== null) $sLog .= "[FAIL] $iFileIdSrc - " . $oStorageDst->getErrorString() . " \n";
            }
        }
    
        return $iUpdated;
    }
}
