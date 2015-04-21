<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolAcl');

define('BX_DOL_VOTE_OLD_VOTES', 365 * 86400); ///< votes older than this number of seconds will be deleted automatically

define('BX_DOL_VOTE_TYPE_STARS', 'stars');
define('BX_DOL_VOTE_TYPE_LIKES', 'likes');

define('BX_DOL_VOTE_USAGE_BLOCK', 'block');
define('BX_DOL_VOTE_USAGE_INLINE', 'inline');
define('BX_DOL_VOTE_USAGE_DEFAULT', BX_DOL_VOTE_USAGE_BLOCK);

/**
 * @page objects
 * @section votes Votes
 * @ref BxDolVote
 */

/**
 * Vote for any content
 *
 * Related classes:
 * - BxDolVoteQuery - vote database queries
 * - BxBaseVote - vote base representation
 * - BxTemplVote - custom template representation
 *
 * AJAX vote for any content. Stars and Plus based representations are supported.
 *
 * To add vote section to your feature you need to add a record to 'sys_objects_vote' table:
 *
 * - ID - autoincremented id for internal usage
 * - Name - your unique module name, with vendor prefix, lowercase and spaces are underscored
 * - TableMain - table name where summary votigs are stored
 * - TableTrack - table name where each vote is stored
 * - PostTimeout - number of seconds to not allow duplicate vote
 * - MinValue - min vote value, 1 by default
 * - MaxValue - max vote value, 5 by default
 * - IsUndo - is Undo enabled for Plus based votes
 * - IsOn - is this vote object enabled
 * - TriggerTable - table to be updated upon each vote
 * - TriggerFieldId - TriggerTable table field with unique record id, primary key
 * - TriggerFieldRate - TriggerTable table field with average rate
 * - TriggerFieldRateCount - TriggerTable table field with votes count
 * - ClassName - your custom class name, if you overrride default class
 * - ClassFile - your custom class path
 *
 * You can refer to BoonEx modules for sample record in this table.
 *
 *
 *
 * @section example Example of usage:
 * To get Star based vote you need to have different values for MinValue and MaxValue (for example 1 and 5)
 * and IsUndo should be equal to 0. To get Plus(Like) based vote you need to have equal values
 * for MinValue and MaxValue (for example 1) and IsUndo should be equal to 1. After filling the other
 * paramenters in the table you can show vote in any place, using the following code:
 * @code
 * $o = BxDolVote::getObjectInstance('system object name', $iYourEntryId);
 * if (!$o->isEnabled()) return '';
 *     echo $o->getElementBlock();
 * @endcode
 *
 *
 * @section acl Memberships/ACL:
 * - vote
 *
 *
 *
 * @section alerts Alerts:
 * Alerts type/unit - every module has own type/unit, it equals to ObjectName.
 * The following alerts are rised:
 *
 * - rate - comment was posted
 *      - $iObjectId - entry id
 *      - $iSenderId - rater user id
 *      - $aExtra['rate'] - rate
 *
 */

class BxDolVote extends BxDolObject
{
    public function __construct($sSystem, $iId, $iInit = true)
    {
        parent::__construct($sSystem, $iId, $iInit);
        if(empty($this->_sSystem))
            return;

        $this->_oQuery = new BxDolVoteQuery($this);
    }

    /**
     * get votes object instanse
     * @param $sSys vote object name
     * @param $iId associated content id, where vote is available
     * @param $iInit perform initialization
     * @return null on error, or ready to use class instance
     */
    public static function getObjectInstance($sSys, $iId, $iInit = true)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolVote!' . $sSys . $iId]))
            return $GLOBALS['bxDolClasses']['BxDolVote!' . $sSys . $iId];

        $aSystems = self::getSystems();
        if(!isset($aSystems[$sSys]))
            return null;

        $sClassName = 'BxTemplVote';
        if(!empty($aSystems[$sSys]['class_name'])) {
            $sClassName = $aSystems[$sSys]['class_name'];
            if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
        }

        $o = new $sClassName($sSys, $iId, $iInit);
        return ($GLOBALS['bxDolClasses']['BxDolVote!' . $sSys . $iId] = $o);
    }

    public static function &getSystems()
    {
        if(!isset($GLOBALS['bx_dol_vote_systems']))
            $GLOBALS['bx_dol_vote_systems'] = BxDolDb::getInstance()->fromCache('sys_objects_vote', 'getAllWithKey', '
                SELECT
                    `ID` as `id`,
                    `Name` AS `name`,
                    `TableMain` AS `table_main`,
                    `TableTrack` AS `table_track`,
                    `PostTimeout` AS `post_timeout`,
                    `MinValue` AS `min_value`,
                    `MaxValue` AS `max_value`,
                    `IsUndo` AS `is_undo`,
                    `IsOn` AS `is_on`,
                    `TriggerTable` AS `trigger_table`,
                    `TriggerFieldId` AS `trigger_field_id`,
                    `TriggerFieldRate` AS `trigger_field_rate`,
                    `TriggerFieldRateCount` AS `trigger_field_count`,
                    `ClassName` AS `class_name`,
                    `ClassFile` AS `class_file`
                FROM `sys_objects_vote`', 'name');

        return $GLOBALS['bx_dol_vote_systems'];
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

            $sQuery = $oDb->prepare("DELETE FROM `{$aSystem['table_track']}` WHERE `date` < (UNIX_TIMESTAMP() - ?)", BX_DOL_VOTE_OLD_VOTES);
            $iDeleted = (int)$oDb->query($sQuery);
            if($iDeleted > 0)
                $oDb->query("OPTIMIZE TABLE `{$aSystem['table_track']}`");

            $iResult += $iDeleted;
        }

        return $iResult;
    }

    public function isUndo()
    {
        return (int)$this->_aSystem['is_undo'] == 1;
    }

    public function isLikeMode()
    {
        $iMinValue = $this->getMinValue();
        $iMaxValue = $this->getMaxValue();

        return $iMinValue == $iMaxValue;
    }

    public function getMinValue()
    {
        return (int)$this->_aSystem['min_value'];
    }

    public function getMaxValue()
    {
        return (int)$this->_aSystem['max_value'];
    }

    /**
     * Interface functions for outer usage
     */
    public function getStatCounter()
    {
        $aVote = $this->_oQuery->getVote($this->getId());
        return $aVote['count'];
    }

    public function getStatRate()
    {
        $aVote = $this->_oQuery->getVote($this->getId());
        return $aVote['rate'];
    }

    public function getSqlParts($sMainTable, $sMainField)
    {
        if(!$this->isEnabled())
            return array();

        return $this->_oQuery->getSqlParts($sMainTable, $sMainField);
    }

    /**
     * Actions functions
     */
    public function actionVote()
    {
        if(!$this->isEnabled()) {
            $this->_echoResultJson(array('code' => 1));
            return;
        }

        $iObjectId = $this->getId();
        $iAuthorId = $this->_getAuthorId();
        $iAuthorIp = $this->_getAuthorIp();

        $bUndo = $this->isUndo();
        $bLikeMode = $this->isLikeMode();

        $bVoted = $this->_oQuery->isVoted($iObjectId, $iAuthorId);
        $bPerformUndo = $bVoted && $bUndo ? true : false;

        if(!$bPerformUndo && !$this->isAllowedVote(true)) {
            $this->_echoResultJson(array('code' => 2, 'msg' => $this->msgErrAllowedVote()));
            return;
        }

        if((!$bLikeMode && !$this->_oQuery->isPostTimeoutEnded($iObjectId, $iAuthorIp)) || ($bLikeMode && $bVoted && !$bUndo)) {
            $this->_echoResultJson(array('code' => 3, 'msg' => _t('_vote_err_duplicate_vote')));
            return;
        }

        $iValue = bx_get('value');
        if($iValue === false) {
            $this->_echoResultJson(array('code' => 4));
            return;
        }

        $iValue = bx_process_input($iValue, BX_DATA_INT);

        $iMinValue = $this->getMinValue();
        if($iValue < $iMinValue)
            $iValue = $iMinValue;

        $iMaxValue = $this->getMaxValue();
        if($iValue > $iMaxValue)
            $iValue = $iMaxValue;

        if(!$this->_oQuery->putVote($iObjectId, $iAuthorId, $iAuthorIp, $iValue, $bPerformUndo)) {
            $this->_echoResultJson(array('code' => 5));
            return;
        }

        $this->_triggerVote();

        $oZ = new BxDolAlerts($this->_sSystem, 'rate', $this->getId(), $iAuthorId, array('value' => $iValue, 'undo' => $bPerformUndo));
        $oZ->alert();

        $aVote = $this->_oQuery->getVote($iObjectId);
        $this->_echoResultJson(array(
            'code' => 0,
            'rate' => $aVote['rate'],
            'count' => $aVote['count'],
            'countf' => (int)$aVote['count'] > 0 ? $this->_getLabelCounter($aVote['count']) : '',
        	'label_icon' => $bLikeMode ? $this->_getIconDoLike(!$bVoted) : '',
        	'label_title' => $bLikeMode ? _t($this->_getTitleDoLike(!$bVoted)) : '',
        	'disabled' => !$bVoted && !$bUndo,
        ));
    }

    public function actionGetVotedBy()
    {
        if (!$this->isEnabled())
           return '';

        return $this->_getVotedBy();
    }

    /**
     * Permissions functions
     */
    public function checkAction ($sAction, $isPerformAction = false)
    {
        $iId = $this->_getAuthorId();
        $a = checkActionModule($iId, $sAction, 'system', $isPerformAction);
        return $a[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkActionErrorMsg ($sAction)
    {
        $iId = $this->_getAuthorId();
        $a = checkActionModule($iId, $sAction, 'system');
        return $a[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $a[CHECK_ACTION_MESSAGE] : '';
    }

    public function isAllowedVote($isPerformAction = false)
    {
        if(isAdmin())
            return true;

        return $this->checkAction('vote', $isPerformAction);
    }

    public function msgErrAllowedVote()
    {
        return $this->checkActionErrorMsg('vote');
    }

    function onObjectDelete($iObjectId = 0)
    {
        $this->_oQuery->deleteObjectVotes($iObjectId ? $iObjectId : $this->getId());
    }

    /**
     * Internal functions
     */
    protected function _getAuthorId ()
    {
        return isMember() ? bx_get_logged_profile_id() : 0;
    }

    protected function _getAuthorIp ()
    {
        return getVisitorIP();
    }

    protected function _getAuthorInfo($iAuthorId = 0)
    {
        $oProfile = $this->_getAuthorObject($iAuthorId);

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit()
        );
    }

    protected function _getAuthorObject($iAuthorId = 0)
    {
        $oProfile = BxDolProfile::getInstance($iAuthorId);
        if (!$oProfile)
            $oProfile = BxDolProfileUndefined::getInstance();

        return $oProfile;
    }

    protected function _getIconDoLike($bVoted)
    {
    	return $bVoted && $this->isUndo() ?  'thumbs-down' : 'thumbs-up';
    }

    protected function _getTitleDoLike($bVoted)
    {
    	return $bVoted && $this->isUndo() ? '_vote_do_unlike' : '_vote_do_like';
    }

    protected function _triggerVote()
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
