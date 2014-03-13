<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextMenu');

/**
 * General class for module menu.
 */
class BxMsgMenu extends BxBaseModTextMenu 
{
    public function __construct($aObject, $oTemplate = false) 
    {
        self::$MODULE = 'bx_messages';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
