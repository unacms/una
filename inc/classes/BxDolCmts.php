<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolPermalinks');
bx_import('BxDolCmtsQuery');

define('BX_CMT_OLD_VOTES', 365*86400); ///< comment votes older than this number of seconds will be deleted automatically

define('BX_CMT_ACTION_POST', 'post');
define('BX_CMT_ACTION_EDIT', 'edit');

define('BX_CMT_DISPLAY_FLAT', 'flat');
define('BX_CMT_DISPLAY_THREADED', 'threaded');

define('BX_CMT_BROWSE_HEAD', 'head');
define('BX_CMT_BROWSE_TAIL', 'tail');
define('BX_CMT_BROWSE_POPULAR', 'popular');
define('BX_CMT_BROWSE_CONNECTION', 'connection');

define('BX_CMT_ORDER_BY_DATE', 'date');
define('BX_CMT_ORDER_BY_POPULAR', 'popular');

define('BX_CMT_FILTER_ALL', 'all');
define('BX_CMT_FILTER_OTHERS', 'others');
define('BX_CMT_FILTER_FRIENDS', 'friends');
define('BX_CMT_FILTER_SUBSCRIPTIONS', 'subscriptions');

define('BX_CMT_ORDER_WAY_ASC', 'asc');
define('BX_CMT_ORDER_WAY_DESC', 'desc');

define('BX_CMT_PFP_TOP', 'top');
define('BX_CMT_PFP_BOTTOM', 'bottom');

define('BX_CMT_RATE_VALUE_PLUS', 1);
define('BX_CMT_RATE_VALUE_MINUS', -1);

/**
 * @page objects
 * @section comments Comments
 * @ref BxDolCmts
 */

/**
 * Comments for any content
 *
 * Related classes:
 * - BxDolCmtsQuery - comments database queries
 * - BxBaseCmts - comments base representation
 * - BxTemplCmts - custom template representation
 *
 * AJAX comments for any content.
 * Self moderated - users rate all comments, and if comment is
 * below viewing treshold it is hidden by default.
 *
 * To add comments section to your module you need to add a record to 'sys_objects_cmts' table:
 *
 * - ID - autoincremented id for internal usage
 * - ObjectName - your unique module name, with vendor prefix, lowercase and spaces are underscored
 * - TableCmts - table name where comments are stored
 * - Nl2br - convert new lines to <br /> on saving
 * - PerView - number of comments on a page
 * - IsRatable - 0 or 1 allow to rate comments or not
 * - ViewingThreshold - comment viewing treshost, if comment is below this number it is hidden by default
 * - IsOn - is this comment object enabled
 * - RootStylePrefix - toot comments style prefix, if you need root comments look different\
 * - ObjectVote - Vote object name to process comments' votes. May be empty if Comment Vote is not needed.
 * - TriggerTable - table to be updated upon each comment
 * - TriggerFieldId - TriggerTable table field with unique record id of
 * - TriggerFieldComments - TriggerTable table field with comments count, it will be updated automatically upon eaech comment
 * - ClassName - your custom class name if you need to override default class, this class must have the same constructor arguments
 * - ClassFile - file where your ClassName is stored.
 *
 * You can refer to BoonEx modules for sample record in this table.
 *
 *
 *
 * @section example Example of usage:
 * After filling in the table you can show comments section in any place, using the following code:
 *
 * @code
 * bx_import ('BxTemplCmts');
 * $o = new BxTemplCmts('value of ObjectName field', $iYourEntryId);
 * if ($o->isEnabled())
 *     echo $o->getCommentsFirst ();
 * @endcode
 *
 * Please note that you never need to use BxDolCmts class directly, use BxTemplCmts instead.
 * Also if you override comments class with your own then make it child of BxTemplCmts class.
 *
 *
 *
 * @section acl Memberships/ACL:
 * - comments post
 * - comments edit own
 * - comments remove own
 * - comments edit all
 *
 *
 *
 * @section alerts Alerts:
 * Alerts type/unit - every module has own type/unit, it equals to ObjectName.
 *
 * The following alerts are rised
 *
 * - commentPost - comment was posted
 *      - $iObjectId - entry id
 *      - $iSenderId - author of comment
 *      - $aExtra['comment_id'] - just added comment id
 *
 * - commentRemoved - comments was removed
 *      - $iObjectId - entry id
 *      - $iSenderId - comment deleter id
 *      - $aExtra['comment_id'] - removed comment id
 *
 * - commentUpdated - comments was updated
 *      - $iObjectId - entry id
 *      - $iSenderId - comment deleter id
 *      - $aExtra['comment_id'] - updated comment id
 *
 * - commentRated - comments was rated
 *      - $iObjectId - entry id
 *      - $iSenderId - comment rater id
 *      - $aExtra['comment_id'] - rated comment id
 *      - $aExtra['rate'] - comment rate 1 or -1
 *
 */
class BxDolCmts extends BxDol implements iBxDolReplaceable
{
    protected $_oQuery = null;

    protected $_sFormObject;
    protected $_sFormDisplayPost;
    protected $_sFormDisplayEdit;

    protected $_sTableImages;
    protected $_sTableImages2Entries;

    protected $_sConnObjFriends;
    protected $_sConnObjSubscriptions;

    protected $_sMenuObjManage;
    protected $_sMenuObjActions;

    protected $_sViewUrl = '';
    protected $_sBaseUrl = '';
    protected $_sListAnchor = '';

    protected $_sSystem = 'profile'; ///< current comment system name
    protected $_aSystem = array (); ///< current comments system array
    protected $_iId = 0; ///< obect id to be commented

    protected $_aMarkers = array ();

    protected $_sDisplayType = '';
    protected $_sDpSessionKey = '';
    protected $_iDpMaxLevel = 0;

    protected $_sBrowseType = '';
    protected $_sBrowseFilter = '';
    protected $_sBpSessionKeyType = '';
    protected $_sBpSessionKeyFilter = '';
    protected $_aOrder = array();

    protected $_sSnippetLenthLiveSearch = 50;

    protected $_iRememberTime = 2592000;

    /**
     * Constructor
     * $sSystem - comments system name
     * $iId - obect id to be commented
     */
    function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct();

        $this->_aSystems = $this->getSystems();
        if(!isset($this->_aSystems[$sSystem]))
            return;

        $this->_sSystem = $sSystem;
        $this->_aSystem = $this->_aSystems[$sSystem];

        $this->_aSystem['table_images'] = 'sys_cmts_images';
        $this->_aSystem['table_images2entries'] = 'sys_cmts_images2entries';

        $this->_aSystem['table_ids'] = 'sys_cmts_ids';

        $this->_iDpMaxLevel = (int)$this->_aSystem['number_of_levels'];
        $this->_sDisplayType = $this->_iDpMaxLevel == 0 ? BX_CMT_DISPLAY_FLAT : BX_CMT_DISPLAY_THREADED;
        $this->_sDpSessionKey = 'bx_' . $this->_sSystem . '_dp_';

        $this->_sBrowseType = $this->_aSystem['browse_type'];
        $this->_sBrowseFilter = BX_CMT_FILTER_ALL;
        $this->_sBpSessionKeyType = 'bx_' . $this->_sSystem . '_bpt_';
        $this->_sBpSessionKeyFilter = 'bx_' . $this->_sSystem . '_bpf_';
        $this->_aOrder = array(
            'by' => BX_CMT_ORDER_BY_DATE,
            'way' => BX_CMT_ORDER_WAY_ASC
        );

        list($mixedUserDp, $mixedUserBpType, $mixedUserBpFilter) = $this->_getUserChoice();
        if(!empty($mixedUserDp))
            $this->_sDisplayType = $mixedUserDp;
        if(!empty($mixedUserBpType))
            $this->_sBrowseType = $mixedUserBpType;
        if(!empty($mixedUserBpFilter))
            $this->_sBrowseFilter = $mixedUserBpFilter;

        $this->_sViewUrl = BX_DOL_URL_ROOT . 'cmts.php';
        $this->_sBaseUrl = BxDolPermalinks::getInstance()->permalink($this->_aSystem['base_url']);
        if(get_mb_substr($this->_sBaseUrl, 0, 4) != 'http')
            $this->_sBaseUrl = BX_DOL_URL_ROOT . $this->_sBaseUrl;
        $this->_sListAnchor = "cmts-anchor-%s-%d";

        $this->_oQuery = new BxDolCmtsQuery($this);

        $this->_sFormObject = 'sys_comment';
        $this->_sFormDisplayPost = 'sys_comment_post';
        $this->_sFormDisplayEdit = 'sys_comment_edit';

        $this->_sConnObjFriends = 'sys_profiles_friends';
        $this->_sConnObjSubscriptions = 'sys_profiles_subscriptions';

        $this->_sMenuObjManage = 'sys_cmts_item_manage';
        $this->_sMenuObjActions = 'sys_cmts_item_actions';

        $this->_sMetatagsObj = 'sys_cmts';

        if ($iInit)
            $this->init($iId);
    }

    /**
     * get comments object instanse
     * @param $sSys comments object name
     * @param $iId associated content id, where comments are postred in
     * @param $iInit perform initialization
     * @return null on error, or ready to use class instance
     */
    public static function getObjectInstance($sSys, $iId, $iInit = true)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolCmts!' . $sSys . $iId]))
            return $GLOBALS['bxDolClasses']['BxDolCmts!' . $sSys . $iId];

        $aSystems = self::getSystems();
        if (!isset($aSystems[$sSys]))
            return null;

        bx_import('BxTemplCmts');
        $sClassName = 'BxTemplCmts';
        if(!empty($aSystems[$sSys]['class_name'])) {
            $sClassName = $aSystems[$sSys]['class_name'];
            if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
            else
                bx_import($sClassName);
        }

        $o = new $sClassName($sSys, $iId, $iInit);
        return ($GLOBALS['bxDolClasses']['BxDolCmts!' . $sSys . $iId] = $o);
    }

    public static function &getSystems ()
    {
        if (!isset($GLOBALS['bx_dol_cmts_systems'])) {
            $GLOBALS['bx_dol_cmts_systems'] = BxDolDb::getInstance()->fromCache('sys_objects_cmts', 'getAllWithKey', '
                SELECT
                    `ID` as `system_id`,
                    `Name` AS `name`,
                    `Table` AS `table`,
                    `CharsPostMin` AS `chars_post_min`,
                    `CharsPostMax` AS `chars_post_max`,
                    `CharsDisplayMax` AS `chars_display_max`,
                    `Nl2br` AS `nl2br`,
                    `PerView` AS `per_view`,
                    `PerViewReplies` AS `per_view_replies`,
                    `BrowseType` AS `browse_type`,
                    `IsBrowseSwitch` AS `is_browse_switch`,
                    `PostFormPosition` AS `post_form_position`,
                    `NumberOfLevels` AS `number_of_levels`,
                    `IsDisplaySwitch` AS `is_display_switch`,
                    `IsRatable` AS `is_ratable`,
                    `ViewingThreshold` AS `viewing_threshold`,
                    `IsOn` AS `is_on`,
                    `RootStylePrefix` AS `root_style_prefix`,
                    `BaseUrl` AS `base_url`,
                    `ObjectVote` AS `object_vote`,
                    `TriggerTable` AS `trigger_table`,
                    `TriggerFieldId` AS `trigger_field_id`,
                    `TriggerFieldTitle` AS `trigger_field_title`,
                    `TriggerFieldComments` AS `trigger_field_comments`,
                    `ClassName` AS `class_name`,
                    `ClassFile` AS `class_file`
                FROM `sys_objects_cmts`', 'name');
        }
        return $GLOBALS['bx_dol_cmts_systems'];
    }

    public function init ($iId)
    {
        if (empty($this->iId) && $iId)
            $this->setId($iId);

        $this->addMarkers(array(
            'object_id' => $this->getId(),
            'user_id' => $this->_getAuthorId()
        ));
    }

    public function getId ()
    {
        return $this->_iId;
    }

    public function isEnabled ()
    {
        return isset($this->_aSystem['is_on']) && $this->_aSystem['is_on'];
    }

    public function getSystemId()
    {
        return $this->_aSystem['system_id'];
    }

    public function getSystemName()
    {
        return $this->_sSystem;
    }

    public function getStorageObjectName()
    {
    	return $this->_getFormObject()->getStorageObjectName();
    }

	public function getTranscoderPreviewName()
    {
    	return $this->_getFormObject()->getTranscoderPreviewName();
    }

    public function getSystemInfo()
    {
        return $this->_aSystem;
    }

    public function getMaxLevel()
    {
        return $this->_iDpMaxLevel;
    }

    public function getOrder ()
    {
        return $this->_sOrder;
    }

    public function getPerView ($iCmtParentId = 0)
    {
        return $iCmtParentId == 0 ? $this->_aSystem['per_view'] : $this->_aSystem['per_view_replies'];
    }

    public function getBaseUrl()
    {
        return $this->_replaceMarkers($this->_sBaseUrl);
    }

    public function getListUrl()
    {
        return $this->getBaseUrl() . '#' . $this->getListAnchor();
    }

    public function getListAnchor()
    {
        return sprintf($this->_sListAnchor, str_replace('_', '-', $this->getSystemName()), $this->getId());
    }

	public function getViewUrl($iCmtId)
    {
    	if(empty($this->_aSystem['trigger_field_title']))
    		return '';

    	return bx_append_url_params($this->_sViewUrl, array(
			'sys' => $this->_sSystem,
			'id' => $this->_iId,
			'cmt_id' => $iCmtId
		));
    }

    public function getConnectionObject($sType)
    {
        $sResult = '';

        switch($sType) {
            case BX_CMT_FILTER_FRIENDS:
                $sResult = $this->_sConnObjFriends;
                break;
            case BX_CMT_FILTER_SUBSCRIPTIONS:
                $sResult = $this->_sConnObjSubscriptions;
                break;
        }

        return $sResult;
    }

    public function getVoteObject($iId)
    {
        if(empty($this->_aSystem['object_vote']))
        	$this->_aSystem['object_vote'] = 'sys_cmts';

        bx_import('BxDolVote');
        $oVote = BxDolVote::getObjectInstance($this->_aSystem['object_vote'], $iId);
        if(!$oVote || !$oVote->isEnabled())
            return false;

        return $oVote;
    }

    public function isNl2br ()
    {
        return $this->_aSystem['nl2br'];
    }

    public function isRatable ()
    {
        return $this->_aSystem['is_ratable'];
    }

    public function isAttachImageEnabled()
    {
        return true;
    }

    /**
     * set id to operate with votes
     */
    public function setId ($iId)
    {
        if ($iId == $this->getId()) return;
        $this->_iId = $iId;
    }

    /**
     * Add replace markers.
     * @param $a array of markers as key => value
     * @return true on success or false on error
     */
    public function addMarkers ($a)
    {
        if (empty($a) || !is_array($a))
            return false;
        $this->_aMarkers = array_merge ($this->_aMarkers, $a);
        return true;
    }

    /**
     * Database functions
     */
    public function getQueryObject ()
    {
        return $this->_oQuery;
    }

    public function getCommentsTableName ()
    {
        return $this->_oQuery->getTableName ();
    }

    public function getObjectTitle ($iObjectId = 0)
    {
        return $this->_oQuery->getObjectTitle ($iObjectId ? $iObjectId : $this->getId());
    }

    public function getCommentsCount ($iObjectId = 0, $iCmtVParentId = -1, $sFilter = '')
    {
        return $this->_oQuery->getCommentsCount ($iObjectId ? $iObjectId : $this->getId(), $iCmtVParentId, $this->_getAuthorId(), $sFilter);
    }

    public function getCommentsArray ($iVParentId, $sFilter, $aOrder, $iStart = 0, $iCount = -1)
    {
        return $this->_oQuery->getComments ($this->getId(), $iVParentId, $this->_getAuthorId(), $sFilter, $aOrder, $iStart, $iCount);
    }

    public function getCommentRow ($iCmtId)
    {
        return $this->_oQuery->getComment ($this->getId(), $iCmtId);
    }

    public function onObjectDelete ($iObjectId = 0)
    {
        // delete comments
        $aFiles = $aCmtIds = array();
        $this->_oQuery->deleteObjectComments ($iObjectId ? $iObjectId : $this->getId(), $aFiles, $aCmtIds);

        // delete meta info
        $this->deleteMetaInfo($aCmtIds);

        // delete files
        if ($aFiles) {
            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance($this->getStorageObjectName());
            if ($oStorage)
                $oStorage->queueFilesForDeletion($aFiles);
        }
    }

    public static function onAuthorDelete ($iAuthorId)
    {
        bx_import('BxDolStorage');
        $aSystems = self::getSystems();
        foreach($aSystems as $sSystem => $aSystem) {
            $o = self::getObjectInstance($sSystem, 0);
            $oQuery = $o->getQueryObject();

            // delete comments
            $aFiles = $aCmtIds = array ();
            $oQuery->deleteAuthorComments($iAuthorId, $aFiles, $aCmtIds);

            // delete meta info
            $o->deleteMetaInfo($aCmtIds);
    
            // delete files
            $oStorage = BxDolStorage::getObjectInstance($o->getStorageObjectName());
            if ($oStorage)
                $oStorage->queueFilesForDeletion($aFiles);
        }
        return true;
    }

    public static function onModuleUninstall ($sModuleName, &$iFiles = null)
    {
        bx_import('BxDolStorage');
        $aSystems = self::getSystems();
        foreach($aSystems as $sSystem => $aSystem) {
            if (0 != strncasecmp($sModuleName, $sSystem, strlen($sModuleName)))
                continue;

            $o = self::getObjectInstance($sSystem, 0);
            $oQuery = $o->getQueryObject();

            // delete comments
            $aFiles = $aCmtIds = array ();
            $oQuery->deleteAll($aSystem['system_id'], $aFiles, $aCmtIds);

            // delete meta info
            $o->deleteMetaInfo($aCmtIds);

            // delete files
            $oStorage = BxDolStorage::getObjectInstance($o->getStorageObjectName());
            if ($oStorage && $aFiles)
                $oStorage->queueFilesForDeletion($aFiles);

            if (null !== $iFiles)
                $iFiles += count($aFiles);
        }

        return true;
    }

    public function deleteMetaInfo ($mixedCmtId)
    {
        if (!$this->_sMetatagsObj)
            return;

        if (!is_array($mixedCmtId))
            $mixedCmtId = array($mixedCmtId);

        bx_import('BxDolMetatags');
        $oMetatags = BxDolMetatags::getObjectInstance($this->_sMetatagsObj);

        foreach ($mixedCmtId as $iCmtId) {
            $oMetatags->onDeleteContent($this->_oQuery->getUniqId($this->_aSystem['system_id'], $iCmtId));
            $this->_oQuery->deleteCmtIds($this->_aSystem['system_id'], $iCmtId);
        }
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

    public function isVoteAllowed ($aCmt, $isPerformAction = false)
    {
        if(!$this->isRatable())
            return false;

        $oVote = $this->getVoteObject($aCmt['cmt_id']);
        if($oVote === false)
            return false;

        $iUserId = (int)$this->_getAuthorId();
        if($iUserId == 0)
            return false;

        if(isAdmin())
            return true;

        return $oVote->isAllowedVote($isPerformAction);
    }

    public function isPostReplyAllowed ($isPerformAction = false)
    {
        return $this->checkAction ('comments post', $isPerformAction);
    }

    public function msgErrPostReplyAllowed ()
    {
        return $this->checkActionErrorMsg('comments post');
    }

    public function isEditAllowed ($aCmt, $isPerformAction = false)
    {
        if(isAdmin())
            return true;

        if($aCmt['cmt_author_id'] == $this->_getAuthorId() && $this->checkAction ('comments edit own', $isPerformAction))
            return true;

        return $this->checkAction('comments edit all', $isPerformAction);
    }

    public function msgErrEditAllowed ()
    {
        return $this->checkActionErrorMsg ('comments edit own');
    }

    public function isRemoveAllowed ($aCmt, $isPerformAction = false)
    {
        if(isAdmin())
            return true;

        if($aCmt['cmt_author_id'] == $this->_getAuthorId() && $this->checkAction ('comments remove own', $isPerformAction))
            return true;

        return $this->checkAction ('comments remove all', $isPerformAction);
    }

    public function msgErrRemoveAllowed ()
    {
        return $this->checkActionErrorMsg('comments remove own');
    }

    /**
     * Actions functions
     */
    public function actionGetFormPost ()
    {
        if (!$this->isEnabled())
            return '';

        $iCmtParentId= isset($_REQUEST['CmtParent']) ? bx_process_input($_REQUEST['CmtParent'], BX_DATA_INT) : 0;
        $sCmtBrowse = isset($_REQUEST['CmtBrowse']) ? bx_process_input($_REQUEST['CmtBrowse'], BX_DATA_TEXT) : '';
        $sCmtDisplay = isset($_REQUEST['CmtDisplay']) ? bx_process_input($_REQUEST['CmtDisplay'], BX_DATA_TEXT) : '';

        return $this->getFormBoxPost(array('parent_id' => $iCmtParentId, 'type' => $sCmtBrowse), array('type' => $sCmtDisplay));
    }

    public function actionGetFormEdit ()
    {
        if (!$this->isEnabled()){
            $this->_echoResultJson(array());
            return;
        }

        $iCmtId = bx_process_input(bx_get('Cmt'), BX_DATA_INT);
        $this->_echoResultJson($this->getFormEdit($iCmtId));
    }

    public function actionGetCmt ()
    {
        if (!$this->isEnabled())
            return '';

        $iCmtId = bx_process_input($_REQUEST['Cmt'], BX_DATA_INT);
        $sCmtBrowse = isset($_REQUEST['CmtBrowse']) ? bx_process_input($_REQUEST['CmtBrowse'], BX_DATA_TEXT) : '';
        $sCmtDisplay = isset($_REQUEST['CmtDisplay']) ? bx_process_input($_REQUEST['CmtDisplay'], BX_DATA_TEXT) : '';

        $aCmt = $this->getCommentRow($iCmtId);
        $this->_echoResultJson(array(
            'parent_id' => $aCmt['cmt_parent_id'],
            'vparent_id' => $aCmt['cmt_vparent_id'],
            'content' => $this->getComment($aCmt, array('type' => $sCmtBrowse), array('type' => $sCmtDisplay))
        ));
    }

    public function actionGetCmts ()
    {
        if (!$this->isEnabled())
            return '';

        $aBp = $aDp = array();
		$this->_getParams($aBp, $aDp);

        return $this->getComments($aBp, $aDp);
    }

    public function actionSubmitPostForm()
    {
        if(!$this->isEnabled() || !$this->isPostReplyAllowed()) {
            $this->_echoResultJson(array());
            return;
        }

        $iCmtParentId = 0;
        if(bx_get('cmt_parent_id') !== false)
            $iCmtParentId = bx_process_input(bx_get('cmt_parent_id'), BX_DATA_INT);

        $this->_echoResultJson($this->getFormPost($iCmtParentId));
    }

    public function actionSubmitEditForm()
    {
        if (!$this->isEnabled()) {
            $this->_echoResultJson(array());
            return;
        };

        $iCmtId = 0;
        if(bx_get('cmt_id') !== false)
            $iCmtId = bx_process_input(bx_get('cmt_id'), BX_DATA_INT);

        $this->_echoResultJson($this->getFormEdit($iCmtId));
    }

    public function actionRemove()
    {
        if (!$this->isEnabled()) {
            $this->_echoResultJson(array());
            return;
        };

        $iCmtId = 0;
        if(bx_get('Cmt') !== false)
            $iCmtId = bx_process_input(bx_get('Cmt'), BX_DATA_INT);

        $aCmt = $this->_oQuery->getCommentSimple ($this->getId(), $iCmtId);
        if(!$aCmt) {
            $this->_echoResultJson(array('msg' => _t('_No such comment')));
            return;
        }

        if ($aCmt['cmt_replies'] > 0) {
            $this->_echoResultJson(array('msg' => _t('_Can not delete comments with replies')));
            return;
        }

        $iCmtAuthorId = $this->_getAuthorId();
        if(!$this->isRemoveAllowed($aCmt)) {
            $this->_echoResultJson(array('msg' => $aCmt['cmt_author_id'] == $iCmtAuthorId ? strip_tags($this->msgErrRemoveAllowed()) : _t('_Access denied')));
            return;
        }

        if($this->_oQuery->removeComment ($this->getId(), $aCmt['cmt_id'], $aCmt['cmt_parent_id'])) {
            $this->_triggerComment();

            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance($this->getStorageObjectName());

            $aImages = $this->_oQuery->getImages($this->_aSystem['system_id'], $aCmt['cmt_id']);
            foreach($aImages as $aImage)
                $oStorage->deleteFile($aImage['image_id']);

            $this->_oQuery->deleteImages($this->_aSystem['system_id'], $aCmt['cmt_id']);

            $this->isRemoveAllowed(true);

            $this->deleteMetaInfo ($aCmt['cmt_id']);

            bx_import('BxDolAlerts');
            $oZ = new BxDolAlerts($this->_sSystem, 'commentRemoved', $this->getId(), $iCmtAuthorId, array('comment_id' => $aCmt['cmt_id'], 'comment_author_id' => $aCmt['cmt_author_id']));
            $oZ->alert();

            $this->_echoResultJson(array('id' => $iCmtId));
            return;
        }

        $this->_echoResultJson(array('msg' => _t('_cmt_err_cannot_perform_action')));
    }

    /**
     * Internal functions
     */
    protected function _getAuthorId ()
    {
        return isMember() ? bx_get_logged_profile_id() : 0;
    }

    protected function _getAuthorPassword ()
    {
        return isMember() ? $_COOKIE['memberPassword'] : "";
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
        bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($iAuthorId);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        return $oProfile;
    }

	protected function _getFormObject($sAction = BX_CMT_ACTION_POST)
    {
        $sDisplayName = '_sFormDisplay' . ucfirst($sAction);

        bx_import('BxDolForm');
        return BxDolForm::getObjectInstance($this->_sFormObject, $this->$sDisplayName);
    }

    protected function _getParams(&$aBp, &$aDp)
    {
    	$aBp['vparent_id'] = isset($_REQUEST['CmtParent'])? bx_process_input($_REQUEST['CmtParent'], BX_DATA_INT) : 0;
    	$aBp['type'] = isset($_REQUEST['CmtBrowse']) ? bx_process_input($_REQUEST['CmtBrowse'], BX_DATA_TEXT) : '';
    	$aBp['filter'] = isset($_REQUEST['CmtFilter']) ? bx_process_input($_REQUEST['CmtFilter'], BX_DATA_TEXT) : '';
        $aBp['start'] = isset($_REQUEST['CmtStart']) ? bx_process_input($_REQUEST['CmtStart'], BX_DATA_INT) : -1;
        $aBp['per_view'] = isset($_REQUEST['CmtPerView']) ? bx_process_input($_REQUEST['CmtPerView'], BX_DATA_INT) : -1;

        $aDp['type'] = isset($_REQUEST['CmtDisplay']) ? bx_process_input($_REQUEST['CmtDisplay'], BX_DATA_TEXT) : '';
        $aDp['blink'] = isset($_REQUEST['CmtBlink']) ? bx_process_input($_REQUEST['CmtBlink'], BX_DATA_TEXT) : '';
    }

    protected function _prepareTextForEdit ($s)
    {
        if ($this->isNl2br())
            return htmlspecialchars_decode(str_replace('<br />', "", $s));

        return $s;
    }

    protected function _prepareTextForSave ($s)
    {
        $iDataAction = $this->isNl2br() ? BX_DATA_TEXT_MULTILINE : BX_DATA_HTML;
        return bx_process_input($s, $iDataAction);
    }

    protected function _prepareTextForOutput ($s, $iCmtId = 0)
    {
    	$s = bx_process_output($s, BX_DATA_HTML);
    	$s = bx_convert_links($s);

        if ($this->_sMetatagsObj && $iCmtId) {
            bx_import('BxDolMetatags');
            $oMetatags = BxDolMetatags::getObjectInstance($this->_sMetatagsObj);
            $s = $oMetatags->keywordsParse($this->_oQuery->getUniqId($this->_aSystem['system_id'], $iCmtId), $s);
        }

        return $s;
    }

    protected function _prepareParams(&$aBp, &$aDp)
    {
        $aBp['type'] = isset($aBp['type']) && !empty($aBp['type']) ? $aBp['type'] : $this->_sBrowseType;
        $aBp['filter'] = isset($aBp['filter']) && !empty($aBp['filter']) ? $aBp['filter'] : $this->_sBrowseFilter;
        $aBp['parent_id'] = isset($aBp['parent_id']) ? $aBp['parent_id'] : 0;
        $aBp['start'] = isset($aBp['start']) ? $aBp['start'] : -1;
        $aBp['per_view'] = isset($aBp['per_view']) ? $aBp['per_view'] : -1;
        $aBp['order']['by'] = isset($aBp['order_by']) ? $aBp['order_by'] : $this->_aOrder['by'];
        $aBp['order']['way'] = isset($aBp['order_way']) ? $aBp['order_way'] : $this->_aOrder['way'];

        $aDp['type'] = isset($aDp['type']) && !empty($aDp['type']) ? $aDp['type'] : $this->_sDisplayType;
        $aDp['blink'] = isset($aDp['blink']) && !empty($aDp['blink']) ? $aDp['blink'] : array();
        if(!is_array($aDp['blink']))
        	$aDp['blink'] = explode(',', $aDp['blink']);

        switch($aDp['type']) {
            case BX_CMT_DISPLAY_FLAT:
                $aBp['vparent_id'] = -1;
                $aBp['per_view'] = $aBp['per_view'] != -1 ? $aBp['per_view'] : $this->getPerView(0);
                break;

            case BX_CMT_DISPLAY_THREADED:
                $aBp['per_view'] = $aBp['per_view'] != -1 ? $aBp['per_view'] : $this->getPerView($aBp['vparent_id']);
                break;
        }

        switch ($aBp['type']) {
            case BX_CMT_BROWSE_POPULAR:
                $aBp['order'] = array(
                    'by' => BX_CMT_ORDER_BY_POPULAR,
                    'way' => BX_CMT_ORDER_WAY_DESC
                );
                break;
        }

        $aBp['count'] = $this->getCommentsCount($this->_iId, $aBp['vparent_id'], $aBp['filter']);
        if($aBp['start'] != -1)
            return;

        $aBp['start'] = 0;
        if($aBp['type'] == BX_CMT_BROWSE_TAIL) {
            $aBp['start'] = $aBp['count'] - $aBp['per_view'];
            if($aBp['start'] < 0) {
                $aBp['per_view'] += $aBp['start'];
                $aBp['start'] = 0;
            }
        }

        $this->_setUserChoice($aDp['type'], $aBp['type'], $aBp['filter']);
    }

    protected function _triggerComment()
    {
        if(!$this->_aSystem['trigger_table'])
            return false;

        $iId = $this->getId();
        if(!$iId)
            return false;

        $iCount = $this->getCommentsCount($iId);
        return $this->_oQuery->updateTriggerTable($iId, $iCount);
    }

    /**
     * Replace provided markers in a string
     * @param $mixed string or array to replace markers in
     * @return string where all occured markers are replaced
     */
    protected function _replaceMarkers ($mixed)
    {
        return bx_replace_markers($mixed, $this->_aMarkers);
    }

    protected function _getUserChoice()
    {
        $mixedDp = $mixedBpType = $mixedBpFilter = false;
        if(!isLogged())
            return array($mixedDp, $mixedBpType, $mixedBpFilter);

        $iUserId = $this->_getAuthorId();

        bx_import('BxDolSession');
        $oSession = BxDolSession::getInstance();

        $mixedDp = $oSession->getValue($this->_sDpSessionKey . $iUserId);
        $mixedBpType = $oSession->getValue($this->_sBpSessionKeyType . $iUserId);
        $mixedBpFilter = $oSession->getValue($this->_sBpSessionKeyFilter . $iUserId);

        return array($mixedDp, $mixedBpType, $mixedBpFilter);
    }

    protected function _setUserChoice($sDp, $sBpType, $sBpFilter)
    {
        if(!isLogged())
            return;

        $iUserId = $this->_getAuthorId();

        bx_import('BxDolSession');
        $oSession = BxDolSession::getInstance();

        if(!empty($sDp))
            $oSession->setValue($this->_sDpSessionKey . $iUserId, $sDp);

        if(!empty($sBpType))
            $oSession->setValue($this->_sBpSessionKeyType . $iUserId, $sBpType);

        if(!empty($sBpFilter))
            $oSession->setValue($this->_sBpSessionKeyFilter . $iUserId, $sBpFilter);
    }

    protected function _sendNotificationEmail($iCmtId, $iCmtParentId)
    {
        $aCmt = $this->getCommentRow($iCmtId);
        $aCmtParent = $this->getCommentRow($iCmtParentId);
        if(empty($aCmt) || !is_array($aCmt) || empty($aCmtParent) || !is_array($aCmtParent) || (int)$aCmt['cmt_author_id'] == (int)$aCmtParent['cmt_author_id'])
            return;

        $oProfile = $this->_getAuthorObject($aCmtParent['cmt_author_id']);

        bx_import('BxDolProfileUndefined');
        if($oProfile instanceof BxDolProfileUndefined)
        	return;

        bx_import('BxDolAccount');
        $iAccount = $oProfile->getAccountId();
        $aAccount = BxDolAccount::getInstance($iAccount)->getInfo();

        $aPlus = array();
        $aPlus['reply_text'] = bx_process_output($aCmt['cmt_text']);
        $aPlus['comment_url'] = sprintf('%scmts.php?sys=%s&id=1&cmt_id=%d', BX_DOL_URL_ROOT, $this->_sSystem, $iCmtParentId);

        bx_import('BxDolEmailTemplates');
        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_CommentReplied', $aPlus);
        return $aTemplate && sendMail($aAccount['email'], $aTemplate['Subject'], $aTemplate['Body']);
    }
}

/** @} */
