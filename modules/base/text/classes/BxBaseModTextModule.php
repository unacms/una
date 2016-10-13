<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Base module class for text based modules
 */
class BxBaseModTextModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    // ====== SERVICE METHODS
	public function serviceGetMenuAddonManageTools()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->fillFilters(array(
			'status' => 'hidden'
        ));
        $o->unsetPaginate();

        return $o->getNum();
	}

	public function serviceGetMenuAddonManageToolsProfileStats()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->fillFilters(array(
			'author' => bx_get_logged_profile_id()
        ));
        $o->unsetPaginate();

        return $o->getNum();
	}

    /**
     * Display public entries
     * @return HTML string
     */
    public function serviceBrowsePublic ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {   
        return $this->_serviceBrowse ('public', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * Display popular entries
     * @return HTML string
     */
    public function serviceBrowsePopular ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('popular', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * Display recently updated entries
     * @return HTML string
     */
    public function serviceBrowseUpdated ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('updated', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * Display entries of the author
     * @return HTML string
     */
    public function serviceBrowseAuthor ($iProfileId = 0, $aParams = array())
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return '';
        return $this->_serviceBrowse ('author', array_merge(array('author' => $iProfileId), $aParams), BX_DB_PADDING_DEF, true);
    }

    /**
     * Display entries favored by a member
     * @return HTML string
     */
    public function serviceBrowseFavorite ($iProfileId = 0, $aParams = array())
    {
        $oProfile = null;
        if((int)$iProfileId)
            $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile && bx_get('profile_id') !== false)
            $oProfile = BxDolProfile:: getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT));
        if(!$oProfile)
            $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return '';

        return $this->_serviceBrowse ('favorite', array_merge(array('user' => $oProfile->id()), $aParams), BX_DB_PADDING_DEF, true);
    }

    /**
     * Entry social sharing block
     */
    public function serviceEntitySocialSharing ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        $CNF = &$this->_oConfig->CNF;
        return $this->_entitySocialSharing ($iContentId, array(
            'id_timeline' => $iContentId,
        	'id_thumb' => $aContentInfo[$CNF['FIELD_THUMB']],
        	'title' => $aContentInfo[$CNF['FIELD_TITLE']],
        	'object_storage' => $CNF['OBJECT_STORAGE'],
            'object_transcoder' => false,
        	'object_vote' => $CNF['OBJECT_VOTES'],
        	'object_report' => $CNF['OBJECT_REPORTS'],
        	'uri_view_entry' => $CNF['URI_VIEW_ENTRY']
        ));
    }

    public function serviceEntityAllActions ($iContentId = 0)
    {
        return $this->_oTemplate->entryAllActions('', $this->serviceEntitySocialSharing($iContentId));
    }

    /**
     * Entry comments
     */
    public function serviceEntityComments ($iContentId = 0)
    {
        return $this->_entityComments($this->_oConfig->CNF['OBJECT_COMMENTS'], $iContentId);
    }

    /**
     * Entry author block
     */
    public function serviceEntityAuthor ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryAuthor', $iContentId);
    }

    /**
     * Entry attachments block
     */
    public function serviceEntityAttachments ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryAttachments', $iContentId);
    }

    public function serviceEntityBreadcrumb ($iContentId = 0)
    {
    	if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

		return $this->_oTemplate->entryBreadcrumb($aContentInfo);
    }

    /**
     * My entries actions block
     */
    public function serviceMyEntriesActions ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId || $iProfileId != $this->_iProfileId)
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_MY_ENTRIES']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * Delete all content by profile 
     * @param $iProfileId profile id 
     * @return number of deleted items
     */
    public function serviceDeleteEntitiesByAuthor ($iProfileId)
    {
        $a = $this->_oDb->getEntriesByAuthor((int)$iProfileId);
        if (!$a)
            return 0;

        $iCount = 0;
        foreach ($a as $aContentInfo)
            $iCount += ('' == $this->serviceDeleteEntity($aContentInfo[$this->_oConfig->CNF['FIELD_ID']]) ? 1 : 0);

        return $iCount;
    }

    // ====== PERMISSION METHODS

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetThumb ()
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'set thumb', $this->getName(), false);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    // ====== AUXILIARY METHODS
    public function getObjectFavorite($sSystem = '', $iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($sSystem) && !empty($CNF['OBJECT_FAVORITES']))
            $sSystem = $CNF['OBJECT_FAVORITES'];

        if(empty($sSystem))
            return false;

        $oFavorite = BxDolFavorite::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oFavorite->isEnabled())
            return false;

        return $oFavorite;
    }

    // ====== PROTECTED METHODS

    protected function _buildRssParams($sMode, $aArgs)
    {
        $aParams = array ();
        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'author':
                $aParams = array('author' => isset($aArgs[0]) ? (int)$aArgs[0] : '');
                break;
        }

        return $aParams;
    }

    protected function _entityComments ($sObject, $iId = 0)
    {
        if (!$iId)
            $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iId)
            return false;

        $oCmts = BxDolCmts::getObjectInstance($sObject, $iId);
        if (!$oCmts || !$oCmts->isEnabled())
            return false;

        return $oCmts->getCommentsBlock(0, 0, false);
    }
}

/** @} */
