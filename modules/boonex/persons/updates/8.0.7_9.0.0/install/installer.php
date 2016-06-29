<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxPersonsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }
    public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_persons_data', 'allow_view_to'))
        		$this->oDb->query("ALTER TABLE `bx_persons_data` ADD `allow_view_to` int(11) NOT NULL DEFAULT '3' AFTER `views`");

			$sFile = BX_DIRECTORY_PATH_TMP . $this->_aConfig['home_uri'] . '_processed.txt';
			if(!file_exists($sFile)) {
				$aEntries = $this->oDb->getAll('SELECT * FROM `bx_persons_data`');
				foreach($aEntries as $aEntry)
					$this->oDb->query('UPDATE `bx_persons_data` SET `description`=? WHERE `id`=?', nl2br(htmlspecialchars_adv($aEntry['description'])), $aEntry['id']);

				$oHandler = fopen($sFile, 'w');
				if($oHandler) {
					fwrite($oHandler, 'processed');
					fclose($oHandler);
				}
			}
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
