<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Contact Contact
 * @ingroup     UnaModules
 *
 * @{
 */

class BxContactDb extends BxDolModuleDb
{
    protected $_oConfig;

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
