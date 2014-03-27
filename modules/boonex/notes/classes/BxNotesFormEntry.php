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

bx_import('BxBaseModTextFormEntry');

/**
 * Create/Edit entry form
 */
class BxNotesFormEntry extends BxBaseModTextFormEntry 
{
    public function __construct($aInfo, $oTemplate = false) 
    {
        $this->MODULE = 'bx_notes';
        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
