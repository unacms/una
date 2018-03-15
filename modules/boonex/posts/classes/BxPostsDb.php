<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxPostsDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function publish()
    {
        $CNF = $this->_oConfig->CNF;

        $aEntries = $this->getAll("SELECT `id`, `" . $CNF['FIELD_PUBLISHED'] . "`, from_unixtime(`" . $CNF['FIELD_PUBLISHED'] . "`)  FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_STATUS'] . "` = 'awaiting'");
        if(empty($aEntries) || !is_array($aEntries))
            return false;

        $iNow = time();
        $aResult = array();
        foreach($aEntries as $aEntry)
            if($aEntry[$CNF['FIELD_PUBLISHED']] <= $iNow) 
                $aResult[] = $aEntry[$CNF['FIELD_ID']];

        return count($aResult) == (int)$this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET `" . $CNF['FIELD_ADDED'] . "`=`" . $CNF['FIELD_PUBLISHED'] . "`, `" . $CNF['FIELD_CHANGED'] . "`=`" . $CNF['FIELD_PUBLISHED'] . "`, `" . $CNF['FIELD_STATUS'] . "` = 'active' WHERE `id` IN (" . $this->implode_escape($aResult) . ")") ? $aResult : false;
    }
}

/** @} */
