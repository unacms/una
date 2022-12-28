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

class BxBaseModGroupsFormPrice extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);        

        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_PRICE_NAME']])) {
            $sJsObject = $this->_oModule->_oConfig->getJsObject('prices');

            $iId = $this->getItemId();
            $aMask = array('mask' => "javascript:%s.checkName(this, '%s');", $sJsObject, $CNF['FIELD_PRICE_NAME']);
            if(!empty($iId) && $this->aParams['display'] == $CNF['OBJECT_FORM_PRICE_DISPLAY_EDIT']) {
                $aMask['mask'] = "javascript:%s.checkName(this, '%s', %d);";
                $aMask[] = $iId;
            }

            $sOnBlur = call_user_func_array('sprintf', array_values($aMask)); 
            $this->aInputs[$CNF['FIELD_PRICE_NAME']]['attrs']['onblur'] = $sOnBlur;
        }
    }

    public function setRoleId($iRoleId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->aInputs[$CNF['FIELD_PRICE_ROLE_ID']]['value'] = (int)$iRoleId;
        if(empty($this->aInputs[$CNF['FIELD_PRICE_NAME']]['value'])) {
            $aRoles = $this->_oModule->_oConfig->getRoles();
            $this->aInputs[$CNF['FIELD_PRICE_NAME']]['value'] = $this->_oModule->_oConfig->getPriceName(_t($aRoles[$iRoleId]));
        }
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_PRICE_NAME']])) {
            $sName = BxDolForm::getSubmittedValue($CNF['FIELD_PRICE_NAME'], $this->aFormAttrs['method'], $this->_aSpecificValues);
            $sName = $this->_oModule->_oConfig->getPriceName($sName);
            BxDolForm::setSubmittedValue($CNF['FIELD_PRICE_NAME'], $sName, $this->aFormAttrs['method'], $this->_aSpecificValues);
        }

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sNameKey = $CNF['FIELD_PRICE_NAME'];
        if(isset($this->aInputs[$sNameKey])) {
            $sName = $this->getCleanValue($sNameKey);
            $aContentInfo = $this->_oModule->_oDb->getPrices(array('type' => 'by_id', 'value' => $iContentId));

            if($aContentInfo[$sNameKey] != $sName) {
                $sName = $this->_oModule->_oConfig->getPriceName($sName);
                BxDolForm::setSubmittedValue($sNameKey, $sName, $this->aFormAttrs['method'], $this->_aSpecificValues);
            }
        }

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    public function getItemId()
    {
        $iResult = 0;

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $sId = bx_get('id');
            if(!empty($sId))
                $iResult = $sId;
        }
        else
            $iResult = array_shift($aIds);

        return (int)$iResult;
    }
}

/** @} */
