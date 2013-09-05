<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

class BxBaseConfig extends BxDol implements iBxDolSingleton {

    var $iTagsMinFontSize = 10;  // minimal font size of tag
    var $iTagsMaxFontSize = 30; // maximal font size of tag

    var $bAllowUnicodeInPreg = false; // allow unicode in regular expressions

    function BxBaseConfig() {
        parent::BxDol();
    }

    public static function getInstance() {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplConfig();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
}

/** @} */
