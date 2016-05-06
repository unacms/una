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

    public function associateFileWithContent($iContentId, $iFileId, $sVersion)
    {
        $sQuery = $this->prepare ("SELECT MAX(`order`) FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ?", $iContentId);
        $iOrder = 1 + (int)$this->getOne($sQuery);

        $sQuery = $this->prepare ("INSERT INTO `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` SET `content_id` = ?, `file_id` = ?, `version` = ?, `order` = ? ON DUPLICATE KEY UPDATE `version` = ?", $iContentId, $iFileId, $sVersion, $iOrder, $sVersion);
        return $this->res($sQuery);
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
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
    	
    	$sWhereClause = "";
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
        }

        $aMethod['params'][0] = "SELECT
        		`tl`.*
            FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` AS `tl`
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

    public function hasLicense ($iProfileId, $iProductId)
    {
    	$sQuery = $this->prepare("SELECT `id` FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` WHERE `profile_id` = ? AND `product_id` = ? LIMIT 1", $iProfileId, $iProductId);
        return (int)$this->getOne($sQuery) > 0;
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
    	
    	$sWhereClause = "";
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

                $sWhereClause = " AND `tfe`.`content_id`=?";
                break;
        }

        $aMethod['params'][0] = "SELECT
        		`tfe`.*
            FROM `" . $sTable . "` AS `tfe`
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
