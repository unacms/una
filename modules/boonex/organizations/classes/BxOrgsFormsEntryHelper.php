<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Organization profile forms functions
 */
class BxOrgsFormsEntryHelper extends BxBaseModGroupsFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }
    
    public function onDataAddAfter($iAccountId, $iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        $aInvitedMembers = bx_get('initial_members');
        if(empty($aInvitedMembers) || !is_array($aInvitedMembers))
            return '';

        $sGroupModule = $this->_oModule->_oConfig->getName();
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $sGroupModule);
        if(!$oGroupProfile)
            return '';
        
        $aGroupContentInfo = bx_srv($sGroupModule, 'get_info', array($iContentId, false));
        if(empty($aGroupContentInfo) || !is_array($aGroupContentInfo) || !in_array($aGroupContentInfo[$CNF['FIELD_AUTHOR']], $aInvitedMembers))
            return '';

        $oGroupAuthor = BxDolProfile::getInstance($aGroupContentInfo[$CNF['FIELD_AUTHOR']]);
        if(!$oGroupAuthor)
            return '';

        $sGroupAuthorModule = $oGroupAuthor->getModule();
        if(!BxDolRequest::serviceExists($sGroupAuthorModule, 'act_as_profile') || !bx_srv($sGroupAuthorModule, 'act_as_profile'))
            return '';

        $this->makeAdmin($oGroupAuthor->id(), $oGroupProfile, $aInvitedMembers);

        return '';
    }
}

/** @} */
