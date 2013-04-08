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

bx_import('BxDolStudioDesigns');
bx_import('BxTemplStudioFormView');
bx_import('BxTemplStudioFunctions');

class BxBaseStudioDesigns extends BxDolStudioDesigns {
    function BxBaseStudioDesigns() {
        parent::BxDolStudioDesigns();
    }
    function getCss() {
        return array('designs.css');
    }
    function getJs() {
        return array('common_anim.js', 'page.js', 'designs.js');
    }
}
/** @} */