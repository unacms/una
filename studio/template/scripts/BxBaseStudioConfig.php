<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */

bx_import('BxTemplConfig');

class BxBaseStudioConfig extends BxTemplConfig
{
    function __construct()
    {
        parent::__construct();

        $this->_aConfig['aLessConfig'] = array_merge($this->_aConfig['aLessConfig'], array(
            'bx-margin' => '30px',
            'bx-margin-sec' => '20px',
            'bx-margin-thd' => '10px',

            'bx-padding' => '30px',
            'bx-padding-sec' => '20px',
            'bx-padding-thd' => '10px',

        	'bx-color-page' => '#ffffff',
        	'bx-border-color-layout' => '#cccccc',

            'bx-size-widget' => '128px',
            'bx-round-corners-radius-widget' => '32px',
        ));
    }

    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplStudioConfig();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
}

/** @} */
