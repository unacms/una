<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

bx_import('BxDolAcl');

define('BX_DOL_VOTE_TYPE_STARS', 'stars');
define('BX_DOL_VOTE_TYPE_LIKES', 'likes');
define('BX_DOL_VOTE_TYPE_REACTIONS', 'reactions');

define('BX_DOL_VOTE_USAGE_BLOCK', 'block');
define('BX_DOL_VOTE_USAGE_INLINE', 'inline');
define('BX_DOL_VOTE_USAGE_DEFAULT', BX_DOL_VOTE_USAGE_BLOCK);

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
    protected $_sType;
    protected $_aVote;

    protected $_aElementDefaults;

    protected function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
        if(empty($this->_sSystem))
            return;

        $this->_aVote = array();
    }

    /**
     * get votes object instanse
     * @param $sSys vote object name
     * @param $iId associated content id, where vote is available
     * @param $iInit perform initialization
     * @return null on error, or ready to use class instance
     */
    public static function getObjectInstance($sSys, $iId, $iInit = true, $oTemplate = false)
    {
        $sKey = 'BxDolVote!' . $sSys . $iId . ($oTemplate ? $oTemplate->getClassName() : '');
        if(isset($GLOBALS['bxDolClasses'][$sKey]))
            return $GLOBALS['bxDolClasses'][$sKey];

        $aSystems = self::getSystems();
        if(!isset($aSystems[$sSys]))
            return null;

        $sClassName = 'BxTemplVoteLikes';
        if(!empty($aSystems[$sSys]['class_name'])) {
            $sClassName = $aSystems[$sSys]['class_name'];
            if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
        }

        $o = new $sClassName($sSys, $iId, false, $oTemplate);
        if($iInit && method_exists($o, 'init'))
            $o->init($iId);

        return ($GLOBALS['bxDolClasses'][$sKey] = $o);
    }

    public static function &getSystems()
    {
        $sKey = 'bx_dol_cache_memory_vote_systems';

        if(!isset($GLOBALS[$sKey]))
            $GLOBALS[$sKey] = BxDolDb::getInstance()->fromCache('sys_objects_vote', 'getAllWithKey', '
                SELECT
                    `ID` as `id`,
                    `Name` AS `name`,
                    `TableMain` AS `table_main`,
                    `TableTrack` AS `table_track`,
                    `PostTimeout` AS `post_timeout`,
                    `MinValue` AS `min_value`,
                    `MaxValue` AS `max_value`,
                    `Pruning` AS `pruning`,
                    `IsUndo` AS `is_undo`,
                    `IsOn` AS `is_on`,
                    `TriggerTable` AS `trigger_table`,
                    `TriggerFieldId` AS `trigger_field_id`,
                    `TriggerFieldAuthor` AS `trigger_field_author`,
                    `TriggerFieldRate` AS `trigger_field_rate`,
                    `TriggerFieldRateCount` AS `trigger_field_count`,
                    `ClassName` AS `class_name`,
                    `ClassFile` AS `class_file`
                FROM `sys_objects_vote`', 'name');

        return $GLOBALS[$sKey];
    }

    public static function onAuthorDelete ($iAuthorId)
    {
        $aSystems = self::getSystems();
        foreach($aSystems as $sSystem => $aSystem)
            self::getObjectInstance($sSystem, 0)->getQueryObject()->deleteAuthorEntries($iAuthorId);

        return true;
    }

    public function getObjectAuthorId($iObjectId = 0)
    {
    	if(empty($this->_aSystem['trigger_field_author']))
            return 0;

        return $this->_oQuery->getObjectAuthorId($iObjectId ? $iObjectId : $this->getId());
    }

    /**
     * Interface functions for outer usage
     */
    public function isUndo()
    {
        return (int)$this->_aSystem['is_undo'] == 1;
    }

    public function getMinValue()
    {
        return (int)$this->_aSystem['min_value'];
    }

    public function getMaxValue()
    {
        return (int)$this->_aSystem['max_value'];
    }

    public function getStatCounter()
    {
        $aVote = $this->_getVote();
        return $aVote['count'];
    }

    public function getStatRate()
    {
        $aVote = $this->_getVote();
        return $aVote['rate'];
    }

    /**
     * Actions functions
     */
    public function actionVote()
    {
        if(!$this->isEnabled())
            return echoJson(array('code' => 1, 'message' => _t('_vote_err_not_enabled')));

        $iObjectId = $this->getId();
        $iObjectAuthorId = $this->getObjectAuthorId($iObjectId);
        $iAuthorId = $this->_getAuthorId();
        $iAuthorIp = $this->_getAuthorIp();

        $bUndo = $this->isUndo();
        $bVoted = $this->isPerformed($iObjectId, $iAuthorId);
        $bPerformUndo = $bVoted && $bUndo;

        if(!$bPerformUndo && !$this->isAllowedVote())
            return echoJson(array('code' => 2, 'message' => $this->msgErrAllowedVote()));

        if($this->_isDuplicate($iObjectId, $iAuthorId, $iAuthorIp, $bVoted))
            return echoJson(array('code' => 3, 'message' => _t('_vote_err_duplicate_vote')));

        $aData = $this->_getVoteData();
        $aParams = $this->_getRequestParamsData();
        if($aData === false)
            return echoJson(array('code' => 4));

        $iId = $this->_putVoteData($iObjectId, $iAuthorId, $iAuthorIp, $aData, $bPerformUndo);
        if($iId === false)
            return echoJson(array('code' => 5));

        if(!$bPerformUndo)
            $this->isAllowedVote(true);

        $this->_trigger();

        bx_alert($this->_sSystem, ($bPerformUndo ? 'un' : '') . 'doVote', $iObjectId, $iAuthorId, array_merge(array('vote_id' => $iId, 'vote_author_id' => $iAuthorId, 'object_author_id' => $iObjectAuthorId), $aData));
        bx_alert('vote', ($bPerformUndo ? 'un' : '') . 'do', $iId, $iAuthorId, array_merge(array('object_system' => $this->_sSystem, 'object_id' => $iObjectId, 'object_author_id' => $iObjectAuthorId), $aData));

        echoJson($this->_returnVoteData($iObjectId, $iAuthorId, $iAuthorIp, $aData, !$bVoted, $aParams));
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
    
    public function isAllowedVoteView($isPerformAction = false)
    {
        if(isAdmin())
            return true;

        return $this->checkAction('vote_view', $isPerformAction);
    }
    
    public function msgErrAllowedVoteView()
    {
        return $this->checkActionErrorMsg('vote_view');
    }
    
    public function isAllowedVoteViewVoters($isPerformAction = false)
    {
        if(isAdmin())
            return true;

        return $this->checkAction('vote_view_voters', $isPerformAction);
    }

    public function msgErrAllowedVoteViewVoters()
    {
        return $this->checkActionErrorMsg('vote_view_voters');
    }

    /**
     * Internal functions
     */
    protected function _isDuplicate($iObjectId, $iAuthorId, $iAuthorIp, $bVoted)
    {
        return false;
    }

    protected function _isCount($aVote = array())
    {
        if(empty($aVote))
            $aVote = $this->_getVote();

        return isset($aVote['count']) && (int)$aVote['count'] != 0;
    }

    protected function _getVoteData()
    {
        $iValue = bx_get('value');
        if($iValue === false)
            return false;

        $iValue = bx_process_input($iValue, BX_DATA_INT);

        $iMinValue = $this->getMinValue();
        if($iValue < $iMinValue)
            $iValue = $iMinValue;

        $iMaxValue = $this->getMaxValue();
        if($iValue > $iMaxValue)
            $iValue = $iMaxValue;

        return array('value' => $iValue);
    }

    protected function _putVoteData($iObjectId, $iAuthorId, $iAuthorIp, $aData, $bPerformUndo)
    {
        return $this->_oQuery->putVote($iObjectId, $iAuthorId, $iAuthorIp, $aData, $bPerformUndo);
    }

    protected function _returnVoteData($iObjectId, $iAuthorId, $iAuthorIp, $aData, $bVoted, $aParams = array())
    {
        $bUndo = $this->isUndo();
        $aVote = $this->_getVote($iObjectId, true);

        return array(
            'code' => 0,
            'rate' => $aVote['rate'],
            'count' => $aVote['count'],
            'countf' => (int)$aVote['count'] > 0 ? $this->_getCounterLabel($aVote['count'], $aParams) : '',
            'label_icon' => $this->_getIconDo($bVoted),
            'label_title' => _t($this->_getTitleDo($bVoted)),
            'disabled' => $bVoted && !$bUndo,
        );
    }

    protected function _prepareRequestParamsData($aParams, $aParamsAdd = array())
    {
        if(isset($aParams['is_voted']))
            $aParamsAdd['is_voted'] = $aParams['is_voted'];

        return parent::_prepareRequestParamsData($aParams, $aParamsAdd);
    }

    protected function _getVote($iObjectId = 0, $bForceGet = false)
    {
        if(!empty($this->_aVote) && !$bForceGet)
            return $this->_aVote;

        if(empty($iObjectId))
            $iObjectId = $this->getId();

        $this->_aVote = $this->_oQuery->getVote($iObjectId);
        return $this->_aVote;
    }

    protected function _isVote($iObjectId = 0, $bForceGet = false)
    {
        $aVote = $this->_getVote($iObjectId, $bForceGet);
        foreach($aVote as $sKey => $iValue)
            if(strpos($sKey, 'count_') !== false && !empty($iValue))
                return true;

        return false;
    }

    protected function _getTrack($iObjectId, $iAuthorId)
    {
        return $this->_oQuery->getTrack($iObjectId, $iAuthorId);
    }

    protected function _getIconDo($bVoted)
    {
    	return '';
    }

    protected function _getTitleDo($bVoted)
    {
    	return '';
    }

    protected function _getTitleDoBy($aParams = array())
    {
    	return _t('_vote_do_by');
    }
}

/** @} */
