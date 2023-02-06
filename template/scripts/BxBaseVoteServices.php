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

    public function serviceDo($aParams)
    {
        if(is_string($aParams))
            $aParams = json_decode($aParams, true);

        if(!$aParams['s'] || !$aParams['o'])
            return ['code' => 1];

        $oVote = BxDolVote::getObjectInstance($aParams['s'], $aParams['o']);
        if(!$oVote)
            return ['code' => BX_DOL_OBJECT_ERR_NOT_AVAILABLE];

        $aResult = $oVote->vote($aParams);
        if((int)$aResult['code'] != 0)
            return $aResult;

        return [
            'reaction' => $aResult['reaction'],
            'icon' => !empty($aResult['label_emoji']) ? $aResult['label_emoji'] : '',
            'counter' => $oVote->getVote()
        ];
    }
}

/** @} */
