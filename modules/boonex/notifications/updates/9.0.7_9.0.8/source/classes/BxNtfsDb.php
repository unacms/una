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

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
        $this->_sTableEvt2Usr = $this->_sPrefix . 'events2users';
    }

    public function markAsRead($iUserId, $iEventId)
    {
    	$sSql = $this->prepare("REPLACE `" . $this->_sTableEvt2Usr . "` SET `user_id`=?, `event_id`=?", $iUserId, $iEventId);
    	return (int)$this->query($sSql) > 0;
    }

    public function getLastRead($iUserId)
    {
        return (int)$this->getOne("SELECT `event_id` FROM `" . $this->_sTableEvt2Usr . "` WHERE `user_id`=:user_id LIMIT 1", array(
            'user_id' => (int)$iUserId
        ));
    }

	protected function _getSqlPartsEventsList($aParams)
	{
		$sJoinClause = $sWhereClause = "";

		if(isset($aParams['active']))
			$sWhereClause .= $this->prepareAsString("AND `{$this->_sTable}`.`active`=? ", (int)$aParams['active']);

		//--- Apply modules or handlers filter
		$sWhereModuleFilter = '';
		if(!empty($aParams['modules']) && is_array($aParams['modules']))
			$sWhereModuleFilter = "AND `" . $this->_sTable . "`.`type` IN (" . $this->implode_escape($aParams['modules']) . ") ";

		if($sWhereModuleFilter == '') {
			$aHidden = $this->_oConfig->getHandlersHidden();
			$sWhereModuleFilter = !empty($aHidden) && is_array($aHidden) ? "AND `" . $this->_sTableHandlers . "`.`id` NOT IN (" . $this->implode_escape($aHidden) . ") " : "";
		}

		if($sWhereModuleFilter != '')
			$sWhereClause .= $sWhereModuleFilter;

		//--- Check flag 'New'
		if(!empty($aParams['new']) && !empty($aParams['owner_id']))
			$sWhereClause .= $this->prepareAsString("AND `{$this->_sTable}`.`id`>? ", $this->getLastRead((int)$aParams['owner_id']));

		//--- Apply privacy filter
		$aPrivacy = array(BX_DOL_PG_ALL);
		if(isLogged())
			$aPrivacy[] = BX_DOL_PG_MEMBERS;

		$oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->getObject('privacy_view'));
		$aQueryParts = $oPrivacy->getContentByGroupAsSQLPart($aPrivacy);
		$sWhereClause .= $aQueryParts['where'] . " ";

		//--- Check type
		if(!empty($aParams['owner_id']))
			switch($aParams['type']) {
				case BX_BASE_MOD_NTFS_TYPE_OWNER:
					$sWhereClause .= $this->prepareAsString("AND `{$this->_sTable}`.`owner_id`=? ", $aParams['owner_id']);
					break;

				case BX_BASE_MOD_NTFS_TYPE_OBJECT_OWNER:
					$sWhereClause .= $this->prepareAsString("AND `{$this->_sTable}`.`owner_id`<>`{$this->_sTable}`.`object_owner_id` AND `{$this->_sTable}`.`object_owner_id`=? ", $aParams['owner_id']);
					break;

				case BX_BASE_MOD_NTFS_TYPE_CONNECTIONS:
					$oConnection = BxDolConnection::getObjectInstance($this->_oConfig->getObject('conn_subscriptions'));

					$aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sPrefix . "events", 'owner_id', $aParams['owner_id']);
					if(!empty($aQueryParts['join']))
					    $sJoinClause .= ' ' . $aQueryParts['join'];
					if(!empty($aQueryParts['fields']['added']))
					    $sWhereClause .= "AND `{$this->_sTable}`.`date` > " . $aQueryParts['fields']['added'];

					$sWhereClause .= "AND `{$this->_sTable}`.`action` <> 'replyPost' ";
					break;

                case BX_NTFS_TYPE_OBJECT_OWNER_AND_CONNECTIONS:
                    $oConnection = BxDolConnection::getObjectInstance($this->_oConfig->getObject('conn_subscriptions'));

					$aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sPrefix . "events", 'owner_id', $aParams['owner_id']);
					if(!empty($aQueryParts['join']))
					    $sJoinClause .= ' ' . str_replace('INNER', 'LEFT', $aQueryParts['join']);

					$sWhereClause .= $this->prepareAsString("AND ((NOT ISNULL(`c`.`content`)" . (!empty($aQueryParts['fields']['added']) ? " AND `{$this->_sTable}`.`date` > " . $aQueryParts['fields']['added'] : "") . " AND `{$this->_sTable}`.`action` <> 'replyPost') || (`{$this->_sTable}`.`owner_id` <> `{$this->_sTable}`.`object_owner_id` AND `{$this->_sTable}`.`object_owner_id`=?)) ", $aParams['owner_id']);
                    break;
			}
            

		return array($sJoinClause, $sWhereClause);
	}
}

/** @} */
