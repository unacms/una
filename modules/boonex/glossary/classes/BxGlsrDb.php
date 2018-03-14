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
        return  $this->getColumn("SELECT DISTINCT UPPER(LEFT(TRIM(`" . $CNF['FIELD_TITLE'] . "`),1)) FROM " . $CNF['TABLE_ENTRIES'] . " ORDER BY UPPER(LEFT(TRIM(`" . $CNF['FIELD_TITLE'] . "`),1)) ");
    }
}

/** @} */
