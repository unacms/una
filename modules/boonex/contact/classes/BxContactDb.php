<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Contact Contact
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

class BxContactDb extends BxDolModuleDb
{
    var $_oConfig;

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
    }
}

/** @} */ 
