<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Default limit for connections list in Counter.
 */
define('BX_CONNECTIONS_LIST_COUNTER', 5);

/**
 * Default limit for connections lists
 */
define('BX_CONNECTIONS_LIST_LIMIT', 1000);

/**
 * No limit for connections lists. 
 * Is needed for Total Number calculation.
 */
define('BX_CONNECTIONS_LIST_NO_LIMIT', -1);

/**
 * Connections order: no order
 */
define('BX_CONNECTIONS_ORDER_NONE', 0);

/**
 * Connections order: by addded time, asceding
 */
define('BX_CONNECTIONS_ORDER_ADDED_ASC', 1);

/**
 * Connections order: by addded time, desceding
 */
define('BX_CONNECTIONS_ORDER_ADDED_DESC', 2);

/**
 * Connection type: one-way
 */
define('BX_CONNECTIONS_TYPE_ONE_WAY', 'one-way');

/**
 * Connection type: mutual
 */
define('BX_CONNECTIONS_TYPE_MUTUAL', 'mutual');

/**
 * Connections content type: content
 */
define('BX_CONNECTIONS_CONTENT_TYPE_CONTENT', 'content');

/**
 * Connections content type: initiators
 */
define('BX_CONNECTIONS_CONTENT_TYPE_INITIATORS', 'initiators');

/**
 * Connections content type: common
 */
define('BX_CONNECTIONS_CONTENT_TYPE_COMMON', 'common');

/**
 * Connection is usefull when you need to organize some sorts of connections between different content,
 * for example: friends, contacts, favorites, block lists, subscriptions, etc.
 *
 * Two types of connections are supported one way connections (block list, favourites) and mutual (friends).
 *
 * For automatic handling of connections (like, add/remove connection in frontend) refer to JS function: @see bx_conn_action()
 *
 * @section connection_create Creating the Connection object:
 *
 * Step 1:
 * Add record to 'sys_objects_connection' table:
 * - object: name of the connection object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing;
 *           for example: bx_blogs_favorites - favorite blogs for users in blogs module.
 * - table: table name with connections, see step 2
 * - type: 'one-way' or 'mutual'
 * - override_class_name: user defined class name which is derived from one of base classes.
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 * Step 2:
 * Create table for connections:
 * @code
 * CREATE TABLE `my_sample_connections` (
 *   `id` int(11) NOT NULL AUTO_INCREMENT,
 *   `initiator` int(11) NOT NULL,
 *   `content` int(11) NOT NULL,
 *   `mutual` tinyint(4) NOT NULL, -- can re removed for one-way connections
 *   `added` int(10) unsigned NOT NULL,
 *   PRIMARY KEY (`id`),
 *   UNIQUE KEY `initiator` (`initiator`,`content`),
 *   KEY `content` (`content`)
 * )
 * @endcode
 *
 *
 * @section example Example of usage
 *
 * Check if two profiles are friends:
 * @code
 *   $oConnectionFriends = BxDolConnection::getObjectInstance('bx_profiles_friends'); // get friends connections object
 *   if ($oConnectionFriends) // check if connections is available for using
 *      echo $oConnectionFriends->isConnected (100, 200, true) ? "100 and 200 are friends" : "100 and 200 aren't friends"; // check if profiles with IDs 100 and 200 have mutual connections
 * @endcode
 *
 * Get mutual content IDs (friends IDs)
 * @code
 *   $oConnectionFriends = BxDolConnection::getObjectInstance('bx_profiles_friends'); // get friends connections object
 *   if ($oConnectionFriends) // check if connections is available for using
 *       print_r($oConnection->getConnectedContent(100, 1)); // print array of friends IDs of 100's profile
 * @endcode
 *
 */
class BxDolConnection extends BxDolFactory implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;
    protected $_oQuery;
    protected $_sType;

    /**
     * Constructor
     * @param $aObject array of connection options
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        $this->_aObject['per_page_default'] = 20;

        $this->_sType = $aObject['type'];

        $this->_oQuery = new BxDolConnectionQuery($aObject);
    }

    /**
     * Get connection object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject)
    {
        if (!$sObject)
            return false;

        if (isset($GLOBALS['bxDolClasses']['BxTemplConnection!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxTemplConnection!'.$sObject];

        $aObject = BxDolConnectionQuery::getConnectionObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = empty($aObject['override_class_name']) ? 'BxTemplConnection' : $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass($aObject);

        return ($GLOBALS['bxDolClasses']['BxTemplConnection!'.$sObject] = $o);
    }

    /**
     * Get connection type.
     * return BX_CONNECTIONS_TYPE_ONE_WAY or BX_CONNECTIONS_TYPE_MUTUAL
     */ 
    public function getType()
    {
        return $this->_sType;
    }

    /**
     * Check whether connection between Initiator and Content can be established.
     */
    public function checkAllowedConnect ($iInitiator, $iContent, $isPerformAction = false, $isMutual = false, $isInvertResult = false, $isSwap = false)
    {
        if(!$iInitiator || !$iContent || $iInitiator == $iContent)
            return _t('_sys_txt_access_denied');

        $oInitiator = BxDolProfile::getInstance($iInitiator);
        $oContent = BxDolProfile::getInstance($iContent);
        if(!$oInitiator || !$oContent)
            return _t('_sys_txt_access_denied');

        // check ACL
        $aCheck = checkActionModule($iInitiator, 'connect', 'system', $isPerformAction);
        if($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        // check content's visibility
        if(($mixedResult = $oContent->checkAllowedProfileView()) !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        if($isSwap)
            $isConnected = $this->isConnected($iContent, $iInitiator, $isMutual);
        else
            $isConnected = $this->isConnected($iInitiator, $iContent, $isMutual);

        if($isInvertResult)
            $isConnected = !$isConnected;

        return $isConnected ? _t('_sys_txt_access_denied') : CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * Add new connection.
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return array
     */
    public function actionAdd ($iContent = 0, $iInitiator = false)
    {
        if (!$iContent)
            $iContent = bx_process_input($_POST['id'], BX_DATA_INT);

        return $this->_action ($iInitiator ? $iInitiator : bx_get_logged_profile_id(), $iContent, 'addConnection', '_sys_conn_err_connection_already_exists', true);
    }

    /**
     * Remove connection. This method is wrapper for @see removeConnection to be called from @see conn.php upon AJAX request to this file.
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return array
     */
    public function actionRemove ($iContent = 0, $iInitiator = false)
    {
        if (!$iContent)
            $iContent = bx_process_input($_POST['id'], BX_DATA_INT);
        
        if ($iContent != bx_get_logged_profile_id() && BX_CONNECTIONS_TYPE_MUTUAL == $this->_aObject['type']) {
            $a = $this->actionReject($iContent);
            if (false == $a['err'])
                return $a;
        }

        return $this->_action ($iInitiator ? $iInitiator : bx_get_logged_profile_id(), $iContent, 'removeConnection', '_sys_conn_err_connection_does_not_exists', false, true);
    }

    /**
     * Reject connection request. This method is wrapper for @see removeConnection to be called from @see conn.php upon AJAX request to this file.
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return array
     */
    public function actionReject ($iContent = 0, $iInitiator = false)
    {
        if (!$iContent)
            $iContent = bx_process_input($_POST['id'], BX_DATA_INT);

        return $this->_action($iContent, $iInitiator ? $iInitiator : bx_get_logged_profile_id(), 'removeConnection', '_sys_conn_err_connection_does_not_exists', false, true);
    }

    protected function _action ($iInitiator, $iContent, $sMethod, $sErrorKey, $isMutual = false, $isInvert = false)
    {
        bx_import('BxDolLanguages');

        if(!$iContent || !$iInitiator)
            return ['err' => true, 'msg' => _t('_sys_conn_err_input_data_is_not_defined')];

        if(($mixedResult = $this->checkAllowedConnect($iInitiator, $iContent, false, false, $isInvert)) !== CHECK_ACTION_RESULT_ALLOWED)
            return ['err' => true, 'msg' => $mixedResult];

        if (!$this->$sMethod((int)$iInitiator, (int)$iContent)) {
            if ($isMutual && BX_CONNECTIONS_TYPE_MUTUAL == $this->_sType && $this->isConnected((int)$iInitiator, (int)$iContent, false) && !$this->isConnected((int)$iInitiator, (int)$iContent, true))
                return ['err' => true, 'msg' => _t('_sys_conn_err_connection_is_awaiting_confirmation')];

            return ['err' => true, 'msg' => _t($sErrorKey)];
        }

        return ['err' => false, 'msg' => _t('_sys_conn_msg_success')];
    }

    public function outputActionResult ($mixed, $sFormat = 'json')
    {
        switch ($sFormat) {
            case 'html':
                echo $mixed;
                break;
                
            case 'json':
            default:
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($mixed);     
        }
        exit;
    }

    /**
     * Add new connection.
     * @param $iInitiator initiator of the connection, in most cases some profile id
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return true - if connection was added, false - if connection already exists or error occured
     */
    public function addConnection ($iInitiator, $iContent, $aParams = array())
    {
        $iMutual = 0;
        $iInitiator = (int)$iInitiator;
        $iContent = (int)$iContent;

        $aAlertExtras = array(
            'initiator' => &$iInitiator,
            'content' => &$iContent,
            'mutual' => &$iMutual,
            'object' => $this,
        );
        if(!empty($aParams['alert_extras']) && is_array($aParams['alert_extras']))
            $aAlertExtras = array_merge($aAlertExtras, $aParams['alert_extras']);
        
        bx_alert($this->_sObject, 'connection_before_add', 0, bx_get_logged_profile_id(), $aAlertExtras);
        
        if (!$this->_oQuery->addConnection((int)$iInitiator, (int)$iContent, $iMutual))
            return false;
        
        bx_alert($this->_sObject, 'connection_added', 0, bx_get_logged_profile_id(), $aAlertExtras);

        $this->checkAllowedConnect ($iInitiator, $iContent, true, $iMutual, false);

        return true;
    }

    /**
     * Remove connection.
     * @param $iInitiator initiator of the connection
     * @param $iContent connected content or other profile id in case of friends
     * @return true - if connection was removed, false - if connection isn't exist or error occured
     */
    public function removeConnection ($iInitiator, $iContent)
    {
        if (!($aConnection = $this->_oQuery->getConnection((int)$iInitiator, (int)$iContent))) // connection doesn't exist
            return false;

        if (!$this->_oQuery->removeConnection((int)$iInitiator, (int)$iContent))
            return false;

        bx_alert($this->_sObject, 'connection_removed', 0, bx_get_logged_profile_id(), array(
            'initiator' => (int)$iInitiator,
            'content' => (int)$iContent,
            'mutual' => isset($aConnection['mutual']) ? $aConnection['mutual'] : 0,
            'object' => $this,
        ));

        return true;
    }

    /**
     * Compound function, which calls getCommonContent, getConnectedContent or getConnectedInitiators depending on $sContentType
     * @param $sContentType content type to get BX_CONNECTIONS_CONTENT_TYPE_CONTENT, BX_CONNECTIONS_CONTENT_TYPE_INITIATORS or BX_CONNECTIONS_CONTENT_TYPE_COMMON
     * @param $iId1 one content or initiator
     * @param $iId2 second content or initiator only in case of BX_CONNECTIONS_CONTENT_TYPE_COMMON content type
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectionsAsArray ($sContentType, $iId1, $iId2, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        if (BX_CONNECTIONS_CONTENT_TYPE_COMMON == $sContentType)
            return $this->getCommonContent($iId1, $iId2, $isMutual, $iStart, $iLimit, $iOrder);

        if (BX_CONNECTIONS_CONTENT_TYPE_INITIATORS == $sContentType)
            $sMethod = 'getConnectedInitiators';
        else
            $sMethod = 'getConnectedContent';

        return $this->$sMethod($iId1, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get common content IDs between two initiators
     * @param $iInitiator1 one initiator
     * @param $iInitiator2 second initiator
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getCommonContent ($iInitiator1, $iInitiator2, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getCommonContent($iInitiator1, $iInitiator2, $isMutual, $iStart, $iLimit, $iOrder);
    }
    
    /**
     * Get common content count between two initiators
     * @param $iInitiator1 one initiator
     * @param $iInitiator2 second initiator
     * @param $isMutual get mutual connections only
     * @return number of connections
     */
    public function getCommonContentCount ($iInitiator1, $iInitiator2, $isMutual = false)
    {
        return $this->_oQuery->getCommonContentCount($iInitiator1, $iInitiator2, $isMutual);
    }

    /**
     * Get connected content count
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return number of connections
     */
    public function getConnectedContentCount ($iInitiator, $isMutual = false)
    {
        return $this->_oQuery->getConnectedContentCount($iInitiator, $isMutual);
    }

    /**
     * Get connected initiators count
     * @param $iContent content of the connection
     * @param $isMutual get mutual connections only
     * @return number of connections
     */
    public function getConnectedInitiatorsCount ($iContent, $isMutual = false)
    {
        return $this->_oQuery->getConnectedInitiatorsCount($iContent, $isMutual);
    }

    /**
     * Get connected content IDs
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectedContent ($iInitiator, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getConnectedContent($iInitiator, $isMutual, $iStart, $iLimit, $iOrder);
    }
    
    /**
     * Get connected content IDs for specified type
     * @param $iInitiator initiator of the connection
     * @param $mixedType type of content or an array of types
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectedContentByType ($iInitiator, $mixedType, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getConnectedContentByType($iInitiator, $mixedType, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get connected initiators IDs
     * @param $iContent content of the connection
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectedInitiators ($iContent, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getConnectedInitiators($iContent, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get connected initiators IDs
     * @param $iContent content of the connection
     * @param $mixedType type of content or an array of types
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectedInitiatorsByType ($iContent, $mixedType, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getConnectedInitiatorsByType($iContent, $mixedType, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Similar to getConnectionsAsArray, but for getCommonContentAsSQLParts, getConnectedContentAsSQLParts or getConnectedInitiatorsAsSQLParts methods
     * @see getConnectionsAsArray
     */
    public function getConnectionsAsSQLParts ($sContentType, $sContentTable, $sContentField, $iId1, $iId2, $isMutual = false)
    {
        if (BX_CONNECTIONS_CONTENT_TYPE_COMMON == $sContentType)
            return $this->getCommonContentAsSQLParts($sContentTable, $sContentField, $iId1, $iId2, $isMutual);

        if (BX_CONNECTIONS_CONTENT_TYPE_INITIATORS == $sContentType)
            $sMethod = 'getConnectedInitiatorsAsSQLParts';
        else
            $sMethod = 'getConnectedContentAsSQLParts';

        return $this->$sMethod($sContentTable, $sContentField, $iId1, $isMutual);
    }

    /**
     * Get necessary parts of SQL query to use connections in other queries
     * @param $sContentTable content table or alias
     * @param $sContentField content table field or field alias
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return array of SQL string parts, for now 'join' part only is returned
     */
    public function getCommonContentAsSQLParts ($sContentTable, $sContentField, $iInitiator1, $iInitiator2, $isMutual = false)
    {
        return $this->_oQuery->getCommonContentSQLParts($sContentTable, $sContentField, $iInitiator1, $iInitiator2, $isMutual);
    }

    /**
     * Get necessary parts of SQL query to use connections in other queries
     * @param $sContentTable content table or alias
     * @param $sContentField content table field or field alias
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return array of SQL string parts, for now 'join' part only is returned
     */
    public function getConnectedContentAsSQLParts ($sContentTable, $sContentField, $iInitiator, $isMutual = false)
    {
        return $this->_oQuery->getConnectedContentSQLParts($sContentTable, $sContentField, $iInitiator, $isMutual);
    }
    
    public function getConnectedContentAsSQLPartsExt ($sContentTable, $sContentField, $iInitiator, $isMutual = false)
    {
        return $this->_oQuery->getConnectedContentSQLPartsExt($sContentTable, $sContentField, $iInitiator, $isMutual);
    }

	/**
     * Get necessary parts of SQL query to use connections in other queries
     * @param $sContentTable content table or alias
     * @param $sContentField content table field or field alias
     * @param $sInitiatorTable initiator table or alias
     * @param $sInitiatorField initiator table field or field alias
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return array of SQL string parts, for now 'join' part only is returned
     */
    public function getConnectedContentAsSQLPartsMultiple ($sContentTable, $sContentField, $sInitiatorTable, $sInitiatorField, $isMutual = false)
    {
        return $this->_oQuery->getConnectedContentSQLPartsMultiple($sContentTable, $sContentField, $sInitiatorTable, $sInitiatorField, $isMutual);
    }

    /**
     * Get necessary parts of SQL query to use connections in other queries
     * @param $sInitiatorTable initiator table or alias
     * @param $sInitiatorField initiator table field or field alias
     * @param $iContent content of the connection
     * @param $isMutual get mutual connections only
     * @return array of SQL string parts, for now 'join' part only is returned
     */
    public function getConnectedInitiatorsAsSQLParts ($sInitiatorTable, $sInitiatorField, $iContent, $isMutual = false)
    {
        return $this->_oQuery->getConnectedInitiatorsSQLParts($sInitiatorTable, $sInitiatorField, $iContent, $isMutual);
    }

	/**
     * Get necessary parts of SQL query to use connections in other queries
     * @param $sInitiatorTable initiator table or alias
     * @param $sInitiatorField initiator table field or field alias
     * @param $sContentTable content table or alias
     * @param $sContentField content table field or field alias
     * @param $isMutual get mutual connections only
     * @return array of SQL string parts, for now 'join' part only is returned
     */
    public function getConnectedInitiatorsAsSQLPartsMultiple ($sInitiatorTable, $sInitiatorField, $sContentTable, $sContentField, $isMutual = false)
    {
        return $this->_oQuery->getConnectedInitiatorsSQLPartsMultiple ($sInitiatorTable, $sInitiatorField, $sContentTable, $sContentField, $isMutual);
    }

    /**
     * Similar to getConnectionsAsArray, but for getCommonContentAsCondition, getConnectedContentAsCondition or getConnectedInitiatorsAsCondition methods
     * @see getConnectionsAsArray
     */
    public function getConnectionsAsCondition ($sContentType, $sContentField, $iId1, $iId2, $isMutual = false)
    {
        if (BX_CONNECTIONS_CONTENT_TYPE_COMMON == $sContentType)
            return $this->getCommonContentAsCondition($sContentField, $iId1, $iId2, $isMutual);

        if (BX_CONNECTIONS_CONTENT_TYPE_INITIATORS == $sContentType)
            $sMethod = 'getConnectedInitiatorsAsCondition';
        else
            $sMethod = 'getConnectedContentAsCondition';

        return $this->$sMethod($sContentField, $iId1, $isMutual);
    }

    /**
     * Get necessary condition array to use connections in search classes
     * @param $sContentField content table field name
     * @param $iInitiator initiator of the connection
     * @param $iMutual get mutual connections only
     * @return array of conditions, for now with 'restriction' and 'join' parts
     */
    public function getCommonContentAsCondition ($sContentField, $iInitiator1, $iInitiator2, $iMutual = false)
    {
        return array(

            'restriction' => array (
                'connections_' . $this->_sObject => array(
                    'value' => $iInitiator1,
                    'field' => 'initiator',
                    'operator' => '=',
                    'table' => 'c',
                ),
                'connections_mutual_' . $this->_sObject => array(
                    'value' => $iMutual,
                    'field' => 'mutual',
                    'operator' => '=',
                    'table' => 'c',
                ),
                'connections2_' . $this->_sObject => array(
                    'value' => $iInitiator2,
                    'field' => 'initiator',
                    'operator' => '=',
                    'table' => 'c2',
                ),
                'connections2_mutual_' . $this->_sObject => array(
                    'value' => $iMutual,
                    'field' => 'mutual',
                    'operator' => '=',
                    'table' => 'c2',
                ),
            ),

            'join' => array (
                'connections_' . $this->_sObject => array(
                    'type' => 'INNER',
                    'table' => $this->_aObject['table'],
                    'table_alias' => 'c',
                    'mainField' => $sContentField,
                    'onField' => 'content',
                    'joinFields' => array(),
                ),
                'connections2_' . $this->_sObject => array(
                    'type' => 'INNER',
                    'table' => $this->_aObject['table'],
                    'table_alias' => 'c2',
                    'mainTable' => 'c',
                    'mainField' => 'content',
                    'onField' => 'content',
                    'joinFields' => array(),
                ),
            ),

        );
    }

    /**
     * Get necessary condition array to use connections in search classes
     * @param $sContentField content table field name
     * @param $iInitiator initiator of the connection
     * @param $iMutual get mutual connections only
     * @return array of conditions, for now with 'restriction' and 'join' parts
     */
    public function getConnectedContentAsCondition ($sContentField, $iInitiator, $iMutual = false)
    {
        $sOperation = '=';
        if(is_array($iInitiator))
            $sOperation = 'in';

        return array(

            'restriction' => array (
                'connections_' . $this->_sObject => array(
                    'value' => $iInitiator,
                    'field' => 'initiator',
                    'operator' => $sOperation,
                    'table' => $this->_aObject['table'],
                ),
                'connections_mutual_' . $this->_sObject => array(
                    'value' => $iMutual,
                    'field' => 'mutual',
                    'operator' => '=',
                    'table' => $this->_aObject['table'],
                ),
            ),

            'join' => array (
                'connections_' . $this->_sObject => array(
                    'type' => 'INNER',
                    'table' => $this->_aObject['table'],
                    'mainField' => $sContentField,
                    'onField' => 'content',
                    'joinFields' => array(),//'initiator'),
                ),
            ),

        );
    }

    /**
     * Get necessary condition array to use connections in search classes
     * @param $sContentField content table field name
     * @param $iInitiator initiator of the connection
     * @param $iMutual get mutual connections only
     * @return array of conditions, for now with 'restriction' and 'join' parts
     */
    public function getConnectedInitiatorsAsCondition ($sContentField, $iContent, $iMutual = false)
    {
        $sOperation = '=';
        if(is_array($iContent))
            $sOperation = 'in';

        return array(

            'restriction' => array (
                'connections_' . $this->_sObject => array(
                    'value' => $iContent,
                    'field' => 'content',
                    'operator' => $sOperation,
                    'table' => $this->_aObject['table'],
                ),
                'connections_mutual_' . $this->_sObject => array(
                    'value' => $iMutual,
                    'field' => 'mutual',
                    'operator' => '=',
                    'table' => $this->_aObject['table'],
                ),
            ),

            'join' => array (
                'connections_' . $this->_sObject => array(
                    'type' => 'INNER',
                    'table' => $this->_aObject['table'],
                    'mainField' => $sContentField,
                    'onField' => 'initiator',
                    'joinFields' => array(),//'initiator'),
                ),
            ),

        );
    }

    /**
     * Check if initiator and content are connected.
     * In case if friends this function in conjunction with isMutual parameter can be used to check pending friend requests.
     * @param $iInitiator initiator of the connection
     * @param $iContent connected content or other profile id in case of friends
     * @return true - if content and initiator are connected or false - in all other cases
     */
    public function isConnected ($iInitiator, $iContent, $isMutual = false)
    {
        $oConnection = $this->_oQuery->getConnection ($iInitiator, $iContent);
        if (!$oConnection)
            return false;
        return false === $isMutual ? true : (isset($oConnection['mutual']) ? $oConnection['mutual'] : false);
    }

    /**
     * Check if initiator and content are connected but connetion is not mutual, for checking pending connection requests.
     * This method makes sense only when type of connection is mutual.
     * @param $iInitiator initiator of the connection
     * @param $iContent connected content or other profile id in case of friends
     * @return true - if content and initiator are connected but connection is not mutual or false in all other cases
     */
    public function isConnectedNotMutual ($iInitiator, $iContent)
    {
        $oConnection = $this->_oQuery->getConnection ($iInitiator, $iContent);
        if (!$oConnection)
            return false;
        return $oConnection['mutual'] ? false : true;
    }

    public function getConnection ($iInitiator, $iContent)
    {
        return $this->_oQuery->getConnection($iInitiator, $iContent);
    }

    public function getConnectionById ($iId)
    {
        return $this->_oQuery->getConnectionById($iId);
    }

    /**
     * Must be called when some content is deleted which can have connections as 'content' or as 'initiator', to delete any associated data
     * @param $iId which can be as conetnt ot initiator
     * @return true if some connections were deleted
     */
    public function onDeleteInitiatorAndContent ($iId)
    {
        $b = $this->onDeleteInitiator ($iId);
        $b = $this->onDeleteContent ($iId) || $b;
        return $b;
    }

    /**
     * Must be called when some content is deleted which can have connections as 'initiator', to delete any associated data
     * @param $iIdInitiator initiator id
     * @return true if some connections were deleted
     */
    public function onDeleteInitiator ($iIdInitiator)
    {
        if(!$this->_oQuery->onDelete ($iIdInitiator, 'initiator'))
            return false;

        bx_alert($this->_sObject, 'connection_removed_all', 0, bx_get_logged_profile_id(), array(
            'initiator' => (int)$iIdInitiator,
            'object' => $this,
        ));

        return true;
    }

    /**
     * Must be called when some content is deleted which can have connections as 'content', to delete any associated data
     * @param $iIdInitiator initiator id
     * @return true if some connections were deleted
     */
    public function onDeleteContent ($iIdContent)
    {
        if(!$this->_oQuery->onDelete ($iIdContent, 'content'))
            return false;

        bx_alert($this->_sObject, 'connection_removed_all', 0, bx_get_logged_profile_id(), array(
            'content' => (int)$iIdContent,
            'object' => $this,
        ));

        return true;
    }


    /**
     * Must be called when module (which can have connections as 'content' or as 'initiator') is deleted, to delete any associated data.
     * This method call may be automated via @see BxBaseModGeneralInstaller::_aConnections property.
     * @param $sTable table name with data which have assiciations with the connection
     * @param $sFieldId id field name which is associated with the connection
     * @return number of deleted connections
     */
    public function onModuleDeleteInitiatorAndContent ($sTable, $sFieldId)
    {
        $iAffected = $this->onModuleDeleteInitiator ($sTable, $sFieldId);
        $iAffected += $this->onModuleDeleteContent ($sTable, $sFieldId);
        return $iAffected;
    }

    /**
     * Must be called when module (which can have connections as 'initiator') is deleted, to delete any associated data.
     * This method call may be automated via @see BxBaseModGeneralInstaller::_aConnections property.
     * @param $sTable table name with data which have assiciations with the connection
     * @param $sFieldId id field name which is associated with the connection
     * @return number of deleted connections
     */
    public function onModuleDeleteInitiator ($sTable, $sFieldId)
    {
        return $this->_oQuery->onModuleDelete ($sTable, $sFieldId, 'initiator');
    }

    /**
     * Must be called when module (which can have connections as 'content') is deleted, to delete any associated data.
     * This method call may be automated via @see BxBaseModGeneralInstaller::_aConnections property.
     * @param $sTable table name with data which have assiciations with the connection
     * @param $sFieldId id field name which is associated with the connection
     * @return number of deleted connections
     */
    public function onModuleDeleteContent ($sTable, $sFieldId)
    {
        return $this->_oQuery->onModuleDelete ($sTable, $sFieldId, 'content');
    }


    /**
     * Must be called when module (which can have connections as 'content' or as 'initiator' with 'sys_profiles' table) is deleted, to delete any associated data.
     * This method call may be automated via @see BxBaseModGeneralInstaller::_aConnections property.
     * @param $sModuleName module name to delete connections for
     * @return number of deleted connections
     */
    public function onModuleProfileDeleteInitiatorAndContent ($sModuleName)
    {
        $iAffected = $this->onModuleProfileDeleteInitiator ($sModuleName);
        $iAffected += $this->onModuleProfileDeleteContent ($sModuleName);
        return $iAffected;
    }

    /**
     * Must be called when module (which can have connections as 'initiator' with 'sys_profiles' table) is deleted, to delete any associated data.
     * This method call may be automated via @see BxBaseModGeneralInstaller::_aConnections property.
     * @param $sModuleName module name to delete connections for
     * @return number of deleted connections
     */
    public function onModuleProfileDeleteInitiator ($sModuleName)
    {
        return $this->_oQuery->onModuleProfileDelete ($sModuleName, 'initiator');
    }

    /**
     * Must be called when module (which can have connections as 'content' with 'sys_profiles' table) is deleted, to delete any associated data.
     * This method call may be automated via @see BxBaseModGeneralInstaller::_aConnections property.
     * @param $sModuleName module name to delete connections for
     * @return number of deleted connections
     */
    public function onModuleProfileDeleteContent ($sModuleName)
    {
        return $this->_oQuery->onModuleProfileDelete ($sModuleName, 'content');
    }
}

/** @} */
