<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModGeneralAlertsResponse extends BxDolAlertsResponse
{
    protected $MODULE;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    public function response($oAlert)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        if('system' == $oAlert->sUnit && 'save_setting' == $oAlert->sAction && isset($CNF['PARAM_SEARCHABLE_FIELDS']) && $CNF['PARAM_SEARCHABLE_FIELDS'] == $oAlert->aExtras['option'])
            return $this->_oModule->_oDb->alterFulltextIndex();

        $sKey = 'OBJECT_VIDEOS_TRANSCODERS';
        if(!empty($CNF[$sKey]) && is_array($CNF[$sKey]) && in_array($oAlert->sUnit, $CNF[$sKey])  && $oAlert->sAction == 'transcoded')
            return $this->_onVideoTranscoded($oAlert->iObject);
    }

    protected function _onVideoTranscoded($iGhostId, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($CNF['FIELD_STATUS']))
            return;

        $sStorage = ''; 
        if(!empty($aParams['storage'])) 
            $sStorage = $aParams['storage']; 
        else if(!empty($CNF['OBJECT_STORAGE_VIDEOS']))
            $sStorage = $CNF['OBJECT_STORAGE_VIDEOS'];
        else
            return;

        $oStorage = BxDolStorage::getObjectInstance($sStorage);
        if(!$oStorage)
            return;

        $aGhost = $oStorage->getGhost($iGhostId);
        if(empty($aGhost) || !is_array($aGhost))
            return;

        $iContentId = (int)$aGhost['content_id'];
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return;

        if(!isset($aContentInfo[$CNF['FIELD_STATUS']]) || $aContentInfo[$CNF['FIELD_STATUS']] != 'awaiting')
            return;
        
        $iNow = time();
        if(isset($CNF['FIELD_PUBLISHED']) && isset($aContentInfo[$CNF['FIELD_PUBLISHED']]) && $aContentInfo[$CNF['FIELD_PUBLISHED']] > $iNow)
            return;

        $aTranscoders = array();
        foreach($CNF['OBJECT_VIDEOS_TRANSCODERS'] as $sKey => $sTranscoder)
            if(in_array($sKey, array('mp4', 'mp4_hd')))
                $aTranscoders[$sTranscoder] = BxDolTranscoder::getObjectInstance($sTranscoder);

        $aGhostsToCheck = $oStorage->getGhosts($aGhost['profile_id'], $iContentId);
        foreach($aGhostsToCheck as $aGhostToCheck)
            foreach($aTranscoders as $sTranscoder => $oTranscoder)
                if(!$oTranscoder->isFileReady($aGhostToCheck['id']))
                    return;

        if((int)$this->_oModule->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'active'), array($CNF['FIELD_ID'] => $iContentId)) > 0)
            $this->_oModule->onPublished($iContentId);
    }
}

/** @} */
