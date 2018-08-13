<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxTimelineMenuItemActions.php');

/**
 * 'Item' menu.
 */
class BxTimelineMenuItemManage extends BxTimelineMenuItemActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if(bx_get('content_id') !== false)
            $this->setEventById((int)bx_get('content_id'));

        $sType = bx_get('type') !== false ? bx_process_input(bx_get('type')) : '';
        $sView = bx_get('view') !== false ? bx_process_input(bx_get('view')) : '';
        $this->setBrowseParams($sType, $sView);

        $this->setDynamicMode(true);

        $this->_bShowTitles = true;
    }
}

/** @} */
