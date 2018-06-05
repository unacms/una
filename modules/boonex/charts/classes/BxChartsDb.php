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
        $sQuery = "INSERT INTO `" . $this->_sTableTopByLikes . "` (`object_id`, `module`, `value`) SELECT `object_id`,'" . $sModuleName . "',`sum` FROM `" . $sTableName . "`";
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
    
    public function saveMostActiveProfiles_View($sModuleName, $sTableName)
    {
        $sQuery = "INSERT INTO `" . $this->_sTableMostActiveProfiles . "` (`object_id`, `module`, `views_count`) SELECT `object_id`,'" . $sModuleName . "', COUNT(`date`) AS views FROM `" . $sTableName . "` 
GROUP BY `object_id`";
        $this->query($sQuery);
    }
    
    public function saveMostActiveProfiles_Create($sModuleName, $sTableName, $sColumnAuthor)
    {
        $sQuery = "INSERT INTO `" . $this->_sTableMostActiveProfiles . "` (`object_id`, `module`, `create_count`)  SELECT `sys_profiles`.`content_id`, `sys_profiles`.`type`, COUNT(`" . $sTableName . "`.`id`) FROM `" . $sTableName . "`
  INNER JOIN `sys_profiles`  ON `sys_profiles`.`id` = `" . $sTableName . "`.`" . $sColumnAuthor . "` WHERE sys_profiles.type='" . $sModuleName . "' GROUP BY `sys_profiles`.`content_id`, `sys_profiles`.`type`";
        $this->query($sQuery);
    }
    
    public function clearMostActiveProfiles()
    {
        $sQuery = "TRUNCATE TABLE `" . $this->_sTableMostActiveProfiles . "`";
        $this->query($sQuery);
    }
    
    public function getMostActiveProfiles()
    {
        return $this->getAll("SELECT SUM(`views_count`) AS views_count, SUM(`create_count`) AS create_count, `object_id`, `module` FROM `bx_charts_most_active_profiles` GROUP BY `object_id`, `module` ORDER BY SUM(`create_count`) DESC, SUM(`views_count`) DESC LIMIT 0," . intval(getParam('bx_charts_chart_most_active_profiles_count')));
    }
}

/** @} */
