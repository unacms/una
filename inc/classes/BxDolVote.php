<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolVoteQuery');

define('BX_DOL_VOTE_OLD_VOTES', 365 * 86400); ///< votes older than this number of seconds will be deleted automatically

define('BX_DOL_VOTE_TYPE_STARS', 'stars');
define('BX_DOL_VOTE_TYPE_LIKES', 'likes');

define('BX_DOL_VOTE_USAGE_BLOCK', 'block');
define('BX_DOL_VOTE_USAGE_INLINE', 'inline');
define('BX_DOL_VOTE_USAGE_DEFAULT', BX_DOL_VOTE_USAGE_BLOCK);

/** 
 * @page objects 
 * @section vote Vote
 * @ref BxDolVote
 */

/**
 * Vote for any content
 *
 * Related classes:
 * - BxDolVoteQuery - vote database queries
 * - BxBaseVoteView - vote base representation
 * - BxTemplVoteView - custom template representation
 *
 * AJAX vote for any content. Big and small vote stars are supported.
 *
 * To add vote section to your site you need to add a record to 'sys_objects_vote' table:
 *
 * - ID - autoincremented id for internal usage
 * - Name - your unique module name, with vendor prefix, lowercase and spaces are underscored
 * - TableMain - table name where summary votigs are stored
 * - TableTrack - table name where each vote is stored
 * - PostTimeout - number of seconds to not allow duplicate vote
 * - MaxValue - max vote value, 5 by default
 * - IsUndo - is Undo enabled 
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
 * After filling in the table you can show big vote in any place, using the following code:
 * @code
 * bx_import('BxTemplVoteView');
 * $o = new BxTemplVoteView ('value of ObjectName field', $iYourEntryId);
 * if (!$o->isEnabled()) return '';
 *     echo $o->getBigVote (1); // 1 - rate is allowed
 * @endcode
 *
 * And small vote, using the following code:
 * @code
 * $o = new BxTemplVoteView ('value of ObjectName field', $iYourEntryId);
 * if (!$o->isEnabled()) return '';
 *     echo $o->getSmallVote (0); // 0 - rate is not allowed, like readonly votes
 * @endcode
 *
 * In some cases votes are already in database and there is no need to execute additional query to get ratings,
 * so you can use the following code:
 * @code
 * $o = new BxTemplVoteView ('value of ObjectName field', 0);
 * foreach ($aRecords as $aData)
 *     echo $o->getJustVoteElement(0, $aData['ID'], $aData['voting_rate']);
 * @endcode
 *
 * Please note that you never need to use BxDolVote class directly, use BxTemplVoteView instead.
 * Also if you override vote class with your own then make it child of BxTemplVoteView class.
 *
 *
 *
 * @section acl Memberships/ACL:
 * vote - ACTION_ID_VOTE
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

/*

CREATE TABLE IF NOT EXISTS `sample_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sample_votes_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

 */


class BxDolVote extends BxDol
{
    var $_iId = 0;    ///< item id to be rated
    var $_sSystem = ''; ///< current rating system name
    var $_aSystem = array(); ///< current rating system array

    var $_oQuery = null;

    protected function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct();

        $this->_aSystems = $this->getSystems();
        if(!isset($this->_aSystems[$sSystem]))
			return;

        $this->_sSystem = $sSystem;
		$this->_aSystem = $this->_aSystems[$sSystem];

		if(!$this->isEnabled()) 
			return;

        $this->_oQuery = new BxDolVoteQuery($this);

        if($iInit)
			$this->init($iId);
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

        bx_import('BxTemplVoteView');
        $sClassName = 'BxTemplVoteView';
        if(!empty($aSystems[$sSys]['class_name'])) {
        	$sClassName = $aSystems[$sSys]['class_name'];
        	if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
            else
                bx_import($sClassName);
        }

        $o = new $sClassName($sSys, $iId, true);
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
    public static function maintenance() {        
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

    public function init($iId)
    {
    	if(!$this->isEnabled()) 
        	return;

        if(empty($this->_iId) && $iId)
			$this->setId($iId);
    }

    /**
     * Settings functions
     */
	public function getSystemId()
    {
        return $this->_aSystem['id'];
    }

    public function getSystemName()
    {
        return $this->_sSystem;
    }

	public function getSystemInfo()
    {
        return $this->_aSystem;
    }

	public function getId()
    {
        return $this->_iId;
    }

    public function isEnabled()
    {
        return (int)$this->_aSystem['is_on'] == 1;
    }

	public function isUndo()
    {
        return (int)$this->_aSystem['is_undo'] == 1;
    }

	public function isLikeMode()
    {
    	$bUndo = $this->isUndo();
    	$iMinValue = $this->getMinValue();
    	$iMaxValue = $this->getMaxValue();

    	return $iMinValue == $iMaxValue && $bUndo;
    }

	public function getMinValue()
    {
        return (int)$this->_aSystem['min_value'];
    }

	public function getMaxValue()
    {
        return (int)$this->_aSystem['max_value'];
    }

    public function setId($iId)
    {
        if($iId == $this->getId())
        	return;

        $this->_iId = $iId;
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

		$bUndo = $this->isUndo() && $this->_oQuery->isVoted($iObjectId, $iAuthorId, $iAuthorIp) ? true : false;

		if(!$bUndo && !$this->isAllowedVote()) {
			$this->_echoResultJson(array('code' => 2, 'msg' => $this->msgErrAllowedVote()));
        	return;
		}

    	if(!$bUndo && !$this->_oQuery->isPostTimeoutEnded($iObjectId, $iAuthorIp)) {
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

	    if(!$this->_oQuery->putVote($iObjectId, $iAuthorId, $iAuthorIp, $iValue, $bUndo)) {
	    	$this->_echoResultJson(array('code' => 5));
        	return;
	    }

		$this->_triggerVote();

		$oZ = new BxDolAlerts($this->_sSystem, 'rate', $this->getId(), $iAuthorId, array('value' => $iValue, 'undo' => $bUndo));
        $oZ->alert();

        $aVote = $this->_oQuery->getVote($iObjectId);
        $this->_echoResultJson(array(
        	'code' => 0, 
        	'rate' => $aVote['rate'],
        	'count' => $aVote['count'],
        	'countf' => (int)$aVote['count'] > 0 ? $this->_getLabelCounter($aVote['count']) : ''
        ));
    }

	public function actionGetVotedBy() {
        if (!$this->isEnabled())
           return '';

        return $this->_getVotedBy();
    }

    /** 
     * Permissions functions
     */
	public function checkAction ($iAction, $isPerformAction = false)
    {
        $iId = $this->_getAuthorId();
        $check_res = checkAction($iId, $iAction, $isPerformAction);
        return $check_res[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkActionErrorMsg ($iAction)
    {
        $iId = $this->_getAuthorId();
        $check_res = checkAction($iId, $iAction);
        return $check_res[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $check_res[CHECK_ACTION_MESSAGE] : '';
    }

	public function isAllowedVote($isPerformAction = false)
    {
    	if(isAdmin())
    		return true;

        return $this->checkAction(ACTION_ID_VOTE, $isPerformAction); 
    }

    public function msgErrAllowedVote()
    { 
        return $this->checkActionErrorMsg(ACTION_ID_VOTE);
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
        return $_SERVER['REMOTE_ADDR'];
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
    	bx_import('BxDolProfile');
		$oProfile = BxDolProfile::getInstance($iAuthorId);
		if (!$oProfile) {
			bx_import('BxDolProfileUndefined');
			$oProfile = BxDolProfileUndefined::getInstance();
		}

		return $oProfile;
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
