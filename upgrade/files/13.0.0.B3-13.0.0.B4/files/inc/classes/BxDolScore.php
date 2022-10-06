<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

bx_import('BxDolAcl');

define('BX_DOL_SCORE_USAGE_BLOCK', 'block');
define('BX_DOL_SCORE_USAGE_INLINE', 'inline');
define('BX_DOL_SCORE_USAGE_DEFAULT', BX_DOL_SCORE_USAGE_BLOCK);

define('BX_DOL_SCORE_DO_UP', 'up');
define('BX_DOL_SCORE_DO_DOWN', 'down');

/**
 * Score for any content
 *
 * Related classes:
 * - BxDolScoreQuery - vote database queries
 * - BxBaseScore - vote base representation
 * - BxTemplScore - custom template representation
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
 * $o = BxDolScore::getObjectInstance('system object name', $iYourEntryId);
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

class BxDolScore extends BxDolObject
{
    protected $_aElementDefaults;

    protected function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
        if(empty($this->_sSystem))
            return;

        $this->_oQuery = new BxDolScoreQuery($this);
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
        $sKey = 'BxDolScore!' . $sSys . $iId . ($oTemplate ? $oTemplate->getClassName() : '');
        if(isset($GLOBALS['bxDolClasses'][$sKey]))
            return $GLOBALS['bxDolClasses'][$sKey];

        $aSystems = self::getSystems();
        if(!isset($aSystems[$sSys]))
            return null;

        $sClassName = 'BxTemplScore';
        if(!empty($aSystems[$sSys]['class_name'])) {
            $sClassName = $aSystems[$sSys]['class_name'];
            if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
        }

        $o = new $sClassName($sSys, $iId, $iInit, $oTemplate);
        return ($GLOBALS['bxDolClasses'][$sKey] = $o);
    }

    public static function &getSystems()
    {
        $sKey = 'bx_dol_cache_memory_score_systems';

        if(!isset($GLOBALS[$sKey]))
            $GLOBALS[$sKey] = BxDolDb::getInstance()->fromCache('sys_objects_score', 'getAllWithKey', '
                SELECT
                    `id` as `id`,
                    `name` AS `name`,
                    `table_main` AS `table_main`,
                    `table_track` AS `table_track`,
                    `post_timeout` AS `post_timeout`,
                    `pruning` AS `pruning`,
                    `is_on` AS `is_on`,
                    `trigger_table` AS `trigger_table`,
                    `trigger_field_id` AS `trigger_field_id`,
                    `trigger_field_author` AS `trigger_field_author`,
                    `trigger_field_score` AS `trigger_field_score`,
                    `trigger_field_cup` AS `trigger_field_cup`,
                    `trigger_field_cdown` AS `trigger_field_cdown`,
                    `class_name` AS `class_name`,
                    `class_file` AS `class_file`
                FROM `sys_objects_score`', 'name');

        return $GLOBALS[$sKey];
    }

    public static function onAuthorDelete ($iAuthorId)
    {
        $aSystems = self::getSystems();
        foreach($aSystems as $sSystem => $aSystem)
            self::getObjectInstance($sSystem, 0)->getQueryObject()->deleteAuthorEntries($iAuthorId);

        return true;
    }

    public function isPerformed($iObjectId, $iAuthorId, $iAuthorIp = 0)
    {
        return parent::isPerformed($iObjectId, $iAuthorId) && !$this->_oQuery->isPostTimeoutEnded($iObjectId, $iAuthorId, $iAuthorIp);        
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
    public function getStatCounterUp()
    {
        $aScore = $this->_oQuery->getScore($this->getId());
        return $aScore['count_up'];
    }

    public function getStatCounterDown()
    {
        $aScore = $this->_oQuery->getScore($this->getId());
        return $aScore['count_down'];
    }

    public function getStatScore()
    {
        $aScore = $this->_oQuery->getScore($this->getId());
        return $aScore['score'];
    }


    /**
     * Actions functions
     */
    public function actionVoteUp()
    {
        return $this->_doVote(BX_DOL_SCORE_DO_UP);
    }

    public function actionVoteDown()
    {
        return $this->_doVote(BX_DOL_SCORE_DO_DOWN);
    }

    public function actionGetVotedBy()
    {
        if (!$this->isEnabled())
           return '';

        $aParams = $this->_getRequestParamsData();
        return $this->_getVotedBy($aParams);
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
    protected function _doVote($sType)
    {
        if(!$this->isEnabled())
            return echoJson(array('code' => 1, 'message' => _t('_sys_score_err_not_enabled')));

        if(!$this->isAllowedVote(true))
            return echoJson(array('code' => 2, 'message' => $this->msgErrAllowedVote()));

        $iObjectId = $this->getId();
        $iObjectAuthorId = $this->getObjectAuthorId($iObjectId);
        $iAuthorId = $this->_getAuthorId();
        $iAuthorIp = $this->_getAuthorIp();

        $bVoted = $this->isPerformed($iObjectId, $iAuthorId, $iAuthorIp);
        if($bVoted)
            return echoJson(array('code' => 3, 'message' => _t('_sys_score_err_duplicate_vote')));

        $iId = $this->_oQuery->putVote($iObjectId, $iAuthorId, $iAuthorIp, $sType);
        if($iId === false)
            return echoJson(array('code' => 5));

        $this->_trigger();

        $sTypeUc = ucfirst($sType);
        bx_alert($this->_sSystem, 'doVote' . $sTypeUc, $iObjectId, $iAuthorId, array('score_id' => $iId, 'score_author_id' => $iAuthorId, 'object_author_id' => $iObjectAuthorId));
        bx_alert('score', 'do' . $sTypeUc, $iId, $iAuthorId, array('object_system' => $this->_sSystem, 'object_id' => $iObjectId, 'object_author_id' => $iObjectAuthorId));

        $aParams = $this->_getRequestParamsData();
        $aParams['show_script'] = false;

        $aScore = $this->_oQuery->getScore($iObjectId);
        $iCup = (int)$aScore['count_up'];
        $iCdown = (int)$aScore['count_down'];
        echoJson(array(
            'code' => 0,
            'type' => $sType,
            'score' => $aScore['score'],
            'scoref' => $iCup > 0 || $iCdown > 0 ? $this->_getCounterLabel($aScore['score'], $aParams) : '',
            'cup' => $iCup,
            'cdown' => $iCdown,
            'counter' => $this->getCounter($aParams),
            'label_icon' => $this->_getIconDo($sType),
            'label_title' => _t($this->_getTitleDo($sType)),
            'disabled' => !$bVoted,
        ));
    }

    /**
     * Note. By default image based controls aren't used.
     * Therefore it can be overwritten in custom template.
     */
    protected function _getImageDo($sType)
    {
        $sResult = '';

        switch($sType) {
            case BX_DOL_SCORE_DO_UP:
                $sResult = '';
                break;
            case BX_DOL_SCORE_DO_DOWN:
                $sResult = '';
                break;
        }

    	return $sResult;
    }

    protected function _getIconDo($sType = '')
    {
        $sResult = '';

        switch($sType) {
            case BX_DOL_SCORE_DO_UP:
                $sResult = 'arrow-up';
                break;

            case BX_DOL_SCORE_DO_DOWN:
                $sResult = 'arrow-down';
                break;

            default:
                $sResult = 'arrows-alt-v';
        }

    	return $sResult;
    }

    protected function _getTitleDo($sType)
    {
        $sResult = '';

        switch($sType) {
            case BX_DOL_SCORE_DO_UP:
                $sResult = '_sys_score_do_up';
                break;
            case BX_DOL_SCORE_DO_DOWN:
                $sResult = '_sys_score_do_down';
                break;
        }

    	return $sResult;
    }

    protected function _getTitleDoBy()
    {
    	return '_sys_score_do_by';
    }

    protected function _encodeElementParams($aParams)
    {
        if(empty($aParams) || !is_array($aParams))
            return '';

        return urlencode(base64_encode(serialize($aParams)));
    }

    protected function _decodeElementParams($sParams, $bMergeWithDefaults = true)
    {
        $aParams = array();
        if(!empty($sParams))
            $aParams = unserialize(base64_decode(urldecode($sParams)));

        if(empty($aParams) || !is_array($aParams))
            $aParams = array();

        if($bMergeWithDefaults)
            $aParams = array_merge($this->_aElementDefaults, $aParams);

        return $aParams;
    }
}

/** @} */
