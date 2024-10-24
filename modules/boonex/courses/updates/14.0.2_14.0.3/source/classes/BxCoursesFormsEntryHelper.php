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
 * Group profile forms functions
 */
class BxCoursesFormsEntryHelper extends BxBaseModGroupsFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    public function onDataAddAfter($iAccountId, $iContentId)
    {
        if($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName());
        if(!$oGroupProfile)
            return '';

        $iAdminProfileId = bx_get_logged_profile_id();
        $aInitialProfiles = bx_get('initial_members');
        if(!is_array($aInitialProfiles) || !in_array($iAdminProfileId, $aInitialProfiles))
            $this->makeAuthorAdmin($oGroupProfile, array($iAdminProfileId));

        return '';
    }

    public function onDataDeleteAfter($iContentId, $aContentInfo, $oProfile)
    {
        if($s = parent::onDataDeleteAfter($iContentId, $aContentInfo, $oProfile))
            return $s;

        $aDataItems = $this->_oModule->_oDb->getContentData([
            'sample' => 'entry_id',
            'entry_id' => $iContentId
        ]);

        if(!empty($aDataItems) && is_array($aDataItems))
            foreach($aDataItems as $aDataItem)
                if(($sMethod = 'delete_entity') && bx_is_srv($aDataItem['content_type'], $sMethod))
                    bx_srv($aDataItem['content_type'], $sMethod, [$aDataItem['content_id']]);

        $this->_oModule->_oDb->deleteContentDataWithTracks($iContentId);

        $this->_oModule->_oDb->deleteContentNodesWithTracks($iContentId);

        $this->_oModule->_oDb->deleteContentStructureNode(['entry_id' => $iContentId]);

        return '';
    }
}

/** @} */
