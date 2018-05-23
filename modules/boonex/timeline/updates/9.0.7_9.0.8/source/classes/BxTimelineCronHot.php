<?php use Twilio\Rest\Api\V2010\Account\Usage\Record\ThisMonthInstance;
defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineCronHot extends BxDolCron
{
	protected $_sModule;
	protected $_oModule;

	public function __construct()
    {
    	$this->_sModule = 'bx_timeline';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct();
    }

    function processing()
    {
        if(!$this->_oModule->_oConfig->isHot())
            return;

        $iInterval = $this->_oModule->_oConfig->getHotInterval();
        $sCommon = $this->_oModule->_oConfig->getPrefix('common_post') . 'post';

        $aSystems = BxDolVote::getSystems();
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));
        
        $aModulesActive = array();
        foreach($aModules as $aModule)
            $aModulesActive[] = $aModule['name'];

        $aHandlers = $this->_oModule->_oConfig->getHandlers();
        $aHandlersHidden = $this->_oModule->_oConfig->getHandlersHidden();
        foreach($aHandlers as $sKey => $aHandler) {
            if($aHandler['type'] != BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT || in_array($aHandler['id'], $aHandlersHidden))
                continue;

            $bCommon = $aHandler['alert_unit'] == $sCommon;
            if(!$bCommon && !in_array($aHandler['alert_unit'], $aModulesActive))
                continue;

            if(!$bCommon) {
                $oModule = BxDolModule::getInstance($aHandler['alert_unit']);
                if(empty($oModule->_oConfig->CNF['OBJECT_VOTES']))
                    continue;

                $sMethod = 'getHotTrackModule';
                $sModule = $oModule->getName();
                $sSystem = $oModule->_oConfig->CNF['OBJECT_VOTES'];
            }
            else {
                $sMethod = 'getHotTrack';
                $sModule = $sCommon;
                $sSystem = $this->_oModule->_oConfig->getObject('vote');
            }

            if(!isset($aSystems[$sSystem]) || (int)$aSystems[$sSystem]['is_on'] != 1)
                continue;

            $aTracks = $this->_oModule->_oDb->$sMethod($sModule, $aSystems[$sSystem]['table_track'], $iInterval);
            if(empty($aTracks) || !is_array($aTracks))
                continue;

            foreach($aTracks as $aTrack)
                $this->_oModule->_oDb->updateHotTrack($aTrack);
        }
    }
}

/** @} */
