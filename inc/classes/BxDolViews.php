<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

define ('BX_OLD_VIEWS', 3*86400); ///< views older than this number of seconds will be deleted automatically

/** 
 * @page objects 
 * @section views_counter Views Counter
 * @ref BxDolViews
 */

/**
 * Track any object views automatically
 *
 * Add record to sys_object_views table to track object views,
 * to record view just create this class instance with your object id, 
 * for example:
 * @code
 *  new BxDolViews('my_system', 25); // 25 - is object id
 * @endcode
 *
 * Description of sys_object_views table fields:
 * @code
 *  `name` - system name, it is better to use unique module prefix here, lowercase and all spaces are underscored
 *  `table_track` - table to track views
 *  `period` - period in secs to update next views, default is 86400(1 day)
 *  `trigger_table` - table where you need to update views field
 *  `trigger_field_id` - table field id to unique determine object
 *  `trigger_field_views` - table field where total view number is stored
 *  `is_on` - is the system activated
 * @endcode
 *
 *  Structure of the track table is the following:
 * @code
 *  CREATE TABLE `my_views_track` (
 *      `id`, -- this field type must be exact as your object id type
 *      `viewer` int(10) unsigned NOT NULL, -- viewer profile id
 *      `ip` int(10) unsigned NOT NULL, -- viewer ip address to track guest views
 *      `ts` int(10) unsigned NOT NULL, -- timestamp of last recorded view
 *      KEY `id` (`id`,`viewer`,`ip`)
 *  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 * @endcode
 *
 */
class BxDolViews extends BxDol
{
    var $_iId = 0;    ///< item id to be viewed
    var $_sSystem = ''; ///< current view system name
    var $_aSystem = array (); ///< current view system array

    /**
     * Constructor
     */
    function BxDolViews($sSystem, $iId, $isMakeView = true)
    {
        $aSystems = $this->getAllSystems ();
        if (!isset($aSystems[$sSystem]))
            return;
        $this->_aSystem = $aSystems[$sSystem];
        $this->_sSystem = $sSystem;
        if (!$this->isEnabled())
            return;
        $this->_iId = $iId;
        if ($isMakeView)
            $this->makeView();
    }

    function makeView ()
    {
        if (!$this->isEnabled()) return false;

        $oDb = BxDolDb::getInstance();
        $iMemberId = getLoggedId();
        $sIp = $_SERVER['REMOTE_ADDR'];
        $iTime = time();

        if ($iMemberId)
            $sWhere = $oDb->prepare(" AND `viewer` = ? ", $iMemberId);
        else
            $sWhere = $oDb->prepare(" AND `viewer` = '0' AND `ip` = INET_ATON(?) ", $sIp);

        $sQuery = $oDb->prepare("SELECT `ts` FROM `{$this->_aSystem['table_track']}` WHERE `id` = ? $sWhere", $this->getId());
        $iTs = (int)($oDb->getOne($sQuery));

        $iRet = 0;
        if (!$iTs) {
            $sQuery = $oDb->prepare("INSERT IGNORE INTO `{$this->_aSystem['table_track']}` SET `id` = ?, `viewer` = ?, `ip` = INET_ATON(?), `ts` = ?", $this->getId(), $iMemberId, $sIp, $iTime);
            $iRet = $oDb->query($sQuery);
        } elseif (($iTime - $iTs) > $this->_aSystem['period']) {
            $sQuery = $oDb->prepare("UPDATE `{$this->_aSystem['table_track']}` SET `ts` = ? WHERE `id` = ? AND `viewer` = ? AND `ip` = INET_ATON(?)", $iTime, $this->getId(), $iMemberId, $sIp);
            $iRet = $oDb->query($sQuery);
        }

        if ($iRet) {
            $this->_triggerView();

            $oZ = new BxDolAlerts($this->_sSystem, 'view', $this->getId(), $iMemberId);
            $oZ->alert();

            return true;
        }

        return false;
    }

    function getId ()
    {
        return $this->_iId;
    }

    function isEnabled ()
    {
        return $this->_aSystem && $this->_aSystem['is_on'];
    }

    function getSystemName()
    {
        return $this->_sSystem;
    }

    function getAllSystems () {
        $oDb = BxDolDb::getInstance();
        return $oDb->fromCache('sys_objects_views', 'getAllWithKey', 'SELECT * FROM `sys_objects_views`', 'name');
    }

    /**
     * call this function when associated object is deleted
     */
    function onObjectDelete ($iId = 0)
    {
        $iId = (int) $iId;

        $oDb = BxDolDb::getInstance();

        $sQuery = $oDb->prepare("DELETE FROM `{$this->_aSystem['table_track']}` WHERE `id` = ?", $iId ? $iId : $this->getId());
        $oDb->query($sQuery);

        $oDb->query("OPTIMIZE TABLE `{$this->_aSystem['table_track']}`");
    }

    /**
     * it is called on cron every day or similar period
     */
    function maintenance () {
        $oDb = BxDolDb::getInstance();
        $iTime = time() - BX_OLD_VIEWS;
        $aSystems = $this->getAllSystems ();
        $iDeletedRecords = 0;
        foreach ($aSystems as $aSystem) {
            if (!$aSystem['is_on'])
                continue;
            $sQuery = $oDb->prepare("DELETE FROM `{$aSystem['table_track']}` WHERE `ts` < ?", $iTime);
            $iCount = $oDb->query($sQuery);
            if ($iCount)
                $oDb->query("OPTIMIZE TABLE `{$aSystem['table_track']}`");
            $iDeletedRecords += $iCount;
        }
        return $iDeletedRecords;
    }

    function _triggerView() {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("UPDATE `{$this->_aSystem['trigger_table']}` SET `{$this->_aSystem['trigger_field_views']}` = `{$this->_aSystem['trigger_field_views']}` + 1 WHERE `{$this->_aSystem['trigger_field_id']}` = ?", $this->getId());
        return $oDb->query($sQuery);
    }
}

/** @} */ 
