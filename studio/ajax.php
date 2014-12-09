<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentEndAdmin Trident Studio End Admin Pages
 * @ingroup     TridentStudio
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

bx_import('BxDolLanguages');

check_logged();
if (!isAdmin())
    exit;

$aResult = array();
switch(bx_get('action')) {
    case 'page-bookmark':
        $sPage = bx_process_input(bx_get('page'));

        bx_import('BxDolStudioPage');
        $oPage = new BxDolStudioPage($sPage);

        $aResult = $oPage->bookmark();
        break;

}

header('Content-Type:text/javascript');
echo json_encode($aResult);
/** @} */
