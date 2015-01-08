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

bx_import('BxBaseModTextAlertsResponse');

class BxPostsAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_posts';
        parent::__construct();
    }
}

/** @} */
