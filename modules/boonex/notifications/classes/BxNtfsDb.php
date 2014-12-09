<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     TridentModules
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

    public function markAsRead($iUserId, $iEventId)
    {
    	$sSql = $this->prepare("REPLACE `" . $this->_sTableEvt2Usr . "` SET `user_id`=?, `event_id`=?", $iUserId, $iEventId);
    	return (int)$this->query($sSql) > 0;
    }

	protected function _getSqlPartsEventsList($aParams)
	{
		$sJoinClause = $sWhereClause = "";

		if(isset($aParams['active']))
			$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`active`=? ", (int)$aParams['active']);

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

		return array($sJoinClause, $sWhereClause);
	}
}

/** @} */
