<?php use system\L;
defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolUpgrader');

class BxDolUpgraderModules extends BxDolUpgrader
{
    public function prepare()
    {
        $iUmaskSave = umask(0);

        bx_import('BxDolStudioInstallerUtils');
        $oInstallerUtils = BxDolStudioInstallerUtils::getInstance();

        while(true) {
        	$aUpdates = $oInstallerUtils->checkUpdates();
	        foreach($aUpdates as $aUpdate) {
	        	$mixedResult = $oInstallerUtils->downloadUpdatePublic($aUpdate['name']);
	        	if($mixedResult !== true) {
	        		$this->setError($mixedResult);
	        		break;
	        	}
	        }

	        if($this->getError()) 
	        	break;

        	$aUpdates = $oInstallerUtils->getUpdates();
        	foreach($aUpdates as $aUpdate) {
        		$aResult = BxDolStudioInstallerUtils::getInstance()->perform($aUpdate['dir'], 'update');
        		if((int)$aResult['code'] != 0) {
        			$this->setError($aResult['message']);
        			break;
        		}
        	}

        	break;
        }

        umask($iUmaskSave);

        return $this->getError() ? false : true;
    }
}

/** @} */
