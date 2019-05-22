<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Directory Directory
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Directory module
 */
class BxDirModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceCategoriesList($aParams = array())
    {
        return 'Categories would be here!';
    }
}

/** @} */
