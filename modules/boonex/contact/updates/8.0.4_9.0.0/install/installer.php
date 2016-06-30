<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxContactUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
			$sFile = BX_DIRECTORY_PATH_TMP . $this->_aConfig['home_uri'] . '_processed.txt';
			if(!file_exists($sFile)) {
				$aEntries = $this->oDb->getAll('SELECT * FROM `bx_contact_entries`');
				foreach($aEntries as $aEntry)
					$this->oDb->query('UPDATE `bx_contact_entries` SET `body`=:body WHERE `id`=:id', array(
						'body' => nl2br(htmlspecialchars_adv($aEntry['body'])), 
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
