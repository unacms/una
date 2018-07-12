<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary 
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxGlsrDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
    
    public function getAlphabeticalIndex()
    {
        $CNF = &$this->_oConfig->CNF;
        $this->pdoExec("SET @row_number = 0;");
        return  $this->getAll("SELECT MIN(`rn`) AS `row_number`, `letter`, '' AS `url` FROM (SELECT @row_number := @row_number + 1 AS rn, `a`.`id`, UPPER(LEFT(TRIM(`a`.`" . $CNF['FIELD_TITLE'] . "`) ,1)) as `letter` FROM `bx_glossary_terms` `a` WHERE `a`.`status_admin`='active' ORDER BY UPPER(LEFT(TRIM(`a`.`" . $CNF['FIELD_TITLE'] . "`), 1))) AS `t` GROUP BY `letter`");
    }
}

/** @} */
