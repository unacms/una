<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAclUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
		$mixedResult = parent::actionExecuteSql($sOperation);
		if($sOperation == 'install' && $mixedResult === BX_DOL_STUDIO_INSTALLER_SUCCESS) {
			$sLangCategory = $this->oDb->getOne("SELECT `lang_category` FROM `sys_modules` WHERE `uri`=:uri LIMIT 1", array(
				'uri' => $this->_aConfig['module_uri']
			));

			if(strcmp($sLangCategory, $this->_aConfig['language_category']) !== 0) {
    			$this->oDb->query("UPDATE `sys_localization_categories` SET `Name`=:name_new WHERE `name`=:name_old", array(
    				'name_new' => $this->_aConfig['language_category'],
    				'name_old' => $sLangCategory,
    			));

    			$this->oDb->query("UPDATE `sys_modules` SET `title`=:title, `lang_category`=:lang_category WHERE `uri`=:uri", array(
    				'uri' => $this->_aConfig['module_uri'],
    				'title' => $this->_aConfig['title'],
    				'lang_category' => $this->_aConfig['language_category']
    			));
			}
		}

		return $mixedResult;
    }
}
