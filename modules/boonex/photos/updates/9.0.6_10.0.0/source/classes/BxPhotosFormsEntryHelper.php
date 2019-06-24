<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxPhotosFormsEntryHelper extends BxBaseModFilesFormsEntryHelper
{
    public function __construct($oModule)
    {
		$this->_sDisplayForFormAdd ='bx_photos_entry_upload';
		$this->_sObjectNameForFormAdd ='bx_photos_upload';
        parent::__construct($oModule);
    }
    
    public function addDataForm ($sDisplay = false, $sCheckFunction = false)
    {
        $mixedContent = $this->addDataFormAction($sDisplay, $sCheckFunction);
        if (is_array($mixedContent) && $mixedContent['need_redirect_after_action']){
            $CNF = &$this->_oModule->_oConfig->CNF;

            $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . bx_get_logged_profile_id());
            if($this->_bAjaxMode) {
                echoJson($this->prepareResponse($sUrl, $this->_bAjaxMode, 'redirect'));
                exit;
            }
            else
                $this->_redirectAndExit($sUrl);
        }
        else {
                return $mixedContent;
        }
    }
}

/** @} */
