<?php defined('BX_DOL') or die('hack attempt');
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

        $this->_oModule->_oDb->clearHot();

        $this->_prepareTrackByDates();
    }

    protected function _prepareTrackByDates()
    {
        $iInterval = $this->_oModule->_oConfig->getHotInterval();
        $sCommon = $this->_oModule->_oConfig->getPrefix('common_post') . 'post';

        $aSystemsComments = BxDolCmts::getSystems();
        $aSystemsVotes = BxDolVote::getSystems();
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));

        $aModulesActive = [];
        foreach($aModules as $aModule)
            $aModulesActive[] = $aModule['name'];

        $aTracksByDate = [];
        $aTracksByDateComments = [];
        $aTracksByDateVotes = [];
        
        if($this->_oModule->_oConfig->isHotSource(BX_TIMELINE_HFS_CONTENT))
            $aTracksByDate = $this->_oModule->_oDb->getHotTrackByDate($iInterval);

        $bHfsComment = $this->_oModule->_oConfig->isHotSource(BX_TIMELINE_HFS_COMMENT);
        $bHfsVote = $this->_oModule->_oConfig->isHotSource(BX_TIMELINE_HFS_VOTE);

        $iHftContent = $this->_oModule->_oConfig->getHotThreshold(BX_TIMELINE_HFS_CONTENT);
        $iHftComments = $this->_oModule->_oConfig->getHotThreshold(BX_TIMELINE_HFS_COMMENT);
        $iHftVotes = $this->_oModule->_oConfig->getHotThreshold(BX_TIMELINE_HFS_VOTE);

        $aHandlers = $this->_oModule->_oConfig->getHandlers();
        $aHandlersHidden = $this->_oModule->_oConfig->getHandlersHidden();
        foreach($aHandlers as $sKey => $aHandler) {
            if($aHandler['type'] != BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT || in_array($aHandler['id'], $aHandlersHidden))
                continue;

            $bSystem = $this->_oModule->_oConfig->isSystem($aHandler['alert_unit'], $aHandler['alert_action']);
            if($bSystem && !in_array($aHandler['alert_unit'], $aModulesActive))
                continue;            

            if($bSystem) {
                $sModule = $aHandler['alert_unit'];
                $oModule = BxDolModule::getInstance($sModule);
                if(!$oModule)
                    continue;

                if($bHfsComment && !empty($oModule->_oConfig->CNF['OBJECT_COMMENTS'])) {
                    $sSystem = $oModule->_oConfig->CNF['OBJECT_COMMENTS'];
                    if(isset($aSystemsComments[$sSystem]) && (int)$aSystemsComments[$sSystem]['is_on'] == 1)
                        $aTracksByDateComments += $this->_oModule->_oDb->getHotTrackByCommentsDateModule($sModule, $aSystemsComments[$sSystem]['table'], $iInterval, $iHftContent, $iHftComments);
                }

                if($bHfsVote) {
                    if(!empty($oModule->_oConfig->CNF['OBJECT_VOTES'])) {
                        $sSystem = $oModule->_oConfig->CNF['OBJECT_VOTES'];
                        if(isset($aSystemsVotes[$sSystem]) && (int)$aSystemsVotes[$sSystem]['is_on'] == 1)
                            $aTracksByDateVotes += $this->_oModule->_oDb->getHotTrackByVotesDateModule($sModule, $aSystemsVotes[$sSystem]['table_track'], $iInterval, $iHftContent, $iHftVotes);
                    }

                    if(!empty($oModule->_oConfig->CNF['OBJECT_REACTIONS'])) {
                        $sSystem = $oModule->_oConfig->CNF['OBJECT_REACTIONS'];
                        if(isset($aSystemsVotes[$sSystem]) && (int)$aSystemsVotes[$sSystem]['is_on'] == 1)
                            $aTracksByDateVotes += $this->_oModule->_oDb->getHotTrackByVotesDateModule($sModule, $aSystemsVotes[$sSystem]['table_track'], $iInterval, $iHftContent, $iHftVotes);
                    }
                }
            }
            else {
                if($bHfsComment) {
                    $sSystem = $this->_oModule->_oConfig->getObject('comment');
                    if(isset($aSystemsComments[$sSystem]) && (int)$aSystemsComments[$sSystem]['is_on'] == 1)
                        $aTracksByDateComments += $this->_oModule->_oDb->getHotTrackByCommentsDate($sCommon, $aSystemsComments[$sSystem]['table'], $iInterval, $iHftContent, $iHftComments);
                }

                if($bHfsVote) {
                    $sSystem = $this->_oModule->_oConfig->getObject('vote');
                    if(isset($aSystemsVotes[$sSystem]) && (int)$aSystemsVotes[$sSystem]['is_on'] == 1)
                        $aTracksByDateVotes += $this->_oModule->_oDb->getHotTrackByVotesDate($sCommon, $aSystemsVotes[$sSystem]['table_track'], $iInterval, $iHftContent, $iHftVotes);

                    $sSystem = $this->_oModule->_oConfig->getObject('reaction');
                    if(isset($aSystemsVotes[$sSystem]) && (int)$aSystemsVotes[$sSystem]['is_on'] == 1)
                        $aTracksByDateVotes += $this->_oModule->_oDb->getHotTrackByVotesDate($sCommon, $aSystemsVotes[$sSystem]['table_track'], $iInterval, $iHftContent, $iHftVotes);
                }
            }
        }

        $aTracks = [];
        $aTracks = $this->_combineArrays($aTracks, $aTracksByDate);
        $aTracks = $this->_combineArrays($aTracks, $aTracksByDateComments);
        $aTracks = $this->_combineArrays($aTracks, $aTracksByDateVotes);

        foreach($aTracks as $iId => $iDate)
            $this->_oModule->_oDb->updateHotTrack(array('event_id' => $iId, 'value' => $iDate));
    }

    /**
     * Hot Track by Sum of Votes during specified Period is currently disabled.
     */
    protected function _prepareTrackByVotesSum()
    {
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

                $sMethod = 'getHotTrackByVotesSumModule';
                $sModule = $oModule->getName();
                $sSystem = $oModule->_oConfig->CNF['OBJECT_VOTES'];
            }
            else {
                $sMethod = 'getHotTrackByVotesSum';
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

    protected function _combineArrays($a1, $a2)
    {
        foreach($a2 as $iId => $iDate) {
            if(!isset($a1[$iId])) {
                $a1[$iId] = $iDate;
                continue;
            }

            if((int)$iDate > (int)$a1[$iId])
                $a1[$iId] = $iDate;
        }

        return $a1;
    }
}

/** @} */
