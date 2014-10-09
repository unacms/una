<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxBaseConfig');

class BxTemplConfig extends BxBaseConfig
{
    function __construct()
    {
        parent::__construct();

        $this->_aConfig['aLessConfig'] = array_merge($this->_aConfig['aLessConfig'], array(
        	'bx-font-family' => '"Source Sans Pro", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif',
        
        	'bx-color-page' => '#b9e2f6',
        	'bx-color-block' => '#fff',
        	'bx-color-box' => '#daf8ff',
        	'bx-color-sec' => '#fff',
        	'bx-color-hl' => 'rgba(202, 242, 252, 0.2)',
        	'bx-color-active' => 'rgba(202, 242, 252, 0.4)',

        	'bx-border-color' => '#00a0ce',
        ));

        bx_import('BxDolTemplate');
        $sCode = BxDolTemplate::getInstance()->getCode();
        $sStaticImages = BX_DOL_URL_ROOT . 'templates/tmpl_' . $sCode . '/images/';

        bx_import('BxDolConfig');
        $oSysConfig = BxDolConfig::getInstance();
        $oSysConfig->set('url_static', 'images', $sStaticImages);
        $oSysConfig->set('url_static', 'icons', $sStaticImages . 'icons/');

        $oSysConfig->set('path_static', 'css', BX_DIRECTORY_PATH_ROOT . 'templates/tmpl_' . $sCode . '/css/');
    }
}

/** @} */
