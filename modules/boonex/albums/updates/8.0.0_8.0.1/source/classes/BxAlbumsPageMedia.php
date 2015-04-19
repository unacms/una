<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAcl');

/**
 * Entry create/edit pages
 */
class BxAlbumsPageMedia extends BxTemplPage
{
    protected $MODULE;
    protected $_oModule;
    protected $_aAlbumInfo = false;
    protected $_aMediaInfo = false;
    protected $_mixedContext = false;

    public function __construct($aObject, $oTemplate = false)
    {        
        parent::__construct($aObject, $oTemplate);
        $this->MODULE = 'bx_albums';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iMediaId)
            $this->_aMediaInfo = $this->_oModule->_oDb->getMediaInfoById($iMediaId);

        if ($this->_aMediaInfo)
            $this->_aAlbumInfo = $this->_oModule->_oDb->getContentInfoById($this->_aMediaInfo['content_id']);

        if ($this->_aAlbumInfo) {
            $this->addMarkers(array_merge($this->_aAlbumInfo, $this->_aMediaInfo)); // every field can be used as marker
            $this->addMarkers(array(
                'title' => !empty($this->_aMediaInfo['title']) ? $this->_aMediaInfo['title'] : _t('_bx_albums_txt_media_title_alt', $this->_aAlbumInfo[$CNF['FIELD_TITLE']]),
            ));

            $sTitle = isset($this->_aAlbumInfo[$CNF['FIELD_TITLE']]) ? $this->_aAlbumInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($this->_aAlbumInfo[$CNF['FIELD_TEXT']], 20, '...');
            $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $this->_aAlbumInfo[$CNF['FIELD_ID']]);

            // select view entry submenu
            $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
            $oMenuSubmenu->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'], array (
                'title' => $sTitle,
                'link' => $sUrl,
                'icon' => $CNF['ICON'],
            ));
        }
    }

    public function getCode ()
    {
        // check if content exists
        if (!$this->_aAlbumInfo || !$this->_aMediaInfo) { // if entry is not found - display standard "404 page not found" page
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        // permissions check 
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($this->_aAlbumInfo))) {
            $this->_oTemplate->displayAccessDenied($sMsg);
            exit;
        }
        $this->_oModule->checkAllowedView($this->_aAlbumInfo, true);

        // count views
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (!empty($CNF['OBJECT_VIEWS_MEDIA']))
            BxDolView::getObjectInstance($CNF['OBJECT_VIEWS_MEDIA'], $this->_aMediaInfo['id'])->doView();

        // add content metatags
        if (!empty($CNF['OBJECT_METATAGS_MEDIA'])) {
            $o = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA']);
            if ($o) {
                $aThumb = false;
                if (!empty($this->_aMediaInfo['file_id']) && !empty($CNF['OBJECT_IMAGES_TRANSCODER_BIG']))
                    $aThumb = array('id' => $this->_aMediaInfo['file_id'], 'transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_BIG']);
                $o->metaAdd($this->_aMediaInfo['id'], $aThumb);
            }
        }

        $aVars = array();
        $this->_oTemplate->addInjection ('injection_footer', 'text', $this->_oModule->_oTemplate->parseHtmlByName('photoswipe.html', $aVars));

        return parent::getCode ();
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oModule->_oTemplate->addCss(array(
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'photo-swipe/|photoswipe.css',
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'photo-swipe/default-skin/|default-skin.css',
        ));

        $this->_oModule->_oTemplate->addJs(array(
            'media_view.js',
            'history.js',
            'photo-swipe/photoswipe.min.js',
            'photo-swipe/photoswipe-ui-default.min.js',
        ));
    }
}

/** @} */
