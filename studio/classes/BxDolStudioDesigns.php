<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioDesigns extends BxTemplStudioModules
{
    function __construct()
    {
        parent::__construct();

        $this->sLangPrefix = 'dsn';
        $this->sParamPrefix = 'dsn';

        $this->sActionUri = 'design.php';
        $this->sJsClass = 'BxDolStudioDesign';
        $this->sJsObject = 'oBxDolStudioDesign';

        $this->_oDb = new BxDolStudioDesignsQuery();       
    }

    public function getTemplatesBy($aParams)
    {
        return $this->_oDb->getTemplatesBy($aParams);
    }
}

/** @} */
