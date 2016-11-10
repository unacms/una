<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxForumMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($aObject, $oTemplate);

        $this->addMarkers(array(
        	'js_object' => $this->_oModule->_oConfig->getJsObject('entry') 
        ));
    }
}

/** @} */
