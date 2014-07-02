<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplPage');

/**
 * Entry create/edit pages
 */
class BxBaseModGeneralPageEntry extends BxTemplPage
{
    protected $MODULE;

    protected $_oModule;
    protected $_aContentInfo = false;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    public function getCode ()
    {
        if (!$this->_aContentInfo) { // if entry is not found - display standard "404 page not found" page
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($this->_aContentInfo))) {
            $this->_oTemplate->displayAccessDenied($sMsg);
            exit;
        }
        $this->_oModule->checkAllowedView($this->_aContentInfo, true);

        if (!empty($CNF['OBJECT_VIEWS'])) {
            bx_import('BxDolView');
            BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $this->_aContentInfo[$CNF['FIELD_ID']])->doView();
        }

        return parent::getCode ();
    }

    protected function _getPageCacheParams ()
    {
        if (!$this->_aContentInfo)
            return '';
        return $this->_aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ID']]; // cache is different for every entry
    }
}

/** @} */
