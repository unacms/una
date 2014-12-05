<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolDb');

/**
 * All queries related to profiles
 */
class BxDolProfileQuery extends BxDolDb implements iBxDolSingleton
{
    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolProfileQuery();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Get all account profiles.
     * @param  string  $iAccountId account id
     * @return profile array
     */
    public function getProfilesByAccount ($iAccountId)
    {
        bx_import('BxDolAccountQuery');
        $oAccountQuery = BxDolAccountQuery::getInstance();
        $aAccountInfo = $oAccountQuery->getInfoById($iAccountId);

        $sSql = $this->prepare("SELECT * FROM `sys_profiles` WHERE `account_id` = ? ORDER BY `id` = ? DESC", $iAccountId, $aAccountInfo['profile_id']);
        return $this->getAllWithKey($sSql, 'id');
    }

    /**
     * Get profile by content id, type and account.
     * @param  string $iAccountId account id
     * @return array  if aprofile ids, key is profile id
     */
    public function getProfileByContentTypeAccount ($iContentId, $sType, $iAccountId)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_profiles` WHERE `account_id` = ? AND `type` = ? AND `content_id` = ?", $iAccountId, $sType, $iContentId);
        return $this->getRow($sSql);
    }

    /**
     * Get profile by content id and type.
     * @param  string $iAccountId account id
     * @return array  if aprofile ids, key is profile id
     */
    public function getProfileByContentAndType ($iContentId, $sType)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_profiles` WHERE `content_id` = ? AND `type` = ?", $iContentId, $sType);
        return $this->getRow($sSql);
    }

    /**
     * Insert account and content id association. Also if currect profile id is not defined - it updates current profile id in account.
     * @param $iAccountId account id
     * @param $iContentId content id
     * @param $sStatus profile status
     * @param $sType profile content type
     * @return inserted profile's id
     */
    public function insertProfile ($iAccountId, $iContentId, $sStatus, $sType = 'system')
    {
        $sSql = $this->prepare("INSERT INTO `sys_profiles` SET `account_id` = ?, `type` = ?, `content_id` = ?, `status` = ?", $iAccountId, $sType, $iContentId, $sStatus);
        if (!$this->query($sSql))
            return false;
        $iProfileId = $this->lastId();
        $this->getCurrentProfileByAccount($iAccountId); // it updates current profile id automatically if it is not defined
        return $iProfileId;
    }

    /**
     * Update profile's status
     * @param $iProfileId profile id
     * @param $sStatus profile status
     * @return true on success or false otherwise
     */
    public function changeStatus ($iProfileId, $sStatus)
    {
        return $this->_updateField ($iProfileId, 'status', $sStatus);
    }

    /**
     * Get current account profile.
     * @param  string  $iAccountId account id
     * @return current account's profile id
     */
    public function getCurrentProfileByAccount ($iAccountId)
    {
        $sSql = $this->prepare("SELECT `profile_id` FROM `sys_accounts` WHERE `id` = ? LIMIT 1", $iAccountId);
        $iProfileId = $this->getOne($sSql);
        if (!$iProfileId) {
            $sSql = $this->prepare("SELECT `id` FROM `sys_profiles` WHERE `account_id` = ? LIMIT 1", $iAccountId);
            $iProfileId = $this->getOne($sSql);
            if (!$iProfileId)
                return false;

            bx_import('BxDolAccountQuery');
            if (!BxDolAccountQuery::getInstance()->updateCurrentProfile($iAccountId, $iProfileId))
                return false;
        }
        return $iProfileId;
    }

    /**
     * Get profile by specified field name and value.
     * It is for internal usage only.
     * Use other funtions to get profile info, like getInfoById, etc.
     * @param  string $sField database field name
     * @param  mixed  $sValue database field value
     * @return array  with porfile info
     */
    protected function _getDataByField ($sField, $sValue)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_profiles` WHERE `$sField` = ? LIMIT 1", $sValue);
        return $this->getRow($sSql);
    }

    /**
     * get profile id by id
     */
    public function getIdById($iId)
    {
        return (int)$this->_getFieldByField('id', 'id', $iId);
    }

    /**
     * get account info by profile id
     */
    public function getAccountInfoByProfileId($iId)
    {
        $sSql = $this->prepare("SELECT `a`.* FROM `sys_accounts` AS `a` INNER JOIN `sys_profiles` AS `p` ON (`p`.`account_id` = `a`.`id`) WHERE `p`.`id` = ? LIMIT 1", $iId);
        return $this->getRow($sSql);
    }

    /**
     * get account email by profile id
     */
    public function getEmailById($iId)
    {
        $a = $this->getAccountInfoByProfileId($iId);
        if (!$a || empty($a['email']))
            return false;
        return $a['email'];
    }

    /**
     * Get profile info by id
     * @param  int   $iID profile id
     * @return array with profile info
     */
    public function getInfoById( $iID )
    {
        return $this->_getDataByField('id', (int)$iID);
    }

    /**
     * Get profile field by specified field name and value.
     * In most cases it is for internal usage only.
     * Use other funtions to get profile info, like getIdByEmail, etc.
     * @param  string    $sFieldRequested database field name to return
     * @param  string    $sFieldSearch    database field name to search for
     * @param  mixed     $sValue          database field value
     * @return specified profile field value
     */
    protected function _getFieldByField ($sFieldRequested, $sFieldSearch, $sValue)
    {
        $sSql = $this->prepare("SELECT `$sFieldRequested` FROM `sys_profiles` WHERE `$sFieldSearch` = ? LIMIT 1", $sValue);
        return $this->getOne($sSql);
    }

    /**
     * Update some field by profile id
     * In most cases it is for internal usage only.
     * Use other funtions to get profile info, like updateLogged, etc.
     * @param  string    $sFieldRequested database field name to return
     * @param  string    $sFieldSearch    database field name to search for
     * @param  mixed     $sValue          database field value
     * @return specified profile field value
     */
    protected function _updateField ($iId, $sFieldForUpdate, $sValue)
    {
        $sSql = $this->prepare("UPDATE `sys_profiles` SET `$sFieldForUpdate` = ? WHERE `id` = ? LIMIT 1", $sValue, $iId);
        return $this->query($sSql);
    }

    /**
     * Delete profile info by id
     * @param  int      $iID profile id
     * @return affected rows
     */
    public function delete($iID)
    {
        $sSql = $this->prepare("DELETE FROM `sys_profiles` WHERE `id` = ? LIMIT 1", $iID);
        return $this->query($sSql);
    }

    /**
     * Reset deleted profile ids and assign system profile ids.
     * Should be called after profiles module deletion.
     * It can be called automatically if 
     * @code
     * 'process_deleted_profiles' => 1 
     * @code
     * is specified in module config.php file in 'uninstall' section.
     */
    public function processDeletedProfiles ()
    {
        $this->query("UPDATE  `sys_accounts` AS  `a` LEFT OUTER JOIN  `sys_profiles` AS  `p` ON  `a`.`profile_id` =  `p`.`id` SET  `a`.`profile_id` =0 WHERE  `p`.`id` IS NULL"); // reset deleted profiles
        return $this->query("UPDATE  `sys_accounts` AS  `a` INNER JOIN  `sys_profiles` AS  `p` ON  `a`.`id` =  `p`.`account_id` AND  `p`.`type` =  'system' SET  `a`.`profile_id` =  `p`.`id`"); // assign system profile to reset accounts
    }
}

/** @} */
