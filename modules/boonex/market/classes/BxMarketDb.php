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

    public function getFile($aParams = array())
    {
    	return $this->_getAttachment($this->_oConfig->CNF['TABLE_FILES2ENTRIES'], $aParams);
    }

    protected function _getAttachment($sTable, $aParams = array())
    {
    	$aMethod = array('name' => 'getRow', 'params' => array(0 => 'query'));
    	
    	$sWhereClause = "";
        switch($aParams['type']) {
            case 'id':
                $sWhereClause = $this->prepare(" AND `tfe`.`id`=?", $aParams['id']);
                break;

			case 'file_id':
                $sWhereClause = $this->prepare(" AND `tfe`.`file_id`=?", $aParams['file_id']);
                break;

            case 'content_id':
            	$aMethod['name'] = 'getAll';

            	$sWhereClause = $this->prepare(" AND `tfe`.`content_id`=?", $aParams['content_id']);
            	if(!empty($aParams['except']))
            		$sWhereClause .= " AND `tfe`.`file_id` NOT IN (" . $this->implode_escape($aParams['except']) . ")";

            	break;

			case 'content_id_key_file_id':
				$aMethod['name'] = 'getAllWithKey';
				$aMethod['params'][1] = 'file_id';

                $sWhereClause = $this->prepare(" AND `tfe`.`content_id`=?", $aParams['content_id']);
                break;
        }

        $aMethod['params'][0] = "SELECT
        		`tfe`.*
            FROM `" . $sTable . "` AS `tfe`
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
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
    
	protected function _deassociateAttachmentWithContent($sTable, $iContentId, $iFileId)
    {
        $sWhere = '';
        if ($iContentId)
            $sWhere .= $this->prepare (" AND `content_id` = ? ", $iContentId);

        if ($iFileId)
            $sWhere .= $this->prepare (" AND `file_id` = ? ", $iFileId);

        $sQuery = "DELETE FROM `" . $sTable . "` WHERE 1 ";
        return $this->query($sQuery . $sWhere);
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
                $sWhereClause = $this->prepare(" AND `tl`.`id`=?", $aParams['id']);
                break;

			case 'unused':
                $sWhereClause = $this->prepare(" AND `tl`.`profile_id`=? AND `tl`.`domain`=''", $aParams['profile_id']);
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
}

/** @} */
