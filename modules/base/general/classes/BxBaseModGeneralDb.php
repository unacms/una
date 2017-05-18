<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
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

    function getEntriesBy($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query', 1 => array()));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$CNF['TABLE_ENTRIES']}`.*";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1]['id'] = (int)$aParams['id'];

                $sWhereClause .= " AND `{$CNF['TABLE_ENTRIES']}`.`" . $CNF['FIELD_ID'] . "` = :id";
                break;

            case 'author':
            	$aMethod['name'] = 'getRow';
                $aMethod['params'][1]['author'] = (int)$aParams['author'];

                $sWhereClause .= " AND `{$CNF['TABLE_ENTRIES']}`.`" . $CNF['FIELD_AUTHOR'] . "` = :author";
                break;

            case 'all_ids':
                $aMethod['name'] = 'getColumn';

                $sSelectClause = "`{$CNF['TABLE_ENTRIES']}`.`" . $CNF['FIELD_ID'] . "`";
                $sOrderClause .=  "`{$CNF['TABLE_ENTRIES']}`.`" . $CNF['FIELD_ADDED'] . "` ASC";
                break;

            case 'all':
                $sOrderClause .=  "`{$CNF['TABLE_ENTRIES']}`.`" . $CNF['FIELD_ADDED'] . "` ASC";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `{$CNF['TABLE_ENTRIES']}` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
		return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
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
        $aIndexes = $this->getAll("SHOW INDEXES FROM `" . $CNF['TABLE_ENTRIES'] . "`");

        foreach ($aIndexes as $r) {
            if ($CNF['TABLE_ENTRIES_FULLTEXT'] == $r['Key_name']) {
                $bFulltextIndex = true;
                break;
            }
        }

        if ($bFulltextIndex)
            $this->query("ALTER TABLE `" . $CNF['TABLE_ENTRIES'] . "` DROP INDEX `" . $CNF['TABLE_ENTRIES_FULLTEXT'] . "`");

        if (!($aFields = explode(',', getParam($CNF['PARAM_SEARCHABLE_FIELDS']))))
            return true;

        $sFields = '';
        foreach ($aFields as $s)
            $sFields .= "`$s`,";

        return $this->query("ALTER TABLE `" . $CNF['TABLE_ENTRIES'] . "` ADD FULLTEXT `" . $CNF['TABLE_ENTRIES_FULLTEXT'] . "` (" . trim($sFields, ', ') . ")");
    }

}

/** @} */
