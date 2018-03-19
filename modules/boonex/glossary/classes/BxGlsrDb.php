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
        return  $this->getAll("SELECT MIN(`id`) AS `row_number`, `letter`, '' AS `url` FROM ( SELECT `a`.`id`, UPPER(LEFT(TRIM(`a`.`" . $CNF['FIELD_TITLE'] . "`) ,1)) as `letter`, COUNT(*) AS `row_number` FROM " . $CNF['TABLE_ENTRIES'] . " `a` JOIN " . $CNF['TABLE_ENTRIES'] . " `b` ON `a`.`id` >= `b`.`id` AND UPPER(LEFT(TRIM(`a`.`" . $CNF['FIELD_TITLE'] . "`), 1)) = UPPER(LEFT(TRIM(`a`.`" . $CNF['FIELD_TITLE'] . "`), 1)) GROUP BY `a`.`id`, UPPER(LEFT(TRIM(`a`.`" . $CNF['FIELD_TITLE'] . "`), 1))) AS `t` GROUP BY `letter` ");
    }
}

/** @} */
