<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
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
        // check if content exists
        if (!$this->_aContentInfo) { // if entry is not found - display standard "404 page not found" page
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        // permissions check 
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($this->_aContentInfo))) {
            $this->_oTemplate->displayAccessDenied($sMsg);
            exit;
        }
        $this->_oModule->checkAllowedView($this->_aContentInfo, true);

        // count views
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (!empty($CNF['OBJECT_VIEWS'])) {
            bx_import('BxDolView');
            BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $this->_aContentInfo[$CNF['FIELD_ID']])->doView();
        }

        // add content metatags
        if (!empty($CNF['OBJECT_METATAGS'])) {
            bx_import('BxDolMetatags');
            $o = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            if ($o) {
                $aThumb = false;
                if (!empty($CNF['FIELD_THUMB']) && !empty($this->_aContentInfo[$CNF['FIELD_THUMB']]) && !empty($CNF['OBJECT_STORAGE']))
                    $aThumb = array('id' => $this->_aContentInfo[$CNF['FIELD_THUMB']], 'object' => $CNF['OBJECT_STORAGE']);
                $o->metaAdd($this->_aContentInfo[$CNF['FIELD_ID']], $aThumb);
            }
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
