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

bx_import('BxBaseModTextTemplate');

/*
 * Notes module representation.
 */
class BxNotesTemplate extends BxBaseModTextTemplate 
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb) 
    {
        self::$MODULE = 'bx_notes';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */ 

