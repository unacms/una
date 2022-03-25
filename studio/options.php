<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaEndAdmin UNA Studio End Admin Pages
 * @ingroup     UnaStudio
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
require_once(BX_DOL_DIR_STUDIO_INC . 'utils.inc.php');

bx_import('BxDolLanguages');

bx_require_authentication(true);

$sType = bx_get('type');
$sType = $sType !== false ? bx_process_input($sType) : '';

$mixedCategory = bx_get('category');
$mixedCategory = $mixedCategory !== false ? bx_process_input($mixedCategory) : '';

$oOptions = new BxTemplStudioOptions($sType, $mixedCategory);

if(($mixedResult = $oOptions->checkAction()) !== false) {
    echoJson($mixedResult);
    exit;
}

echo $oOptions->getCode();
/** @} */
