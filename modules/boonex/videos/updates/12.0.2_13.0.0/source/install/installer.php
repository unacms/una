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

class BxVideosInstaller extends BxBaseModTextInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    protected function actionInitializeOEmbedEndpoints($sOperation) {
        bx_srv('bx_videos', 'update_oembed_providers', array());
        return BX_DOL_STUDIO_INSTALLER_SUCCESS;
    }
}

/** @} */
