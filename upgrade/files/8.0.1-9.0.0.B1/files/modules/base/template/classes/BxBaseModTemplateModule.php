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

bx_import ('BxBaseModGeneralModule');

class BxBaseModTemplateModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }
}

/** @} */
