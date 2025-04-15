<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit Group Form.
 */
class BxBaseModGroupsFormEntry extends BxBaseModProfileFormEntry
{
    protected $_bDisplayInvite;

    public function __construct($aInfo, $oTemplate = false)
    {
        if (!isset($this->_bAllowChangeUserForAdmins))
            $this->_bAllowChangeUserForAdmins = true;
        
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_bDisplayInvite = isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_INVITE']) && $CNF['OBJECT_FORM_ENTRY_DISPLAY_INVITE'] == $this->aParams['display'];

        if (isset($this->aInputs['initial_members'])) {
            if(!isset($this->aInputs['initial_members']['value']))
                $this->aInputs['initial_members']['value'] = $this->_bDisplayInvite ? [] : [bx_get_logged_profile_id()];

            if($this->_bDisplayInvite)
                $this->aInputs['initial_members'] = array_merge($this->aInputs['initial_members'], [
                    'required' => 1,
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_Enter_value_here'),
                    ]                    
                ]);
        }
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bValues = $aValues && !empty($aValues['id']);
        $aContentInfo = $bValues ? $this->_oModule->_oDb->getContentInfoById($aValues['id']) : false;

        if(isset($CNF['FIELD_PUBLISHED']) && $this->aParams['display'] == $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'] && isset($this->aInputs[$CNF['FIELD_PUBLISHED']]))
            if($bValues && in_array($aValues[$CNF['FIELD_STATUS']], array('active', 'hidden')))
                unset($this->aInputs[$CNF['FIELD_PUBLISHED']]);

        parent::initChecker ($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($CNF['FIELD_ADDED']) && empty($aValsToAdd[$CNF['FIELD_ADDED']])) {
            $iAdded = 0;
            if(isset($this->aInputs[$CNF['FIELD_ADDED']]))
                $iAdded = $this->getCleanValue($CNF['FIELD_ADDED']);

            if(empty($iAdded))
                 $iAdded = time();

            $aValsToAdd[$CNF['FIELD_ADDED']] = $iAdded;
        }

        if(isset($CNF['FIELD_PUBLISHED']) && empty($aValsToAdd[$CNF['FIELD_PUBLISHED']])) {
            $iPublished = 0;
            if(isset($this->aInputs[$CNF['FIELD_PUBLISHED']]))
                $iPublished = $this->getCleanValue($CNF['FIELD_PUBLISHED']);
                
             if(empty($iPublished))
                 $iPublished = time();

             $aValsToAdd[$CNF['FIELD_PUBLISHED']] = $iPublished;
        }

        if(!empty($CNF['FIELD_STATUS']) && empty($aValsToAdd[$CNF['FIELD_STATUS']]))
            $aValsToAdd[$CNF['FIELD_STATUS']] = (isset($CNF['FIELD_PUBLISHED']) && ($aValsToAdd[$CNF['FIELD_PUBLISHED']] > $aValsToAdd[$CNF['FIELD_ADDED']])) ? 'awaiting' : 'active';

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($CNF['FIELD_PUBLISHED'])) {
            if(empty($aValsToAdd[$CNF['FIELD_PUBLISHED']]) && isset($this->aInputs[$CNF['FIELD_PUBLISHED']])) {
                $iPublished = $this->getCleanValue($CNF['FIELD_PUBLISHED']);
                if(empty($iPublished))
                    $iPublished = time();

                $aValsToAdd[$CNF['FIELD_PUBLISHED']] = $iPublished;
            }
        }

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

    protected function genCustomInputInitialMembers ($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . "modules/?r=" . $this->_oModule->_oConfig->getUri() . "/ajax_get_initial_members";
        if(bx_is_api()) {
            $aInput['ajax_get_suggestions'] = $this->_oModule->_oConfig->getName() . "/get_initial_members&params[]=";

            $aInput['value_data'] = [];
            if(!empty($aInput['value']) && is_array($aInput['value']))
                foreach($aInput['value'] as $iProfileId)
                    if(($oProfile = BxDolProfile::getInstance($iProfileId)) !== false) {
                        $aProfile = $oProfile->getUnitAPI();
                        $aInput['value_data'][] = $aProfile['author_data'];
                    }
        }

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
}

/** @} */
