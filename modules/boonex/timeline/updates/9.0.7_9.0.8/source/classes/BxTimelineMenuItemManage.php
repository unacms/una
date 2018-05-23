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

        if(bx_get('view') !== false)
            $this->setView(bx_process_input(bx_get('view')));

        $this->setDynamicMode(true);

        $this->_bShowTitles = true;
    }
}

/** @} */
