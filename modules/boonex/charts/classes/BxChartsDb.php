<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Charts Charts
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

class BxChartsDb extends BxBaseModGeneralDb
{
    protected $_sTableTopByLikes;
    protected $_sTableMostActiveProfiles;
    
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
        $this->_sTableTopByLikes = $this->_sPrefix . 'top_by_likes';
        $this->_sTableMostActiveProfiles = $this->_sPrefix . 'most_active_profiles';
    }
    
    public function saveTopByLikes($sModuleName, $sTableName)
    {
        $sQuery = "INSERT INTO `" . $this->_sTableTopByLikes . "` (`object_id`, `module`, `value`) SELECT `object_id`,'" . $sModuleName . "',`count` FROM `" . $sTableName . "` ORDER BY `count` DESC LIMIT 0, " . intval(getParam('bx_charts_chart_top_contents_by_likes_count')) . "";
        $this->query($sQuery);
    }
    
    public function clearTopByLikes()
    {
        $sQuery = "TRUNCATE TABLE `" . $this->_sTableTopByLikes . "`";
        $this->query($sQuery);
    }
    
    public function getTopByLikes()
    {
        return $this->getAll("SELECT * FROM  `" . $this->_sTableTopByLikes . "` ORDER BY `value` DESC LIMIT 0," . intval(getParam('bx_charts_chart_top_contents_by_likes_count')));
    }
    
    public function saveMostActiveProfiles_View($sProfileModuleName, $sTableName)
    {
        $sQuery = "INSERT INTO `" . $this->_sTableMostActiveProfiles . "` (`object_id`, `profile_module`, `views_count`) SELECT `object_id`,'" . $sProfileModuleName . "', COUNT(`date`) AS views FROM `" . $sTableName . "` 
 WHERE `object_id` IN (SELECT `object_id` FROM `" . $this->_sTableMostActiveProfiles . "` WHERE `profile_module` = '" . $sProfileModuleName . "' ) GROUP BY `object_id`";
        $this->query($sQuery);
    }
    
    public function saveMostActiveProfiles_Create($sProfileModuleName, $sContentModuleName, $sTableName, $sColumnAuthor)
    {
        $sQuery = "INSERT INTO `" . $this->_sTableMostActiveProfiles . "` (`object_id`, `profile_module`, `content_module`, `create_count`)  SELECT `sys_profiles`.`content_id`, `sys_profiles`.`type`, '" . $sContentModuleName . "' , COUNT(`" . $sTableName . "`.`id`) FROM `" . $sTableName . "`
  INNER JOIN `sys_profiles`  ON `sys_profiles`.`id` = `" . $sTableName . "`.`" . $sColumnAuthor . "` WHERE sys_profiles.type='" . $sProfileModuleName . "' GROUP BY `sys_profiles`.`content_id`, `sys_profiles`.`type` ORDER BY COUNT(`" . $sTableName . "`.`id`) DESC LIMIT 0," . intval(getParam('bx_charts_chart_most_active_profiles_count'));
        $this->query($sQuery);
    }
    
    public function clearMostActiveProfiles()
    {
        $sQuery = "TRUNCATE TABLE `" . $this->_sTableMostActiveProfiles . "`";
        $this->query($sQuery);
    }
    
    public function getMostActiveProfiles()
    {
        return $this->getAll("SELECT SUM(`views_count`) AS views_count, SUM(`create_count`) AS create_count, `object_id`, `profile_module` as `module` FROM `bx_charts_most_active_profiles` GROUP BY `object_id`, `module` ORDER BY SUM(`create_count`) DESC, SUM(`views_count`) DESC LIMIT 0," . intval(getParam('bx_charts_chart_most_active_profiles_count')));
    }
}

/** @} */
