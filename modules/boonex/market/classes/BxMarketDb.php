<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxMarketDb extends BxBaseModTextDb
{
	protected $_aRecurringDurations;

    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_aRecurringDurations = array(
        	'week' => 'INTERVAL 7 DAY',
        	'month' => 'INTERVAL 1 MONTH',
        	'year' => 'INTERVAL 1 YEAR',
        );
    }

    public function getContentInfoBy ($aParams)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
		$aOrderWay = array('up' => 'ASC', 'down' => 'DESC');

    	$sFieldsClause = $sJoinClause = $sWhereClause = $sOrderClause = '';

    	//--- Add file info.
    	$sFieldsClause .= " `te`.`" . $CNF['FIELD_PACKAGE'] . "` AS `file_id`, `tf`.`file_name` AS `file_name`, `tfe`.`version` AS `file_version`, ";
    	$sJoinClause .= " LEFT JOIN `" . $CNF['TABLE_FILES2ENTRIES'] . "` AS `tfe` ON `te`.`" . $CNF['FIELD_ID'] . "`=`tfe`.`content_id` AND `te`.`" . $CNF['FIELD_PACKAGE'] . "`=`tfe`.`file_id` LEFT JOIN `" . $CNF['TABLE_FILES'] . "` AS `tf` ON `te`.`" . $CNF['FIELD_PACKAGE'] . "`=`tf`.`id` ";

    	//--- Add license checking for Public listings if Client is specified.
    	if(in_array($aParams['type'], array('featured', 'category', 'tag', 'vendor', 'keyword')) && isset($aParams['client']) && (int)$aParams['client'] != 0) {
    		$sFieldsClause .= " `tl`.`added` AS `purchased_on`, `tl`.`added` AS `purchased_on_f`, UNIX_TIMESTAMP() - `tl`.`added` AS `purchased_ago`, ";
    		$sJoinClause .= $this->prepareAsString(" LEFT JOIN `" . $CNF['TABLE_LICENSES'] . "` AS `tl` ON `te`.`id`=`tl`.`product_id` AND `tl`.`profile_id`=? AND (`tl`.`domain`=?" . (empty($aParams['key_assigned']) ? " OR `tl`.`domain`=''" : "") . ") ", (int)$aParams['client'], $aParams['key']);
    	}

		switch($aParams['type']) {
			case 'id':
				$aMethod['name'] = 'getRow';
				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= $this->prepareAsString(" AND `te`.`" . $CNF['FIELD_ID'] . "`=? ", $aParams['value']);
				$sOrderClause .= "";
				break;

			case 'name':
				$aMethod['name'] = 'getRow';
				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= $this->prepareAsString(" AND `te`.`" . $CNF['FIELD_NAME'] . "`=? ", $aParams['value']);
				$sOrderClause .= "";
				break;

			case 'file_id':
				$aMethod['name'] = 'getRow';
				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= $this->prepareAsString(" AND `te`.`" . $CNF['FIELD_PACKAGE'] . "`=? ", $aParams['value']);
				$sOrderClause .= "";
				break;

			case 'latest':
				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= "";
				$sOrderClause = "`te`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			case 'featured':
				if(!is_array($aParams['value']))
					$aParams['value'] = array($aParams['value']);

				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= " AND `te`.`" . $CNF['FIELD_AUTHOR'] . "` IN (" . $this->implode_escape($aParams['value']) . ")";
				$sOrderClause = "`te`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			case 'selected':
				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= " AND `te`.`id` IN (" . $this->implode_escape($aParams['selected']) . ") ";
				$sOrderClause = "`te`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			case 'category':
				if(!is_array($aParams['value']))
					$aParams['value'] = array($aParams['value']);

				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= " AND `te`.`" . $CNF['FIELD_CATEGORY'] . "` IN (" . $this->implode_escape($aParams['value']) . ")";
				$sOrderClause = "`te`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			//TODO: There is no tags in current version.
			case 'tag':
				$sFieldsClause .= "";
				$sJoinClause .= "";
				$sWhereClause .= $this->prepareAsString(" AND `te`.`tags`=? ", $aParams['value']);
				$sOrderClause = "`te`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			case 'vendor':
				$sFieldsClause .= "";
				$sJoinClause .= "";

				$sWhereClause .= $this->prepareAsString(" AND `te`.`" . $CNF['FIELD_AUTHOR'] . "`=? ", (int)$aParams['value']); 
				if(isset($aParams['paid']) && (int)$aParams['paid'] == 1)
					$sWhereClause .= " AND `te`.`" . $CNF['FIELD_PRICE_SINGLE'] . "`<>'0' AND `te`.`" . $CNF['FIELD_PRICE_RECURRING'] . "`<>'0'";

				$sOrderClause = "`te`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			case 'keyword':
				$sFieldsClause .= $this->prepareAsString(" MATCH(`" . $CNF['FIELD_TITLE'] . "`, `" . $CNF['FIELD_TEXT'] . "`) AGAINST (?) AS `search_condition`, ", $aParams['value']);
				$sJoinClause .= "";
				$sWhereClause .= $this->prepareAsString(" AND MATCH(`" . $CNF['FIELD_TITLE'] . "`, `" . $CNF['FIELD_TEXT'] . "`) AGAINST (?) ", $aParams['value']);
				$sOrderClause = "`search_condition` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			case 'granted':
				$sFieldsClause .= " '" . $aParams['license']['license'] . "' AS `license`, '" . $aParams['license']['profile_id'] . "' AS `purchased_by`, '' AS `purchased_for`, '" . $aParams['license']['added'] . "' AS `purchased_on`, '" . $aParams['license']['added'] . "' AS `purchased_on_f`, UNIX_TIMESTAMP() - '" . $aParams['license']['added'] . "' AS `purchased_ago`, ";
				$sWhereClause .= $this->prepareAsString(" AND `te`.`" . $CNF['FIELD_AUTHOR'] . "`=? AND (`te`.`" . $CNF['FIELD_PRICE_SINGLE'] . "`<>'0' OR `te`.`" . $CNF['FIELD_PRICE_RECURRING'] . "`<>'0') ", (int)$aParams['value']);
				$sOrderClause = "`te`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;

			case 'purchased':
				$sFieldsClause .= " `tl`.`license` AS `license`, `tl`.`profile_id` AS `purchased_by`, `tl`.`domain` AS `purchased_for`, `tl`.`added` AS `purchased_on`, `tl`.`added` AS `purchased_on_f`, UNIX_TIMESTAMP() - `tl`.`added` AS `purchased_ago`, ";
				$sJoinClause .= " LEFT JOIN `" . $CNF['TABLE_LICENSES'] . "` AS `tl` ON `te`.`" . $CNF['FIELD_ID'] . "`=`tl`.`product_id` ";
				$sWhereClause .= $this->prepareAsString(" AND `tl`.`profile_id`=? AND (`tl`.`domain`=?" . (empty($aParams['key_assigned']) ? " OR `tl`.`domain`=''" : "") . ") ", (int)$aParams['client'], $aParams['key']);
				$sOrderClause = "`tl`.`added` " . (isset($aParams['order_way']) ? $aOrderWay[$aParams['order_way']] : "DESC");
				break;
		}

		$sOrderClause = $sOrderClause ? " ORDER BY " . $sOrderClause : "";

		$aMethod['params'][0] = "SELECT
        		" . $sFieldsClause . "`te`.*
            FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `te`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

	public function getContentInfoByName ($sContentName)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_NAME'] . "` = ?", $sContentName);
        return $this->getRow($sQuery);
    }

	public function getPhoto($aParams = array())
    {
    	return $this->_getAttachment($this->_oConfig->CNF['TABLE_PHOTOS2ENTRIES'], $aParams);
    }

	public function updatePhoto($aSet, $aWhere)
    {
    	return $this->_updateAttachment($this->_oConfig->CNF['TABLE_PHOTOS2ENTRIES'], $aSet, $aWhere);
    }

    public function getFile($aParams = array())
    {
    	return $this->_getAttachment($this->_oConfig->CNF['TABLE_FILES2ENTRIES'], $aParams);
    }

	public function updateFile($aSet, $aWhere)
    {
    	return $this->_updateAttachment($this->_oConfig->CNF['TABLE_FILES2ENTRIES'], $aSet, $aWhere);
    }

	public function associatePhotoWithContent($iContentId, $iFileId, $sTitle)
    {
        $sQuery = $this->prepare ("SELECT MAX(`order`) FROM `" . $this->_oConfig->CNF['TABLE_PHOTOS2ENTRIES'] . "` WHERE `content_id` = ?", $iContentId);
        $iOrder = 1 + (int)$this->getOne($sQuery);

        $sQuery = $this->prepare ("INSERT INTO `" . $this->_oConfig->CNF['TABLE_PHOTOS2ENTRIES'] . "` SET `content_id` = ?, `file_id` = ?, `title` = ?, `order` = ? ON DUPLICATE KEY UPDATE `title` = ?", $iContentId, $iFileId, $sTitle, $iOrder, $sTitle);
        return $this->res($sQuery);
    }

	public function deassociatePhotoWithContent($iContentId, $iFileId)
    {
    	return $this->_deassociateAttachmentWithContent($this->_oConfig->CNF['TABLE_PHOTOS2ENTRIES'], $iContentId, $iFileId);
    }

    public function associateFileWithContent($iContentId, $iFileId, $aParams)
    {
        $sQuery = $this->prepare ("SELECT MAX(`order`) FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ?", $iContentId);
        $iOrder = 1 + (int)$this->getOne($sQuery);

        $aBindings = array_merge(array(
        	'content_id' => $iContentId,
        	'file_id' => $iFileId,
        	'order' => $iOrder,
        ), $aParams);

        $sDiv = ", ";
        $sParams = "";
        foreach($aParams as $sKey => $mixedValue)
            $sParams .= "`" . $sKey . "` = :" . $sKey . $sDiv;
		$sParams = trim($sParams, $sDiv);

        $sQuery = $this->prepare("INSERT INTO `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` SET `content_id` = :content_id, `file_id` = :file_id, " . $sParams . ", `order` = :order ON DUPLICATE KEY UPDATE " . $sParams);
        return $this->res($sQuery, $aBindings);
    }

    public function deassociateFileWithContent($iContentId, $iFileId)
    {
    	return $this->_deassociateAttachmentWithContent($this->_oConfig->CNF['TABLE_FILES2ENTRIES'], $iContentId, $iFileId);
    }

    public function insertDownload($iFileId, $iProfileId, $iProfileNip)
    {
    	$sQuery = $this->prepare("INSERT IGNORE INTO `" . $this->_oConfig->CNF['TABLE_DOWNLOADS'] . "` SET `file_id` = ?, `profile_id` = ?, `profile_nip` = ?, `date` = UNIX_TIMESTAMP()", $iFileId, $iProfileId, $iProfileNip);
		return (int)$this->query($sQuery) > 0;
    }

    /**
     * Integration with Payment based modules.  
     */
	public function getLicense($aParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sJoinClause = $sWhereClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                	'id' => $aParams['id']
                );

                $sWhereClause = " AND `tl`.`id`=:id";
                break;

			case 'unused':
				$aMethod['params'][1] = array(
                	'profile_id' => $aParams['profile_id']
                );

                $sWhereClause = " AND `tl`.`profile_id`=:profile_id AND `tl`.`domain`=''";
                break;

			case 'profile_id':
				$aMethod['params'][1] = array(
                	'profile_id' => $aParams['profile_id']
                );

				$sWhereClause = " AND `tl`.`profile_id`=:profile_id";

				if(!empty($aParams['product_id'])) {
					$aMethod['params'][1]['product_id'] = $aParams['product_id'];
					$sWhereClause .= " AND `tl`.`product_id`=:product_id";
				}
				break;

			case 'profile_id_file_id_key':
				$aMethod['name'] = "getRow";
				$aMethod['params'][1] = array(
                	'profile_id' => $aParams['profile_id'],
					'file_id' => $aParams['file_id'],
					'key' => $aParams['key']
                );

				$sJoinClause .= " LEFT JOIN `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `te` ON `tl`.`product_id`=`te`.`" . $CNF['FIELD_ID'] . "`";
				$sWhereClause .= " AND `tl`.`profile_id`=:profile_id AND `te`.`" . $CNF['FIELD_PACKAGE'] . "`=:file_id AND (`tl`.`domain`='' OR `tl`.`domain`=:key)";
				break;
        }

        $aMethod['params'][0] = "SELECT
        		`tl`.*
            FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` AS `tl`" . $sJoinClause . "
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

	public function updateLicense($aSet, $aWhere)
    {
        $sQuery = "UPDATE `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1");
        return (int)$this->query($sQuery) > 0;
    }

	public function deleteLicense($aWhere)
    {
    	if(empty($aWhere))
    		return false;

        $sQuery = "DELETE FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` WHERE " . $this->arrayToSQL($aWhere, ' AND ');
        return (int)$this->query($sQuery) > 0;
    }

    public function hasLicense ($iProfileId, $iProductId, $sDomain = '')
    {
    	$aBindings = array(
    		'profile_id' => $iProfileId,
    		'product_id' => $iProductId,
    	);

    	$sWhereAddon = '';
    	if(!empty($sDomain)) {
    		$aBindings['domain'] = $sDomain;
    		$sWhereAddon = " AND `domain`=:domain";
    	}

    	$sQuery = "SELECT `id` FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` WHERE `profile_id` = :profile_id AND `product_id` = :product_id" . $sWhereAddon . " LIMIT 1";
        return (int)$this->getOne($sQuery, $aBindings) > 0;
    }

	public function registerLicense($iProfileId, $iProductId, $iCount, $sOrder, $sLicense, $sType, $sDuration = '')
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aQueryParams = array(
    		'profile_id' => $iProfileId,
    		'product_id' => $iProductId,
    		'count' => $iCount, 
    		'order' => $sOrder, 
    		'license' => $sLicense,
    		'type' => $sType
    	);

		$sExpireParam = ''; 
		if(!empty($sDuration))
			$sExpireParam = ', `expired`=UNIX_TIMESTAMP(DATE_ADD(DATE_ADD(NOW(), ' . $this->_aRecurringDurations[$sDuration] . '), INTERVAL ' . (int)$this->getParam($CNF['OPTION_RECURRING_RESERVE']) . ' DAY))';

    	$sQuery = $this->prepare("INSERT INTO `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` SET " . $this->arrayToSQL($aQueryParams) . ", `added`=UNIX_TIMESTAMP()" . $sExpireParam);
        return (int)$this->query($sQuery) > 0;
    }

    public function unregisterLicense($iProfileId, $iProductId, $sOrder, $sLicense, $sType)
    {
    	$sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` WHERE `profile_id` = ? AND `product_id` = ? AND `order` = ? AND `license` = ?", $iProfileId, $iProductId, $sOrder, $sLicense);
        return (int)$this->query($sQuery) > 0;
    }

	protected function _deassociateAttachmentWithContent($sTable, $iContentId, $iFileId)
    {
        $sWhere = '';
        $aBindings = array();
        if ($iContentId) {
            $sWhere .= " AND `content_id` = :content_id ";
            $aBindings['content_id'] = $iContentId;
        }

        if ($iFileId) {
            $sWhere .= " AND `file_id` = :file_id ";
            $aBindings['file_id'] = $iFileId;
        }

        $sQuery = "DELETE FROM `" . $sTable . "` WHERE 1 " . $sWhere;
        return $this->query($sQuery, $aBindings);
    }

	protected function _getAttachment($sTable, $aParams = array())
    {
    	$aMethod = array('name' => 'getRow', 'params' => array(0 => 'query'));
    	
    	$sFieldsClause = "`tfe`.*";
    	$sJoinClause = $sWhereClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['params'][1] = array(
                	'id' => $aParams['id']
                );

                $sWhereClause = " AND `tfe`.`id`=:id";
                break;

			case 'file_id':
				$aMethod['params'][1] = array(
                	'file_id' => $aParams['file_id']
                );

                $sWhereClause = " AND `tfe`.`file_id`=:file_id";
                break;

			case 'file_id_ext':
				$aMethod['params'][1] = array(
                	'file_id' => $aParams['file_id']
                );

                $sFieldsClause .= ", `tf`.`file_name`, `tf`.`size` AS `file_size`";
                $sJoinClause = " LEFT JOIN `" . $this->_oConfig->CNF['TABLE_FILES'] . "` AS `tf` ON `tfe`.`file_id`=`tf`.`id` ";
                $sWhereClause = " AND `tfe`.`file_id`=:file_id";
                break;
                

            case 'content_id':
            	$aMethod['name'] = 'getAll';
            	$aMethod['params'][1] = array(
                	'content_id' => $aParams['content_id']
                );

            	$sWhereClause = " AND `tfe`.`content_id`=:content_id";
            	if(!empty($aParams['except']))
            		$sWhereClause .= " AND `tfe`.`file_id` NOT IN (" . $this->implode_escape($aParams['except']) . ")";

            	break;

			case 'content_id_key_file_id':
				$aMethod['name'] = 'getAllWithKey';
				$aMethod['params'][1] = 'file_id';
				$aMethod['params'][2] = array(
                	'content_id' => $aParams['content_id']
                );

                $sWhereClause = " AND `tfe`.`content_id`=:content_id";
                break;

			case 'content_id_and_type':
				$aMethod['name'] = 'getAll';
            	$aMethod['params'][1] = array(
                	'content_id' => $aParams['content_id'],
            		'type' => $aParams['file_type']
                );

                $sFieldsClause .= ", `tf`.`file_name`, `tf`.`size` AS `file_size`";
                $sJoinClause = " LEFT JOIN `" . $this->_oConfig->CNF['TABLE_FILES'] . "` AS `tf` ON `tfe`.`file_id`=`tf`.`id` ";
                $sWhereClause = " AND `tfe`.`content_id`=:content_id AND `tfe`.`type`=:type";

                if(!empty($aParams['version'])) {
                	$aMethod['name'] = 'getRow';
                	$aMethod['params'][1]['version'] = $aParams['version'];
                	$sWhereClause .= " AND `tfe`.`version`=:version";
                }
            	break;
        }

        $aMethod['params'][0] = "SELECT
        		" . $sFieldsClause . "
            FROM `" . $sTable . "` AS `tfe`" . $sJoinClause . "
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

	protected function _updateAttachment($sTable, $aSet, $aWhere)
    {
        $sQuery = "UPDATE `" . $sTable . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1");
        return (int)$this->query($sQuery) > 0;
    }
}

/** @} */
