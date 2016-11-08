<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

class BxTemplStudioConfig extends BxBaseStudioConfig
{
    function __construct()
    {
        parent::__construct();

        $this->_aConfig['aLessConfig'] = array_merge($this->_aConfig['aLessConfig'], array(
        	'bx-color-page' => '#f3f3f8',
        	'bx-border-color-layout' => '#b9e2f6',
        ));
    }
}

/** @} */
