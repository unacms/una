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
        if(!$oVote)
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $sMethod = '_serviceDo' . bx_gen_method_name($oVote->getType());
        if(!method_exists($this, $sMethod))
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        return $this->$sMethod($aParams, $oVote);
    }

    protected function _serviceDoLikes($aParams, &$oVote)
    {
        $aResult = $oVote->vote($aParams);
        if((int)$aResult['code'] != 0)
            return $aResult;

        return [
            'is_voted' => $aResult['voted'],
            'is_disabled' => $aResult['disabled'],
            'icon' => $aResult['label_emoji'],
            'title' => $aResult['label_title'],
            'counter' => $oVote->getVote()
        ];
    }

    protected function _serviceDoReactions($aParams, &$oVote)
    {
        $aResult = $oVote->vote($aParams);
        if((int)$aResult['code'] != 0)
            return $aResult;

        $aDefault = $oVote->getReaction($oVote->getDefault());
        $aDefaultInfo = $oVote->getReaction($aDefault['name']);

        return [
            'is_voted' => $aResult['voted'],
            'is_disabled' => $aResult['disabled'],
            'reaction' => $aResult['reaction'],
            'icon' => !empty($aResult['label_emoji']) ? $aResult['label_emoji'] : $aDefaultInfo['emoji'],
            'title' => !empty($aResult['label_title']) ? $aResult['label_title'] : '',
            'counter' => $oVote->getVote()
        ];
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
        if(!$oVote)
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $sMethod = '_serviceGetPerformedBy' . bx_gen_method_name($oVote->getType());
        if(!method_exists($this, $sMethod))
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        return $this->$sMethod($aParams, $oVote);
    }

    protected function _serviceGetPerformedByLikes($aParams, &$oVote)
    {
        $aValues = $oVote->getQueryObject()->getPerformedBy($oVote->getId());

        $aTmplUsers = [];
        foreach($aValues as $mValue) {
            $mValue = is_array($mValue) ? $mValue : ['author_id' => (int)$mValue, 'reaction' => ''];

            $aTmplUsers[] = BxDolProfile::getData($mValue['author_id']);
        }

        return [
            'performed_by' => $aTmplUsers
        ];
    }

    protected function _serviceGetPerformedByReactions($aParams, &$oVote)
    {
        $aReactions = !empty($aParams['reaction']) ? [$aParams['reaction']] : $oVote->getReactions();

        $aResult = [];
        foreach($aReactions as $sReaction) {
            $aValues = $oVote->getQueryObject()->getPerformed(['type' => 'by', 'object_id' => $oVote->getId(), 'reaction'=> $sReaction]);

            $aTmplUsers = [];
            foreach($aValues as $mValue) {
                $mValue = is_array($mValue) ? $mValue : ['author_id' => (int)$mValue, 'reaction' => ''];

                $aTmplUsers[] = BxDolProfile::getData($mValue['author_id']);
            }

            $aResult[$sReaction] = $aTmplUsers;
        }

        return [
            'performed_by' => $aResult
        ];
    }
}

/** @} */
