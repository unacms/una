<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolMenu
 */
class BxTemplMenuCustom extends BxBaseMenuCustom
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    protected function _getMenuItem ($a)
    {
        $aResult = parent::_getMenuItem($a);
        if($aResult === false)
            return $aResult;

        if(!empty($a['primary'])) {
            if(!isset($aResult['class']))
                $aResult['class'] = '';

            $aResult['class'] .= ' bx-mi-primary'; 
        }

        return $aResult;
    }
}

/** @} */
