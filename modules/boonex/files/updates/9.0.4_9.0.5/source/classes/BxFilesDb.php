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
}

/** @} */
