<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Jobs Jobs
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxJobsGridinvites extends BxBaseModGroupsGridInvites
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sContentModule = 'bx_jobs';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
