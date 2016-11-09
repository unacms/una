<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioLanguages extends BxDolStudioLanguages
{
    function __construct()
    {
        parent::__construct();
    }
    function getCss()
    {
        return array('languages.css');
    }
    function getJs()
    {
        return array('jquery.anim.js', 'page.js', 'languages.js');
    }
}

/** @} */
