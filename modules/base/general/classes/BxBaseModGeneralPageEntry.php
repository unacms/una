<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolAcl');

/**
 * Entry create/edit pages
 */
class BxBaseModGeneralPageEntry extends BxTemplPage
{
    protected $MODULE;

    protected $_oModule;
    protected $_aContentInfo = false;

    protected $_sCoverClass;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($aObject, $oTemplate ? $oTemplate : $this->_oModule->_oTemplate);

        $this->_sCoverClass = $this->_oModule->getName() . '_cover';

        $this->addMarkers([
            'module' => $this->MODULE,
        ]);
    }

    public function isActive()
    {
        return true;
    }

    public function getContentInfo()
    {
        return $this->_aContentInfo;
    }
    
    public function getCode ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // check if content exists
        if (!$this->_aContentInfo || !$this->isActive()) { // if entry is not found - display standard "404 page not found" page
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        if(!empty($CNF['FIELD_CF'])) {
            $oCf = BxDolContentFilter::getInstance();
            if($oCf->isEnabled() && !$oCf->isAllowed($this->_aContentInfo[$CNF['FIELD_CF']])) {
                $this->_oTemplate->displayPageNotFound();
                exit;
            }
        }

        $oCover = BxDolCover::getInstance($this->_oModule->_oTemplate);        
        if ($oCover)
            $oCover->setCoverClass($this->_sCoverClass);

        // permissions check 
        $this->_processPermissionsCheck ();

        // count views
        if(!empty($CNF['OBJECT_VIEWS'])) {
            $oView = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
            if($oView && $oView->isEnabled())
                $oView->doView();
        }

        // set cover image
        $mixedCover = method_exists($this, '_getImageForPageCover') ? $this->_getImageForPageCover() : $this->_getThumbForMetaObject();
        if($mixedCover !== false) {
            $aCover = array(
                'id' => $mixedCover['id']
            );

            if(!empty($mixedCover['transcoder']))
                $aCover['transcoder'] = $mixedCover['transcoder'];
            else if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_COVER'])) 
                $aCover['transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_COVER'];
            else if(!empty($mixedCover['object']))
                $aCover['object'] = $mixedCover['object'];

            $oCover->setCoverImageUrl($aCover);
        }

        // add content metatags
        if(!empty($CNF['OBJECT_METATAGS']) && ($o = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])) !== false)
            $o->addPageMetaInfo($this->_aContentInfo[$CNF['FIELD_ID']], $this->_getThumbForMetaObject());

        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if($oMenuSubmenu) {
            // add actions menu to submenu
            if(isset($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']))
                $oMenuSubmenu->setObjectActionsMenu($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);

            // add social sharing menu to submenu
            $oMenuSubmenu->setServiceSocialSharing(array(
                'module' => $this->MODULE,
                'method' => 'entity_social_sharing',
            ));
        }
        
        BxDolTemplate::getInstance()->setPageUrl('page.php?i=' . $this->_aObject['uri'] . '&id=' . $this->_aContentInfo[$CNF['FIELD_ID']]);
        
        return parent::getCode ();
    }

    protected function _processPermissionsCheck ()
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($this->_aContentInfo))) {
            $this->_oTemplate->displayAccessDenied($sMsg);
            exit;
        }
        $this->_oModule->checkAllowedView($this->_aContentInfo, true);
    }

    protected function _getPageMetaDesc()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sResult = '';
        if(isset($CNF['FIELD_TEXT'])) {
            $sResult = strip_tags($this->_oModule->_oTemplate->getText($this->_aContentInfo, false));

            $iLength = 0;
            if(!empty($CNF['PARAM_CHARS_SUMMARY']))
                $iLength = (int)getParam($CNF['PARAM_CHARS_SUMMARY']);
            if(empty($iLength))
                $iLength = 240;

            if(mb_strlen($sResult) > $iLength)
                $sResult = mb_substr($sResult, 0, $iLength);
        }

        if(empty($sResult))
            $sResult = $this->_replaceMarkers(_t($this->_aObject['meta_description']));

        return $sResult;
    }

    protected function _getThumbForMetaObject ()
    {
        return $this->_oModule->getEntryImageData($this->_aContentInfo);
    }

    protected function _getPageCacheParams ()
    {
        $s = parent::_getPageCacheParams ();
        if (!$this->_aContentInfo)
            return $s;
        return $s . $this->_aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ID']]; // cache is different for every entry
    }
}

/** @} */
