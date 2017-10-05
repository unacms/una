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
            return BxDolMetatags::locationsRetrieveFromForm($sName, $this);

        //--- Process field with 'Date Range Age' and 'Date-Time Range Age' type.
        if($bType && in_array($this->aInputs[$sName]['type'], array('datepicker_range_age', 'datetime_range_age'))) {
            $sMethod = $this->aFormAttrs['method'];
            $sValue = self::getSubmittedValue($sName, $sMethod);

            $aMatches = array();
            if(!preg_match("/^([0-9]+)-([0-9]+)$/i", $sValue, $aMatches))
                return array();

            $aArgs = array("%s-%s-%s", date('Y'), date('m'), date('d'));
            if($this->aInputs[$sName]['type'] == 'datetime_range_age') {
                $aArgs[0] = "%s-%s-%s %s:%s:%s";
                $aArgs = array_merge($aArgs, array(date('H'), date('i'), date('s')));
            }

            $aArgsFrom = $aArgs;
            $aArgsFrom[1] -= $aMatches[2];

            $aArgsTo = $aArgs;
            $aArgsTo[1] -= $aMatches[1];

            self::setSubmittedValue($sName, array(
                call_user_func_array('sprintf', $aArgsFrom),
                call_user_func_array('sprintf', $aArgsTo)
            ), $sMethod);
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

                $aAttrs= array('min' => $iMin, 'max' => $iMax, 'step' => 1);
                if(!empty($aInput['attrs']) && is_array($aInput['attrs']))
                    $aInput['attrs'] = array_merge($aInput['attrs'], $aAttrs);
                else 
                    $aInput['attrs'] = $aAttrs;

                return $this->genInputStandard($aInput);
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
}

/** @} */
