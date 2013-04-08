<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxBaseConfig');

class BxTemplConfig extends BxBaseConfig {
    function BxTemplConfig() {
        parent::BxBaseConfig();

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
