<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplFormView extends BxBaseFormView
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }

    public function genInputSwitcher(&$aInput)
    {
        $aCheckbox = array_merge($aInput, ['type' => 'checkbox']);
        return $this->oTemplate->parseHtmlByName('form_field_switcher.html', [
            'class' => isset($aInput['checked']) && $aInput['checked'] ? 'on' : 'off',
            'checkbox' => $this->genInputStandard($aCheckbox)
        ]);
    }
}

/** @} */
