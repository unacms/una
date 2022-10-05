<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNtfsDb extends BxBaseModNotificationsDb
{
    protected $_sTableEvt2Usr;
    protected $_sTableQueue;

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_aDeliveryTypes = array(
            BX_BASE_MOD_NTFS_DTYPE_SITE, 
            BX_BASE_MOD_NTFS_DTYPE_EMAIL, 
            BX_BASE_MOD_NTFS_DTYPE_PUSH
        );

        $this->_sTableEvt2Usr = $this->_sPrefix . 'events2users';
        $this->_sTableQueue = $this->_sPrefix . 'queue';
    }

    public function markAsRead($iUserId, $iEventId)
    {
        $this->queueDeleteByProfile($iUserId, $iEventId);

        return (int)$this->query("INSERT INTO `" . $this->_sTableEvt2Usr . "` (`user_id`, `event_id`) VALUES (:user_id, :event_id) ON DUPLICATE KEY UPDATE `event_id`=:event_id", array(
            'user_id' => $iUserId,
            'event_id' => $iEventId
        )) > 0;
    }

    public function getLastRead($iUserId)
    {
        return (int)$this->getOne("SELECT `event_id` FROM `" . $this->_sTableEvt2Usr . "` WHERE `user_id`=:user_id LIMIT 1", array(
            'user_id' => (int)$iUserId
        ));
    }

    public function deleteEvent($aParams, $sWhereAddon = "")
    {
        $aEvents = $this->getAll("SELECT * FROM `{$this->_sTable}` WHERE " . $this->arrayToSQL($aParams, " AND ") . $sWhereAddon);
        if(!empty($aEvents) && is_array($aEvents)) 
            foreach($aEvents as $aEvent)
                $this->queueDelete(array('event_id' => $aEvent['id']));

        return parent::deleteEvent($aParams, $sWhereAddon);
    }

    public function getEvents($aParams, $bReturnCount = false)
    {
        bx_alert($this->_oConfig->getName(), 'get_events_before', 0, 0, [
            'params' => &$aParams,
        ]);

        if($aParams['browse'] != 'list' || $aParams['type'] != BX_NTFS_TYPE_OBJECT_OWNER_AND_CONNECTIONS)
            return parent::getEvents($aParams);

        $bCountOnly = !empty($aParams['count_only']);

        //--- Get query for 'Object Owner' notifications
        $sLimitClause = isset($aParams['per_page']) ? "LIMIT 0, " . ($aParams['start'] + $aParams['per_page']) : "";

        $aParams['type'] = BX_BASE_MOD_NTFS_TYPE_OBJECT_OWNER;
        list($sMethod, $sSelectClause, $sJoinClausePo, $sWhereClausePo, $sOrderClausePo) = $this->_getSqlPartsEvents($aParams);

        $sQueryOwner = "SELECT {select}
            FROM `{$this->_sTable}`
            LEFT JOIN `{$this->_sTableHandlers}` ON `{$this->_sTable}`.`type`=`{$this->_sTableHandlers}`.`alert_unit` AND `{$this->_sTable}`.`action`=`{$this->_sTableHandlers}`.`alert_action` " . $sJoinClausePo . "
            WHERE 1 " . $sWhereClausePo . (!$bCountOnly ? " " . $sOrderClausePo . " " . $sLimitClause : "");

        //--- Get query for 'Connections based' notifications
        $aParams['type'] = BX_BASE_MOD_NTFS_TYPE_CONNECTIONS;
        list($sMethod, $sSelectClause, $sJoinClausePc, $sWhereClausePc, $sOrderClausePc) = $this->_getSqlPartsEvents($aParams);

        $sQueryConnections = "SELECT {select}
            FROM `{$this->_sTable}`
            LEFT JOIN `{$this->_sTableHandlers}` ON `{$this->_sTable}`.`type`=`{$this->_sTableHandlers}`.`alert_unit` AND `{$this->_sTable}`.`action`=`{$this->_sTableHandlers}`.`alert_action` " . $sJoinClausePc . "
            WHERE 1 " . $sWhereClausePc . (!$bCountOnly ? " " . $sOrderClausePc . " " . $sLimitClause : "");

        //--- Combine both queries in one
        $sUnionMethod = 'getColumn';
        $sUnionOrderClause = $sUnionLimitClause = '';
        if(!$bCountOnly) {
            $sUnionMethod = $sMethod;
            $sUnionOrderClause = "ORDER BY `date` DESC, `id` DESC";
            $sUnionLimitClause = isset($aParams['per_page']) ? "LIMIT " . $aParams['start'] . ", " . $aParams['per_page'] : "";
        }

        $aAlertParams = $aParams;
        unset($aAlertParams['browse']);

        /**
         * Parts: 
         * PO - notifications related to Owner's content
         * PC - notifications related to Connections' content
         */
        bx_alert($this->_oConfig->getName(), 'get_events', 0, 0, [
            'browse' => $aParams['browse'],
            'params' => $aAlertParams,
            'table' => $this->_sTable,
            'method' => &$sUnionMethod,
            'select_clause' => &$sSelectClause,
            'join_clause_po' => &$sJoinClausePo,
            'join_clause_pc' => &$sJoinClausePc,
            'where_clause_po' => &$sWhereClausePo,
            'where_clause_pc' => &$sWhereClausePc,
            'order_clause_po' => &$sOrderClausePo,
            'order_clause_pc' => &$sOrderClausePc,
            'order_clause' => &$sUnionOrderClause,
            'limit_clause' => &$sUnionLimitClause,
            'query_po' => &$sQueryOwner,
            'query_pc' => &$sQueryConnections,
        ]);

        $sQuery = "(" . $sQueryOwner . ") UNION (" . $sQueryConnections . ") {order} {limit}";
        $sQuery = str_replace(['{select}', '{order}', '{limit}'], [$sSelectClause, $sUnionOrderClause, $sUnionLimitClause], $sQuery);
        $aEntries = $this->$sUnionMethod($sQuery);

        if($bCountOnly)
            $aEntries = (int)array_sum($aEntries);

        return $aEntries;
    }

    protected function _getSqlPartsEvents($aParams)
    {
        list($sMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause) = parent::_getSqlPartsEvents($aParams);

        switch($aParams['browse']) {
            case 'list':
                if(!empty($aParams['count_only'])) {
                    $sMethod = 'getOne';
                    $sSelectClause = 'COUNT(DISTINCT `' . $this->_sTable . '`.`id`)';
                }
                else {
                    $sSelectClause .= ", `{$this->_sTableHandlers}`.`priority` AS `priority`";

                    if(in_array($aParams['type'], array(BX_BASE_MOD_NTFS_TYPE_CONNECTIONS, BX_NTFS_TYPE_OBJECT_OWNER_AND_CONNECTIONS)))
                        $sSelectClause = 'DISTINCT ' . $sSelectClause;
                }

                break;
        }

        return array($sMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);
    }

    protected function _getSqlPartsEventsList($aParams)
    {
        $sJoinClause = $sWhereClause = "";

        //--- Apply status filter
        $sWhereClauseStatus = '';
        if(isset($aParams['active']))
            $sWhereClauseStatus = $this->prepareAsString("AND `{$this->_sTable}`.`active`=? ", (int)$aParams['active']);

        //--- Apply modules or handlers filter
        $sWhereClauseModules = '';
        if(!empty($aParams['modules']) && is_array($aParams['modules']))
            $sWhereClauseModules = "AND `" . $this->_sTable . "`.`type` IN (" . $this->implode_escape($aParams['modules']) . ") ";

        if($sWhereClauseModules == '') {
            $aHidden = $this->_oConfig->getHandlersHidden();
            $sWhereClauseModules = !empty($aHidden) && is_array($aHidden) ? "AND `" . $this->_sTableHandlers . "`.`id` NOT IN (" . $this->implode_escape($aHidden) . ") " : "";
        }

        //--- Check flag 'New'
        $sWhereClauseNew = '';
        if(!empty($aParams['new']) && !empty($aParams['owner_id']))
            $sWhereClauseNew = $this->prepareAsString("AND `{$this->_sTable}`.`id`>? ", $this->getLastRead((int)$aParams['owner_id']));

        //--- Check type
        $sWhereClauseSettings = $sWhereClauseType = '';
        if(!empty($aParams['owner_id']))
            switch($aParams['type']) {
                /*
                 * Note. It isn't used for now and outdated.
                 */
                case BX_BASE_MOD_NTFS_TYPE_OWNER:
                    $sWhereClauseType = $this->prepareAsString("AND `{$this->_sTable}`.`owner_id`=? ", $aParams['owner_id']);
                    break;

                case BX_BASE_MOD_NTFS_TYPE_OBJECT_OWNER:
                    $sJoinClause .= $this->prepareAsString(" INNER JOIN `{$this->_sTableSettings}` ON `{$this->_sTableHandlers}`.`id`=`{$this->_sTableSettings}`.`handler_id` AND `{$this->_sTableSettings}`.`delivery`='" . BX_BASE_MOD_NTFS_DTYPE_SITE . "' AND `{$this->_sTableSettings}`.`active`='1' AND `{$this->_sTableSettings}`.`type`=?", BX_NTFS_STYPE_PERSONAL);

                    $sJoinClauseS2U = " LEFT JOIN `{$this->_sTableSettings2Users}` ON `{$this->_sTableSettings}`.`id`=`{$this->_sTableSettings2Users}`.`setting_id` ";
                    if(!empty($aParams['owner_id']))
                        $sJoinClauseS2U .= $this->prepareAsString("AND `{$this->_sTableSettings2Users}`.`user_id`=? ", $aParams['owner_id']);

                    $sJoinClause .= $sJoinClauseS2U;
                    $sWhereClauseSettings = "AND ((ISNULL(`{$this->_sTableSettings2Users}`.`active`) AND `{$this->_sTableSettings}`.`value`='1') OR (NOT ISNULL(`{$this->_sTableSettings2Users}`.`active`) AND `{$this->_sTableSettings2Users}`.`active`='1')) ";

                    $sWhereClauseType = $this->prepareAsString("AND `{$this->_sTable}`.`owner_id`<>`{$this->_sTable}`.`object_owner_id` AND ((`{$this->_sTable}`.`owner_id`=? AND `{$this->_sTable}`.`object_privacy_view`<0 AND `{$this->_sTable}`.`owner_id`=ABS(`{$this->_sTable}`.`object_privacy_view`)) OR `{$this->_sTable}`.`object_owner_id`=?) ", $aParams['owner_id'], $aParams['owner_id']);
                    break;

                case BX_BASE_MOD_NTFS_TYPE_CONNECTIONS:
                    $oConnection = BxDolConnection::getObjectInstance($this->_oConfig->getObject('conn_subscriptions'));
                    $aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sPrefix . "events", 'owner_id', $aParams['owner_id']);
                    if(empty($aQueryParts) || !is_array($aQueryParts) || empty($aQueryParts['join']))
                        break;

                    $sJoinClause .= " LEFT JOIN `sys_profiles` AS `tsp` ON `{$this->_sTable}`.`owner_id`=`tsp`.`id` " . $aQueryParts['join'];

                    $sWhereClauseType = '';
                    if(!empty($aQueryParts['fields']['added']))
                        $sWhereClauseType = "AND `{$this->_sTable}`.`date` > " . $aQueryParts['fields']['added'];

                    list($aModulesProfiles, $aModulesContexts) = $this->_oConfig->getProfileBasedModules();
                    $sJoinClause .= $this->prepareAsString(" INNER JOIN `{$this->_sTableSettings}` ON `{$this->_sTableHandlers}`.`id`=`{$this->_sTableSettings}`.`handler_id` AND `{$this->_sTableSettings}`.`delivery`='" . BX_BASE_MOD_NTFS_DTYPE_SITE . "' AND `{$this->_sTableSettings}`.`active`='1' AND ((`{$this->_sTableSettings}`.`type`=? AND `tsp`.`type` IN (" . $this->implode_escape($aModulesProfiles) . ")) || (`{$this->_sTableSettings}`.`type`=? AND `tsp`.`type` IN (" . $this->implode_escape($aModulesContexts) . ")))", BX_NTFS_STYPE_FOLLOW_MEMBER, BX_NTFS_STYPE_FOLLOW_CONTEXT);

                    $sJoinClauseS2U = " LEFT JOIN `{$this->_sTableSettings2Users}` ON `{$this->_sTableSettings}`.`id`=`{$this->_sTableSettings2Users}`.`setting_id` ";
                    if(!empty($aParams['owner_id']))
                        $sJoinClauseS2U .= $this->prepareAsString("AND `{$this->_sTableSettings2Users}`.`user_id`=? ", $aParams['owner_id']);

                    $sJoinClause .= $sJoinClauseS2U;
                    $sWhereClauseSettings = "AND ((ISNULL(`{$this->_sTableSettings2Users}`.`active`) AND `{$this->_sTableSettings}`.`value`='1') OR (NOT ISNULL(`{$this->_sTableSettings2Users}`.`active`) AND `{$this->_sTableSettings2Users}`.`active`='1')) ";
                    break;

                case BX_NTFS_TYPE_OBJECT_OWNER_AND_CONNECTIONS:
                    /**
                     * 'personal' notifications are taken by: 
                     * 1. 'owner_id', in case of somebody performed an action in requested profile's context
                     * 'object_privacy_view' < 0 AND 'owner_id' == ABS('object_privacy_view'))
                     * Currently it happens when somebody posted a Direct Timeline post in requested profile.
                     * 2. 'object_owner_id', in case of somebody performed an action with content 
                     * owned by the requested profile.
                     */
                    $sWhereClauseObjectOwner = $this->prepareAsString("`{$this->_sTable}`.`owner_id` <> `{$this->_sTable}`.`object_owner_id` AND ((`{$this->_sTable}`.`owner_id`=? AND `{$this->_sTable}`.`object_privacy_view`<0 AND `{$this->_sTable}`.`owner_id`=ABS(`{$this->_sTable}`.`object_privacy_view`)) OR `{$this->_sTable}`.`object_owner_id`=?) ", $aParams['owner_id'], $aParams['owner_id']);

                    /**
                     * 'follow' notifications are taken by connection with `owner_id`
                     */
                    $sWhereClauseConnections = '0';
                    $oConnection = BxDolConnection::getObjectInstance($this->_oConfig->getObject('conn_subscriptions'));
                    $aQueryParts = $oConnection->getConnectedContentAsSQLPartsExt($this->_sTable, 'owner_id', $aParams['owner_id']);
                    if(!empty($aQueryParts) && is_array($aQueryParts) && !empty($aQueryParts['join'])) {
                        $sJoinClause .= " LEFT JOIN `sys_profiles` AS `tsp` ON `{$this->_sTable}`.`owner_id`=`tsp`.`id`";
                        $sJoinClause .= " LEFT JOIN `" . $aQueryParts['join']['table'] . "` AS `" . $aQueryParts['join']['table_alias'] . "` ON (" . $aQueryParts['join']['condition'] . ")";

                        $sWhereClauseConnections = "NOT ISNULL(`c`.`content`) ";
                        if(!empty($aQueryParts['fields']['added']))
                            $sWhereClauseConnections .= "AND `{$this->_sTable}`.`date` >= `" . $aQueryParts['fields']['added']['table_alias'] . "`.`" . $aQueryParts['fields']['added']['name'] . "` ";
                    }

                    list($aModulesProfiles, $aModulesContexts) = $this->_oConfig->getProfileBasedModules();
                    $sJoinClause .= $this->prepareAsString(" INNER JOIN `{$this->_sTableSettings}` ON `{$this->_sTableHandlers}`.`id`=`{$this->_sTableSettings}`.`handler_id` AND `{$this->_sTableSettings}`.`delivery`='" . BX_BASE_MOD_NTFS_DTYPE_SITE . "' AND `{$this->_sTableSettings}`.`active`='1' AND (`{$this->_sTableSettings}`.`type`=? OR ((`{$this->_sTableSettings}`.`type`=? AND `tsp`.`type` IN (" . $this->implode_escape($aModulesProfiles) . ")) || (`{$this->_sTableSettings}`.`type`=? AND `tsp`.`type` IN (" . $this->implode_escape($aModulesContexts) . "))))", BX_NTFS_STYPE_PERSONAL, BX_NTFS_STYPE_FOLLOW_MEMBER, BX_NTFS_STYPE_FOLLOW_CONTEXT);

                    $sJoinClauseS2U = " LEFT JOIN `{$this->_sTableSettings2Users}` ON `{$this->_sTableSettings}`.`id`=`{$this->_sTableSettings2Users}`.`setting_id` ";
                    if(!empty($aParams['owner_id']))
                        $sJoinClauseS2U .= $this->prepareAsString("AND `{$this->_sTableSettings2Users}`.`user_id`=? ", $aParams['owner_id']);

                    $sJoinClause .= $sJoinClauseS2U;
                    $sWhereClauseSettings = "AND ((ISNULL(`{$this->_sTableSettings2Users}`.`active`) AND `{$this->_sTableSettings}`.`value`='1') OR (NOT ISNULL(`{$this->_sTableSettings2Users}`.`active`) AND `{$this->_sTableSettings2Users}`.`active`='1')) ";

                    $sWhereClauseType = "AND ((" . $sWhereClauseObjectOwner . ") OR (" . $sWhereClauseConnections . ")) ";
                    break;
            }

        $aAlertParams = $aParams;
        unset($aAlertParams['type'], $aAlertParams['owner_id']);

        bx_alert($this->_oConfig->getName(), 'get_list_by_type', 0, 0, [
            'type' => $aParams['type'],
            'owner_id' => $aParams['owner_id'],
            'params' => $aAlertParams,
            'table' => $this->_sTable,
            'join_clause' => &$sJoinClause,
            'where_clause' => &$sWhereClause,
            'where_clause_status' => &$sWhereClauseStatus,
            'where_clause_modules' => &$sWhereClauseModules,
            'where_clause_new' => &$sWhereClauseNew,
            'where_clause_settings' => &$sWhereClauseSettings,
            'where_clause_type' => &$sWhereClauseType
        ]);

        $sWhereClause .= $sWhereClauseStatus;
        $sWhereClause .= $sWhereClauseModules;
        $sWhereClause .= $sWhereClauseNew;
        $sWhereClause .= $sWhereClauseSettings;
        $sWhereClause .= $sWhereClauseType;

        return [$sJoinClause, $sWhereClause];
    }

    public function getEventsToProcess($iLimit = 0)
    {
        $aEvents = $this->getAll("SELECT * FROM `" . $this->_sTable . "` WHERE `id`>:id ORDER BY `id` ASC" . ($iLimit != 0 ? ' LIMIT ' . $iLimit : ''), array(
            'id' => $this->_oConfig->getProcessedEvent()
        ));

        if(!empty($aEvents) && is_array($aEvents)) {
            $aEventEnd = end($aEvents);

            $this->_oConfig->setProcessedEvent($aEventEnd['id']);

            reset($aEvents);
        }

        return $aEvents;
    }

    public function queueGet($aParams)
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sSelectClause = "*";
    	$sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['type']) {
            case 'count':
            	$aMethod['name'] = 'getOne';

                $sSelectClause = "COUNT(*)";
                break;

            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `id`=:id";
                break;

            case 'all_to_send':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = array(
                    'timeout' => $aParams['timeout']
                );

                $sWhereClause = " AND `date`+:timeout < UNIX_TIMESTAMP()";
                $sOrderClause = "`date` ASC";
                break;
        }

        $sOrderClause = !empty($sOrderClause) ? "ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? "LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
                " . $sSelectClause . "
            FROM `" . $this->_sTableQueue . "`
            WHERE 1" . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function queueAdd($aSet)
    {
        if(empty($aSet))
            return false;

        return (int)$this->query("INSERT INTO `" . $this->_sTableQueue . "` SET " . $this->arrayToSQL($aSet)) > 0;
    }

    public function queueDelete($aWhere)
    {
    	if(empty($aWhere))
            return false;

        return (int)$this->query("DELETE FROM `" . $this->_sTableQueue . "` WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }

    public function queueDeleteByProfile($iProfileId, $iEventId)
    {
        return (int)$this->query("DELETE FROM `" . $this->_sTableQueue . "` WHERE `profile_id`=:profile_id AND `event_id`<=:event_id", array(
            'profile_id' => $iProfileId,
            'event_id' => $iEventId
        )) > 0;
    }

    public function queueDeleteByIds($mixedId)
    {
        if(empty($mixedId))
            return false;

        if(!is_array($mixedId))
            $mixedId = array($mixedId);

        return (int)$this->query("DELETE FROM `" . $this->_sTableQueue . "` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")") > 0;
    }
    
    public function cleanEvents($iClearIntervalInDays)
    {
        $this->query("DELETE FROM `{$this->_sTable}` WHERE `date` < :date", array('date' => time() - $iClearIntervalInDays * 86400));
    }   

    public function filterProfileIdsByModule($aIds, $mixedModule)
    {
        if(!is_array($mixedModule))
            $mixedModule = array($mixedModule);

        return $this->getColumn("SELECT `id` FROM `sys_profiles` WHERE `id` IN (" . $this->implode_escape($aIds) . ") AND `type` IN (" . $this->implode_escape($mixedModule) . ")");
    }
}

/** @} */
