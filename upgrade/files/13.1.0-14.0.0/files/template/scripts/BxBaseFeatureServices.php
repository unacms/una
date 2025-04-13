<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services for 'Features' engine.
 */
class BxBaseFeatureServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-features Features
     * @subsubsection bx_system_general-perform perform
     * 
     * @code bx_srv('system', 'perform', [[...]], 'TemplFeatureServices'); @endcode
     * @code {{~system:perform:TemplFeatureServices[[...]]~}} @endcode
     * 
     * Performs Perform action (featured, unfeatured, etc) with features object.
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseFeatureServices::servicePerform
     */
    /** 
     * @ref bx_system_general-perform "perform"
     * @api @ref bx_system_general-perform "perform"
     */
    public function servicePerform($aParams)
    {
        if(is_string($aParams))
            $aParams = json_decode($aParams, true);

        if(!$aParams['s'] || !$aParams['o'])
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $oFeature = BxDolFeature::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oFeature || !$oFeature->isEnabled())
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $aResult = $oFeature->doFeature($aParams);
        return (int)$aResult['code'] != 0 ? $aResult : $aResult['api'];
    }
}

/** @} */
