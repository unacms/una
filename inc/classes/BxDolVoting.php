<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolVotingQuery');

define( 'BX_PERIOD_PER_VOTE', 7 * 86400 ); ///< user can vote again for the same content after this number of seconds
define( 'BX_OLD_VOTES', 365*86400 ); ///< votes older than this number of seconds will be deleted automatically

/** 
 * @page objects 
 * @section votings Votings
 * @ref BxDolVoting
 */

/**
 * Votings for any content
 *
 * Related classes:
 * - BxDolVotingQuery - votings database queries
 * - BxBaseVotingView - votings base representation
 * - BxTemplVotingView - custom template representation
 *
 * AJAX votings for any content. Big and small votings stars are supported.
 *
 * To add votings section to your site you need to add a record to 'sys_objects_vote' table:
 *
 * - ID - autoincremented id for internal usage
 * - ObjectName - your unique module name, with vendor prefix, lowercase and spaces are underscored
 * - TableRating - table name where sumarry votigs are stored
 * - TableTrack - table name where each vote is stored
 * - RowPrefix - row prefix for TableRating
 * - MaxVotes - max vote number, 5 by default
 * - PostName - post variable name with rating
 * - IsDuplicate - number of seconds to not allow duplicate vote (for some bad reason it is define here)
 * - IsOn - is this vote object enabled
 * - ClassName - custom class name for HotOrNot @ref BxDolRate
 * - ClassFile - custom class path for HotOrNot
 * - TriggerTable - table to be updated upon each vote
 * - TriggerFieldRate - TriggerTable table field with average rate
 * - TriggerFieldRateCount - TriggerTable table field with votes count
 * - TriggerFieldId - TriggerTable table field with unique record id, primary key
 * - OverrideClassName - your custom class name, if you overrride default class
 * - OverrideClassFile - your custom class path
 *
 * You can refer to BoonEx modules for sample record in this table.
 *
 *
 *
 * @section example Example of usage:
 * After filling in the table you can show big votings in any place, using the following code:
 * @code
 * bx_import('BxTemplVotingView');
 * $o = new BxTemplVotingView ('value of ObjectName field', $iYourEntryId);
 * if (!$o->isEnabled()) return '';
 *     echo $o->getBigVoting (1); // 1 - rate is allowed
 * @endcode
 *
 * And small votings, using the following code:
 * @code
 * $o = new BxTemplVotingView ('value of ObjectName field', $iYourEntryId);
 * if (!$o->isEnabled()) return '';
 *     echo $o->getSmallVoting (0); // 0 - rate is not allowed, like readonly votings
 * @endcode
 *
 * In some cases votes are already in database and there is no need to execute additional query to get ratings,
 * so you can use the following code:
 * @code
 * $o = new BxTemplVotingView ('value of ObjectName field', 0);
 * foreach ($aRecords as $aData)
 *     echo $o->getJustVotingElement(0, $aData['ID'], $aData['voting_rate']);
 * @endcode
 *
 * Please note that you never need to use BxDolVoting class directly, use BxTemplVotingView instead.
 * Also if you override votings class with your own then make it child of BxTemplVotingView class.
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
class BxDolVoting extends BxDol
{
    var $_iId = 0;    ///< item id to be rated
    var $_iCount = 0; ///< number of votes
    var $_fRate = 0; ///< average rate
    var $_sSystem = 'profile'; ///< current rating system name

    var $_aSystem = array (); ///< current rating system array

    var $_oQuery = null;

    /**
     * Constructor
     */
    function BxDolVoting( $sSystem, $iId, $iInit = 1)
    {
        parent::BxDol();

        $this->_aSystems =& $this->getSystems();

        $this->_sSystem = $sSystem;
        if (isset($this->_aSystems[$sSystem]))
            $this->_aSystem = $this->_aSystems[$sSystem];
        else
            return;

        $this->_oQuery = new BxDolVotingQuery($this->_aSystem);

        if ($iInit)
            $this->init($iId);

        if (!$this->isEnabled()) return;

        $iVoteResult = $this->_getVoteResult ();
        if ($iVoteResult)
        {
            if (!$this->makeVote ($iVoteResult))
            {
                exit;
            }
            $this->initVotes ();
            echo $this->getVoteRate() . ',' . $this->getVoteCount();
            exit;
        }
    }

    function & getSystems ()
    {
        if (isset($GLOBALS['bx_dol_voting_systems'])) {
            return $GLOBALS['bx_dol_voting_systems'];
        }

        $oDb = BxDolDb::getInstance();
        $oCache = $oDb->getDbCacheObject();

        $GLOBALS['bx_dol_voting_systems'] = $oCache->getData($oDb->genDbCacheKey('sys_objects_vote'));

        if (null === $GLOBALS['bx_dol_voting_systems']) {

            // cache is empty | load data from DB

            $sQuery  = "SELECT * FROM `sys_objects_vote`";
            $sRows = $oDb->getAll($sQuery);

            $GLOBALS['bx_dol_voting_systems'] = array();
            foreach ($sRows as $aRow)
            {
                $GLOBALS['bx_dol_voting_systems'][$aRow['ObjectName']] = array
                (
                    'table_rating'    => $aRow['TableRating'],
                    'table_track'    => $aRow['TableTrack'],
                    'row_prefix'    => $aRow['RowPrefix'],
                    'max_votes'        => $aRow['MaxVotes'],
                    'post_name'        => $aRow['PostName'],
                    'is_duplicate'    => is_int($aRow['IsDuplicate']) ? $aRow['IsDuplicate'] : constant($aRow['IsDuplicate']),
                    'is_on'            => $aRow['IsOn'],

                    'className'     => $aRow['className'],
                    'classFile'     => $aRow['classFile'],

                    'trigger_table'            => $aRow['TriggerTable'], // table with field to update on every rating change
                    'trigger_field_rate'       => $aRow['TriggerFieldRate'], // table field name with rating
                    'trigger_field_rate_count' => $aRow['TriggerFieldRateCount'], // table field name with rating count
                    'trigger_field_id'         => $aRow['TriggerFieldId'], // table field name with object id

                    'override_class_name' => $aRow['OverrideClassName'], // new class to override
                    'override_class_file' => $aRow['OverrideClassFile'], // class file path
                );
            }

            // write data into cache file

            $oCache = $oDb->getDbCacheObject();
            $oCache->setData ($oDb->genDbCacheKey('sys_objects_vote'), $GLOBALS['bx_dol_voting_systems']);
        }

        return $GLOBALS['bx_dol_voting_systems'];
    }

    function init ($iId)
    {
        if (!$iId)
            $iId = $this->_iId;

        if (!$this->isEnabled()) return;

        if (!$this->_iId && $iId)
        {
            $this->setId($iId);
        }

    }

    function initVotes ()
    {
        if (!$this->isEnabled()) return;
        if (!$this->_oQuery) return;

        $a = $this->_oQuery->getVote ($this->getId());
        if (!$a) return;
        $this->_iCount = $a['count'];
        $this->_fRate = $a['rate'];
    }

    function makeVote ($iVote)
    {
        if (!$this->isEnabled()) return false;
        if ($this->isDublicateVote()) return false;
        if (!$this->checkAction()) return false;

        if ($this->_sSystem == 'profile' && $this->getId() == getLoggedId())
            return false;

        if ($this->_oQuery->putVote ($this->getId(), getVisitorIP(), $iVote)) {
            $this->_triggerVote();

            $oZ = new BxDolAlerts($this->_sSystem, 'rate', $this->getId(), getLoggedId(), array ('rate' => $iVote));
            $oZ->alert();
            return true;
        }
        return false;
    }

    function checkAction ()
    {
        bx_import('BxDolAcl');
        if (isset($this->_checkActionResult))
            return $this->_checkActionResult;
        $iId = getLoggedId();
        $check_res = checkAction( $iId, ACTION_ID_VOTE );
        return ($this->_checkActionResult = ($check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED));
    }

    function isDublicateVote ()
    {
        if (!$this->isEnabled()) return false;
        return $this->_oQuery->isDublicateVote ($this->getId(), getVisitorIP());
    }

    function getId ()
    {
        return $this->_iId;
    }

    function isEnabled ()
    {
        return $this->_aSystem['is_on'];
    }

    function getMaxVote()
    {
        return $this->_aSystem['max_votes'];
    }

    function getVoteCount()
    {
        return $this->_iCount;
    }

    function getVoteRate()
    {
        return $this->_fRate;
    }

    function getSystemName()
    {
        return $this->_sSystem;
    }

    /**
     * set id to operate with votes
     */
    function setId ($iId)
    {
        if ($iId == $this->getId()) return;
        $this->_iId = $iId;
        $this->initVotes();
    }

    function getSqlParts ($sMailTable, $sMailField)
    {
        if ($this->isEnabled())
            return $this->_oQuery->getSqlParts ($sMailTable, $sMailField);
        else
            return array();
    }


    function isValidSystem ($sSystem)
    {
        return isset($this->_aSystems[$sSystem]);
    }

    function deleteVotings ($iId)
    {
        if (!(int)$iId || !$this->_oQuery) 
            return false;
        $this->_oQuery->deleteVotings ($iId);
        return true;
    }

    function getTopVotedItem ($iDays, $sJoinTable = '', $sJoinField = '', $sWhere = '')
    {
        return $this->_oQuery->getTopVotedItem ($iDays, $sJoinTable, $sJoinField, $sWhere);
    }

    function getVotedItems ($sIp)
    {
        return $this->_oQuery->getVotedItems ($sIp);
    }

    /**
     * it is called on cron every day or similar period to clean old votes
     */
    function maintenance () {        
        $iDeletedRecords = 0;
        $oDb = BxDolDb::getInstance();
        foreach ($this->_aSystems as $aSystem) {
            if (!$aSystem['is_on'])
                continue;
            $sPre = $aSystem['row_prefix'];
            $sQuery = $oDb->prepare("DELETE FROM `{$aSystem['table_track']}` WHERE `{$sPre}date` < DATE_SUB(NOW(), INTERVAL ? SECOND)", BX_OLD_VOTES);
            $iDeletedRecords += $oDb->query($sQuery);
            $oDb->query("OPTIMIZE TABLE `{$aSystem['table_track']}`");
        }
        return $iDeletedRecords;
    }

    /** private functions
    *********************************************/

    function _getVoteResult ()
    {
        if (empty($_GET[$this->_aSystem['post_name']]) || 0 != strcasecmp($_SERVER['REQUEST_METHOD'], 'POST'))
            return 0;
        $iVote = (int)$_GET[$this->_aSystem['post_name']];
        if (!$iVote)
            return 0;

        if ($iVote > $this->getMaxVote())
            $iVote = $this->getMaxVote();
        if ($iVote < 1)
            $iVote = 1;
        return $iVote;
    }

    function _triggerVote()
    {
        if (!$this->_aSystem['trigger_table'])
            return false;
        $iId = $this->getId();
        if (!$iId)
            return false;
        $this->initVotes ();
        $iCount = $this->getVoteCount();
        $fRate = $this->getVoteRate();
        return $this->_oQuery->updateTriggerTable($iId, $fRate, $iCount);
    }
}

/** @} */ 
