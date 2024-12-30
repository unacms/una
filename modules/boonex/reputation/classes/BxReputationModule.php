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

class BxReputationModule extends BxBaseModNotificationsModule
{
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * ACTION METHODS
     */
    public function actionCheckName()
    {
        $CNF = &$this->_oConfig->CNF;

    	$sName = bx_process_input(bx_get('name'));
    	if(empty($sName))
            return echoJson([]);

        $sResult = '';
        if(($iId = (int)bx_get('id')) != 0) {
            $aLevel = $this->_oDb->getLevels(['sample' => 'id', 'id' => $iId]);
            if(strcmp($sName, $aLevel[$CNF['FIELD_LEVEL_NAME']]) == 0) 
                $sResult = $sName;
        }

    	echoJson([
            'name' => !empty($sResult) ? $sResult : $this->_oConfig->getLevelName($sName)
    	]);
    }

    /**
     * SERVICE METHODS
     */
    public function serviceAssignPoints($iProfileId, $iPoints)
    {
        return $this->assignPoints($iProfileId, $iPoints);
    }

    public function serviceGetLevels($iProfileId)
    {
        return $this->_oDb->getLevels([
            'sample' => 'profile_id', 
            'profile_id' => $iProfileId
        ]);
    }

    public function serviceGetBlockSummary($iProfileId = 0)
    {
        if(!$iProfileId && ($iLoggedId = bx_get_logged_profile_id()))
            $iProfileId = $iLoggedId;
        if(!$iProfileId)
            return false;

        return $this->_oTemplate->getBlockSummary($iProfileId);
    }

    public function serviceGetBlockLeaderboard($iDays = 0)
    {
        return $this->_oTemplate->getBlockLeaderboard($iDays);
    }

    /**
     * COMMON METHODS
     */
    public function assignPoints($iProfileId, $iPoints)
    {
        if(!$this->_oDb->insertProfile($iProfileId, $iPoints))
            return false; 
            
        $iProfilePoints = $this->_oDb->getProfilePoints($iProfileId);

        $aLevels = $this->_oDb->getLevels([
            'sample' => 'points', 
            'points' => $iProfilePoints
        ]);

        $bMultilevel = $this->_oConfig->isMultilevel();
        if($bMultilevel)
            $this->_oDb->deleteProfilesLevelsByPoints($iProfileId, $iProfilePoints);

        foreach($aLevels as $aLevel) {
            $iLevelId = (int)$aLevel['id'];
            if($this->hasLevel($iProfileId, $iLevelId))
                continue;

            if(!$bMultilevel)
                $this->_oDb->deleteProfilesLevels(['sample' => 'profile_id', 'profile_id' => $iProfileId]);

            $this->_oDb->insertProfilesLevels(['profile_id' => $iProfileId, 'level_id' => $iLevelId]);
        }

        return true; 
    }

    public function hasLevel($iProfileId, $iLevelId)
    {
        $aProfileLevels = $this->_oDb->getLevels([
            'sample' => 'profile_id', 
            'profile_id' => $iProfileId
        ]);

        foreach($aProfileLevels as $aProfileLevel)
            if($aProfileLevel['id'] == $iLevelId)
                return true;

        return false;
    }
}

/** @} */
