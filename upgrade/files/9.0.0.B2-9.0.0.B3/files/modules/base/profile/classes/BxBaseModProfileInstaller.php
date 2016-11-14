<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileInstaller extends BxBaseModGeneralInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    function disable($aParams)
    {
        $aResult = parent::disable($aParams);

        if ($aResult['result']) { // disabling was successful
            // TODO: switch accounts context which active profiles belong to this module
        }

        return $aResult;
    }
}

/** @} */
