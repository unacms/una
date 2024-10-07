<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Courses profiles module.
 */
class BxCoursesModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_aSearchableNamesExcept[] = $this->_oConfig->CNF['FIELD_JOIN_CONFIRMATION'];
    }

    public function serviceGetOptionsContentModules()
    {
        $aResult = [];

        $aModules = bx_srv('system', 'get_modules_by_type', ['content']);
        foreach($aModules as $aModule)
            $aResult[] = ['key' => $aModule['name'], 'value' => $aModule['title']];

        return $aResult;
    }

    public function serviceIsContentAvaliable($iProfileId)
    {
        if(!$this->_oConfig->isContent())
            return false;

        $aContentInfo = $this->_oDb->getContentInfoByProfileId($iProfileId);
        if($this->checkAllowedEdit($aContentInfo) !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return true;
    }

    public function serviceOnContentAdded($sContentType, $iContentId, $iContextId, $iContextNodeId)
    {
        $this->_oDb->insertContentData([
            'entry_id' => $iContextId,
            'node_id' => $iContextNodeId,
            'content_type' => $sContentType,
            'content_id' => $iContentId,
            'added' => time(),
            'order' => $this->_oDb->getContentDataOrderMax($iContextId, $iContextNodeId)
        ]);

        $aNode = $this->_oDb->getContentNodes(['sample' => 'id', 'id' => $iContextNodeId]);

        $aCounters = [];
        if(!empty($aNode['counters']))
            $aCounters = json_decode($aNode['counters'], true);

        if(!isset($aCounters[$sContentType]))
            $aCounters[$sContentType] = 0;
        $aCounters[$sContentType] += 1;

        $this->_oDb->updateContentNodes(['counters' => json_encode($aCounters)], ['id' => $iContextNodeId]);
    }

    public function serviceOnContentDeleted($sContentType, $iContentId, $iContextId)
    {
        $aData = $this->_oDb->getContentData(['sample' => 'content', 'content_type' => $sContentType, 'content_id' => $iContentId]);
        if(empty($aData) || !is_array($aData))
            return;

        $this->_oDb->deleteContentData([
            'entry_id' => $iContextId,
            'content_type' => $sContentType,
            'content_id' => $iContentId,
        ]);

        $aNode = $this->_oDb->getContentNodes(['sample' => 'id', 'id' => $aData['node_id']]);
        if(!empty($aNode['counters'])) {
            $aCounters = json_decode($aNode['counters'], true);
            if(!empty($aCounters[$sContentType])) {
                $aCounters[$sContentType] -= 1;

                $this->_oDb->updateContentNodes(['counters' => json_encode($aCounters)], ['id' => $aData['node_id']]);
            }
        }
    }

    public function serviceEntityContentStructureBlock($iProfileId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oConfig->isContent())
            return '';

        if(!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if(!$iProfileId)
            return '';

        $aContentInfo = $this->_oDb->getContentInfoByProfileId($iProfileId);

        $sGridKey = 'OBJECT_GRID_CNT_STRUCTURE_' . ($this->checkAllowedEdit($aContentInfo) === CHECK_ACTION_RESULT_ALLOWED ? 'MANAGE' : 'VIEW');
        $oGrid = BxDolGrid::getObjectInstance($CNF[$sGridKey]);
        if(!$oGrid)
            return '';

        $oGrid->setEntryId($aContentInfo[$CNF['FIELD_ID']]);
        return $oGrid->getCode();
    }
    
    public function serviceEntityContentDataBlock($iProfileId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oConfig->isContent())
            return '';

        if(!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if(!$iProfileId)
            return '';

        $aContentInfo = $this->_oDb->getContentInfoByProfileId($iProfileId);

        $sGridKey = 'OBJECT_GRID_CNT_DATA_' . ($this->checkAllowedEdit($aContentInfo) === CHECK_ACTION_RESULT_ALLOWED ? 'MANAGE' : 'VIEW');
        $oGrid = BxDolGrid::getObjectInstance($CNF[$sGridKey]);
        if(!$oGrid)
            return '';

        $oGrid->setEntryId($aContentInfo[$CNF['FIELD_ID']]);
        return $oGrid->getCode();
    }
    
}

/** @} */
