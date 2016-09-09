<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxBaseModGeneralDb extends BxDolModuleDb
{
    protected $_oConfig;

    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
        $this->_oConfig = $oConfig;
    }

    public function getEntriesByAuthor ($iProfileId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_AUTHOR'] . "` = ?", $iProfileId);
        return $this->getAll($sQuery);
    }

    public function getEntriesNumByAuthor ($iProfileId)
    {
        $sQuery = $this->prepare ("SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_AUTHOR'] . "` = ?", $iProfileId);
        return $this->getOne($sQuery);
    }

    public function alterFulltextIndex ()
    {
        $CNF = $this->_oConfig->CNF;        

        $bFulltextIndex = false;
        $aIndexes = $this->getAll("SHOW INDEXES FROM `bx_persons_data`");

        foreach ($aIndexes as $r) {
            if ($CNF['TABLE_ENTRIES_FULLTEXT'] == $r['Key_name']) {
                $bFulltextIndex = true;
                break;
            }
        }

        if ($bFulltextIndex)
            $this->pdoExec("ALTER TABLE `" . $CNF['TABLE_ENTRIES'] . "` DROP INDEX `" . $CNF['TABLE_ENTRIES_FULLTEXT'] . "`");

        if (!($aFields = explode(',', getParam($CNF['PARAM_SEARCHABLE_FIELDS']))))
            return true;

        $sFields = '';
        foreach ($aFields as $s)
            $sFields .= "`$s`,";

        return $this->pdoExec("ALTER TABLE `" . $CNF['TABLE_ENTRIES'] . "` ADD FULLTEXT `" . $CNF['TABLE_ENTRIES_FULLTEXT'] . "` (" . trim($sFields, ', ') . ")");
    }

}

/** @} */
