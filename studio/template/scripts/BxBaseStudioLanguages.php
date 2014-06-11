<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioLanguages');
bx_import('BxTemplStudioFunctions');

class BxBaseStudioLanguages extends BxDolStudioLanguages {
    function __construct() {
        parent::__construct();
    }
    function getCss() {
        return array('languages.css');
    }
    function getJs() {
        return array('jquery.anim.js', 'page.js', 'languages.js');
    }
}
/** @} */
