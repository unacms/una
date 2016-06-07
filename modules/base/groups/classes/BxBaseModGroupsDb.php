<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Groups module database queries
 */
class BxBaseModGroupsDb extends BxBaseModProfileDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function updateAuthorById ($iContentId, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = "UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `" . $CNF['FIELD_AUTHOR'] . "` = :author WHERE `" . $CNF['FIELD_ID'] . "` = :id";
        return $this->query($sQuery, array(
    		'id' => $iContentId,
    		'author' => $iProfileId,
    	));
    }

    public function toAdmins ($iGroupProfileId, $mixedFansIds)
    {
        if (is_array($mixedFansIds))
            foreach ($mixedFansIds as $iFanId)
                $this->toAdmins ($iFanId);

        $iFanId = (int)$mixedFansIds;
        $sQuery = $this->prepare("INSERT IGNORE INTO `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` SET `group_profile_id` = ?, `fan_id` = ?", $iGroupProfileId, $iFanId);
        return $this->res($sQuery);
    }

    public function fromAdmins ($iGroupProfileId, $mixedFansIds)
    {
        if (is_array($mixedFansIds))
            foreach ($mixedFansIds as $iFanId)
                $this->toAdmins ($iFanId);

        $iFanId = (int)$mixedFansIds;
        $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? AND `fan_id` = ?", $iGroupProfileId, $iFanId);
        return $this->res($sQuery);
    }

    public function deleteAdminsByGroupId ($iGroupProfileId)
    {
        $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ?", $iGroupProfileId);
        return $this->res($sQuery);
    }

    public function deleteAdminsByProfileId ($iProfileId)
    {
        $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `fan_id` = ?", $iProfileId);
        return $this->res($sQuery);
    }

    public function isAdmin ($iGroupProfileId, $iFanId, $aDataEntry = array())
    {
        if (isset($aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']]) && $iFanId == $aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']])
            return true;

        $sQuery = $this->prepare("SELECT `id` FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? AND `fan_id` = ?", $iGroupProfileId, $iFanId);
        return $this->getOne($sQuery) ? true : false;
    }

    public function getAdmins ($iGroupProfileId)
    {
        $sQuery = $this->prepare("SELECT `fan_id` FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ?", $iGroupProfileId);
        return $this->getColumn($sQuery);
    }
}

/** @} */
