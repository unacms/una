<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxMarketUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_market_products', 'favorites'))
        		$this->oDb->query("ALTER TABLE `bx_market_products` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");

			if ($this->oDb->isIndexExists('bx_market_cmts', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_market_cmts` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_market_cmts` ADD FULLTEXT KEY `search_fields` (`cmt_text`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
