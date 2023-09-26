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
 * Browse entries pages.
 */
class BxForumPageBrowse extends BxBaseModTextPageBrowse
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($aObject, $oTemplate);

        $this->_oModule->_oTemplate->addJs(array('main.js'));

		$sKeyword = bx_process_input(bx_get('keyword'));
        if(!empty($sKeyword))
	        $this->addMarkers(array(
	        	'keyword_name' => '#' . $sKeyword
	        ));
    }
}

/** @} */
