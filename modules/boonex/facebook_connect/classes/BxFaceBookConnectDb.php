<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    FacebookConnect Facebook Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxFaceBookConnectDb extends BxBaseModConnectDb
{
    /**
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    /**
     * Process big number
     *
     * @param $mValue mixed
     * @return integer
     */
    function _processBigNumber($mValue)
    {
        return preg_replace('/[^0-9]/', '', $mValue);
    }

    /**
     * Check fb profile id
     *
     * @param $iFbUid integer
     * @return integer
     */
    function getProfileId($iFbUid)
    {
        $iFbUidCopy = (int) $iFbUid;
        $iFbUid = $this -> _processBigNumber($iFbUid);


        //-- handle 64 bit number on 32bit system ( will need remove it in a feature version)--//
        if($iFbUidCopy != $iFbUid) {
            //update id
            $sQuery = $this -> prepare("UPDATE `{$this -> sTablePrefix}accounts` SET `fb_profile` = ?
                WHERE `fb_profile` = ?", $iFbUid, $iFbUidCopy);

            $this -> query($sQuery);
        }
        //--

        //-- new auth method --//
        $sQuery = $this -> prepare("SELECT `id_profile` FROM `{$this -> sTablePrefix}accounts` WHERE
            `fb_profile` = ? LIMIT 1", $iFbUid);

        $iProfileId = $this -> getOne($sQuery);
        //--

        return $iProfileId;
    }

    /**
     *  Save new Fb uid
     *
     * @param $iProfileId integer
     * @param $iFbUid integer
     * @return void
     */
    function saveRemoteId($iProfileId, $iFbUid)
    {
        $iFbUid = $this -> _processBigNumber($iFbUid);
        $iProfileId = (int) $iProfileId;

        $sQuery = $this -> prepare("REPLACE INTO `{$this -> sTablePrefix}accounts`
                    SET `id_profile` = ?, `fb_profile` = ?", $iProfileId, $iFbUid);

        return $this -> query($sQuery);
    }

    /**
     * Delete Fb's uid
     *
     * @param $iProfileId integer
     * @return void
     */
    function deleteRemoteAccount($iProfileId)
    {
        $iProfileId = (int) $iProfileId;
        $sQuery = $this -> prepare("DELETE FROM `{$this -> sTablePrefix}accounts`
            WHERE `id_profile` = ?", $iProfileId);

        return $this -> query($sQuery);
    }
}

/** @} */
