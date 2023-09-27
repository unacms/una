<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ocean Ocean Template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import ('BxBaseModTemplateModule');

class BxOceanModule extends BxBaseModTemplateModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceGetActions($aWidget)
    {
        $oInformer = BxDolInformer::getInstance(BxDolStudioTemplate::getInstance());
        $oInformer->add('sys-module-discontinued', _t('_adm_txt_modules_discontinued', BX_DOL_URL_STUDIO . 'design.php?name=bx_ocean', _t('_bx_ocean_stg_cpt_type')), BX_INFORMER_ALERT);

        return bx_srv('system', 'get_actions', [$aWidget], 'TemplStudioDesigns');
    }
}

/** @} */
