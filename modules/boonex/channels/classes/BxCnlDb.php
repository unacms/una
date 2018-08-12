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
        $aBindings = array(
            'name' => $sName,
        );
        return $this->getOne("SELECT `" . $CNF['FIELD_ID'] . "` FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_NAME'] . "` = :name", $aBindings);
    }
    
    public function addContentToChannel($iContentId, $iCnlId, $sModuleName, $iAuthorId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'content_id' => $iContentId,
            'cnl_id' => $iCnlId,
            'module_name' => $sModuleName,
            'author_id' => $iAuthorId,
        );
        $this->query("INSERT INTO `" . $CNF['TABLE_CONTENT'] . "` (`content_id`, `cnl_id`, `module_name`, `author_id`) VALUES (:content_id, :cnl_id, :module_name, :author_id)", $aBindings);
        return $this->lastId();
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
        $aBindings = array(
            'id' => $iId
        );
        return $this->getFirstRow("SELECT * FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `id` = :id", $aBindings);
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
        $aBindings = array(
            'content_id' => $iContentId,
            'module_name' => $sModuleName
        );
        $this->query("DELETE FROM `" . $CNF['TABLE_CONTENT'] . "` WHERE `content_id` = :content_id AND `module_name` = :module_name", $aBindings);
    }
}

/** @} */
