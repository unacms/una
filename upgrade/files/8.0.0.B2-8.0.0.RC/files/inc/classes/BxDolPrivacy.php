<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_DOL_PG_HIDDEN', '1');
define('BX_DOL_PG_MEONLY', '2');
define('BX_DOL_PG_ALL', '3');
define('BX_DOL_PG_MEMBERS', '4');
define('BX_DOL_PG_FRIENDS', '5');

define('BX_DOL_PG_DEFAULT', BX_DOL_PG_ALL);

/**
 * @page objects
 * @section privacy Privacy
 * @ref BxDolPrivacy
 */

/**
 * Privacy settings for any content.
 *
 * Integration of the content with privacy engine allows site member
 * to organize the access to his content.
 *
 * Related classes:
 *  BxDolPrivacyQuery - database queries.
 *
 * Example of usage:
 * 1. Register your privacy actions in `sys_privacy_actions` database table.
 * 2. Add one privacy field(with INT type) in the table with your items for each action.
 *    For example, for action 'comment', the field name should be 'allow_comment_to'.
 * 3. Add group choosers for necessary actions in the form, which is used to add new items.
 * @code
 *    $oPrivacy = new BxDolPrivacy();
 *    $oPrivacy->getGroupChooser($iItemOwnerId, $sModuleUri, $sModuleAction);
 * @endcode
 *
 * 4. Check privacy when any user tries to view an item.
 * @code
 *    $oPrivacy = new BxDolPrivacy($sTable, $sFieldId, $sFieldOwnerId);
 *    if($oPrivacy->check($sAction, $iObjectId, $iViewerId)) {
 *     //show necessary content
 *    }
 * @endcode
 *
 *    @see an example of integration in BoonEx modules, for example: Posts
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */
class BxDolPrivacy extends BxDol implements iBxDolFactoryObject
{
    protected $_oDb;
    protected $_sObject;
    protected $_aObject;

    /**
     * Constructor
     * @param $aObject array of grid options
     */
    function __construct($aObject)
    {
        parent::__construct();

        $this->_aObject = $aObject;
        $this->_sObject = $aObject['object'];

        $this->_oDb = new BxDolPrivacyQuery($this->_aObject);
    }

    /**
     * Get privacy object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    public static function getObjectInstance($sObject)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolPrivacy!' . $sObject]))
            return $GLOBALS['bxDolClasses']['BxDolPrivacy!' . $sObject];

        $aObject = BxDolPrivacyQuery::getPrivacyObject($sObject);
        if(!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxTemplPrivacy';
        if(!empty($aObject['override_class_name'])) {
            $sClass = $aObject['override_class_name'];
            if(!empty($aObject['override_class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);
        }

        $o = new $sClass($aObject);
        return ($GLOBALS['bxDolClasses']['BxDolPrivacy!' . $sObject] = $o);
    }

    /**
     * Get Select element with available groups.
     *
     * @param  string  $sObject  privacy object name.
     * @param  integer $iOwnerId object's owner ID.
     * @param  array   $aParams  an array of custom selector's params (dynamic_groups - an array of arrays('key' => group_id, 'value' => group_title), title - the title to be used for generated field).
     * @return an      array with Select element description.
     */
    public static function getGroupChooser($sObject, $iOwnerId = 0, $aParams = array())
    {
        $oPrivacy = BxDolPrivacy::getObjectInstance($sObject);
        if(empty($oPrivacy))
            return array();

        $sModule = $oPrivacy->_aObject['module'];
        $sAction = $oPrivacy->_aObject['action'];

        if($iOwnerId == 0)
            $iOwnerId = bx_get_logged_profile_id();

        $sValue = $oPrivacy->_oDb->getDefaultGroupByUser($sModule, $sAction, $iOwnerId);
        if(empty($sValue))
            $sValue = $oPrivacy->_oDb->getDefaultGroup($sModule, $sAction);

        $aValues = array();
        $aGroups = $oPrivacy->_oDb->getGroupsBy(array('type' => 'active'));
        foreach($aGroups as $aGroup) {
            if((int)$aGroup['active'] == 0)
               continue;

            $aValues[] = array('key' => $aGroup['id'], 'value' => _t($aGroup['title']));
        }

        if(isset($aParams['dynamic_groups']) && is_array($aParams['dynamic_groups']))
            $aValues = array_merge($aValues, $aParams['dynamic_groups']);

        $sName = $oPrivacy->convertActionToField($sAction);

        $sTitle = isset($aParams['title']) && !empty($aParams['title']) ? $aParams['title'] : '';
        if(empty($sTitle)) {
            $sTitle = $oPrivacy->_oDb->getTitle($sModule, $sAction);
            $sTitle = _t(!empty($sTitle) ? $sTitle : '_' . $sName);
        }

        return array(
            'type' => 'select',
            'name' => $sName,
            'caption' => $sTitle,
            'value' => $sValue,
            'values' => $aValues,
            'checker' => array(
                'func' => 'avail',
                'error' => _t('_ps_ferr_incorrect_select')
            ),
            'db' => array(
                'pass' => 'Int'
            )
        );
    }

    /**
     * Get database field name for action.
     *
     * @param  string $sObject privacy object name.
     * @param  string $sAction action name.
     * @return string with field name.
     */
    public static function getFieldName($sObject, $sAction = '')
    {
    	$oPrivacy = BxDolPrivacy::getObjectInstance($sObject);
        if(empty($oPrivacy))
            return '';

		if(empty($sAction))
			$sAction = $oPrivacy->_aObject['action'];

        return $oPrivacy->convertActionToField($sAction);
    }

    /**
     * Get necessary condition array to use privacy in search classes
     * @param $mixedGroupId group ID or array of group IDs
     * @return array of conditions, for now with 'restriction' part only is returned
     */
    public function getContentByGroupAsCondition($mixedGroupId)
    {
        return array(
            'restriction' => array (
                'privacy_' . $this->_sObject => array(
                    'value' => $mixedGroupId,
                    'field' => $this->convertActionToField($this->_aObject['action']),
                    'operator' => is_array($mixedGroupId) ? 'in' : '=',
                    'table' => $this->_aObject['table'],
                ),
            ),
        );
    }

    /**
     * Get necessary condition array to use privacy in search classes
     * @param $iProfileIdOwner owner profile ID
     * @return array of conditions, for now with 'restriction' part only is returned
     */
    public function getContentPublicAsCondition($iProfileIdOwner = 0)
    {
        $mixedPrivacyGroups = BX_DOL_PG_ALL;
        if(isLogged()) {
            $iProfileIdLogged = bx_get_logged_profile_id();
            if($iProfileIdLogged == $iProfileIdOwner)
                return array();

            if ($iProfileIdOwner && $this->checkConnections($iProfileIdOwner, bx_get_logged_profile_id()))
                $mixedPrivacyGroups = array(BX_DOL_PG_ALL, BX_DOL_PG_MEMBERS, BX_DOL_PG_FRIENDS);
            else
                $mixedPrivacyGroups = array(BX_DOL_PG_ALL, BX_DOL_PG_MEMBERS);
        }
        return $this->getContentByGroupAsCondition($mixedPrivacyGroups);
    }

    /**
     * Get necessary parts of SQL query to use privacy in other queries
     * @param $mixedGroupId group ID or array of group IDs
     * @return array of SQL string parts, for now 'where' part only is returned
     */
    public function getContentByGroupAsSQLPart($mixedGroupId)
    {
        $sField = $this->convertActionToField($this->_aObject['action']);
        return $this->_oDb->getContentByGroupAsSQLPart($sField, $mixedGroupId);
    }

    /**
     * Check whether the viewer can make requested action.
     *
     * @param  integer $iObjectId object ID the action to be performed with.
     * @param  integer $iViewerId viewer ID.
     * @return boolean result of operation.
     */
    function check($iObjectId, $iViewerId = 0)
    {
        if(empty($iViewerId))
            $iViewerId = bx_get_logged_profile_id();

        $aObject = $this->_oDb->getObjectInfo($this->convertActionToField($this->_aObject['action']), $iObjectId);
        if(empty($aObject) || !is_array($aObject))
            return false;

        if($aObject['group_id'] == BX_DOL_PG_HIDDEN)
            return false;

        if(isAdmin() || $iViewerId == $aObject['owner_id'])
            return true;

        $aGroup = $this->_oDb->getGroupsBy(array('type' => 'id', 'id' => $aObject['group_id']));
        if(!empty($aGroup) && is_array($aGroup) && (int)$aGroup['active'] == 1 && !empty($aGroup['check'])) {
            $sCheckMethod = $this->getCheckMethod($aGroup['check']);
            if(method_exists($this, $sCheckMethod) && $this->$sCheckMethod($aObject['owner_id'], $iViewerId))
                return true;
        }

        return $this->isDynamicGroupMember($aObject['group_id'], $aObject['owner_id'], $iViewerId, $iObjectId);
    }

    public function checkMeOnly($iOwnerId, $iViewerId)
    {
        return false;
    }

    public function checkPublic($iOwnerId, $iViewerId)
    {
        return true;
    }

    public function checkMembers($iOwnerId, $iViewerId)
    {
        return isMember();
    }

    public function checkConnections($iOwnerId, $iViewerId)
    {
        return BxDolConnection::getObjectInstance('sys_profiles_friends')->isConnected($iOwnerId, $iViewerId, true);
    }

    protected function getCheckMethod($s)
    {
        if(substr($s, 0, 1) != '@')
            return false;

        return bx_gen_method_name(str_replace('@', 'check_', $s));
    }

    protected function convertActionToField($sAction)
    {
    	return 'allow_' . strtolower(str_replace(' ', '-', $sAction)) . '_to';
    }

    /**
     * Check whethere viewer is a member of dynamic group.
     *
     * @param  mixed   $mixedGroupId   dynamic group ID.
     * @param  integer $iObjectOwnerId object owner ID.
     * @param  integer $iViewerId      viewer ID.
     * @return boolean result of operation.
     */
    protected function isDynamicGroupMember($mixedGroupId, $iObjectOwnerId, $iViewerId, $iObjectId)
    {
        return false;
    }
}

/** @} */
