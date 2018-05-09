<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Spaces module database queries
 */
class BxSpacesDb extends BxBaseModGroupsDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
    
    public function searchByTermForParentSpace($iProfileId, $iContentId, $iLevelsLimit, $sTerm, $iLimit)
    {
        if (!$this->_oConfig->CNF['FIELDS_QUICK_SEARCH'])
            return array();

        $aBindings = array(
            'type' => $this->_oConfig->getName(),
            'status' => BX_PROFILE_STATUS_ACTIVE,
            'ProfileId' => $iProfileId,
            'ContentId' => $iContentId,
            'LevelsLimit' => $iLevelsLimit
        );

        $sWhere = '';
        foreach ($this->_oConfig->CNF['FIELDS_QUICK_SEARCH'] as $sField) {
            $aBindings[$sField] = '%' . $sTerm . '%';

            $sWhere .= " OR `c`.`$sField` LIKE :" . $sField;
        }

        $sOrderBy = $this->prepareAsString(" ORDER BY `added` DESC LIMIT ?", (int)$iLimit);

        $sQuery = "SELECT `c`.`id` AS `content_id`, `p`.`account_id`, `p`.`id` AS `profile_id`, `p`.`status` AS `profile_status` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `sys_profiles` AS `p` ON (`p`.`content_id` = `c`.`id` AND `p`.`type` = :type) WHERE `p`.`status` = :status AND (`c`." . $this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO'] . " = 3 OR `c`." . $this->_oConfig->CNF['FIELD_AUTHOR'] . " = :ProfileId) AND `c`." . $this->_oConfig->CNF['FIELD_ID'] . " <> :ContentId AND `c`." . $this->_oConfig->CNF['FIELD_LEVEL'] . " < :LevelsLimit AND (0 $sWhere)" . $sOrderBy;
        return $this->getAll($sQuery, $aBindings);
    }
    
    public function getCountEntriesByParent ($iParent)
    {
        $aBindings = array(
            'content_id' => $iParent,
            'type' => $this->_oConfig->getName()
        );
        $sQuery = "SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_PARENT'] . "` IN (SELECT `id` FROM `sys_profiles` WHERE `content_id` = :content_id  AND `type` = :type)";
        return $this->getOne($sQuery, $aBindings);
    }
    
    public function getLevelById ($iId)
    {
        $sQuery = $this->prepare ("SELECT `" . $this->_oConfig->CNF['FIELD_LEVEL'] . "` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_ID'] . "` = ? ", $iId);
        return $this->getOne($sQuery);
    }
    
    public function getChildEntriesIdByProfileId ($iParent)
    {
        $aBindings = array(
            'content_id' => $iParent,
            'type' => $this->_oConfig->getName()
        );
        $sQuery = "SELECT `id` FROM `sys_profiles` WHERE `content_id` IN (SELECT `" . $this->_oConfig->CNF['FIELD_ID'] . "` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_PARENT'] . "` IN (SELECT `id` FROM `sys_profiles` WHERE `content_id` = :content_id AND `type` = :type)) AND `type` = :type";
        return $this->getColumn($sQuery, $aBindings);
    }
}

/** @} */
