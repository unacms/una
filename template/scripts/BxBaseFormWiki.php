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

        if (isset($this->aInputs['lang'])) {
            $this->aInputs['lang']['values'] = BxDolLanguages::getInstance()->getLanguages(false, false);
        }
    }

}

/** @} */
