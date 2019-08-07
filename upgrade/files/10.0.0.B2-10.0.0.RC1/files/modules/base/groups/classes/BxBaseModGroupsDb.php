<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
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
        if (is_array($mixedFansIds)) {
            foreach ($mixedFansIds as $iFanId)
                $this->toAdmins ($iFanId);
            return true;
        }

        $iFanId = (int)$mixedFansIds;
        $sQuery = $this->prepare("INSERT IGNORE INTO `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` SET `group_profile_id` = ?, `fan_id` = ?", $iGroupProfileId, $iFanId);
        if (!$this->res($sQuery))
            return false;

        $oModule = BxDolModule::getInstance($this->_oConfig->getName());
        if ($oModule && method_exists($oModule, 'onFanAddedToAdmins'))
            $oModule->onFanAddedToAdmins($iGroupProfileId, $iFanId);

        return true;
    }

    public function fromAdmins ($iGroupProfileId, $mixedFansIds)
    {
        if (is_array($mixedFansIds)) {
            foreach ($mixedFansIds as $iFanId)
                $this->fromAdmins ($iGroupProfileId, $iFanId);
            return true;
        }

        $iFanId = (int)$mixedFansIds;
        $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? AND `fan_id` = ?", $iGroupProfileId, $iFanId);
        if (!$this->res($sQuery))
            return false;

        $oModule = BxDolModule::getInstance($this->_oConfig->getName());
        if ($oModule && method_exists($oModule, 'onFanRemovedFromAdmins'))
            $oModule->onFanRemovedFromAdmins($iGroupProfileId, $iFanId);
        
        return true;
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
        if (!isset($this->_oConfig->CNF['TABLE_ADMINS']))
            return false;
        $sQuery = $this->prepare("SELECT `id` FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? AND `fan_id` = ?", $iGroupProfileId, $iFanId);
        return $this->getOne($sQuery) ? true : false;
    }

    public function getAdmins ($iGroupProfileId, $iStart = 0, $iLimit = 0)
    {
		$sQuery = $this->prepare("SELECT `fan_id` FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ?", $iGroupProfileId);
		if ($iLimit > 0)
            $sQuery = $this->prepare("SELECT `fan_id` FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? LIMIT ?, ?", $iGroupProfileId, $iStart, $iLimit);
        return $this->getColumn($sQuery);
    }
}

/** @} */
