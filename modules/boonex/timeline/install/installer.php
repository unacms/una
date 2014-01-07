<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');

class BxTimelineInstaller extends BxDolStudioInstaller
{
	function __construct($aConfig) {
        parent::__construct($aConfig);
    }

    function enable($aParams)
    {
    	$aResult = parent::enable($aParams);

    	if($aResult['result'])
            BxDolService::call($this->_aConfig['name'], 'add_handlers');

		return $aResult;
    }

    function disable($aParams)
    {
     	BxDolService::call($this->_aConfig['name'], 'delete_handlers');

    	return parent::disable($aParams);
    }
}

/** @} */ 
