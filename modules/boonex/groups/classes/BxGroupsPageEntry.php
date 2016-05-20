<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Profile create/edit/delete pages.
 */
class BxGroupsPageEntry extends BxBaseModProfilePageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_groups';
        parent::__construct($aObject, $oTemplate);
    }

    protected function _processPermissionsCheck ()
    {
        $mixedAllowView = $this->_oModule->checkAllowedView($this->_aContentInfo);
        if ('c' != $this->_aContentInfo['allow_view_to'] && CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $mixedAllowView)) {
            $this->_oTemplate->displayAccessDenied($sMsg);
            exit;
        }
        elseif ('c' == $this->_aContentInfo['allow_view_to'] && 'bx_groups_view_profile' == $this->_sObject && CHECK_ACTION_RESULT_ALLOWED !== $mixedAllowView) {
            // replace current page with different set of blocks
            $aObject = BxDolPageQuery::getPageObject('bx_groups_view_profile_closed');
            $this->_sObject = $aObject['object'];
            $this->_aObject = $aObject;
            $this->_oQuery = new BxDolPageQuery($this->_aObject);
        }

        $this->_oModule->checkAllowedView($this->_aContentInfo, true);
    }
}

/** @} */
