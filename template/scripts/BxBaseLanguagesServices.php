<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Languages.
 */
class BxBaseLanguagesServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetLanguages($bIdAsKey = false, $bActiveOnly = false)
    {
        $aValues = BxDolLanguages::getInstance()->getLanguages($bIdAsKey, $bActiveOnly);

        $aResult = array();
        foreach($aValues as $sKey => $sValue)
            $aResult[] = array(
                'key' => $sKey,
                'value' => $sValue
            );

        return $aResult;
    }
}

/** @} */
