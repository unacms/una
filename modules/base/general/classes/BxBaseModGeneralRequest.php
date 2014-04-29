<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolLanguages');
bx_import('BxDolRequest');

class BxBaseModGeneralRequest extends BxDolRequest 
{
    function __construct() 
    {
        parent::__construct();
    }

    static function processAsAction($aModule, &$aRequest, $sClass = "Module") 
    {
        $sClassRequire = $aModule['class_prefix'] . $sClass;
        $oModule = BxDolRequest::_require($aModule, $sClassRequire);
        $aVars = array ('BaseUri' => $oModule->_oConfig->getBaseUri());

        return BxDolRequest::processAsAction($aModule, $aRequest, $sClass);
    }
}

/** @} */ 

