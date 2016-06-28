<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Message constants passed to _t_ext() function by checkAction()
 *
 * NOTE: checkAction() returns language dependent messages
 */
define('CHECK_ACTION_MESSAGE_NOT_ALLOWED',			"_sys_acl_action_not_allowed");
define('CHECK_ACTION_MESSAGE_LIMIT_REACHED',		"_sys_acl_action_limit_reached");
define('CHECK_ACTION_MESSAGE_MESSAGE_EVERY_PERIOD',	"_sys_acl_action_every_period");
define('CHECK_ACTION_MESSAGE_NOT_ALLOWED_BEFORE',	"_sys_acl_action_not_allowed_before");
define('CHECK_ACTION_MESSAGE_NOT_ALLOWED_AFTER',	"_sys_acl_action_not_allowed_after");

define('CHECK_ACTION_MESSAGE_UNAUTHENTICATED',	    "_sys_acl_action_unauthenticated");
define('CHECK_ACTION_MESSAGE_UNCONFIRMED',			"_sys_acl_action_unconfirmed");
define('CHECK_ACTION_MESSAGE_PENDING',			    "_sys_acl_action_pending");
define('CHECK_ACTION_MESSAGE_SUSPENDED',			"_sys_acl_action_suspended");

/**
 * Nodes of $args array that are passed to _t_ext() function by checkAction()
 */
define('CHECK_ACTION_LANG_FILE_ACTION', 1);
define('CHECK_ACTION_LANG_FILE_MEMBERSHIP', 2);
define('CHECK_ACTION_LANG_FILE_LIMIT', 3);
define('CHECK_ACTION_LANG_FILE_PERIOD', 4);
define('CHECK_ACTION_LANG_FILE_AFTER', 5);
define('CHECK_ACTION_LANG_FILE_BEFORE', 6);
define('CHECK_ACTION_LANG_FILE_SITE_EMAIL',	7);

/**
 * Standard membership ID's
 */
define('MEMBERSHIP_ID_NON_MEMBER', 1);
define('MEMBERSHIP_ID_ACCOUNT', 2);
define('MEMBERSHIP_ID_STANDARD', 3);
define('MEMBERSHIP_ID_UNCONFIRMED', 4);
define('MEMBERSHIP_ID_PENDING', 5);
define('MEMBERSHIP_ID_SUSPENDED', 6);
define('MEMBERSHIP_ID_MODERATOR', 7);
define('MEMBERSHIP_ID_ADMINISTRATOR', 8);

/**
 * Indices for checkAction() result array
 */
define('CHECK_ACTION_RESULT', 0);
define('CHECK_ACTION_MESSAGE', 1);
define('CHECK_ACTION_PARAMETER', 3);

/**
 * CHECK_ACTION_RESULT node values
 */
define('CHECK_ACTION_RESULT_ALLOWED', 0);
define('CHECK_ACTION_RESULT_NOT_ALLOWED', 1);
define('CHECK_ACTION_RESULT_NOT_ACTIVE', 2);
define('CHECK_ACTION_RESULT_LIMIT_REACHED', 3);
define('CHECK_ACTION_RESULT_NOT_ALLOWED_BEFORE', 4);
define('CHECK_ACTION_RESULT_NOT_ALLOWED_AFTER', 5);

/**
 * Standard period units
 */
define('MEMBERSHIP_PERIOD_UNIT_DAY', 'day');
define('MEMBERSHIP_PERIOD_UNIT_WEEK', 'week');
define('MEMBERSHIP_PERIOD_UNIT_MONTH', 'month');
define('MEMBERSHIP_PERIOD_UNIT_YEAR', 'year');


class BxDolAcl extends BxDol implements iBxDolSingleton
{
    protected $oDb;

    protected $_aStandardMemberships = array(
        MEMBERSHIP_ID_NON_MEMBER => 1,
        MEMBERSHIP_ID_ACCOUNT => 1,
        MEMBERSHIP_ID_UNCONFIRMED => 1,
        MEMBERSHIP_ID_PENDING => 1,
        MEMBERSHIP_ID_SUSPENDED => 1,
        MEMBERSHIP_ID_STANDARD => 1,
    );

    protected $_aProfileStatus2LevelMap = array (
        BX_PROFILE_STATUS_SUSPENDED => MEMBERSHIP_ID_SUSPENDED,
        BX_PROFILE_STATUS_PENDING => MEMBERSHIP_ID_PENDING,
    );

    protected $_aLevel2MessageMap = array (
        MEMBERSHIP_ID_NON_MEMBER => '_sys_acl_action_unauthenticated',
        MEMBERSHIP_ID_ACCOUNT => '_sys_acl_action_account',
        MEMBERSHIP_ID_UNCONFIRMED => '_sys_acl_action_unconfirmed',
        MEMBERSHIP_ID_PENDING => '_sys_acl_action_pending',
        MEMBERSHIP_ID_SUSPENDED => '_sys_acl_action_suspended',
    );

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->oDb = new BxDolAclQuery();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplAcl();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Check if member has one of the provided membership levels
     * @param $iPermissions - integer value to check permissions for, every bit is matched with some membership id
     * @param $iProfileId - profile to check, if it isn't provided or is false then currently logged in profile is used.
     * @return true if member has privided membership levels, or false if member hasn't.
     */
    public function isMemberLevelInSet($iPermissions, $iProfileId = false)
    {
        if (!$iPermissions)
            return false;

        if (false === $iProfileId) {
            $oProfile = BxDolProfile::getInstance();
            $iProfileId = $oProfile ? $oProfile->id() : 0;
        }

        $aACL = $this->getMemberMembershipInfo($iProfileId);
        return ($iPermissions & pow(2, $aACL['id'] - 1));
    }

    /**
     * Checks if a given action is allowed for a given profile and updates action information if the
     * action is performed.
     *
     * @param  int     $iProfileId     ID of a profile that is going to perform an action
     * @param  int     $iActionId      ID of the action itself
     * @param  boolean $bPerformAction if true, then action information is updated, i.e. action is 'performed'
     * @return array(
     *                                CHECK_ACTION_RESULT => CHECK_ACTION_RESULT_ constant,
     *                                CHECK_ACTION_MESSAGE => CHECK_ACTION_MESSAGE_ constant,
     *                                CHECK_ACTION_PARAMETER => additional action parameter (string)
     *                                )
     *
     * NOTES:
     *
     * $aResult[CHECK_ACTION_MESSAGE] contains a message with detailed information about the result,
     * already processed by the language file
     *
     * if $aResult[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED then this node contains
     * an empty string
     *
     * The error messages themselves are stored in the language file. Additional variables are
     * passed to the languages.inc.php function _t_ext() as an array and can be used there in the form of
     * {0}, {1}, {2} ...
     *
     * Additional variables passed to the lang. file on errors (can be used in error messages):
     *
     * 	For all errors:
     *
     * 		$arg0[CHECK_ACTION_LANG_FILE_ACTION]	= name of the action
     * 		$arg0[CHECK_ACTION_LANG_FILE_MEMBERSHIP]= name of the current membership
     *
     * 	CHECK_ACTION_RESULT_LIMIT_REACHED:
     *
     * 		$arg0[CHECK_ACTION_LANG_FILE_LIMIT]		= limit on number of actions allowed for the profile
     * 		$arg0[CHECK_ACTION_LANG_FILE_PERIOD]	= period that the limit is set for (in hours, 0 if unlimited)
     *
     * 	CHECK_ACTION_RESULT_NOT_ALLOWED_BEFORE:
     *
     * 		$arg0[CHECK_ACTION_LANG_FILE_BEFORE]	= date/time since when the action is allowed
     *
     * 	CHECK_ACTION_RESULT_NOT_ALLOWED_AFTER:
     *
     * 		$arg0[CHECK_ACTION_LANG_FILE_AFTER]		= date/time since when the action is not allowed
     *
     * $aResult[CHECK_ACTION_PARAMETER] contains an additional parameter that can be considered
     * when performing the action (like the number of profiles to show in search result)
    */
    function checkAction($iProfileId, $iActionId, $bPerformAction = false)
    {
        $aResult = array();
        $aLangFileParams = array();

        $iProfileId = (int)$iProfileId;
        $iActionId = (int)$iActionId;
        $bPerformAction = $bPerformAction ? true : false;

        $aMembership = $this->getMemberMembershipInfo($iProfileId); // get current profile's membership information

        $aLangFileParams[CHECK_ACTION_LANG_FILE_MEMBERSHIP] = _t($aMembership['name']);
        $aLangFileParams[CHECK_ACTION_LANG_FILE_SITE_EMAIL] = getParam('site_email');

        $aAction = $this->oDb->getAction($aMembership['id'], $iActionId);
        if (!$aAction)
            bx_trigger_error('Unknown action ID: ' . $iActionId, 2);

        $aResult[CHECK_ACTION_PARAMETER] = $aAction['additional_param_value'];
        $aLangFileParams[CHECK_ACTION_LANG_FILE_ACTION] = !empty($aAction['title']) ? _t($aAction['title']) : $aAction['name'];

        /**
         * Action is not allowed for the current membership
         */
        if (is_null($aAction['id'])) {

            $sLangKey = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            if (isset($this->_aLevel2MessageMap[$aMembership['id']]))
                $sLangKey = $this->_aLevel2MessageMap[$aMembership['id']];

            $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_NOT_ALLOWED;
            $aResult[CHECK_ACTION_MESSAGE] = _t_ext($sLangKey, $aLangFileParams);
            return $aResult;
        }

        /**
         * Check fixed period limitations if present (also for non-members)
         */
        if($aAction['allowed_period_start'] && time() < $aAction['allowed_period_start']) {
            $aLangFileParams[CHECK_ACTION_LANG_FILE_BEFORE] = bx_time_js($aAction['allowed_period_start'], BX_FORMAT_DATE_TIME);

            $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_NOT_ALLOWED_BEFORE;
            $aResult[CHECK_ACTION_MESSAGE] = _t_ext(CHECK_ACTION_MESSAGE_NOT_ALLOWED_BEFORE, $aLangFileParams);

            return $aResult;
        }

        if($aAction['allowed_period_end'] && time() > $aAction['allowed_period_end']) {
            $aLangFileParams[CHECK_ACTION_LANG_FILE_AFTER] = bx_time_js($aAction['allowed_period_end'], BX_FORMAT_DATE_TIME);

            $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_NOT_ALLOWED_AFTER;
            $aResult[CHECK_ACTION_MESSAGE] = _t_ext(CHECK_ACTION_MESSAGE_NOT_ALLOWED_AFTER, $aLangFileParams);

            return $aResult;
        }

        /**
         * if non-member, allow action without performing further checks
         */
        if ($aMembership['id'] == MEMBERSHIP_ID_NON_MEMBER) {
            $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
            return $aResult;
        }

        /**
         * Check other limitations (for members only)
         */
        $iAllowedCnt = (int)$aAction['allowed_count']; ///< Number of allowed actions. Unlimited if not specified or 0
        $iPeriodLen = (int)$aAction['allowed_period_len']; ///< Period for AllowedCount in hours. If not specified, AllowedCount is treated as total number of actions permitted.

        if($iAllowedCnt > 0) {
            $aActionTrack = $this->oDb->getActionTrack($iActionId, $iProfileId);

            $iActionsLeft = $bPerformAction ? $iAllowedCnt - 1 : $iAllowedCnt;
            $iValidSince = time();

            /**
             * Member is requesting/performing this action for the first time,
             * and there is no corresponding record in sys_acl_actions_track table.
             */
            if(!$aActionTrack) {
                $this->oDb->insertActionTarck($iActionId, $iProfileId, $iActionsLeft, $iValidSince);

                $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
                return $aResult;
            }

            /**
             * Action has been requested/performed at least once at this point and there is a corresponding record in sys_acl_actions_track table
             *
             * Action record in sys_acl_actions_track table is out of date.
             */
            $iPeriodEnd = (int)$aActionTrack['valid_since'] + $iPeriodLen * 3600; //ValidSince is in seconds, PeriodLen is in hours
            if($iPeriodLen > 0 && $iPeriodEnd < time()) {
                $this->oDb->updateActionTrack($iActionId, $iProfileId, $iActionsLeft, $iValidSince);

                $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
                return $aResult;
            }

            $iActionsLeft = (int)$aActionTrack['actions_left']; ///< Action record is up to date

            /**
             * Action limit reached for now
             */
            if($iActionsLeft <= 0){
                $aLangFileParams[CHECK_ACTION_LANG_FILE_LIMIT] = $iAllowedCnt;
                $aLangFileParams[CHECK_ACTION_LANG_FILE_PERIOD] = $iPeriodLen;

                $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_LIMIT_REACHED;
                $aResult[CHECK_ACTION_MESSAGE] = '<div style="width: 80%">' . _t_ext(CHECK_ACTION_MESSAGE_LIMIT_REACHED, $aLangFileParams) . ($iPeriodLen > 0 ? _t_ext(CHECK_ACTION_MESSAGE_MESSAGE_EVERY_PERIOD, $aLangFileParams) : '') . '.</div>';

                return $aResult;
            }

            if($bPerformAction) {
                $iActionsLeft--;
                $this->oDb->updateActionTrack($iActionId, $iProfileId, $iActionsLeft);
            }
        }

        $aResult[CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        return $aResult;
    }

    /**
     * Get the list of existing memberships
     *
     * @param  bool   $bPurchasableOnly if true, fetches only purchasable memberships; 'purchasable' here means that:
     *                                  - MemLevels.Purchasable = 'yes'
     *                                  - MemLevels.Active = 'yes'
     *                                  - there is at least one pricing option for the membership
     * @return array( membershipID_1 => membershipName_1,  membershipID_2 => membershipName_2, ...) if no such memberships, then just array()
     */
    function getMemberships($bPurchasableOnly = false, $bActiveOnly = false, $isTranslate = true)
    {
        $sType = 'all_pair';
        if($bPurchasableOnly)
            $sType = 'all_active_purchasble_pair';
        else if($bActiveOnly)
            $sType = 'all_active_pair';

        $aLevels = array();
        $this->oDb->getLevels(array('type' => $sType), $aLevels, false);
        if ($isTranslate)
            foreach ($aLevels as $k => $s)
                $aLevels[$k] = _t($s);
        return $aLevels;
    }

    /**
     * Get info about a given membership
     *
     * @param  int    $iLevelId membership to get info about
     * @return array(
     *                         'id'					=>	ID,
     *                         'name'					=>	name,
     *                         'icon'					=>	icon,
     *                         'description'			=>	description,
     *                         'active'				=>	active,
     *                         'purchasable'			=>	purchasable,
     *                         'removable'				=>	removable
     *                         'quota_size'			=>	quota size,
     *                         'quota_number'			=>	quota number,
     *                         'quota_max_file_size'	=>	quota max file size
     *                         )
     */
    function getMembershipInfo($iLevelId)
    {
        $aLevel = array();
        $this->oDb->getLevels(array('type' => 'by_id', 'value' => $iLevelId), $aLevel, false);
        return $aLevel;
    }

    /**
     * Retrieves information about membership for a given profile at a given moment.
     *
     * If there are no memberships purchased/assigned to the
     * given profile or all of them have expired at the given point,
     * the profile is assumed to be a standard profile, and the function
     * returns	information about the Standard membership. This will
     * also happen if a profile wasnt actually registered in the database
     * at that point - the function will still return info about Standard
     * membership, not the Non-member one.
     *
     * If there is no profile with the given $iProfileId,
     * the function returns information about the Non-member or Authenticated
     * predefined membership.
     *
     * The Standard, Authenticated and Non-member memberships have their
     * DateStarts and DateExpires attributes set to NULL.
     *
     * @param  int    $iProfileId ID of a profile to get info about
     * @param  int    $time       specifies the time to use when determining membership; if not specified, the function takes the current time
     * @return array(
     *                           'id'			=> membership id,
     *                           'name'			=> membership name,
     *                           'date_starts'	=> (UNIX timestamp) date/time purchased,
     *                           'date_expires'	=> (UNIX timestamp) date/time expires
     *                           )
     */
    function getMemberMembershipInfo($iProfileId, $iTime = 0)
    {
        $aMembershipCurrent = $this->getMemberMembershipInfoCurrent($iProfileId, $iTime);
        if (isset($this->_aStandardMemberships[$aMembershipCurrent['id']]))
            return $aMembershipCurrent;

        $aMembership = $aMembershipCurrent;
        do {
            $iDateStarts = $aMembership['date_starts'];
            $aMembership = $this->getMemberMembershipInfoCurrent($iProfileId, ((int)$iDateStarts < 1 ? 0 : $iDateStarts - 1));
        }
        while($aMembership['id'] == $aMembershipCurrent['id'] && (int)$aMembership['date_starts']);

        $aMembership = $aMembershipCurrent;
        do {
            $iDateExpires = $aMembership['date_expires'];
            $aMembership = $this->getMemberMembershipInfoCurrent($iProfileId, $iDateExpires);
        } while($aMembership['id'] == $aMembershipCurrent['id'] && (int)$aMembership['date_expires']);

        $aMembershipCurrent['date_starts'] = $iDateStarts;
        $aMembershipCurrent['date_expires'] = $iDateExpires;

        return $aMembershipCurrent;
    }

    /**
     * Set a membership for a profile
     *
     * @param  int     $iProfileId profile that is going to get the membership
     * @param  int     $iLevelId   membership that is going to be assigned to the profile
     *                             if $iLevelId == MEMBERSHIP_ID_STANDARD then $days and $bStartsNow parameters are not used,
     *                             so Standard membership is always set immediately and `forever`
     * @param  mixed   $mixedPeriod  number of Days to set membership for or an array with 'period'-'period unit' pair. If number or 'period' in pair equal 0, then the membership is set forever
     * @param  boolean $bStartsNow if true, the membership will start immediately if false, the membership will start after the current membership expires
     * @return boolean true in case of success, false in case of failure
     */
    function setMembership($iProfileId, $iLevelId, $mixedPeriod = 0, $bStartsNow = false, $sTransactionId = '')
    {
        $iProfileId = (int)$iProfileId;
        $iLevelId = (int)$iLevelId;
        $bStartsNow = $bStartsNow ? true : false;

        if (!$iProfileId)
            $iProfileId = -1;

        if (empty($sTransactionId))
            $sTransactionId = 'NULL';

        // check if profile exists
        if(($sProfileEmail = BxDolProfileQuery::getInstance()->getEmailById($iProfileId)) === false)
            return false;

        // check if membership exists
        $aLevel = array();
        $this->oDb->getLevels(array('type' => 'by_id', 'value' => $iLevelId), $aLevel, false);
        if(empty($aLevel) || !is_array($aLevel))
            return false;

        if($iLevelId == MEMBERSHIP_ID_NON_MEMBER)
            return false;

        $aMembershipCurrent = $this->getMemberMembershipInfo($iProfileId);
        $aMembershipLatest = $this->getMemberMembershipInfoLatest($iProfileId);

        // setting Standard membership level
        if ($iLevelId == MEMBERSHIP_ID_STANDARD) {
            if ($aMembershipCurrent['id'] == MEMBERSHIP_ID_STANDARD)
                return true;

            // delete present and future memberships
            return $this->oDb->deleteLevelByProfileId($iProfileId);
        }

        if ((is_numeric($mixedPeriod) && (int)$mixedPeriod < 0) || (is_array($mixedPeriod) && (!isset($mixedPeriod['period']) || $mixedPeriod['period'] < 0)))
            return false;

        $iDateStarts = time();
        if (!$bStartsNow) {
            // Make the membership starts after the latest membership expires or return false if latest membership isn't Standard and is lifetime membership.
            if(!empty($aMembershipLatest['date_expires']))
                $iDateStarts = $aMembershipLatest['date_expires'];
            else if(empty($aMembershipLatest['date_expires']) && $aMembershipLatest['id'] != MEMBERSHIP_ID_STANDARD)
                return false;
        }
        else {
            // Delete any profile's membership level and actions traces
            $this->oDb->deleteLevelByProfileId($iProfileId, true); 
            $this->oDb->clearActionsTracksForMember($iProfileId);
        }

        // set lifetime membership if 0 days is used.
        if(is_numeric($mixedPeriod))
        	$mixedPeriod = array('period' => (int)$mixedPeriod, 'period_unit' => MEMBERSHIP_PERIOD_UNIT_DAY);

        if(!$this->oDb->insertLevelByProfileId($iProfileId, $iLevelId, $iDateStarts, $mixedPeriod, $sTransactionId))
           return false;

        // raise membership alert
        $oZ = new BxDolAlerts('profile', 'set_membership', '', $iProfileId, array('mlevel'=> $iLevelId, 'period' => $mixedPeriod['period'], 'period_unit' => $mixedPeriod['period_unit'], 'starts_now' => $bStartsNow, 'txn_id' => $sTransactionId));
        $oZ->alert();

        // Send notification
        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_MemChanged', array('membership_level' => _t($aLevel['name'])), 0, $iProfileId);
        if ($aTemplate)
            sendMail($sProfileEmail, $aTemplate['Subject'], $aTemplate['Body']);

        return true;
    }

    function unsetMembership($iProfileId, $iLevelId, $sTransactionId)
    {
    	return $this->oDb->deleteLevelBy(array(
    		'IDMember' => $iProfileId,
    		'IDLevel' => $iLevelId,
    		'TransactionID' => $sTransactionId
    	));
    }

    /**
     * get action id by module and name
     * @param $sAction action name
     * @param $sModule module name
     * @param $aActions array of actions from sys_acl_actions table, with default array keys (starting from 0) and text values
     */
    function getMembershipActionId($sAction, $sModule)
    {
        $this->oDb->getActions(array('type' => 'by_names_and_module', 'value' => $sAction, 'module' => $sModule), $aActions, false);
        if (count($aActions) > 1)
            trigger_error('Duplicate action - name:' . $sAction . ', module:' . $sModule, E_USER_ERROR);
        $aAction = array_pop($aActions);
        return $aAction['id'];
    }

    function getExpirationLetter($iProfileId, $sLevelName, $iLevelExpireDays )
    {
        $iProfileId = (int)$iProfileId;

        if(!$iProfileId)
            return false;

        $oProfileQuery = BxDolProfileQuery::getInstance();
        $sProfileEmail = $oProfileQuery->getEmailById($iProfileId);

        $aPlus = array(
            'membership_name' => $sLevelName,
            'expire_days' => $iLevelExpireDays
        );

        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_MemExpiration', $aPlus, 0, $iProfileId);

        $iResult = $aTemplate && sendMail($sProfileEmail, $aTemplate['Subject'], $aTemplate['Body'], $iProfileId, $aPlus);
        return !empty($iResult);
    }

    /**
     * clear expired membership levels
     */
    public function maintenance ()
    {
        $iDaysToCleanMemLevels = (int) getParam("db_clean_mem_levels");
        return $this->oDb->maintenance ($iDaysToCleanMemLevels);
    }

    protected function getMemberMembershipInfoCurrent($iProfileId, $iTime = 0)
    {
        $aMemLevel = false;

        // get profile status
        $oProfile = BxDolProfile::getInstance($iProfileId);
        $aProfileInfo = $oProfile ? $oProfile->getInfo() : false;
        $sProfileStatus = $aProfileInfo ? $aProfileInfo['status'] : false;
        $sProfileType = $aProfileInfo ? $aProfileInfo['type'] : false;

        // account profile
	    if($sProfileType == 'system') {
        	$aMemLevel = $this->oDb->getLevelByIdCached(MEMBERSHIP_ID_ACCOUNT);
        	if (!$aMemLevel)
                trigger_error ('Standard member level is missing: ' . MEMBERSHIP_ID_ACCOUNT, E_USER_ERROR);

        	return $aMemLevel;
        }

        // profile is not active, so return standard memberships according to profile status
        if (BX_PROFILE_STATUS_ACTIVE != $sProfileStatus) {
            $oAccount = $aProfileInfo ? BxDolAccount::getInstance($aProfileInfo['account_id']) : false;
            if ($oAccount && !$oAccount->isConfirmed())
                $iLevelId = MEMBERSHIP_ID_UNCONFIRMED; // every account's profile is unconfirmed if account is unconfirmed
            elseif (!isset($this->_aProfileStatus2LevelMap[$sProfileStatus]))
                $iLevelId = MEMBERSHIP_ID_NON_MEMBER; // if there is no profile status - then it isn't member
            else
                $iLevelId = $this->_aProfileStatus2LevelMap[$sProfileStatus]; // get member level id which associated with every non-active status

            $aMemLevel = $this->oDb->getLevelByIdCached($iLevelId);

            if (!$aMemLevel)
                trigger_error ('Standard member level is missing: ' . $iLevelId, E_USER_ERROR);

            return $aMemLevel;
        }

        // profile is active get memebr level from profile
        $aMemLevel = $this->oDb->getLevelCurrent((int)$iProfileId, $iTime);

        // There are no purchased/assigned memberships for the profile or all of them have expired.
        // In this case the profile is assumed to have Standard membership.
        if (!$aMemLevel || is_null($aMemLevel['id'])) {
            $aMemLevel = $this->oDb->getLevelByIdCached(MEMBERSHIP_ID_STANDARD);
            if (!$aMemLevel)
                trigger_error ('Standard member level is missing: ' . MEMBERSHIP_ID_NON_MEMBER, E_USER_ERROR);
        }

        return $aMemLevel;
    }

    protected function getMemberMembershipInfoLatest($iProfileId, $iTime = 0)
    {
        $aMembershipCurrent = $this->getMemberMembershipInfoCurrent($iProfileId, $iTime);
        if (isset($this->_aStandardMemberships[$aMembershipCurrent['id']]))
            return $aMembershipCurrent;

        $aMembership = $aMembershipCurrent;
        while ($aMembership['id'] != MEMBERSHIP_ID_STANDARD) {
            $aMembershipLast = $aMembership;
            if(!isset($aMembership['date_expires']) || (int)$aMembership['date_expires'] == 0)
                break;

            $aMembership = $this->getMemberMembershipInfoCurrent($iProfileId, $aMembership['date_expires']);
        }

        return $aMembershipLast;
    }

    public function onProfileDelete ($iProfileId)
    {
        return $this->oDb->deleteLevelByProfileId($iProfileId, true);
    }
}

function checkAction($iProfileId, $iActionId, $bPerformAction = false)
{
    return BxDolAcl::getInstance()->checkAction($iProfileId, $iActionId, $bPerformAction);
}

function checkActionModule($iProfileId, $sActionName, $sModuleName, $bPerformAction = false)
{
    $oACL = BxDolAcl::getInstance();

    $iActionId = $oACL->getMembershipActionId($sActionName, $sModuleName);

    return $oACL->checkAction($iProfileId, $iActionId, $bPerformAction);
}

/** @} */
