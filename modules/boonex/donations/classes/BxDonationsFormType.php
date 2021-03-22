<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxDonationsFormType extends BxTemplStudioFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_donations';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_NAME']])) {
            $sJsObject = $this->_oModule->_oConfig->getJsObject('form');

            $iId = $this->getId();
            $aMask = array('mask' => "javascript:%s.checkName('%s');", $sJsObject, $CNF['FIELD_NAME']);
            if(!empty($iId) && $this->aParams['display'] == $CNF['OBJECT_FORM_TYPE_DISPLAY_EDIT']) {
                $aMask['mask'] = "javascript:%s.checkName('%s', %d);";
                $aMask[] = $iId;
            }

            $sOnBlur = call_user_func_array('sprintf', array_values($aMask)); 
            $this->aInputs[$CNF['FIELD_NAME']]['attrs']['onblur'] = $sOnBlur;
        }
    }

    public function setAction($sAction)
    {
        $this->aFormAttrs['action'] = $sAction;
    }

    public function getCode($bDynamicMode = false)
    {
    	$sJs = $this->_oModule->_oTemplate->addJs(array('form.js'), $bDynamicMode);

        $sCode = '';
        if($bDynamicMode)
            $sCode .= $sJs;

        $sCode .= $this->_oModule->_oTemplate->getJsCode('form');
        $sCode .= parent::getCode($bDynamicMode);

        return $sCode;
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_NAME']])) {
            $sName = BxDolForm::getSubmittedValue($CNF['FIELD_NAME'], $this->aFormAttrs['method'], $this->_aSpecificValues);
            $sName = $this->_oModule->_oConfig->getTypeName($sName);
            BxDolForm::setSubmittedValue($CNF['FIELD_NAME'], $sName, $this->aFormAttrs['method'], $this->_aSpecificValues);
        }

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sNameKey = $CNF['FIELD_NAME'];
        if(isset($this->aInputs[$sNameKey])) {
            $sName = $this->getCleanValue($sNameKey);
            $aContentInfo = $this->_oModule->_oDb->getTypes(array('type' => 'by_id', 'value' => $iContentId));

            if($aContentInfo[$sNameKey] != $sName) {
                $sName = $this->_oModule->_oConfig->getTypeName($sName);
                BxDolForm::setSubmittedValue($sNameKey, $sName, $this->aFormAttrs['method'], $this->_aSpecificValues);
            }
        }

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    public function getId()
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
