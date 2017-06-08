<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxBaseModTextDb extends BxBaseModGeneralDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function searchByAuthorTerm($iAuthor, $sTerm, $iLimit)
    {
        $CNF = &$this->_oConfig->CNF;

        if (empty($CNF['FIELDS_QUICK_SEARCH']))
            return array();

		$aBindings = array(
		    'author' => $iAuthor
		);

        $sWhere = '';
        foreach ($CNF['FIELDS_QUICK_SEARCH'] as $sField) {
        	$aBindings[$sField] = '%' . $sTerm . '%';

            $sWhere .= " OR `c`.`$sField` LIKE :" . $sField;
        }

        $sOrderBy = $this->prepareAsString(" ORDER BY `c`.`added` DESC LIMIT ?", (int)$iLimit);

        $sQuery = "SELECT `c`.* FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` WHERE `c`.`" . $CNF['FIELD_AUTHOR'] . "`=:author AND (0 $sWhere)" . $sOrderBy;
        return $this->getAll($sQuery, $aBindings);
    }
}

/** @} */
