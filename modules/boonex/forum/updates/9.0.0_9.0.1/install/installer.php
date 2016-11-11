<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'rate'))
        		$this->oDb->query("bx_forum_discussions` ADD `rate` float NOT NULL default '0' AFTER `views`");

    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'votes'))
        		$this->oDb->query("bx_forum_discussions` ADD `votes` int(11) NOT NULL default '0' AFTER `rate`");

    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'favorites'))
        		$this->oDb->query("bx_forum_discussions` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");

			if ($this->oDb->isIndexExists('bx_forum_cmts', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_forum_cmts` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_forum_cmts` ADD FULLTEXT KEY `search_fields` (`cmt_text`)");
		}

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
