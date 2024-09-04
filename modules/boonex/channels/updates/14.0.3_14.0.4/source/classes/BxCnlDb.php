<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Channels module database queries
 */
class BxCnlDb extends BxBaseModGroupsDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getChannelIdByName($sName)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->getOne("SELECT `" . $CNF['FIELD_ID'] . "` FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_NAME'] . "` = :name", array(
            'name' => $sName,
        ));
    }

    public function getChannelInfoByName($sName)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->getRow("SELECT `c`.*, `p`.`account_id`, `p`.`id` AS `profile_id`, `a`.`email` AS `profile_email`, `a`.`ip` AS `profile_ip`, `p`.`status` AS `profile_status` FROM `" . $CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `sys_profiles` AS `p` ON (`p`.`content_id` = `c`.`id` AND `p`.`type` = :type) INNER JOIN `sys_accounts` AS `a` ON (`p`.`account_id` = `a`.`id`) WHERE `c`.`channel_name` = :name", array(
            'type' => $this->_oConfig->getName(),
            'name' => $sName,
        ));
    }

    public function addContentToChannel($iContentId, $iCnlId, $sModuleName, $iAuthorId)
    {
        $CNF = &$this->_oConfig->CNF;

        $iNow = time();
        if(!$this->query("INSERT INTO `" . $CNF['TABLE_CONTENT'] . "` (`content_id`, `cnl_id`, `author_id`, `module_name`, `date`) VALUES (:content_id, :cnl_id, :author_id, :module_name, :date)", [
            'content_id' => $iContentId,
            'cnl_id' => $iCnlId,
            'module_name' => $sModuleName,
            'author_id' => $iAuthorId,
            'date' => $iNow
        ]))
            return 0;

        $iCnlContentId = $this->lastId();

        $this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET `lc_id`=:lc_id, `lc_date`=:lc_date, `contents`=:contents WHERE `" . $CNF['FIELD_ID'] . "`=:id", [
            'lc_id' => $iCnlContentId,
            'lc_date' => $iNow,
            'contents' => (int)$this->getOne("SELECT COUNT(*) FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `cnl_id`=:cnl_id LIMIT 1", ['cnl_id' => $iCnlId]),
            'id' => $iCnlId
        ]);

        return $iCnlContentId;
    }
    
    public function checkContentInChannel($iContentId, $iCnlId, $sModuleName, $iAuthorId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'content_id' => $iContentId,
            'cnl_id' => $iCnlId,
            'module_name' => $sModuleName,
            'author_id' => $iAuthorId,
        );
        return $this->getOne("SELECT COUNT(*) FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `content_id` = :content_id AND `module_name` = :module_name  AND `cnl_id` = :cnl_id AND `author_id` =:author_id", $aBindings);
    }
    
    public function getContentById($iId)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->getRow("SELECT * FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `id` = :id LIMIT 1", array(
            'id' => $iId
        ));
    }
    
    public function getDataByContent($iContentId, $sModuleName)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'content_id' => $iContentId,
            'module_name' => $sModuleName
        );
        return $this->getAll("SELECT `id`, `author_id`, `cnl_id` FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `content_id` = :content_id AND `module_name` = :module_name", $aBindings);
    }
    
    public function removeContentFromChannel($iContentId, $sModuleName)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = [
            'content_id' => $iContentId,
            'module_name' => $sModuleName
        ];

        $aContents = $this->getAll("SELECT * FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `content_id` = :content_id AND `module_name` = :module_name", $aBindings);
        if(empty($aContents) || !is_array($aContents))
            return;

        $this->query("DELETE FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `content_id` = :content_id AND `module_name` = :module_name", $aBindings);

        foreach($aContents as $aContent) {
            $iCnlId = (int)$aContent['cnl_id'];

            $aLatest = $this->getRow("SELECT * FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `cnl_id`=:cnl_id ORDER BY `date` DESC LIMIT 1", [
                'cnl_id' => $iCnlId
            ]);

            $iLcId = $iLcDate = 0;
            if(!empty($aLatest) && is_array($aLatest)) {
                $iLcId = (int)$aLatest['id'];
                $iLcDate = (int)$aLatest['date'];
            }

            $this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET `lc_id`=:lc_id, `lc_date`=:lc_date, `contents`=:contents WHERE `" . $CNF['FIELD_ID'] . "`=:id", [
                'lc_id' => $iLcId,
                'lc_date' => $iLcDate,
                'contents' => (int)$this->getOne("SELECT COUNT(*) FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `cnl_id`=:cnl_id LIMIT 1", ['cnl_id' => $iCnlId]),
                'id' => $iCnlId
            ]);
        }
    }
	
    public function removeChannelContent($iChannelId)
    {
        $CNF = &$this->_oConfig->CNF;

        $this->query("DELETE FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `cnl_id` = :channel_id", [
            'channel_id' => $iChannelId
        ]);
    }
}

/** @} */
