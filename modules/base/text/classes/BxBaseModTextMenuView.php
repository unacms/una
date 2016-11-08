<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxBaseModTextMenuView extends BxBaseModTextMenu
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if ($this->_aContentInfo)
            $this->addMarkers(array('content_id' => (int)$iContentId));
    }
}

/** @} */
