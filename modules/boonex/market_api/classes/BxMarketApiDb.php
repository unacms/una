<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    MarketApi MarketApi
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxMarketApiDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

	public function getLicense($aParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sJoinClause = $sWhereClause = "";
        switch($aParams['type']) {
            case 'license_id_profile_id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                	'license_id' => $aParams['license_id'],
            		'profile_id' => $aParams['profile_id']
                );

                $sWhereClause = " AND `tl`.`" . $CNF['FIELD_LICENSE_ID'] . "`=:license_id AND `tl`.`" . $CNF['FIELD_PROFILE_ID'] . "`=:profile_id";
                break;

			case 'profile_id_type':
				$aMethod['params'][1] = array(
            		'profile_id' => $aParams['profile_id'],
					'type' => $aParams['type']
                );

				$sWhereClause = " AND `tl`.`" . $CNF['FIELD_PROFILE_ID'] . "`=:profile_id AND `tl`.`" . $CNF['FIELD_TYPE'] . "`=:type";
                break;
        }

        $aMethod['params'][0] = "SELECT
        		`tl`.*
            FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` AS `tl`" . $sJoinClause . "
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
