<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralInstaller');

class BxTimelineInstaller extends BxBaseModGeneralInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
        $this->_aTranscoders = array ('bx_timeline_photos_preview', 'bx_timeline_photos_view');
        $this->_aStorages = array ('bx_timeline_photos');
    }

    function enable($aParams)
    {
        $aResult = parent::enable($aParams);

        if($aResult['result'])
            BxDolService::call($this->_aConfig['name'], 'add_handlers');

        return $aResult;
    }

    function disable($aParams)
    {
         BxDolService::call($this->_aConfig['name'], 'delete_handlers');

        return parent::disable($aParams);
    }
}

/** @} */
