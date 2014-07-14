<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModNotificationsDb');

class BxNtfsDb extends BxBaseModNotificationsDb
{
    protected $_sTableEvt2Usr;

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
        $this->_sTableEvt2Usr = $this->_sPrefix . 'events2users';
    }

    
    public function getEvents($aParams, $bReturnCount = false)
    {
        $sMethod = 'getAll';
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(isset($aParams['active']))
        	$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`active`=? ", (int)$aParams['active']);

        switch($aParams['browse']) {
            case 'id':
                $sMethod = 'getRow';
                $sWhereClause = $this->prepare("AND `{$this->_sTable}`.`id`=? ", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;

            case 'descriptor':
                $sMethod = 'getRow';
                $sWhereClause = $this->prepare("AND `{$this->_sTable}`.`type`=? AND `{$this->_sTable}`.`action`=? AND `{$this->_sTable}`.`object_id`=? ", $aParams['type'], $aParams['action'], $aParams['object_id']);
                $sLimitClause = "LIMIT 1";
                break;

            case 'first':
            case 'last':
            case 'list':
                //--- Apply modules or handlers filter
                $sWhereModuleFilter = '';
                if(isset($aParams['modules']) && !empty($aParams['modules']) && is_array($aParams['modules']))
                    $sWhereModuleFilter = "AND `type` IN (" . $this->implode_escape($aParams['modules']) . ") ";

                if($sWhereModuleFilter == '') {
                    $aHidden = $this->_oConfig->getHandlersHidden();
                    $sWhereModuleFilter = is_array($aHidden) && !empty($aHidden) ? "AND `th`.`id` NOT IN (" . $this->implode_escape($aHidden) . ") " : "";
                }

                if($sWhereModuleFilter == '')
                    $sWhereClause .= $sWhereModuleFilter;

				//--- Check flag 'New'
            	if(!empty($aParams['new']) && !empty($aParams['owner_id'])) {
            		$sSql = $this->prepare("SELECT `event_id` FROM `" . $this->_sTableEvt2Usr . "` WHERE `user_id`=? LIMIT 1", (int)$aParams['owner_id']);
    				$iId = (int)$this->getOne($sSql);

        			$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`id`>? ", $iId);
            	}

                //--- Check type
                if(!empty($aParams['owner_id']))
                    switch($aParams['type']) {
                        case BX_BASE_MOD_NTFS_TYPE_OWNER:
                            $sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`owner_id`=? ", $aParams['owner_id']);
                            break;

                        case BX_BASE_MOD_NTFS_TYPE_CONNECTIONS:
                            bx_import('BxDolConnection');
                            $oConnection = BxDolConnection::getObjectInstance($this->_oConfig->getObject('conn_subscriptions'));

                            $aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sPrefix . "events", 'owner_id', $aParams['owner_id']);
                            $sJoinClause .= ' ' . $aQueryParts['join'];
                            break;
                    }

                switch($aParams['browse']) {
                	case 'first':
                        $sMethod = 'getRow';
                        $sOrderClause = "ORDER BY `{$this->_sTable}`.`date` DESC";
                        $sLimitClause = "LIMIT 1";
                        break;
                    case 'last':
                        $sMethod = 'getRow';
                        $sOrderClause = "ORDER BY `{$this->_sTable}`.`date` ASC";
                        $sLimitClause = "LIMIT 1";
                        break;
                    case 'list':
                        $sOrderClause = "ORDER BY `{$this->_sTable}`.`date` DESC";
                        $sLimitClause = isset($aParams['per_page']) ? "LIMIT " . $aParams['start'] . ", " . $aParams['per_page'] : "";
                        break;
                }
                break;
        }

        $sSql = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `{$this->_sTable}`.`id` AS `id`,
                `{$this->_sTable}`.`owner_id` AS `owner_id`,
                `{$this->_sTable}`.`type` AS `type`,
                `{$this->_sTable}`.`action` AS `action`,
                `{$this->_sTable}`.`object_id` AS `object_id`,
                `{$this->_sTable}`.`content` AS `content`,
                `{$this->_sTable}`.`date` AS `date`,
                YEAR(FROM_UNIXTIME(`{$this->_sTable}`.`date`)) AS `year`
            FROM `{$this->_sTable}`
            LEFT JOIN `{$this->_sTableHandlers}` ON `{$this->_sTable}`.`type`=`{$this->_sTableHandlers}`.`alert_unit` AND `{$this->_sTable}`.`action`=`{$this->_sTableHandlers}`.`alert_action` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        $aEntries = $this->$sMethod($sSql);
        if(!$bReturnCount)
        	return $aEntries;

        return array($aEntries, (int)$this->getOne("SELECT FOUND_ROWS()"));
    }

    public function markAsRead($iUserId, $iEventId)
    {
    	$sSql = $this->prepare("REPLACE `" . $this->_sTableEvt2Usr . "` SET `user_id`=?, `event_id`=?", $iUserId, $iEventId);
    	return (int)$this->query($sSql) > 0;
    }
}

/** @} */
