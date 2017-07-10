<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Base module class for text based modules
 */
class BxBaseModTextModule extends BxBaseModGeneralModule implements iBxDolContentInfoService
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    // ====== SERVICE METHODS
    public function serviceGetThumb ($iContentId, $sTranscoder = '') 
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($sTranscoder) && !empty($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']))
            $sTranscoder = $CNF['OBJECT_IMAGES_TRANSCODER_GALLERY'];

        $mixedResult = $this->_getFieldValueThumb('FIELD_THUMB', $iContentId, $sTranscoder);
        return $mixedResult !== false ? $mixedResult : '';
    }

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
        if (!$iProfileId || !($oProfile = BxDolProfile::getInstance($iProfileId)))
            return false;

        if ($iProfileId != $this->_iProfileId)
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
}

/** @} */
