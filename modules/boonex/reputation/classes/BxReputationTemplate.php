<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reputation Reputation
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReputationTemplate extends BxBaseModNotificationsTemplate
{
    public function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getBlockSummary($iProfileId)
    {
        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return false;

        $aProfileInfo = $this->_oDb->getProfiles(['sample' => 'id', 'id' => $iProfileId]);
        $bProfileInfo = !empty($aProfileInfo) && is_array($aProfileInfo);

        return $this->parseHtmlByName('block_summary.html', [
            'profile_image' => $oProfile->getUnit($iProfileId, ['template' => ['name' => 'unit_wo_info', 'size' => 'ava']]),
            'profile_name' => $oProfile->getDisplayName(),
            'points' => $bProfileInfo ? $aProfileInfo['points'] : 0,
        ]);
    }

    public function getBlockLeaderboard($iDays = 0, $iLimit = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        
        if(!$iLimit)
            $iLimit = (int)getParam($CNF['PARAM_LEADERBOARD_LIMIT']);

        $bGrowth = $iDays > 0;

        if($bGrowth) 
            $aItems = $this->_oDb->getEvents(['sample' => 'stats', 'days' => $iDays, 'limit' => $iLimit]);
        else
            $aItems = $this->_oDb->getProfiles(['sample' => 'stats', 'limit' => $iLimit]);

        $aTmplVarsProfiles = [];
        foreach($aItems as $iProfileId => $iPoints)
            if(($iProfileId = abs($iProfileId)) && ($oProfile = BxDolProfile::getInstance($iProfileId)) !== false)
                $aTmplVarsProfiles[] = [
                    'unit' => $oProfile->getUnit($iProfileId),
                    'sign' => $bGrowth ? ($iPoints > 0 ? '+' : '-') : '',
                    'points' => $bGrowth ? abs($iPoints) : $iPoints
                ];

        $this->addCss(['main.css']);
        return $this->parseHtmlByName('block_leaderboard.html', [
            'bx_repeat:profiles' => $aTmplVarsProfiles
        ]);
    }
}

/** @} */
