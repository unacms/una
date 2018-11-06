<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxRibbonsPageEntry extends BxTemplPage
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_ribbons';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
