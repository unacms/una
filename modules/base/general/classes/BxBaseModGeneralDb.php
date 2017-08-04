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

    public function getContentInfoById ($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_ENTRIES']) || empty($CNF['FIELD_ID']))
            return array();

        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_ID'] . "` = ?", $iContentId);
        return $this->getRow($sQuery);
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

            case 'search_ids':
                $aMethod['name'] = 'getColumn';
                
                $sSelectClause = "`{$CNF['TABLE_ENTRIES']}`.`" . $CNF['FIELD_ID'] . "`";

                $sWhereConditions = "1";
                foreach($aParams['search_params'] as $sSearchParam => $aSearchParam) {
                    $sSearchValue = "";
                    switch ($aSearchParam['operator']) {
                        case 'like':
                            $sSearchValue = " LIKE " . $this->escape("%" . $aSearchParam['value'] . "%");
                            break;

                        case 'in':
                            $sSearchValue = " IN (" . $this->implode_escape($aSearchParam['value']) . ")";
                            break;

                        case 'and':
                            $iResult = 0;
                            if (is_array($aSearchParam['value']))
                                foreach ($aSearchParam['value'] as $iValue)
                                    $iResult |= pow (2, $iValue - 1);
                            else 
                                $iResult = (int)$aSearchParam['value'];

                            $sSearchValue = " & " . $iResult . "";
                            break;

                        default:
                             $sSearchValue = " " . $aSearchParam['operator'] . " :" . $sSearchParam;
                             $aMethod['params'][1][$sSearchParam] = $aSearchParam['value'];                             
                    }

                    $sWhereConditions .= " AND `{$CNF['TABLE_ENTRIES']}`.`" . $sSearchParam . "`" . $sSearchValue;
                }

                $sWhereClause .= " AND (" . $sWhereConditions . ")"; 

                $sOrderClause .=  "`{$CNF['TABLE_ENTRIES']}`.`" . $CNF['FIELD_ADDED'] . "` ASC";
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
