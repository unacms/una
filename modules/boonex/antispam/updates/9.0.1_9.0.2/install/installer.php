<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAntispamUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function update($aParams)
    {
		$aResult = parent::update($aParams);
		if(!$aResult['result'])
			return $aResult;

        BxDolService::call('bx_antispam', 'update_disposable_domains_lists');

		return $aResult;
	}
}
