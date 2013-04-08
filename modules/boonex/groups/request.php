<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Groups Groups
 * @ingroup     DolphinModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

check_logged();

bx_import('BxDolLanguages');
bx_import('BxDolRequest');

class BxGroupsRequest extends BxDolRequest {

    function BxGroupsRequest() {
        parent::BxDolRequest();
    }

    function processAsAction($aModule, &$aRequest, $sClass = "Module") {

        $sClassRequire = $aModule['class_prefix'] . $sClass;
        $oModule = BxDolRequest::_require($aModule, $sClassRequire);
        $aVars = array ('BaseUri' => $oModule->_oConfig->getBaseUri());
        // $GLOBALS['oTopMenu']->setCustomSubActions($aVars, 'bx_groups_title', false); // TODO:

        return BxDolRequest::processAsAction($aModule, $aRequest, $sClass);
    }
}

BxGroupsRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

/** @} */ 
