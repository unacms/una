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
        if(isset($CNF[$sKey]) && !empty($CNF[$sKey]['mp4']) && strcmp($oAlert->sUnit , $CNF[$sKey]['mp4']) === 0 && $oAlert->sAction == 'transcoded')
            return $this->_onVideoTranscoded($oAlert->iObject, $oAlert->aExtras['ret']);
    }

    protected function _onVideoTranscoded($iGhostId, $bResult, $aParams = array())
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
        $bNotify = $iNow - $aContentInfo[$CNF['FIELD_ADDED']] > $this->_oModule->_oConfig->getDpnTime();
        $iSystemBotProfileId = (int)getParam('sys_profile_bot');

        if(!$bResult) {
            if((int)$this->_oModule->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'failed'), array($CNF['FIELD_ID'] => $iContentId)) > 0) {
                $this->_oModule->onFailed($iContentId);

                if($bNotify)
                    /**
                     * @hooks
                     * @hookdef hook-bx_base_general-publish_failed '{module_name}', 'publish_failed' - hook to notify about failed content publishing
                     * - $unit_name - module name
                     * - $action - equals `publish_failed`
                     * - $object_id - content id 
                     * - $sender_id - system bot profile id
                     * - $extra_params - array of additional params with the following array keys:
                     *      - `object_author_id` - [int] content author profile id
                     *      - `privacy_view` - [int] privacy for view action, equals to BX_DOL_PG_ALL, @see BxDolPrivacy
                     * @hook @ref hook-bx_base_general-publish_failed
                     */
                    bx_alert($this->_oModule->getName(), 'publish_failed', $aContentInfo[$CNF['FIELD_ID']], $iSystemBotProfileId, array(
                        'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']],
                        'privacy_view' => BX_DOL_PG_ALL
                    ));
            }

            return;
        }

        if(isset($CNF['FIELD_PUBLISHED']) && isset($aContentInfo[$CNF['FIELD_PUBLISHED']]) && $aContentInfo[$CNF['FIELD_PUBLISHED']] > $iNow)
            return;

        $oTranscoder = BxDolTranscoder::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);
        $aGhostsToCheck = $oStorage->getGhosts($aGhost['profile_id'], $iContentId);
        foreach($aGhostsToCheck as $aGhostToCheck) {
            $aGhostInfo = $oStorage->getFile((int)$aGhostToCheck['id']);
            if(strncmp($aGhostInfo['mime_type'], 'video/', 6) !== 0)
                continue;

            if(!$oTranscoder->isFileReady((int)$aGhostToCheck['id']))
                return;
        }

        if(!$this->_oModule->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'active'), array($CNF['FIELD_ID'] => $iContentId))) 
            return;

        $this->_oModule->onPublished($iContentId);

        if($bNotify)
            /**
             * @hooks
             * @hookdef hook-bx_base_general-publish_succeeded '{module_name}', 'publish_succeeded' - hook to notify about successfully published content
             * - $unit_name - module name
             * - $action - equals `publish_succeeded`
             * - $object_id - content id 
             * - $sender_id - system bot profile id
             * - $extra_params - array of additional params with the following array keys:
             *      - `object_author_id` - [int] content author profile id
             *      - `privacy_view` - [int] privacy for view action, equals to BX_DOL_PG_ALL, @see BxDolPrivacy
             * @hook @ref hook-bx_base_general-publish_succeeded
             */
            bx_alert($this->MODULE, 'publish_succeeded', $aContentInfo[$CNF['FIELD_ID']], $iSystemBotProfileId, [
                'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']],
                'privacy_view' => BX_DOL_PG_ALL
            ]);
    }
}

/** @} */
