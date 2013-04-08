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

bx_import('BxBaseStudioDesigner');

class BxTemplStudioDesigner extends BxBaseStudioDesigner { 
    function BxTemplStudioDesigner($sPage = '') {
        parent::BxBaseStudioDesigner($sPage);
    }
}
/** @} */