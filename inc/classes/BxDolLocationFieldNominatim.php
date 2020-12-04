<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Plain fields with Nomination service geocoding
 */
class BxDolLocationFieldNominatim extends BxDolLocationField
{
    public function genInputLocation (&$aInput, $oForm)
    {        
        $isManualInput = (int)(isset($aInput['manual_input']) && $aInput['manual_input']);
        if (!$isManualInput) {
            $aVars = array();
            $aInputField = $aInput;

            $aLocationIndexes = BxDolForm::$LOCATION_INDEXES;
            foreach ($aLocationIndexes as $sKey)
                $aVars[$sKey] = $this->getLocationVal($aInput, $sKey, $oForm);

            $aInputField['caption'] = _t('_sys_location_undefined');

            if ($this->getLocationVal($aInput, 'lat', $oForm) && $this->getLocationVal($aInput, 'lng', $oForm))
                $aInputField['checked'] = true;
            else
                $aInputField['checked'] = $oForm->getCleanValue($aInput['name'] . '_lat') && $oForm->getCleanValue($aInput['name'] . '_lng') ? 1 : 0;

            $sLocationString = _t('_sys_location_undefined');
            if ($aVars['country']) {
                $aCountries = BxDolFormQuery::getDataItems('Country');
                $sLocationString = ($aVars['street_number'] ? $aVars['street_number'] . ', ' : '') . ($aVars['street'] ? $aVars['street'] . ', ' : '') . ($aVars['city'] ? $aVars['city'] . ', ' : '') . ($aVars['state'] ? $aVars['state'] . ', ' : '') . $aCountries[$aVars['country']];
            }
            elseif ($aVars['lat'] || $aVars['lng']) {
                $sLocationString = $aVars['lat'] . ', ' . $aVars['lng'];
            }

            $aVars['name'] = $aInput['name'];
            $aVars['input'] = $oForm->genInputSwitcher($aInputField);
            $aVars['id_status'] = $oForm->getInputId($aInput) . '_status';
            $aVars['location_string'] = $sLocationString;
            $aVars['nominatim_server'] = $this->getNominatimServer();
            $aVars['nominatim_email'] = $this->getNominatimEmail();

            $sRet = $oForm->getTemplate()->parseHtmlByName('location_field_plain_auto.html', $aVars);
        }
        else {
            $aFields = array(
                'lat' => array('type' => 'hidden'),
                'lng' => array('type' => 'hidden'),
                'street_number' => array('type' => 'text', 'ph' => _t('_sys_location_ph_number')),
                'street' => array('type' => 'text', 'ph' => _t('_sys_location_ph_street')),
                'city' => array('type' => 'text', 'ph' => _t('_sys_location_ph_city')),
                'state' => array('type' => 'text', 'ph' => _t('_sys_location_ph_state')),
                'zip' => array('type' => 'text', 'ph' => _t('_sys_location_ph_zip')),
                'country' => array('type' => 'select'),
            );

            $sInputs = '';
            foreach ($aFields as $sKey => $a) {
                $aInputField = $aInput;
                $aInputField['name'] = $aInput['name'] . '_' . $sKey;
                $aInputField['type'] = $a['type'];
                $aInputField['value'] = $this->getLocationVal($aInput, $sKey, $oForm);            
                $aInputField['attrs']['placeholder'] = empty($a['ph']) ? '' : _t($a['ph']);
                if (isset($aInputField['attrs']['class']))
                    $aInputField['attrs']['class'] .= ' bx-form-input-location-' . $sKey;
                else
                    $aInputField['attrs']['class'] = 'bx-form-input-location-' . $sKey;
                if ('country' == $sKey) {
                    $aCountries = BxDolFormQuery::getDataItems('Country');
                    array_unshift($aCountries, array('key' => '', 'value' => _t('_None')));
                    $aInputField['values'] = $aCountries;
                    $sInputs .= $oForm->genInputSelect($aInputField);
                }
                else {
                    $sInputs .= $oForm->genInputStandard($aInputField);
                }
            }
            $sRet = $oForm->getTemplate()->parseHtmlByName('location_field_plain.html', array(
                'name' => $aInput['name'],
                'nominatim_server' => $this->getNominatimServer(),
                'nominatim_email' => $this->getNominatimEmail(),
                'inputs' => $sInputs,
            ));
        }
        return $sRet;
    }

    protected function getNominatimServer ()
    {
        return trim(trim(getParam('sys_nominatim_server')), '/');
    }

    protected function getNominatimEmail ()
    {
        return trim(getParam('sys_nominatim_email'));
    }
}

/** @} */
