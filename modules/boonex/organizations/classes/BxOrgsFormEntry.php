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
 * Create/Edit Organization Form.
 */
class BxOrgsFormEntry extends BxBaseModGroupsFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_bAllowChangeUserForAdmins = true;
        
        $this->MODULE = 'bx_organizations';
        parent::__construct($aInfo, $oTemplate);
    }
    
    public function update ($iContentId, $aValsToAdd = [], &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        $mixedResult = parent::update($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
        if($mixedResult && ($iAuthorId = (int)$this->getCleanValue($CNF['FIELD_AUTHOR'])) && (int)$aContentInfo[$CNF['FIELD_AUTHOR']] != $iAuthorId) {
            $oProfileAuthor = BxDolProfile::getInstance($iAuthorId);
            $oProfileContent = BxDolProfile::getInstanceByContentAndType($iContentId, $this->MODULE);
            if($oProfileAuthor !== false && $oProfileContent !== false)
                $oProfileContent->move($oProfileAuthor->getAccountId());
        }

        return $mixedResult;
    }
}

/** @} */
