<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolAcl');

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
	protected $_oTemplate;

	protected $_bLike = true;
	protected $_sType = BX_DOL_VOTE_TYPE_LIKES;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit);
        if(empty($this->_sSystem))
            return;

        $this->_oQuery = new BxDolVoteQuery($this);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

		$this->_bLike = $this->isLikeMode();
        $this->_sType = $this->_bLike ? BX_DOL_VOTE_TYPE_LIKES : BX_DOL_VOTE_TYPE_STARS;
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

        $o = new $sClassName($sSys, $iId, $iInit, $oTemplate);
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
                    `TriggerFieldAuthor` AS `trigger_field_author`,
                    `TriggerFieldRate` AS `trigger_field_rate`,
                    `TriggerFieldRateCount` AS `trigger_field_count`,
                    `ClassName` AS `class_name`,
                    `ClassFile` AS `class_file`
                FROM `sys_objects_vote`', 'name');

        return $GLOBALS['bx_dol_vote_systems'];
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

	public function getObjectAuthorId($iObjectId = 0)
    {
    	if(empty($this->_aSystem['trigger_field_author']))
    		return 0;

        return $this->_oQuery->getObjectAuthorId($iObjectId ? $iObjectId : $this->getId());
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

    /**
     * Actions functions
     */
    public function actionVote()
    {
        if(!$this->isEnabled()) {
            echoJson(array('code' => 1));
            return;
        }

        $iObjectId = $this->getId();
        $iObjectAuthorId = $this->getObjectAuthorId($iObjectId);
        $iAuthorId = $this->_getAuthorId();
        $iAuthorIp = $this->_getAuthorIp();

        $bUndo = $this->isUndo();
        $bLikeMode = $this->isLikeMode();

        $bVoted = $this->_oQuery->isPerformed($iObjectId, $iAuthorId);
        $bPerformUndo = $bVoted && $bUndo ? true : false;

        if(!$bPerformUndo && !$this->isAllowedVote(true)) {
            echoJson(array('code' => 2, 'msg' => $this->msgErrAllowedVote()));
            return;
        }

        if((!$bLikeMode && !$this->_oQuery->isPostTimeoutEnded($iObjectId, $iAuthorIp)) || ($bLikeMode && $bVoted && !$bUndo)) {
            echoJson(array('code' => 3, 'msg' => _t('_vote_err_duplicate_vote')));
            return;
        }

        $iValue = bx_get('value');
        if($iValue === false) {
            echoJson(array('code' => 4));
            return;
        }

        $iValue = bx_process_input($iValue, BX_DATA_INT);

        $iMinValue = $this->getMinValue();
        if($iValue < $iMinValue)
            $iValue = $iMinValue;

        $iMaxValue = $this->getMaxValue();
        if($iValue > $iMaxValue)
            $iValue = $iMaxValue;

		$iId = $this->_oQuery->putVote($iObjectId, $iAuthorId, $iAuthorIp, $iValue, $bPerformUndo);
        if($iId === false) {
            echoJson(array('code' => 5));
            return;
        }

        $this->_trigger();

        $oZ = new BxDolAlerts($this->_sSystem, ($bPerformUndo ? 'un' : '') . 'doVote', $iObjectId, $iAuthorId, array('vote_id' => $iId, 'vote_author_id' => $iAuthorId, 'object_author_id' => $iObjectAuthorId, 'value' => $iValue));
        $oZ->alert();

        $oZ = new BxDolAlerts('vote', ($bPerformUndo ? 'un' : '') . 'do', $iId, $iAuthorId, array('object_system' => $this->_sSystem, 'object_id' => $iObjectId, 'object_author_id' => $iObjectAuthorId, 'value' => $iValue));
        $oZ->alert();

        $aVote = $this->_oQuery->getVote($iObjectId);
        echoJson(array(
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

    /**
     * Internal functions
     */
    protected function _getIconDoLike($bVoted)
    {
    	return $bVoted && $this->isUndo() ?  'thumbs-down' : 'thumbs-up';
    }

    protected function _getTitleDoLike($bVoted)
    {
    	return $bVoted && $this->isUndo() ? '_vote_do_unlike' : '_vote_do_like';
    }
}

/** @} */
