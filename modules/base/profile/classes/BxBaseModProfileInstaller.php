<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralInstaller');

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
