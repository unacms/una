<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Marker.io Marker.io
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarkerIoStudioSettings extends BxTemplStudioSettings
{
    protected function field($aItem, $aItems2Mixes)
    {
        $sName = trim(str_replace($this->sType, '', $aItem['name']), '_');
        $mixedValue = isset($aItems2Mixes[$aItem['name']]) ? $aItems2Mixes[$aItem['name']] : $aItem['value'];

        $aField = [];
        if($aItem['type'] == 'text' && $sName == 'code')
            $aField = array(
                'type' => 'textarea',
                'name' => $aItem['name'],
                'caption' => _t($aItem['caption']),
                'value' => $mixedValue,
                'attrs' => [],
                'code' => true,
                'db' => array (
                    'pass' => 'XssHtml',
                ),
            );
        else 
            $aField = parent::field($aItem, $aItems2Mixes);

        return $aField;
    }
}