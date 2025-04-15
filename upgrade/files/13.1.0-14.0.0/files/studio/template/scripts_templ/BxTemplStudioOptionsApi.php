<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudioView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxTemplStudioOptionsApi extends BxBaseStudioOptionsApi
{
    public function __construct($sType = '', $sCategory = '', $sMix = '')
    {
        parent::__construct($sType, $sCategory, $sMix);
    }
}
/** @} */
