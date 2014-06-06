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

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

check_logged();

bx_import('BxDolLanguages');
bx_import('BxDolRequest');

class BxSitesRequest extends BxDolRequest 
{
    function __construct() 
    {
        parent::__construct();
    }
}

BxSitesRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

/** @} */ 
