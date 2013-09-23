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

class BxNotesSearchResult extends BxTemplSearchResult {

    var $aCurrent = array(
        'name' => 'bx_notes',
        'title' => '_bx_notes_page_title_browse',
        'table' => 'bx_notes_posts',
        'ownFields' => array('id', 'title', 'text', 'summary', 'thumb', 'author', 'added'),
        'searchFields' => array('title', 'text'),
        'restriction' => array(
            'owner' => array('value' => '', 'field' => 'author', 'operator' => '='),
        ),
        'paginate' => array('perPage' => 8, 'start' => 0),
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
        'ident' => 'id'
    );


    function __construct($sMode = '', $sValue = '', $sValue2 = '', $sValue3 = '') {

        $oModuleMain = $this->getMain();

        switch ($sMode) {

            case 'search':
                if ($sValue)
                    $this->aCurrent['restriction']['keyword'] = array('value' => $sValue,'field' => '','operator' => 'against');

                $this->sBrowseUrl = "search/?q=" . $sValue;
                $this->aCurrent['title'] = _t('_bx_notes_page_title_search_results', $sValue);
                unset($this->aCurrent['rss']);
                break;

            case 'user':
                $iProfileId = (int)$sValue;                
                $aContentInfo = $oModuleMain->_oDb->getContentInfoById($iContentId);
                if (!$aContentInfo)
                    $this->isError = true;
                else
                    $this->aCurrent['restriction']['owner']['value'] = $iProfileId;

                $this->sBrowseUrl = "browse/user/$sValue";
                $this->aCurrent['title'] = _t('_bx_notes_page_title_browse_by_author', $aContentInfo['fullname']); // TODO: owner name here
                if (bx_get('rss')) {
                    $aData = getProfileInfo($iProfileId);
                    if ($aData['Avatar']) {
                        $a = array ('ID' => $aData['author_id'], 'Avatar' => $aData['thumb']);
                        $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                        if (!$aImage['no_image'])
                            $this->aCurrent['rss']['image'] = $aImage['file'];
                    }
                }
                break;
            
            case 'recent':
            case '':
                $this->sBrowseUrl = 'browse/recent';
                $this->aCurrent['title'] = _t('_bx_notes_page_title_browse_recent');
                break;

            default:
                $this->isError = true;
        }

        // $this->aCurrent['paginate']['perPage'] = $oModuleMain->_oDb->getParam('bx_groups_perpage_browse'); // TODO:

        if (isset($this->aCurrent['rss']))
            $this->aCurrent['rss']['link'] = BX_DOL_URL_ROOT . $oModuleMain->_oConfig->getBaseUri() . $this->sBrowseUrl;

        if (bx_get('rss')) {
            $this->aCurrent['paginate']['perPage'] = 10;//$oModuleMain->_oDb->getParam('bx_groups_max_rss_num');
        }

        $this->sFilterName = 'bx_notes_filter';

        parent::__construct();
    }

    function displayResultBlock () {
        $s = parent::displayResultBlock ();
        $s = '<div class="bx-notes-wrapper">' . $s . '</div>';
        return $s;
    }

    function getAlterOrder() {
        if ($this->aCurrent['sorting'] == 'last') {
            $aSql = array();
            $aSql['order'] = " ORDER BY `bx_notes_posts`.`added` DESC";
            return $aSql;
        }
        return array();
    }

    function getMain() {
        return BxDolModule::getInstance($this->aCurrent['name']);
    }

    function getRssUnitLink (&$a) {
        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-note&id=' . $a['id']);
    }

    function _getPseud () {
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

