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

        $oModule->doAudit($iGroupProfileId, $iFanId, '_sys_audit_action_group_to_admins');
        
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
        
        $oModule->doAudit($iGroupProfileId, $iFanId, '_sys_audit_action_group_from_admins');
        
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
    
    public function insertInvite($sKey, $sGroupProfileId, $iAuthorProfileId, $iInvitedProfileId)
    {
        $aBindings = array(
            'key' => $sKey,
            'group_profile_id' => $sGroupProfileId,
            'author_profile_id' => $iAuthorProfileId,
            'invited_profile_id' => $iInvitedProfileId,
            'added' => time()
        );
        $CNF = $this->_oConfig->CNF; 
        $this->query("INSERT `" . $CNF["TABLE_INVITES"] . "` (`key`, `group_profile_id`, `author_profile_id`, `invited_profile_id`, `added`) VALUES (:key, :group_profile_id, :author_profile_id, :invited_profile_id, :added)", $aBindings);
        return (int)$this->lastId();
    }
    
    public function getInviteByKey($sKey, $iGroupProfileId)
    {
        $aBindings = array(
            'key' => $sKey,
            'group_profile_id' => $iGroupProfileId
        );
        $CNF = $this->_oConfig->CNF; 
        return $this->getRow("SELECT * FROM `" . $CNF["TABLE_INVITES"] . "` WHERE `key` = :key AND group_profile_id = :group_profile_id", $aBindings);
    }
    
    public function getInviteByInvited($iInvitedProfileId, $iGroupProfileId)
    {
        $aBindings = array(
            'invited_profile_id' => $iInvitedProfileId,
            'group_profile_id' => $iGroupProfileId
        );
        $CNF = $this->_oConfig->CNF; 
        return $this->getOne("SELECT COUNT(*) FROM `" . $CNF["TABLE_INVITES"] . "` WHERE `invited_profile_id` = :invited_profile_id AND group_profile_id = :group_profile_id", $aBindings);
    }
    
    public function updateInviteByKey($sKey, $iGroupProfileId, $sColumn, $sValue)
    {
        $aBindings = array(
           'key' => $sKey,
           'value' => $sValue,
           'group_profile_id' => $iGroupProfileId
       );
        $CNF = $this->_oConfig->CNF; 
        return $this->query("UPDATE `" . $CNF["TABLE_INVITES"] . "` SET `" . $sColumn . "` = :value WHERE `key` = :key AND group_profile_id = :group_profile_id", $aBindings);
    }
    
    public function deleteInviteByKey($sKey, $iGroupProfileId)
    {
        $aBindings = array(
           'key' => $sKey,
           'group_profile_id' => $iGroupProfileId
       );
        $CNF = $this->_oConfig->CNF; 
        return $this->query("DELETE FROM `" . $CNF["TABLE_INVITES"] . "` WHERE `key` = :key AND group_profile_id = :group_profile_id", $aBindings);
    }
    
    public function deleteInvite($iId)
    {
        $aBindings = array(
           'id' => $iId
       );
        $CNF = $this->_oConfig->CNF; 
        return $this->query("DELETE FROM `" . $CNF["TABLE_INVITES"] . "` WHERE `id` = :id", $aBindings);
    }
}

/** @} */
