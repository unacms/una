<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Images' Processing.
 */
class BxBaseImageServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceAlertResponseFileDeleteLocal($oAlert, $sObject)
    {
        BxDolTranscoderImage::onAlertResponseFileDeleteLocal($oAlert, $sObject);
    }

    public function serviceAlertResponseFileDeleteOrig($oAlert, $sObject)
    {
        BxDolTranscoderImage::onAlertResponseFileDeleteOrig($oAlert, $sObject);
    }
}

/** @} */
