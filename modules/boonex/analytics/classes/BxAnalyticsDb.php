<?php defined('BX_DOL') or die('hack attempt');
/**
* Copyright (c) UNA, Inc - https://una.io
* MIT License - https://opensource.org/licenses/MIT
*
* @defgroup    Analytics Analytics
* @ingroup     UnaModules
*
* @{
*/

/*
 * Module database queries
 */
class BxAnalyticsDb extends BxBaseModGeneralDb
{
    public function getGrowth($sTableName, $sColumnAdded, $iDateFrom, $iDateTo)
    {
        $aBindings = array(
            'datefrom' => $iDateFrom,
            'dateto' => $iDateTo
        );
        $sQuery = "SELECT DATE(FROM_UNIXTIME(`" . $sColumnAdded . "`)) AS `period`, YEAR(FROM_UNIXTIME(`" . $sColumnAdded . "`)) AS `year`, COUNT(*) AS `count` FROM " . $sTableName . " WHERE `" . $sColumnAdded . "` >= :datefrom AND `" . $sColumnAdded . "` <= :dateto GROUP BY `period`, `year` ORDER BY `year`, `period` ASC";
        return $this->getAll($sQuery, $aBindings);
    }
          
    public function getGrowthInitValue($sTableName, $sColumnAdded, $iDateFrom)
    {
        $aBindings = array(
            'datefrom' => $iDateFrom
        );
        $sQuery = "SELECT COUNT(*) AS `count` FROM " . $sTableName . " WHERE `" . $sColumnAdded . "` < :datefrom ";
        return $this->getOne($sQuery, $aBindings);
    }
          
    public function getTopContentByLikes($sModuleName, $sTableName, $iDateFrom, $iDateTo)
    {
        $aBindings = array(
            'datefrom' => $iDateFrom,
            'dateto' => $iDateTo
        );
        $sQuery = "SELECT `object_id`,'" . $sModuleName . "' AS `module`, COUNT(*) AS `value` FROM `" . $sTableName . "` WHERE `date` >= :datefrom AND `date` <= :dateto GROUP BY `object_id` ORDER BY COUNT(*) DESC LIMIT 0, " . intval(getParam('bx_analytics_items_count'));
        return $this->getAll($sQuery, $aBindings);
    }
          
    public function getMostFollowedProfiles($sModuleName, $iDateFrom, $iDateTo)
    {
        $aBindings = array(
            'datefrom' => $iDateFrom,
            'dateto' => $iDateTo
        );
        $sQuery = "SELECT `sys_profiles`.`content_id` AS `object_id`, `sys_profiles`.`type` , COUNT(`sys_profiles_conn_subscriptions`.`id`) AS `value` FROM `sys_profiles_conn_subscriptions`
INNER JOIN `sys_profiles` ON `sys_profiles`.`id` = `sys_profiles_conn_subscriptions`.`content` WHERE sys_profiles.type='" . $sModuleName . "' AND `sys_profiles_conn_subscriptions`.`added` >= :datefrom AND `sys_profiles_conn_subscriptions`.`added` <= :dateto GROUP BY `sys_profiles`.`content_id`, `sys_profiles`.`type` ORDER BY COUNT(`sys_profiles_conn_subscriptions`.`id`) DESC LIMIT 0," . intval(getParam('bx_analytics_items_count'));
        return $this->getAll($sQuery, $aBindings);
    }
          
    public function getMostActiveProfiles($sModuleName, $sContentModuleName, $sTableName, $sColumnAuthor, $sColumnAdded, $iDateFrom, $iDateTo)
    {
        $aBindings = array(
            'datefrom' => $iDateFrom,
            'dateto' => $iDateTo
        );
        $sQuery = "SELECT `sys_profiles`.`content_id` AS `object_id`, `sys_profiles`.`type`, '" . $sContentModuleName . "' , COUNT(`" . $sTableName . "`.`id`) AS `value` FROM `" . $sTableName . "`
INNER JOIN `sys_profiles` ON `sys_profiles`.`id` = `" . $sTableName . "`.`" . $sColumnAuthor . "` WHERE sys_profiles.type='" . $sModuleName . "' AND `" . $sTableName . "`.`" . $sColumnAdded . "` >= :datefrom AND `" . $sTableName . "`.`" . $sColumnAdded . "` <= :dateto GROUP BY `sys_profiles`.`content_id`, `sys_profiles`.`type` ORDER BY COUNT(`" . $sTableName . "`.`id`) DESC LIMIT 0," . intval(getParam('bx_analytics_items_count'));
        return $this->getAll($sQuery, $aBindings);
    }
}

/** @} */
