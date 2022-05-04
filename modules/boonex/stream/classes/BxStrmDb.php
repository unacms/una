<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxStrmDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getNewRecordingId($iContentId)
    {
        if (!$this->query("INSERT INTO `bx_stream_recordings_seq` SET `content_id` = :content_id, `added` = :ts", ['content_id' => $iContentId, 'ts' => time()]))
            return false;
        return $this->lastId();
    }

    public function getRecordingId($iContentId)
    {
        return $this->getOne("SELECT `id` FROM `bx_stream_recordings_seq` WHERE `content_id` = :content_id ORDER BY `id` DESC", ['content_id' => $iContentId]);
    }

    public function getPendingRecordings ($iLimit = 2)
    {
        return $this->getAll("SELECT * FROM `bx_stream_recordings_seq` ORDER BY `id` ASC LIMIT " . (int)$iLimit);
    }

    public function updateRecording($iRecordingId, $aValues = [])
    {
        $sValues = $this->arrayToSQL($aValues);
        return $this->getOne("UPDATE `bx_stream_recordings_seq` SET $sValues WHERE `id` = :id", ['id' => $iRecordingId]);
    }

    public function deleteRecording($iRecordingId)
    {
        return $this->query("DELETE FROM `bx_stream_recordings_seq` WHERE `id` = :id", ['id' => $iRecordingId]);
    }
}

/** @} */
