<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Groups module database queries
 */
class BxGroupsDb extends BxBaseModProfileDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
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

    public function deleteAdmins ($iGroupProfileId)
    {
        $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ?", $iGroupProfileId);
        return $this->res($sQuery);
    }

    public function isAdmin ($iGroupProfileId, $iFanId)
    {
        $sQuery = $this->prepare("SELECT `id` FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? AND `fan_id` = ?", $iGroupProfileId, $iFanId);
        return $this->getOne($sQuery) ? true : false;
    }
}

/** @} */
