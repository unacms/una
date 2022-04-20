<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxTasksDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
	
	public function getLists ($iContextId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_LISTS'] . "` WHERE `context_id` = ?", $iContextId);
        return $this->getAll($sQuery);
    }
	
	public function getList ($iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_LISTS'] . "` WHERE `id` = ?", $iId);
        return $this->getRow($sQuery);
    }
    
    public function deleteList ($iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare ("DELETE FROM `" . $CNF['TABLE_LISTS'] . "` WHERE `id` = ?", $iId);
        $this->query($sQuery);
        
        $sQuery = $this->prepare ("DELETE FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_TASKLIST'] . "` = ?", $iId);
        $this->query($sQuery);
    }
	
    public function getTasks ($iContextId = 0, $iListId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = [
            $CNF['FIELD_ALLOW_VIEW_TO'] => $iContextId,
            $CNF['FIELD_TASKLIST'] => $iListId
        ];

        $sWhereClause = " AND `" . $CNF['FIELD_ALLOW_VIEW_TO'] . "` = :" . $CNF['FIELD_ALLOW_VIEW_TO'] . " AND `" . $CNF['FIELD_TASKLIST'] . "` = :" . $CNF['FIELD_TASKLIST'];

        $oCf = BxDolContentFilter::getInstance();
        if($oCf->isEnabled())
            $sWhereClause .= $oCf->getSQLParts($CNF['TABLE_ENTRIES'], $CNF['FIELD_CF']);

        return $this->getAll("SELECT * FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE 1" . $sWhereClause, $aBindings);
    }
	
    public function getEntriesByDate($sDateFrom, $sDateTo, $aSQLPart = array())
    {
        // validate input data
        if (false === ($oDateFrom = date_create($sDateFrom, new DateTimeZone('UTC'))))
            return array();
        if (false === ($oDateTo = date_create($sDateTo, new DateTimeZone('UTC'))))
            return array();
        if ($oDateFrom > $oDateTo)
            return array();

        // increase start and end date to cover timezones
        $oDateFrom = $oDateFrom->sub(new DateInterval("P1D"));
        $oDateTo = $oDateTo->add(new DateInterval("P1D"));

        // look throught all days in the interval
        $oDateIter = clone($oDateFrom);
        $aEntries = array();
        while ($oDateIter->format('Y-m-d') != $oDateTo->format('Y-m-d')) {

            $oDateMin = date_create($oDateIter->format('Y-m-d') . '00:00:00', new DateTimeZone('UTC'));
            $oDateMax = date_create($oDateIter->format('Y-m-d') . '23:59:59', new DateTimeZone('UTC'));
                
            // get all events for the specific day            
            $oDateMonthBegin = date_create($oDateIter->format('Y-m-01'), new DateTimeZone('UTC'));
            $iWeekOfMonth = $oDateIter->format('W') - $oDateMonthBegin->format('W') + 1;
            $aBindings = array(
                'timestamp_min' => $oDateMin->getTimestamp(),
                'timestamp_max' => $oDateMax->getTimestamp(),
              
            );

            $sWhere = isset($aSQLPart['where']) ? $aSQLPart['where'] : '';
            $a = $this->getAll("SELECT DISTINCT `bx_tasks_tasks`.`id`, `bx_tasks_tasks`.`title` AS `title`, `bx_tasks_tasks`.`due_date`
				FROM `bx_tasks_tasks`
                WHERE (`bx_tasks_tasks`.`due_date` >= :timestamp_min AND `bx_tasks_tasks`.`due_date` <= :timestamp_max ) $sWhere
            ", $aBindings);

            // prepare variables for each event
            $sCurrentDay = $oDateIter->format('Y-m-d');
            foreach ($a as $k => $r) {
                $oDateStart = new DateTime();
                $oDateStart->setTimestamp($r['due_date']);
                $oDateStart->setTimezone(new DateTimeZone('UTC'));
                $oDateEnd = new DateTime();
                $oDateEnd->setTimestamp($r['due_date']);
                $oDateEnd->setTimezone(new DateTimeZone('UTC'));
                $oDuration = $oDateStart->diff($oDateEnd);

                $sHoursStart = $oDateStart->format('H:i:s');

                $oStart = date_create($sCurrentDay . ' ' . $sHoursStart, new DateTimeZone('UTC'));
                $oEnd = $oStart ? clone($oStart) : null;
                $oEnd = $oEnd ? $oEnd->add($oDuration) : null;

                $a[$k]['start'] = $oStart ? $oStart->format('c') : 0;
                $a[$k]['end'] = $oEnd ? $oEnd->format('c') : 0;
                $a[$k]['start_utc'] = $oStart ? $oStart->getTimestamp() : 0;
                $a[$k]['end_utc'] = $oEnd ? $oEnd->getTimestamp() : 0;
                $a[$k]['url'] = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $r['id']);
            }

            // merge with all other events
            $aEntries = array_merge($aEntries, $a);

            // go to the next day
            $oDateIter = $oDateIter->add(new DateInterval("P1D"));
        }

        return $aEntries;
    }

    public function expireEntries()
    {
        $CNF = $this->_oConfig->CNF;

        $aResult = $this->getColumn("SELECT `id` FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_DUEDATE'] . "` < UNIX_TIMESTAMP()  AND `" . $CNF['FIELD_EXPIRED'] . "` = '0'");
        if(empty($aResult) || !is_array($aResult))
            return false;

        return count($aResult) == (int)$this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET `" . $CNF['FIELD_EXPIRED'] . "` = '1' WHERE `id` IN (" . $this->implode_escape($aResult) . ")") ? $aResult : false;
    }
}

/** @} */
