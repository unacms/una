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
        
        	'bx-color-page' => '#b9e2f6',
        	'bx-color-block' => '#fff',
        	'bx-color-box' => '#daf8ff',
        	'bx-color-sec' => '#fff',
        	'bx-color-hl' => 'rgba(202, 242, 252, 0.2)',
        	'bx-color-active' => 'rgba(202, 242, 252, 0.4)',

        	'bx-border-color' => '#00a0ce',
        ));

        $this->setPageWidth('bx_ocean_page_width');
    }
}

/** @} */
