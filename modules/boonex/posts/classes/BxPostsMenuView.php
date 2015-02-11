<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Posts Posts
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxPostsMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_posts';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
