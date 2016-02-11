<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolPayments
 */
class BxDolPaymentsQuery extends BxDolDb
{
	public function __construct()
    {
    	parent::__construct();
    }

    public function getObjects()
    {
    	$sQuery = $this->prepare("SELECT * FROM `sys_objects_payments` WHERE 1");
    	
        $aObjects = $this->getAll($sQuery);
        if(empty($aObjects) || !is_array($aObjects))
            return array();

        return $aObjects;
    }
}

/** @} */
