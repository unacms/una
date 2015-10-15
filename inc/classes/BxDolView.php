<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define ('BX_DOL_VIEW_OLD_VIEWS', 3 * 86400); ///< views older than this number of seconds will be deleted automatically

/**
 * @page objects
 * @section views Views
 * @ref BxDolView
 */

/**
 * Track any object views automatically
 *
 * Add record to sys_object_view table to track object views,
 * to record view just create this class instance with your object id,
 * for example:
 * @code
 *  BxDolView::getObjectInstance('my_system', 25); // 25 - is object id
 * @endcode
 *
 * Description of sys_object_view table fields:
 * @code
 *  `name` - system name, it is better to use unique module prefix here, lowercase and all spaces are underscored
 *  `table_track` - table to track views
 *  `period` - period in secs to update next views, default is 86400(1 day)
 *  `is_on` - is the system activated
 *  `trigger_table` - table where you need to update views field
 *  `trigger_field_id` - table field id to unique determine object
 *  `trigger_field_count` - table field where total view number is stored
 *  `class_name` - your custom class name, if you overrride default class
 *  `class_file` - your custom class path
 * @endcode
 *
 * Structure of the track table is the following:
 * @code
 *  CREATE TABLE `my_views_track` (
 *      `object_id` int(11) NOT NULL default '0', -- this field type must be exact as your object id type
 *      `viewer_id` int(11) NOT NULL default '0', -- viewer profile id
 *      `viewer_nip` int(11) unsigned NOT NULL default '0', -- viewer ip address to track guest views
 *      `date` int(11) NOT NULL default '0', -- timestamp of last recorded view
 *      KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
 *  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 * @endcode
 *
 */
class BxDolView extends BxDolObject
{
    /**
     * Constructor
     */
    function __construct($sSystem, $iId, $iInit = true)
    {
        parent::__construct($sSystem, $iId, $iInit);
        if(empty($this->_sSystem))
            return;

        $this->_oQuery = new BxDolViewQuery($this);
    }

   /**
     * get votes object instanse
     * @param $sSys view object name
     * @param $iId associated content id, where vote is available
     * @param $iInit perform initialization
     * @return null on error, or ready to use class instance
     */
    public static function getObjectInstance($sSys, $iId, $iInit = true)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolView!' . $sSys . $iId]))
            return $GLOBALS['bxDolClasses']['BxDolView!' . $sSys . $iId];

        $aSystems = self::getSystems();
        if(!isset($aSystems[$sSys]))
            return null;

        $sClassName = 'BxTemplView';
        if(!empty($aSystems[$sSys]['class_name'])) {
            $sClassName = $aSystems[$sSys]['class_name'];
            if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
        }

        $o = new $sClassName($sSys, $iId, $iInit);
        return ($GLOBALS['bxDolClasses']['BxDolView!' . $sSys . $iId] = $o);
    }

    public static function &getSystems()
    {
        if(!isset($GLOBALS['bx_dol_view_systems']))
            $GLOBALS['bx_dol_view_systems'] = BxDolDb::getInstance()->fromCache('sys_objects_view', 'getAllWithKey', '
                SELECT
                    `id` as `id`,
                    `name` AS `name`,
                    `table_track` AS `table_track`,
                    `period` AS `period`,
                    `is_on` AS `is_on`,
                    `trigger_table` AS `trigger_table`,
                    `trigger_field_id` AS `trigger_field_id`,
                    `trigger_field_count` AS `trigger_field_count`,
                    `class_name` AS `class_name`,
                    `class_file` AS `class_file`
                FROM `sys_objects_view`', 'name');

        return $GLOBALS['bx_dol_view_systems'];
    }

    /**
     * it is called on cron every day or similar period to clean old votes
     */
    public static function maintenance()
    {
        $iResult = 0;
        $oDb = BxDolDb::getInstance();

        $aSystems = self::getSystems();
        foreach($aSystems as $aSystem) {
            if(!$aSystem['is_on'])
                continue;

            $sQuery = $oDb->prepare("DELETE FROM `{$aSystem['table_track']}` WHERE `date` < (UNIX_TIMESTAMP() - ?)", BX_DOL_VIEW_OLD_VIEWS);
            $iDeleted = (int)$oDb->query($sQuery);
            if($iDeleted > 0)
                $oDb->query("OPTIMIZE TABLE `{$aSystem['table_track']}`");

            $iResult += $iDeleted;
        }

        return $iResult;
    }

    function doView()
    {
        if(!$this->isEnabled())
            return false;

        $iObjectId = $this->getId();
        $iAuthorId = $this->_getAuthorId();
        $sAuthorIp = $this->_getAuthorIp();

        if($this->_oQuery->doView($iObjectId, $iAuthorId, $sAuthorIp)) {
            $this->_triggerView();

            $oZ = new BxDolAlerts($this->_sSystem, 'view', $iObjectId, $iAuthorId);
            $oZ->alert();

            return true;
        }

        return false;
    }

    function onObjectDelete($iObjectId = 0)
    {
        $this->_oQuery->deleteObjectViews($iObjectId ? $iObjectId : $this->getId());
    }

    protected function _getAuthorId ()
    {
        return isMember() ? bx_get_logged_profile_id() : 0;
    }

    protected function _getAuthorIp ()
    {
        return getVisitorIP();
    }

    protected function _triggerView()
    {
        if(!$this->_aSystem['trigger_table'])
            return false;

        $iId = $this->getId();
        if(!$iId)
            return false;

        return $this->_oQuery->updateTriggerTable($iId);
    }
}

/** @} */
