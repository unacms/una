<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxInvUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_inv_invites', 'key'))
        		$this->oDb->query("ALTER TABLE `bx_inv_invites` ADD `key` varchar(128) collate utf8_unicode_ci NOT NULL AFTER `profile_id`");

			$sFile = BX_DIRECTORY_PATH_TMP . $this->_aConfig['home_uri'] . '_processed.txt';
			if(!file_exists($sFile)) {
				$aEntries = $this->oDb->getAll('SELECT * FROM `bx_inv_requests`');
				foreach($aEntries as $aEntry)
					$this->oDb->query('UPDATE `bx_inv_requests` SET `text`=:text WHERE `id`=:id', array(
						'text' => nl2br(htmlspecialchars_adv($aEntry['text'])), 
						'id' => $aEntry['id']
					));

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
