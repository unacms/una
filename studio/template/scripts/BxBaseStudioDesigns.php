<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */

bx_import('BxDolStudioDesigns');
bx_import('BxTemplStudioFormView');
bx_import('BxTemplStudioFunctions');

class BxBaseStudioDesigns extends BxDolStudioDesigns
{
    function __construct()
    {
        parent::__construct();
    }
    function getCss()
    {
        return array('designs.css');
    }
    function getJs()
    {
        return array('jquery.anim.js', 'page.js', 'designs.js');
    }
}

/** @} */
