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

class BxJobsConnectionFans extends BxBaseModGroupsConnectionFans
{
    public function __construct($aObject)
    {
        $this->_sModule = 'bx_jobs';

        parent::__construct($aObject);

        $this->_bBan = true;
        $this->_bQuestionnaire = true;
    }
}

/** @} */
