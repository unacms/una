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

bx_import('BxBaseModTextInstaller');

class BxNotesInstaller extends BxBaseModTextInstaller 
{
    function __construct($aConfig) 
    {
        parent::__construct($aConfig);
        $this->_aTranscoders = array ('bx_notes_preview');
        $this->_aStorages = array ('bx_notes_files');
    }
}

/** @} */ 
