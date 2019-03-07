<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services for mobile apps.
 */
class BxBaseServiceMobileApps extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get injection code required for mobile apps
     * @return string with Styles and JS code
     */
    public function serviceInjection()
    {
        if (false === strpos($_SERVER['HTTP_USER_AGENT'], 'UNAMobileApp')) 
            return '';

        return BxDolTemplate::getInstance()->parseHtmlByName('mobile_apps_styles.html', array());
    }
}

/** @} */
