<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxOrgsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
			if ($this->isIndex('bx_organizations_data', 'org_name'))
				$this->oDb->query("ALTER TABLE `bx_organizations_data` DROP INDEX `org_name`");

			if ($this->isIndex('bx_organizations_data', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_organizations_data` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_organizations_data` ADD FULLTEXT `search_fields` (`org_name`,`org_desc`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }

	protected function isIndex($sTable, $sName)
	{
		$bIndex = false;
        $aIndexes = $this->oDb->getAll("SHOW INDEXES FROM `" . $sTable . "`");
        foreach ($aIndexes as $aIndex) {
            if ($aIndex['Key_name'] == $sName) {
                $bIndex = true;
                break;
            }
        }

		return $bIndex;
	}
}
