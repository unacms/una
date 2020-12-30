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

class BxTemplStudioDesign extends BxBaseStudioDesign
{
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);
    }
}
/** @} */
