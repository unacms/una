<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Score.
 */
class BxBaseScoreServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-score Score
     * @subsubsection bx_system_general-do do
     * 
     * @code bx_srv('system', 'do', [[...]], 'TemplScoreServices'); @endcode
     * @code {{~system:do:TemplScoreServices[[...]]~}} @endcode
     * 
     * Performs Do (Up/Down) action
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseScoreServices::serviceDo
     */
    /** 
     * @ref bx_system_general-do "do"
     * @api @ref bx_system_general-do "do"
     */
    public function serviceDo($aParams)
    {
        if(is_string($aParams))
            $aParams = json_decode($aParams, true);

        if(!$aParams['s'] || !$aParams['o'] || !$aParams['a'])
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $oScore = BxDolScore::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oScore || !$oScore->isEnabled())
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $aResult = $oScore->vote(['type' => $aParams['a']]);
        if((int)$aResult['code'] != 0)
            return $aResult;

        return [
            'is_voted' => $aResult['voted'],
            'is_disabled' => $aResult['disabled'],
            $aParams['a'] => [
                'icon' => $aResult['label_icon'],
                'title' => $aResult['label_title'],
            ],
            'counter' => $oScore->getVote()
        ];
    }


    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-score Score
     * @subsubsection bx_system_general-get_performed_by get_performed_by
     * 
     * @code bx_srv('system', 'get_performed_by', [[...]], 'TemplScoreServices'); @endcode
     * @code {{~system:get_performed_by:TemplScoreServices[[...]]~}} @endcode
     * 
     * Gets a list of PerformedBy users.
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseScoreServices::serviceGetPerformedBy
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

        $oScore = BxDolScore::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oScore || !$oScore->isEnabled())
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $aValues = $oScore->getQueryObject()->getPerformedBy($oScore->getId());

        $aTmplUsers = [];
        foreach($aValues as $aValue)
            $aTmplUsers[] = [
                'author_data' => BxDolProfile::getData($aValue['id']),
                'vote_type' => $aValue['vote_type'],
                'vote_date' => $aValue['vote_date']
            ];

        return [
            'performed_by' => $aTmplUsers
        ];
    }
}

/** @} */
