<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once(BX_DIRECTORY_PATH_CLASSES . "BxDolInstaller.php");

class BxPfwInstaller extends BxDolInstaller
{
    function BxPfwInstaller($aConfig)
    {
        parent::BxDolInstaller($aConfig);
    }

    function install($aParams)
    {
        $aResult = parent::install($aParams);

        if($aResult['result'])
            BxDolService::call($this->_aConfig['home_uri'], 'update_dependent_modules');

        return $aResult;
    }
}
