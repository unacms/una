<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Polls module
 */
class BxPollsModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceEntitySubentriesBlock($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entrySubentries', $iContentId);
    }
}

/** @} */
