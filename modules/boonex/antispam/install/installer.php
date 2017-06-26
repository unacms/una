<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAntispamInstaller extends BxDolStudioInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function enable($aParams)
    {
        $aResult = parent::enable($aParams);
        if ($aResult['result'])
            BxDolService::call('bx_antispam', 'update_disposable_domains_lists');

        return $aResult;
    }    
}

/** @} */
