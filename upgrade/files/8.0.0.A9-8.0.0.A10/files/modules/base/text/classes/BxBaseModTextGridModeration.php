<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 * 
 * @{
 */

bx_import('BxBaseModTextGridAdministration');

class BxBaseModTextGridModeration extends BxBaseModTextGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sManageType = 'moderation';
    }

	public function performActionDelete($aParams = array())
    {
        $this->_echoResultJson(array());
    }
}

/** @} */
