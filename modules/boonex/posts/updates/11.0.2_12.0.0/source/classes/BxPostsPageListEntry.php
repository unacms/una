<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * List entry page
 */
class BxPostsPageListEntry extends BxBaseModTextPageListEntry
{    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_posts';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
