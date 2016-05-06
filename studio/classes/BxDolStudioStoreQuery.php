<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioStoreQuery extends BxDolStudioModulesQuery
{
    function __construct()
    {
        parent::__construct();
    }

    public function isQueued($sAction, $sName)
    {
    	$sJobName = '';
    	switch($sAction) {
    		case 'download':
    			$sJobName = BxDolStudioInstallerUtils::getNameDownloadFile($sName);
    			break;

    		case 'action':
    			$sJobName = BxDolStudioInstallerUtils::getNamePerformAction($sName);
    			break;
    	}

    	$sSql = $this->prepare("SELECT `id` FROM `sys_cron_jobs` WHERE `name`=? LIMIT 1", $sJobName);
    	return (int)$this->getOne($sSql) > 0;
    }
}

/** @} */
