<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioDesigns extends BxDolStudioDesigns
{
    function __construct()
    {
        parent::__construct();
    }

    function getJs()
    {
        return array_merge(parent::getJs(), array('design.js'));
    }
}

/** @} */
