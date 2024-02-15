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
 * View Entry (brief)
 */
class BxPhotosPageEntryBrief extends BxTemplPage
{
    protected $_sModule;
    protected $_oModule;

    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_photos';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate ? $oTemplate : $this->_oModule->_oTemplate);

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        $sMode = '';
        if(bx_get('mode') !== false)
            $sMode = bx_process_input(bx_get('mode'));

        if($iContentId)
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
    }

    protected function _isAvailablePage($a)
    {
        if(!$this->_aContentInfo || !$this->_oModule->isEntryActive($this->_aContentInfo))
            return false;

        if(!empty($CNF['FIELD_CF'])) {
            $oCf = BxDolContentFilter::getInstance();
            if($oCf->isEnabled() && !$oCf->isAllowed($this->_aContentInfo[$CNF['FIELD_CF']]))
                return false;
        }

        return parent::_isAvailablePage($a);
    }
    
    protected function _isVisiblePage ($a)
    {
        if(($mixedCheckResult = $this->_oModule->checkAllowedView($this->_aContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED) 
            return $mixedCheckResult;

        return parent::_isVisiblePage($a);
    }
}

/** @} */
