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

bx_import('BxBaseModTextSearchResult');

class BxNotesSearchResult extends BxBaseModTextSearchResult 
{
    function __construct($sMode = '', $aParams = array()) 
    {
        parent::__construct($sMode, $aParams);

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

        $this->sFilterName = 'bx_notes_filter';

        $oProfileAuthor = null;

        $CNF = &$this->oModule->_oConfig->CNF;

        switch ($sMode) {

            case 'search':
                if ($sValue)
                    $this->aCurrent['restriction']['keyword'] = array('value' => $sValue,'field' => '','operator' => 'against');

                $this->sBrowseUrl = "search/?q=" . $sValue;
                $this->aCurrent['title'] = _t('_bx_notes_page_title_search_results', $sValue);
                unset($this->aCurrent['rss']); // no RSS for search results
                break;

            case 'author':
                bx_import('BxDolProfile');                
                $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['author']);
                if (!$oProfileAuthor) {
                    $this->isError = true;
                    break;
                }

                $this->aCurrent['restriction']['author']['value'] = $oProfileAuthor->id();

                $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id={profile_id}';
                $this->aCurrent['title'] = _t('_bx_notes_page_title_browse_by_author');
                $this->aCurrent['rss']['link'] = 'modules/?r=notes/rss/' . $sMode . '/' . $oProfileAuthor->id();
                break;
            
            case 'public':
            case '':
                $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_HOME'];
                $this->aCurrent['title'] = _t('_bx_notes_page_title_browse_recent');
                $this->aCurrent['rss']['link'] = 'modules/?r=notes/rss/' . $sMode;
                break;

            default:
                $sMode = '';
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
        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        $a = $oPrivacy ? $oPrivacy->getContentPublicAsCondition($oProfileAuthor ? $oProfileAuthor->id() : 0) : array();
        if (isset($a['restriction']))
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
        if (isset($a['join']))
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);

        $this->setProcessPrivateContent(false);
    }

    function displayResultBlock () 
    {
        $s = parent::displayResultBlock ();
        $s = '<div class="bx-notes-wrapper ' . ('unit_gallery.html' == $this->sUnitTemplate ? 'bx-def-margin-neg bx-clearfix' : '') . '">' . $s . '</div>';
        return $s;
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
}

/** @} */

