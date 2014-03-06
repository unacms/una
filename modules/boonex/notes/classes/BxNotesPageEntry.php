<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextPageEntry');

/**
 * Entry create/edit pages
 */
class BxNotesPageEntry extends BxBaseModTextPageEntry 
{    
    public function __construct($aObject, $oTemplate = false) 
    {
        self::$MODULE = 'bx_notes';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
