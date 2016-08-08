<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseTemplate Base classes for template modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModTemplateInstaller extends BxDolStudioInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

	function enable($aParams)
    {
        $aResult = parent::enable($aParams);

        if ($aResult['result'] && !getParam('template'))
            setParam('template', $this->_aConfig['home_uri']);

        return $aResult;
    }
}

/** @} */
