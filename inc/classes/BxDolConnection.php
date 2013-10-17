<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolConnectionQuery');


/**
 * Default limit for connections lists
 */
define('BX_CONNECTIONS_LIST_LIMIT', 1000);


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
 * @page objects 
 * @section connection Connection
 * @ref BxDolConnection
 */

/**
 * Connection is usefull when you need to organize some sorts of connections between different content, 
 * for example: friends, contacts, favorites, block lists, subscriptions, etc.
 * 
 * Two types of connections are supported one way connections (block list, favourites) and mutual (friends). 
 *
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
 *   bx_import('BxDolConnection'); // import connection class
 *   $oConnectionFriends = BxDolConnection::getObjectInstance('bx_profiles_friends'); // get friends connections object
 *   if ($oConnectionFriends) // check if connections is available for using
 *      echo $oConnectionFriends->isConnected (100, 200, true) ? "100 and 200 are friends" : "100 and 200 aren't friends"; // check if profiles with IDs 100 and 200 have mutual connections
 * @endcode
 *
 * Get mutual content IDs (friends IDs)
 * @code
 *   bx_import('BxDolConnection'); // import connection class
 *   $oConnectionFriends = BxDolConnection::getObjectInstance('bx_profiles_friends'); // get friends connections object
 *   if ($oConnectionFriends) // check if connections is available for using
 *       print_r($oConnection->getConnectedContent(100, 1)); // print array of friends IDs of 100's profile
 * @endcode
 * 
 */
class BxDolConnection extends BxDol implements iBxDolFactoryObject {

    protected $_sObject;
    protected $_aObject;
    protected $_oQuery;
    protected $_sType;

    /**
     * Constructor
     * @param $aObject array of connection options
     */
    protected function __construct($aObject) {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        $this->_sType = $aObject['type'];

        $this->_oQuery = new BxDolConnectionQuery($aObject);
    }

    /**
     * Get connection object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject) {

        if (!$sObject)
            return false;

        if (isset($GLOBALS['bxDolClasses']['BxDolConnection!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolConnection!'.$sObject];
        
        $aObject = BxDolConnectionQuery::getConnectionObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = empty($aObject['override_class_name']) ? 'BxDolConnection' : $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);
        else    
            bx_import($sClass);
        
        $o = new $sClass($aObject);

        return ($GLOBALS['bxDolClasses']['BxDolConnection!'.$sObject] = $o);
    }

    /**
     * Add new connection.
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return array
     */ 
    public function actionAdd () {
        return $this->_action ('addConnection', '_sys_conn_err_connection_already_exists', false, true);
    }

    /**
     * Remove connection.
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return array
     */ 
    public function actionRemove () {
        return $this->_action ('removeConnection', '_sys_conn_err_connection_does_not_exists', false);
    }

    /**
     * Reject connection request.
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return array
     */ 
    public function actionReject () {
        return $this->_action ('removeConnection', '_sys_conn_err_connection_does_not_exists', true);
    }

    protected function _action ($sMethod, $sErrorKey, $bSwapInitiatorAndContent, $isMutual = false) {
        bx_import('BxDolLanguages');
        $iContent = 0;
        $iInitiator = 0;
        if ($bSwapInitiatorAndContent) {
            $iInitiator = bx_process_input($_POST['id'], BX_DATA_INT);
            $iContent = bx_get_logged_profile_id();
        } else {
            $iContent = bx_process_input($_POST['id'], BX_DATA_INT);
            $iInitiator = bx_get_logged_profile_id();
        }

        if (!$iContent || !$iInitiator)
            return array ('err' => true, 'msg' => _t('_sys_conn_err_input_data_is_not_defined'));

        if (!$this->$sMethod((int)$iInitiator, (int)$iContent)) {
            if ($isMutual && BX_CONNECTIONS_TYPE_MUTUAL == $this->_sType && $this->isConnected((int)$iInitiator, (int)$iContent, false) && !$this->isConnected((int)$iInitiator, (int)$iContent, true))
                return array ('err' => true, 'msg' => _t('_sys_conn_err_connection_is_awaiting_confirmation'));

            return array ('err' => true, 'msg' => _t($sErrorKey));
        }

        return array ('err' => false, 'msg' => _t('_sys_conn_msg_success'));
    }

    public function outputActionResult ($mixed, $sFormat = 'json') {
        switch ($sFormat) {
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
    public function addConnection ($iInitiator, $iContent) {
        $iMutual = 0;
        if (!$this->_oQuery->addConnection((int)$iInitiator, (int)$iContent, $iMutual))
            return false; 
    
        bx_alert($this->_sObject, 'connection_added', 0, getLoggedId(), array(
            'initiator' => (int)$iInitiator, 
            'content' => (int)$iContent, 
            'mutual' => (int)$iMutual,
            'object' => $this,
        ));

        return true;
    }

    /**
     * Remove connection.
     * @param $iInitiator initiator of the connection
     * @param $iContent connected content or other profile id in case of friends
     * @return true - if connection was removed, false - if connection isn't exist or error occured
     */
    public function removeConnection ($iInitiator, $iContent) {
        if (!($aConnection = $this->_oQuery->getConnection((int)$iInitiator, (int)$iContent))) // connection doesn't exist
            return false;

        if (!$this->_oQuery->removeConnection((int)$iInitiator, (int)$iContent))
            return false;

        bx_alert($this->_sObject, 'connection_removed', 0, getLoggedId(), array(
            'initiator' => (int)$iInitiator, 
            'content' => (int)$iContent, 
            'mutual' => isset($aConnection['mutual']) ? $aConnection['mutual'] : 0,
            'object' => $this,
        ));

        return true;
    }

    /**
     * Get connected content IDs
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */    
    public function getConnectedContent ($iInitiator, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE) {
        return $this->_oQuery->getConnectedContent($iInitiator, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get connected initiators IDs
     * @param $iContent content of the connection
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */    
    public function getConnectedInitiators ($iContent, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE) {
        return $this->_oQuery->getConnectedInitiators($iContent, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get necessary parts of SQL query to use connections in other queries
     * @param $sContentTable content table or alias
     * @param $sContentField content table field or field alias
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return array of SQL string parts, for now 'join' part only is returned
     */
    public function getConnectedContentAsSQLParts ($sContentTable, $sContentField, $iInitiator, $isMutual = false) {
        return $this->_oQuery->getConnectedContentSQLParts($sContentTable, $sContentField, $iInitiator, $isMutual);
    }

    /**
     * Get necessary parts of SQL query to use connections in other queries
     * @param $sContentTable content table or alias
     * @param $sContentField content table field or field alias
     * @param $iContent content of the connection
     * @param $isMutual get mutual connections only
     * @return array of SQL string parts, for now 'join' part only is returned
     */
    public function getConnectedInitiatorsAsSQLParts ($sContentTable, $sContentField, $iContent, $isMutual = false) {
        return $this->_oQuery->getConnectedInitiatorsSQLParts($sContentTable, $sContentField, $iContent, $isMutual);
    }

    /**
     * Get necessary condition array to use connections in search classes
     * @param $sContentField content table field name
     * @param $iInitiator initiator of the connection
     * @param $iMutual get mutual connections only
     * @return array of conditions, for now with 'restriction' and 'join' parts
     */
    public function getConnectedContentAsCondition ($sContentField, $iInitiator, $iMutual = false) {
        return array(

            'restriction' => array (
                'connections_' . $this->_sObject => array(
                    'value' => $iInitiator,
                    'field' => 'initiator',
                    'operator' => '=',
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
    public function getConnectedInitiatorsAsCondition ($sContentField, $iContent, $iMutual = false) {
        return array(

            'restriction' => array (
                'connections_' . $this->_sObject => array(
                    'value' => $iContent,
                    'field' => 'content',
                    'operator' => '=',
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
    public function isConnected ($iInitiator, $iContent, $isMutual = false) {
        $oConnection = $this->_oQuery->getConnection ($iInitiator, $iContent);
        if (!$oConnection)
            return false;
        return false === $isMutual ? true : $oConnection['mutual'];
    }

    /**
     * Check if initiator and content are connected but connetion is not mutual, for checking pending connection requests. 
     * This method makes sense only when type of connection is mutual.
     * @param $iInitiator initiator of the connection
     * @param $iContent connected content or other profile id in case of friends
     * @return true - if content and initiator are connected but connection is not mutual or false in all other cases
     */    
    public function isConnectedNotMutual ($iInitiator, $iContent) {
        $oConnection = $this->_oQuery->getConnection ($iInitiator, $iContent);
        if (!$oConnection)
            return false;
        return $oConnection['mutual'] ? false : true;
    }

}

/** @} */
