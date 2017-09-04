<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxForumFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($aInfo, $oTemplate);
    }

    public function insert($aValsToAdd = array(), $isIgnore = false)
    {
    	$CNF = $this->_oModule->_oConfig->CNF;

        $aValsToAdd['lr_timestamp'] = time();
        $aValsToAdd['lr_profile_id'] = bx_get_logged_profile_id();

        return parent::insert($aValsToAdd, $isIgnore);
    }
}

/** @} */
