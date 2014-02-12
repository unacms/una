<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplSearchResult');

class BxNotesSearchResult extends BxTemplSearchResult 
{
    protected $aUnitViews = array('extended' => 'unit.html', 'gallery' => 'unit_gallery.html');
    protected $sUnitViewDefault = 'gallery';
    protected $sUnitViewParamName = 'unit_view';

    function __construct($sMode = '', $aParams = array()) 
    {
        $this->aCurrent = array(
            'name' => 'bx_notes',
            'title' => '_bx_notes_page_title_browse',
            'table' => 'bx_notes_posts',
            'ownFields' => array('id', 'title', 'text', 'summary', 'thumb', 'author', 'added'),
            'searchFields' => array('title', 'text'),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
            ),
            'paginate' => array('perPage' => getParam('bx_notes_per_page_browse'), 'start' => 0),
            'sorting' => 'last',
            'rss' => array(
                'title' => '',
                'link' => '',
                'image' => '',
                'profile' => 0,
                'fields' => array (
                    'Guid' => 'link',
                    'Link' => 'link',
                    'Title' => 'title',
                    'DateTimeUTS' => 'added',
                    'Desc' => 'text',
                ),
            ),
            'ident' => 'id',
        );

        $this->aGetParams = array($this->sUnitViewParamName);
        $this->sUnitTemplate = $this->aUnitViews[$this->sUnitViewDefault];
        if (isset($this->aUnitViews[bx_get($this->sUnitViewParamName)]))
            $this->sUnitTemplate = $this->aUnitViews[bx_get($this->sUnitViewParamName)];

        $oProfileAuthor = null;

        $oModuleMain = $this->getMain();

        switch ($sMode) {

            case 'search':
                if ($sValue)
                    $this->aCurrent['restriction']['keyword'] = array('value' => $sValue,'field' => '','operator' => 'against');

                $this->sBrowseUrl = "search/?q=" . $sValue;
                $this->aCurrent['title'] = _t('_bx_notes_page_title_search_results', $sValue);
                unset($this->aCurrent['rss']);
                break;

            case 'author':
                bx_import('BxDolProfile');                
                $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['author']);
                if (!$oProfileAuthor) {
                    $this->isError = true;
                    break;
                }

                $this->aCurrent['restriction']['author']['value'] = $oProfileAuthor->id();

                $this->sBrowseUrl = 'page.php?i=notes-author&profile_id={profile_id}';
                $this->aCurrent['title'] = _t('_bx_notes_page_title_browse_by_author');               
                break;
            
            case 'public':
            case '':
                $this->sBrowseUrl = 'page.php?i=notes-home';
                $this->aCurrent['title'] = _t('_bx_notes_page_title_browse_recent');
                break;

            default:
                $this->isError = true;
        }

        // add replacable markers and replace them
        if ($oProfileAuthor) {
            $this->addMarkers($oProfileAuthor->getInfo()); // profile info is replacable
            $this->addMarkers(array('profile_id' => $oProfileAuthor->id())); // profile id is replacable
            $this->addMarkers(array('display_name' => $oProfileAuthor->getDisplayName())); // profile display name is replacable
        }

        $this->sBrowseUrl = $this->_replaceMarkers($this->sBrowseUrl);
        $this->aCurrent['title'] = $this->_replaceMarkers($this->aCurrent['title']);

        // add conditions for private content
        bx_import('BxDolPrivacy');
        $oPrivacy = BxDolPrivacy::getObjectInstance(BxNotesConfig::$OBJECT_PRIVACY_VIEW);
        $a = $oPrivacy ? $oPrivacy->getContentPublicAsCondition($oProfileAuthor ? $oProfileAuthor->id() : 0) : array();
        if (isset($a['restriction']))
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
        if (isset($a['join']))
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);

        $this->setProcessPrivateContent(false);

        // set rss links if required
        if (isset($this->aCurrent['rss']))
            $this->aCurrent['rss']['link'] = $this->sBrowseUrl;

        if (bx_get('rss'))
            $this->aCurrent['paginate']['perPage'] = getParam('bx_notes_rss_num');

        $this->sFilterName = 'bx_notes_filter';

        // $this->aCurrent['paginate']['perPage'] = $oModuleMain->_oDb->getParam('bx_groups_perpage_browse'); // TODO:

        parent::__construct();
    }

    function getMain() 
    {
        return BxDolModule::getInstance($this->aCurrent['name']);
    }

    function displayResultBlock () 
    {
        $s = parent::displayResultBlock ();
        $s = '<div class="bx-notes-wrapper ' . ('unit_gallery.html' == $this->sUnitTemplate ? 'bx-def-margin-neg bx-clearfix' : '') . '">' . $s . '</div>';
        return $s;
    }

    function getDesignBoxMenu () 
    {
        $aMenu = parent::getDesignBoxMenu ();

        return array_merge(
            array(
                array('name' => 'gallery', 'title' => _t('_sys_menu_title_gallery'), 'link' => $this->getCurrentUrl(array($this->sUnitViewParamName => 'gallery')), 'icon' => 'th'),
                array('name' => 'extended', 'title' => _t('_sys_menu_title_extended'), 'link' => $this->getCurrentUrl(array($this->sUnitViewParamName => 'extended')), 'icon' => 'list'),
            ),
            $aMenu
        );
    }

    function getAlterOrder() 
    {
        if ($this->aCurrent['sorting'] == 'last') {
            $aSql = array();
            $aSql['order'] = " ORDER BY `bx_notes_posts`.`added` DESC";
            return $aSql;
        }
        return array();
    }

    function getRssUnitLink (&$a) 
    {
        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-note&id=' . $a['id']);
    }

    function _getPseud () 
    {
        return array(
            'id' => 'id',
            'title' => 'title',
            'text' => 'text',
            'added' => 'added',
            'author' => 'author',
            'photo' => 'photo',
        );
    }
}

/** @} */

