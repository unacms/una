<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioOptionsApi extends BxDolStudioOptionsApi
{
    public function __construct($sType = '', $mixedCategory = '', $sMix = '')
    {
        parent::__construct($sType, $mixedCategory, $sMix);
    }

    protected function field($aItem, $aItems2Mixes)
    {
        $aField = parent::field($aItem, $aItems2Mixes);

        switch($aItem['name']) {
            case 'sys_api_config':
                $aField = array_merge($aField, [
                    'code' => 1
                ]);
                break;
        }

        return $aField;
    }
}

/** @} */
