<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxTemplConfig extends BxBaseConfig
{
    function __construct()
    {
        parent::__construct();

        $this->_aConfig['aLessConfig'] = array_merge($this->_aConfig['aLessConfig'], array(
        	'bx-font-family' => 'Helvetica, Arial, sans-serif',

        	'bx-color-page' => '#f2fbff',
        	'bx-color-block' => '#fff',
        	'bx-color-box' => '#daf8ff',
        	'bx-color-sec' => '#fff',
        	'bx-color-hl' => 'rgba(202, 242, 252, 0.2)',
        	'bx-color-active' => 'rgba(202, 242, 252, 0.4)',

        	'bx-border-color' => '#d5dde0',

        	'bx-font-size-default' => '18px',
            'bx-font-size-small' => '13px',
            'bx-font-size-middle' => '16px',
            'bx-font-size-large' => '24px',
            'bx-font-size-h1' => '36px',
            'bx-font-size-h2' => '28px',
            'bx-font-size-h3' => '20px',

        	'bx-font-color-default' => '#666',
        	'bx-font-color-default-sec' => '#333',
        ));

        $this->setPageWidth('bx_air_page_width');
    }
}

/** @} */
