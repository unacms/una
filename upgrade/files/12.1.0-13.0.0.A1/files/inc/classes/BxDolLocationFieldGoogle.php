<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLocationFieldGoogle extends BxDolLocationField
{
    public function genInputLocation (&$aInput, $oForm)
    {
        $aVars = $this->_getInputLocationVars($aInput, $oForm);
        return $oForm->getTemplate()->parseHtmlByName('location_field_google.html', $aVars);
    }

    protected function _getInputLocationVars(&$aInput, $oForm)
    {
        $isManualInput = (int)(isset($aInput['manual_input']) && $aInput['manual_input']);
        $sIdStatus = $oForm->getInputId($aInput) . '_status';
        $sIdInput = $oForm->getInputId($aInput) . '_location';

        $aVars = array (
            'key' => trim(getParam('sys_maps_api_key')),
            'lang' => bx_lang_name(),
            'name' => $aInput['name'],
            'id_status' => $sIdStatus,
            'id_input' => $sIdInput,
            'manual_input' => $isManualInput,            
            'bx_if:manual_input' => array(
                'condition' => $isManualInput,
                'content' => array(),
            ),
            'bx_if:auto_input' => array(
                'condition' => !$isManualInput,
                'content' => array(
                    'id_status' => $sIdStatus,
                    'location_string' => _t('_sys_location_field_label'),
                ),
            ),
            'api_field_name_short' => 'short_name',
            'api_field_name_long' => 'long_name',
            'api_field_name_2_length' => json_encode(array()),
        );

        $aLocationIndexes = BxDolForm::$LOCATION_INDEXES;
        foreach ($aLocationIndexes as $sKey)
            $aVars[$sKey] = $this->getLocationVal($aInput, $sKey, $oForm);

        if ($isManualInput) {
            $aAttrs = empty($aInput['attrs']) ? array() : $aInput['attrs'];
            $aInput['type'] = 'text';
            $aInput['attrs']['id'] = $sIdInput;
            $aInput['attrs'] = array_merge($aAttrs, $aInput['attrs']);
            $aVars['input'] = $oForm->genInputStandard($aInput);
        } 
        else {
            if ($this->getLocationVal($aInput, 'lat', $oForm) && $this->getLocationVal($aInput, 'lng', $oForm))
                $aInput['checked'] = true;
            else
                $aInput['checked'] = $oForm->getCleanValue($aInput['name'] . '_lat') && $oForm->getCleanValue($aInput['name'] . '_lng') ? 1 : 0;
            $aVars['input'] = $oForm->genInputSwitcher($aInput);

            $sLocationString = _t($aInput['checked'] ? '_sys_location_undefined' : '_sys_location_field_label');
            if ($aVars['country']) {
                $aCountries = BxDolFormQuery::getDataItems('Country');
                $sLocationString = ($aVars['street_number'] ? $aVars['street_number'] . ', ' : '') . ($aVars['street'] ? $aVars['street'] . ', ' : '') . ($aVars['city'] ? $aVars['city'] . ', ' : '') . ($aVars['state'] ? $aVars['state'] . ', ' : '') . $aCountries[$aVars['country']];
            }
            $aVars['bx_if:auto_input']['content']['location_string'] = $sLocationString;
        }

        return $aVars;
    }
}

/** @} */
