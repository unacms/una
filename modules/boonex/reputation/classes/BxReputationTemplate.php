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

        $aProfileInfo = $this->_oDb->getProfile(['sample' => 'id', 'id' => $iProfileId]);
        $bProfileInfo = !empty($aProfileInfo) && is_array($aProfileInfo);

        return $this->parseHtmlByName('block_summary.html', [
            'profile_image' => $oProfile->getUnit($iProfileId, ['template' => ['name' => 'unit_wo_info', 'size' => 'ava']]),
            'profile_name' => $oProfile->getDisplayName(),
            'points' => $bProfileInfo ? $aProfileInfo['points'] : 0,
        ]);
    }
}

/** @} */
