<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudioView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

class BxTemplStudioOptions extends BxBaseStudioOptions
{
    function __construct($sType = '', $sCategory = '', $sMix = '')
    {
        parent::__construct($sType, $sCategory, $sMix);
    }
}
/** @} */
