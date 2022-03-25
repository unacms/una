<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioSettings extends BxTemplStudioWidget
{
    protected $oOptions;

    function __construct($sType = '', $mixedCategory = '')
    {
        parent::__construct('settings');

        $this->oDb = new BxDolStudioSettingsQuery();

        $this->oOptions = new BxTemplStudioOptions($sType, $mixedCategory);
    }

    public function checkAction()
    {
        return $this->oOptions->checkAction();
    }
}

/** @} */
