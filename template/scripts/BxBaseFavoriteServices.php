<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services for 'Favorites' engine.
 */
class BxBaseFavoriteServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-favorites Favorites
     * @subsubsection bx_system_general-perform perform
     * 
     * @code bx_srv('system', 'perform', [[...]], 'TemplFavoriteServices'); @endcode
     * @code {{~system:perform:TemplFavoriteServices[[...]]~}} @endcode
     * 
     * Performs Perform action (favorite, unfavorite, etc) with favorites object.
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseFavoriteServices::servicePerform
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

        $oFavorite = BxDolFavorite::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oFavorite || !$oFavorite->isEnabled())
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $aResult = $oFavorite->doFavorite($aParams);
        return (int)$aResult['code'] != 0 ? $aResult : $aResult['api'];
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-favorites Favorites
     * @subsubsection bx_system_general-get_performed_by get_performed_by
     * 
     * @code bx_srv('system', 'get_performed_by', [[...]], 'TemplFavoriteServices'); @endcode
     * @code {{~system:get_performed_by:TemplFavoriteServices[[...]]~}} @endcode
     * 
     * Gets a list of PerformedBy users.
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseFavoriteServices::serviceGetPerformedBy
     */
    /** 
     * @ref bx_system_general-get_performed_by "get_performed_by"
     * @api @ref bx_system_general-get_performed_by "get_performed_by"
     */
    public function serviceGetPerformedBy($aParams)
    {
        if(is_string($aParams))
            $aParams = json_decode($aParams, true);

        if(!$aParams['s'] || !$aParams['o'])
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $oFavorite = BxDolFavorite::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oFavorite || !$oFavorite->isEnabled())
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        return $oFavorite->getPerformedByAPI($aParams);
    }
}

/** @} */
