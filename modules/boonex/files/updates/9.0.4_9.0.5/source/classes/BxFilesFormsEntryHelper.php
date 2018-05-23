<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxFilesFormsEntryHelper extends BxBaseModFilesFormsEntryHelper
{
    public function __construct($oModule)
    {
		$this->_sDisplayForFormAdd ='bx_files_entry_upload';
		$this->_sObjectNameForFormAdd ='bx_files_upload';
        parent::__construct($oModule);
    }

    public function addDataForm ($sDisplay = false, $sCheckFunction = false)
    {
       
		$mixedContent = $this->addDataFormAction($sDisplay, $sCheckFunction);
		if (is_array($mixedContent) && $mixedContent['need_redirect_after_action']){
			$CNF = &$this->_oModule->_oConfig->CNF;
			$aContentIds = $mixedContent['content_ids_array'];
			$iContentId = array_pop($aContentIds);
			$aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
			$oProfile = BxDolProfile::getInstance($aContentInfo[$CNF['FIELD_AUTHOR']]);
			$sUri = BxDolService::call($oProfile->getModule(), 'is_group_profile') ? $CNF['URI_GROUP_ENTRIES'] . '&profile_id=' . $oProfile->id() : $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . bx_get_logged_profile_id();
			$this->_redirectAndExit('page.php?i=' . $sUri);
		}
		else{
			return $mixedContent;	
		}
    }

    protected function redirectAfterDelete($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $oProfile = BxDolProfile::getInstance($aContentInfo[$CNF['FIELD_AUTHOR']]);
        if (BxDolService::call($oProfile->getModule(), 'is_group_profile'))
            $this->_redirectAndExit('page.php?i=' . $CNF['URI_GROUP_ENTRIES'] . '&profile_id=' . $oProfile->id());
        else
            $this->_redirectAndExit($CNF['URL_HOME'], true, array(
                'account_id' => getLoggedId(),
                'profile_id' => bx_get_logged_profile_id(),
            ));
    }
}

/** @} */
