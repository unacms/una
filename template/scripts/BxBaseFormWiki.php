<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Wiki Form
 */
class BxBaseFormWiki extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        if (isset($this->aInputs['language']))
            $this->aInputs['language']['values'] = BxDolLanguages::getInstance()->getLanguages(false, true);
        if (isset($this->aInputs['content']))
            $this->aInputs['content']['code'] = true;
    }

    function genLabel(&$aInput)
    {
        if (isset($aInput['label']) && $aInput['label'] && ('language' == $aInput['name'] || 'content_main' == $aInput['name'])) {
            $sInputID = $this->getInputId($aInput);
            return '<label for="' . $sInputID . '">' . $aInput['label'] . '</label>';
        }
        return parent::genLabel($aInput);
    }
}

/** @} */
