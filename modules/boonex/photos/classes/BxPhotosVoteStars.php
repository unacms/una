<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPhotosVoteStars extends BxTemplVoteStars
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct($sSystem, $iId, $iInit);        
    }
}

/** @} */
