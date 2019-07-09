<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Extended Search Form.
 * 
 * @see BxDolSearchExtended
 */
class BxBaseSearchExtendedForm extends BxTemplFormView
{
    protected $_iAgeMin;
    protected $_iAgeMax;

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_iAgeMin = 1;
        $this->_iAgeMax = 75;
    }

    function getCleanValue($sName)
    {
        $bType = isset($this->aInputs[$sName]['type']);

        //--- Process field with 'Location' type. 
        if($bType && $this->aInputs[$sName]['type'] == 'location')
            return array(
                'string' => parent::getCleanValue($sName), 
                'array' => BxDolMetatags::locationsRetrieveFromForm($sName, $this)
            );
        
        if($bType && $this->aInputs[$sName]['type'] == 'location_radius'){
            $aTmp = BxDolMetatags::locationsRetrieveFromForm($sName, $this);   
            $sValue = bx_get($sName . '_rad');
            $iValue = is_numeric($sValue) ? (int)$sValue : 0;
            array_push($aTmp, $iValue);
            return array(
                'string' => parent::getCleanValue($sName), 
                'array' => $aTmp
            );
        }
        //--- Process field with 'Date Range Age' and 'Date-Time Range Age' type.
        if($bType && in_array($this->aInputs[$sName]['type'], array('datepicker_range_age', 'datetime_range_age'))) {
            $bTypeDateTime = $this->aInputs[$sName]['type'] == 'datetime_range_age';

            $sMethod = $this->aFormAttrs['method'];
            $sValue = self::getSubmittedValue($sName, $sMethod);

            if(!empty($sValue) && is_string($sValue)) {
                $aMatches = array();
                if(!preg_match("/^([0-9]+)-([0-9]+)$/i", $sValue, $aMatches))
                    return array();

                $aArgs = array("%s-%s-%s", date('Y'), date('m'), date('d'));
                if($bTypeDateTime) {
                    $aArgs[0] = "%s-%s-%s %s:%s:%s";
                    $aArgs = array_merge($aArgs, array(date('H'), date('i'), date('s')));
                }

                $aArgsFrom = $aArgs;
                $aArgsFrom[1] -= ($aMatches[2] + 1);
                if($bTypeDateTime)
                    $aArgsFrom[6] += 1;
                else
                    $aArgsFrom[3] += 1;

                $aArgsTo = $aArgs;
                $aArgsTo[1] -= $aMatches[1];

                self::setSubmittedValue($sName, array(
                    call_user_func_array('sprintf', $aArgsFrom),
                    call_user_func_array('sprintf', $aArgsTo)
                ), $sMethod);
            }
        }

        return parent::getCleanValue($sName);
    }

    public function genInput(&$aInput)
    {
        switch($aInput['type'])
        {
            case 'datepicker_range':
            case 'datetime_range':
                $bValue = !empty($aInput['value']) && is_array($aInput['value']);

                $aInput['name'] .= '[]';
                $aInput['type'] = str_replace('_range', '', $aInput['type']);

                $aSubInputs = array($aInput, $aInput);
                foreach($aSubInputs as $iKey => $aSubInput) {
                    if($bValue && isset($aInput['value'][$iKey]))
                        $aSubInput['value'] = $aInput['value'][$iKey];

                    $aSubInputs[$iKey] = $this->genInputStandard($aSubInput);
                }

                return implode(' - ', $aSubInputs);

            case 'datepicker_range_age':
            case 'datetime_range_age':
                $iMin = $this->_iAgeMin;
                $iMax = $this->_iAgeMax;

                $aInput['type'] = 'doublerange';
				
                if(empty($aInput['value']))
                    $aInput['value'] = $iMin . '-' . $iMax;

				if (!isset($aInput['attrs']['min']) && !isset($aInput['attrs']['max'])){
					$aAttrs = array('min' => $iMin, 'max' => $iMax, 'step' => 1);
					if(!empty($aInput['attrs']) && is_array($aInput['attrs']))
						$aInput['attrs'] = array_merge($aInput['attrs'], $aAttrs);
					else 
						$aInput['attrs'] = $aAttrs;
				}

                return $this->genInputStandard($aInput);
                
            case 'location_radius':
                return $this->genInputLocationRadius($aInput);
        }

        return parent::genInput($aInput);
    }

    public function genInputLocation(&$aInput)
    {
        $aInput['manual_input'] = true;
        return parent::genInputLocation($aInput);
    }

	protected function genCustomInputAuthor($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . 'searchExtended.php?action=get_authors';

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
    
    public function genInputLocationRadius(&$aInput)
    {
        $aInput['manual_input'] = true;
        
        $aInputRadius = $aInput;
        $aInputRadius['type'] = 'text';
        $aInputRadius['name'] = $aInputRadius['name'] . '_rad';
        $sValue = bx_get($aInputRadius['name']);
        $aInputRadius['value'] = is_numeric($sValue) ? $sValue : '';
        $aInputRadius['attrs']['placeholder'] = _t('_sys_form_input_location_radius_label');
        
        $aVars = array (
            'location_input' => parent::genInputLocation($aInput),
            'radius_input' => parent::genInputStandard($aInputRadius),
        );
        $this->_addCss('search_extended.css');
        return $this->oTemplate->parseHtmlByName('form_field_location_radius.html', $aVars);
    }
}

/** @} */
