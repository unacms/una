<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Marker.io Marker.io
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarkerIoConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
            // some params
            'PARAM_CODE' => 'bx_markerio_code',
        );
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        //NOTE: Some settings can be inited here.
    }
}

/** @} */
