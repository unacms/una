<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Votes.
 */
class BxBaseVoteServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-vote Vote
     * @subsubsection bx_system_general-do do
     * 
     * @code bx_srv('system', 'do', [[...]], 'TemplVoteServices'); @endcode
     * @code {{~system:do:TemplVoteServices[[...]]~}} @endcode
     * 
     * Performs Do (Vote) action
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseVoteServices::serviceDo
     */
    /** 
     * @ref bx_system_general-do "do"
     * @api @ref bx_system_general-do "do"
     */
    public function serviceDo($aParams)
    {
        if(is_string($aParams))
            $aParams = json_decode($aParams, true);

        if(!$aParams['s'] || !$aParams['o'])
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $oVote = BxDolVote::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oVote || !$oVote->isEnabled())
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $aResult = $oVote->vote($aParams);
        return (int)$aResult['code'] != 0 ? $aResult : $aResult['api'];
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-vote Vote
     * @subsubsection bx_system_general-get_performed_by get_performed_by
     * 
     * @code bx_srv('system', 'get_performed_by', [[...]], 'TemplVoteServices'); @endcode
     * @code {{~system:get_performed_by:TemplVoteServices[[...]]~}} @endcode
     * 
     * Gets a list of PerformedBy users.
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseVoteServices::serviceGetPerformedBy
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

        $oVote = BxDolVote::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oVote || !$oVote->isEnabled())
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        return $oVote->getPerformedByAPI($aParams);
    }
}

/** @} */
