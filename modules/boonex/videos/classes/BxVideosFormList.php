<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxVideosFormList extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_videos';
        parent::__construct($aInfo, $oTemplate);
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (isset($this->aInputs[$CNF['FIELD_ALLOW_LIST_VIEW_TO']]))
            $this->aInputs[$CNF['FIELD_ALLOW_LIST_VIEW_TO']] = array_merge($this->aInputs[$CNF['FIELD_ALLOW_LIST_VIEW_TO']], BxDolPrivacy::getGroupChooser($CNF['OBJECT_PRIVACY_VIEW_LIST']));
    }
}

/** @} */
