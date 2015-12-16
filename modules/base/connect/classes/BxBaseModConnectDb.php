<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModConnectDb extends BxBaseModGeneralDb
{
    protected $sTablePrefix;

    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this -> sTablePrefix = $oConfig -> getDbPrefix();
    }
    
    /**
     * Check remote profile id
     *
     * @param $iRemoteId integer
     * @return local profile id
     */
    function getProfileId($iRemoteId)
    {
        $iRemoteId = (int) $iRemoteId;

        $sQuery = $this->prepare ("SELECT `local_profile` FROM `{$this -> sTablePrefix}accounts` WHERE `remote_profile` = ? LIMIT 1", $iRemoteId);
        return $this -> getOne($sQuery);
    }

    /**
     * Save new remote ID
     *
     * @param $iProfileId integer
     * @param $iRemoteId integer
     * @return bool
     */
    function saveRemoteId($iProfileId, $iRemoteId)
    {
        $iRemoteId = (int) $iRemoteId;
        $iProfileId = (int) $iProfileId;

        $sQuery = $this->prepare ("REPLACE INTO `{$this -> sTablePrefix}accounts` SET `local_profile` = ?, `remote_profile` = ?", $iProfileId, $iRemoteId);
        return $this -> query($sQuery);
    }

    /**
     * Delete remote account
     *
     * @param $iProfileId integer
     * @return void
     */
    function deleteRemoteAccount($iProfileId)
    {
        $iProfileId = (int) $iProfileId;

        $sQuery = $this->prepare ("DELETE FROM `{$this -> sTablePrefix}accounts` WHERE `local_profile` = ?", $iProfileId);
        return $this -> query($sQuery);
    }

    /**
     * Make as friends
     *
     * @param $iMemberId integer
     * @param $iProfileId intger
     * @return void
     */
    function makeFriend($iMemberId, $iProfileId)
    {
        // TODO:
        return null;
    }

    /**
     * Create new profile;
     *
     * @param  : (array) $aProfileFields    - `Profiles` table's fields;
     * @return : (integer)  - profile's Id;
     */
    function createProfile(&$aProfileFields)
    {
        // TODO:
        return null;
    }

    /**
     * Function will update  profile's status;
     *
     * @param  : $iProfileId (integer) - profile's Id;
     * @param  : $sStatus    (string)  - profile's status;
     * @return : void;
     */
    function updateProfileStatus($iProfileId, $sStatus)
    {
        // TODO:
        return null;
    }

    /**
     * Function will check field name in 'Profiles` table;
     *
     * @param $sFieldName string
     * @return : (boolean);
     */
    function isFieldExist($sFieldName)
    {
        // TODO:
        return null;
    }

    /**
     * Check existing email
     *
     * @param $sEmail string
     * @return boolean
     */
    function isEmailExisting($sEmail)
    {
        // TODO:
        return null;
    }
}

/** @} */
