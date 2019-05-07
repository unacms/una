<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplConfig extends BxBaseConfig
{
    function __construct()
    {
        parent::__construct();

        $this->_aConfig['aLessConfig'] = array_merge($this->_aConfig['aLessConfig'], array(
            'bx-font-family' => 'Helvetica, Arial, sans-serif',

            'bx-color-page' => '#b9e2f6',
            'bx-color-block' => '#fff',
            'bx-color-box' => 'rgba(218, 248, 255, 1.0)',
            'bx-color-box-hover' => 'rgba(218, 248, 255, 0.5)',
            'bx-color-sec' => '#fff',
            'bx-color-hl' => 'rgba(202, 242, 252, 0.2)',
            'bx-color-active' => 'rgba(202, 242, 252, 0.4)',

            'bx-border-color' => '#00a0ce',

            'bx-color-menu-page-gradient-left' => 'linear-gradient(to right, #ffffff 0%, rgba(255, 255, 255, 0) 100%)',
            'bx-color-menu-page-gradient-right' => 'linear-gradient(to right, rgba(255, 255, 255, 0) 0%, #ffffff 100%)'
        ));

        $this->setPageWidth('bx_ocean_page_width');
    }
}

/** @} */
