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

    public function resetContentPictureByFileId($iFileId, $sFieldPicture)
    {
        return $this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `" . $sFieldPicture . "` = 0 WHERE `" . $sFieldPicture . "` = :file", [
    		'file' => $iFileId,
        ]);
    }

    public function updateContentPictureById($iContentId, $iProfileId, $iPictureId, $sFieldPicture)
    {
    	$aBindings = array(
    		'pic' => $iPictureId,
    		'id' => $iContentId
    	);

        $sWhere = '';
        if ($iProfileId) {
        	$aBindings['author'] = $iProfileId;

            $sWhere = " AND `author` = :author ";
        }

        $sQuery = "UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `" . $sFieldPicture . "` = :pic WHERE `id` = :id" . $sWhere;
        return $this->query($sQuery, $aBindings);
    }

    public function searchByTerm($sTerm, $iLimit)
    {
        if (!$this->_oConfig->CNF['FIELDS_QUICK_SEARCH'])
            return array();

		$aBindings = array(
			'type' => $this->_oConfig->getName(),
			'status' => BX_PROFILE_STATUS_ACTIVE
		);

        $sWhere = '';
        foreach ($this->_oConfig->CNF['FIELDS_QUICK_SEARCH'] as $sField) {
        	$aBindings[$sField] = '%' . $sTerm . '%';

            $sWhere .= " OR `c`.`$sField` LIKE :" . $sField;
        }

        $sOrderBy = $this->prepareAsString(" ORDER BY `added` DESC LIMIT ?", (int)$iLimit);

        $sQuery = "SELECT `c`.`id` AS `content_id`, `p`.`account_id`, `p`.`id` AS `profile_id`, `p`.`status` AS `profile_status` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `sys_profiles` AS `p` ON (`p`.`content_id` = `c`.`id` AND `p`.`type` = :type) WHERE `p`.`status` = :status AND (0 $sWhere)" . $sOrderBy;
        return $this->getAll($sQuery, $aBindings);
    }
}

/** @} */
