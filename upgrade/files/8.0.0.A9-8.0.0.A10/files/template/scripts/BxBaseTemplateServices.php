<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
