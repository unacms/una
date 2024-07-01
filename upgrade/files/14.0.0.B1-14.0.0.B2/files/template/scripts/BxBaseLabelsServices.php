<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Languages.
 */
class BxBaseLabelsServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetLabels($aParams = [])
    {
        $oLabel = BxDolLabel::getInstance();

        $iParentId = isset($aParams['parent_id']) ? (int)$aParams['parent_id'] : 0;

        return [
            'system' => $oLabel->getLabelsSystem($iParentId),
            'context' => $oLabel->getLabelsContext()
        ];
    }
}

/** @} */
