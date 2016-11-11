<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxChatPlusUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
		$mixedResult = parent::actionExecuteSql($sOperation);
		if($sOperation == 'install' && $mixedResult === BX_DOL_STUDIO_INSTALLER_SUCCESS) {
			$this->oDb->query("UPDATE `sys_modules` SET `title`=:title WHERE `uri`=:uri", array(
				'uri' => $this->_aConfig['module_uri'],
				'title' => $this->_aConfig['title']
			));
		}

		return $mixedResult;
    }
}
