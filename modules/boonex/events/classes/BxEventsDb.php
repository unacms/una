<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Events Events
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Groups module database queries
 */
class BxEventsDb extends BxBaseModGroupsDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getIntervals ($iContentId) 
    {
        return $this->getAllWithKey("SELECT * FROM `bx_events_intervals` WHERE `event_id` = :event_id", 'interval_id', array(
            'event_id' => $iContentId,
        ));
    }

    public function getEntriesByDate($sDateStart, $sDateEnd)
    {
        // validate input data
        if (false === ($oDateStart = date_create($sDateStart, new DateTimeZone('UTC'))))
            return array();
        if (false === ($oDateEnd = date_create($sDateEnd, new DateTimeZone('UTC'))))
            return array();
        if ($oDateStart > $oDateEnd)
            return array();

        // increase start and end date to cover timezones
        $oDateStart = $oDateStart->sub(new DateInterval("P1D"));
        $oDateEnd = $oDateEnd->add(new DateInterval("P1D"));

        // look throught all days in the interval
        $oDateIter = clone($oDateStart);
        $aEntries = array();
        while ($oDateIter->format('Y-m-d') != $oDateEnd->format('Y-m-d')) {

            $oDateMin = date_create($oDateIter->format('Y-m-d') . '00:00:00', new DateTimeZone('UTC'));
            $oDateMax = date_create($oDateIter->format('Y-m-d') . '23:59:59', new DateTimeZone('UTC'));
                
            // get all events for the specific day
            $oDateMonthBegin = date_create($oDateIter->format('Y-m-01'), new DateTimeZone('UTC'));
            $a = $this->getAll("SELECT DISTINCT `e`.`id`, `e`.`event_name` AS `title`, `e`.`date_start`, `e`.`date_end`, `e`.`timezone`
                FROM `bx_events_data` AS `e`
                LEFT JOIN `bx_events_intervals` AS `i` ON (
                    `e`.`id` = `i`.`event_id`
                    AND
                    `e`.`date_start` <= :timestamp_min
                    AND
                    `i`.`repeat_stop` >= :timestamp_max
                    AND (
                        (0 = `i`.`repeat_year` OR 0 = (:year - :year_start) % `i`.`repeat_year`)
                        AND 
                        (0 = `i`.`repeat_month` OR  :month = `i`.`repeat_month`)
                        AND 
                        (0 = `i`.`repeat_week_of_month` OR :week_of_month = `i`.`repeat_week_of_month`)
                        AND 
                        (0 = `i`.`repeat_day_of_month` OR :day_of_month = `i`.`repeat_day_of_month`)
                        AND 
                        (0 = `i`.`repeat_day_of_week` OR :day_of_week = `i`.`repeat_day_of_week`)
                    )
                )
                WHERE (`e`.`date_start` >= :timestamp_min AND `e`.`date_end` <= :timestamp_max AND `i`.`interval_id` IS NULL) OR (`i`.`interval_id` IS NOT NULL)
            ", array(
                'timestamp_min' => $oDateMin->getTimestamp(),
                'timestamp_max' => $oDateMax->getTimestamp(),
                'year_start' => $oDateStart->format('Y'),
                'year' => $oDateIter->format('Y'),
                'month' => $oDateIter->format('n'),
                'week_of_month' => $oDateIter->format('W') - $oDateMonthBegin->format('W') + 1,
                'day_of_month' => $oDateIter->format('j'),
                'day_of_week' => $oDateIter->format('N'),
            ));

            // prepare variables for each event
            $sCurrentDay = $oDateIter->format('Y-m-d');
            foreach ($a as $k => $r) {
                // TODO: check permissions
                $sHoursStart = date('H:i:s', $r['date_start']);
                $sHoursEnd = date('H:i:s', $r['date_end']);
                $oStart = date_create($sCurrentDay . ' ' . $sHoursStart, new DateTimeZone($r['timezone']));
                $oEnd = date_create($sCurrentDay . ' ' . $sHoursEnd, new DateTimeZone($r['timezone']));
                $a[$k]['start'] = $oStart ? $oStart->format('c') : 0;
                $a[$k]['end'] = $oEnd ? $oEnd->format('c') : 0;
                $a[$k]['url'] = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $r['id']);
            }

            // merge with all other events
            $aEntries = array_merge($aEntries, $a);

            // go to the next day
            $oDateIter = $oDateIter->add(new DateInterval("P1D"));
        }

        return $aEntries;
    }
}

/** @} */
