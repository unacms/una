<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

class BxElsModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceAdd($sString, $iLanguageId = 0, $iOrig = 1)
    {
    }

    public function serviceEdit($iKeyId, $sString, $iLanguageId = 0, $bMarkAsOrig = false, $bRemoveOther = false)
    {
    }

    public function serviceDelete($iKeyId)
    {
    }
}

/** @} */
