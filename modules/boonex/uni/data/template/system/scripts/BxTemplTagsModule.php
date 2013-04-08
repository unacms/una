<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

// TODO: reconsider almost all functionality in this file, according to new concept it should NOT be separate page with all tags, every module has its own page with tags

bx_import('BxBaseTagsModule');

class BxTemplTagsModule extends BxBaseTagsModule {
    function BxTemplTagsModule($aParam, $sTitle, $sUrl) {
        parent::BxBaseTagsModule($aParam, $sTitle, $sUrl);
    }
}
