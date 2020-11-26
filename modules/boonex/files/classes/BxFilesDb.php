<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxFilesDb extends BxBaseModFilesDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
	
    public function updateFileId ($iContentId, $iFileId)
    {
		return $this->updateEntries(array('file_id' => $iFileId, 'data' => '', 'data_processed' => 0), array('id' => $iContentId));
    }
    
    public function updateFileData ($iContentId, $sData, $iDataProcessed = 1)
    {
		return $this->updateEntries(array('data' => $sData, 'data_processed' => $iDataProcessed), array('id' => $iContentId));
    }

    public function getNotProcessedFiles ($iLimit)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `data_processed` = 0 ORDER BY `added` ASC LIMIT ?", $iLimit);
        return $this->getAll($sQuery);
    }

    public function bookmarkFile($iContentId, $iProfileId) {
        if ($this->isFileBookmarked($iContentId, $iProfileId)) {
            $this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_BOOKMARKS'] . "` WHERE `object_id` = :id AND `profile_id` = :profile_id", [
                'id' => $iContentId,
                'profile_id' => $iProfileId
            ]);
        } else {
            $this->query("INSERT INTO `" . $this->_oConfig->CNF['TABLE_BOOKMARKS'] . "` (`object_id`, `profile_id`) VALUES (:id, :profile_id)", [
                'id' => $iContentId,
                'profile_id' => $iProfileId
            ]);
        }
    }

    public function deleteFileBookmarks($iContentId) {
        $this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_BOOKMARKS'] . "` WHERE `object_id` = :id", [
            'id' => $iContentId,
        ]);
    }

    public function deleteProfileBookmarks($iProfileId) {
        $this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_BOOKMARKS'] . "` WHERE `profile_id` = :profile_id", [
            'profile_id' => $iProfileId,
        ]);
    }

    public function isFileBookmarked($iContentId, $iProfileId) {
        return $this->getOne("SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_BOOKMARKS'] . "` WHERE `object_id` = :id AND `profile_id` = :profile_id", [
            'id' => $iContentId,
            'profile_id' => $iProfileId
        ]);
    }

    public function updateEntryTitle($iContentId, $sNewTitle) {
        $this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `" . $this->_oConfig->CNF['FIELD_TITLE'] . "` = :title WHERE `" . $this->_oConfig->CNF['FIELD_ID'] . "` = :id", [
            'id' => $iContentId,
            'title' => $sNewTitle,
        ]);
    }
}

/** @} */
