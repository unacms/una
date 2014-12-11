<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralDb');

/*
 * Profile based module database queries
 */
class BxBaseModProfileDb extends BxBaseModGeneralDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getContentInfoById ($iContentId)
    {
        $sQuery = $this->prepare ("SELECT `c`.*, `p`.`account_id`, `p`.`id` AS `profile_id`, `p`.`status` AS `profile_status` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `sys_profiles` AS `p` ON (`p`.`content_id` = `c`.`id` AND `p`.`type` = ?) WHERE `c`.`id` = ?", $this->_oConfig->getName(), $iContentId);
        return $this->getRow($sQuery);
    }

    public function updateContentPictureById($iContentId, $iProfileId, $iPictureId, $sFieldPicture)
    {
        $sQuery = $this->prepare ("UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `" . $sFieldPicture . "` = ? WHERE `id` = ? AND `author` = ?", $iPictureId, $iContentId, $iProfileId);
        return $this->res($sQuery);
    }

    public function searchByTerm($sTerm, $iLimit)
    {
        if (!$this->_oConfig->CNF['FIELDS_QUICK_SEARCH'])
            return array();

        $sWhere = '';
        foreach ($this->_oConfig->CNF['FIELDS_QUICK_SEARCH'] as $sField)
            $sWhere .= $this->prepare(" OR `c`.`$sField` LIKE ? ", '%' . $sTerm . '%');

        $sOrderBy = $this->prepare(" ORDER BY `added` DESC LIMIT ?", (int)$iLimit);

        $sQuery = $this->prepare("SELECT `c`.`id` AS `content_id`, `p`.`account_id`, `p`.`id` AS `profile_id`, `p`.`status` AS `profile_status` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `sys_profiles` AS `p` ON (`p`.`content_id` = `c`.`id` AND `p`.`type` = ?) WHERE `p`.`status` = ?", $this->_oConfig->getName(), BX_PROFILE_STATUS_ACTIVE);
        return $this->getAll($sQuery . " AND (0 $sWhere) " . $sOrderBy);
    }
}

/** @} */
