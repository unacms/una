<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAdsChartGrowth extends BxDolChartGrowth
{
    protected function __construct($aObject)
    {
        parent::__construct($aObject);
    }
    
    public function actionLoadDataByInterval()
    {
        $this->addMarkers([
            'content_id' => (int)bx_get('content_id')
        ]);

        return parent::actionLoadDataByInterval();
    }
}

/** @} */
