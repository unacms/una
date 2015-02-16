<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
