<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Templates.
 */
class BxBaseTemplateServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetTemplates($bEnabledOnly = true, $bShortInfo = true)
    {
        $aValues = get_templates_array($bEnabledOnly, $bShortInfo);

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
