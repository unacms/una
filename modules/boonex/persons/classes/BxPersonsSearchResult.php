<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolTwigSearchResult');

class BxPersonsSearchResult extends BxDolTwigSearchResult {

    var $aCurrent = array(
        'name' => 'bx_persons',
        'title' => '_bx_persons_page_title_browse',
        'table' => 'sys_profiles',
        'ownFields' => array(),
        'searchFields' => array('fullname'),
        'restriction' => array(
            'perofileStatus' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
            'perofileType' => array('value' => 'bx_persons', 'field' => 'type', 'operator' => '='),
            'owner' => array('value' => '', 'field' => 'author', 'operator' => '=', 'table' => 'bx_persons_data'),
        ),
        'join' => array (
            'profile' => array(
                'type' => 'INNER',
                'table' => 'bx_persons_data',
                'mainField' => 'content_id',
                'onField' => 'id',
                'joinFields' => array('id', 'fullname', 'picture', 'added'),
            ),
        ),
        'paginate' => array('perPage' => 20, 'start' => 0),
        'sorting' => 'none',
        'rss' => array(
            'title' => '',
            'link' => '',
            'image' => '',
            'profile' => 0,
            'fields' => array (
                'Guid' => 'link',
                'Link' => 'link',
                'Title' => 'fullname',
                'DateTimeUTS' => 'added',
                'Desc' => 'fullname',
                'Picture' => 'picture',
            ),
        ),
        'ident' => 'id'
    );

    function __construct($sMode = '', $mixedParams = false) {

        $oModuleMain = $this->getMain();

        switch ($sMode) {

            case 'connections':
                $aParams = $mixedParams;
                bx_import('BxDolConnection');
                $oConnection = isset($aParams['object']) ? BxDolConnection::getObjectInstance($aParams['object']) : false;
                if ($oConnection && isset($aParams['profile']) && (int)$aParams['profile']) {

                    $sMethod = 'getConnectedContentAsCondition';
                    if (isset($aParams['type']) && $aParams['type'] == 'initiators')
                        $sMethod = 'getConnectedInitiatorsAsCondition';

                    $a = $oConnection->$sMethod('id', (int)$aParams['profile'], isset($aParams['mutual']) ? $aParams['mutual'] : false);
                    $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
                    $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);

                }
                break;

            case 'search':
                $sValue = $mixedParams;
                if ($sValue)
                    $this->aCurrent['restriction']['keyword'] = array('value' => $sValue,'field' => '','operator' => 'against');

                $this->sBrowseUrl = "search/?q=" . $sValue;
                $this->aCurrent['title'] = _t('_bx_persons_page_title_search_results', $sValue);
                unset($this->aCurrent['rss']);
                break;

            case 'user':
                $iProfileId = (int)$mixedParams;
                $aContentInfo = $oModuleMain->_oDb->getContentInfoById($iContentId);
                if (!$aContentInfo)
                    $this->isError = true;
                else
                    $this->aCurrent['restriction']['owner']['value'] = $iProfileId;

                $this->sBrowseUrl = "browse/user/$iProfileId";
                $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_by_author', $aContentInfo['fullname']); // TODO: owner name here
                if (bx_get('rss')) {
                    $aData = getProfileInfo($iProfileId);
                    if ($aData['Avatar']) {
                        if (!$aImage['no_image'])
                            $this->aCurrent['rss']['image'] = $aImage['file'];
                    }
                }
                break;
            
            case 'recent':
            case '':
                $this->sBrowseUrl = 'browse/recent';
                $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_recent');
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

        $this->sFilterName = 'bx_persons_filter';

        parent::__construct();
    }

    function getAlterOrder() {
        switch ($this->aCurrent['sorting']) {
        case 'none':
            return array();
        case 'last':
        default:
            $aSql = array();
            $aSql['order'] = " ORDER BY `bx_persons_data`.`added` DESC";
            return $aSql;
        }
    }

    function displayResultBlock () {        
        $s = parent::displayResultBlock ();
        if ($s) {
            $oModuleMain = $this->getMain();
            BxDolTemplate::getInstance()->addDynamicLocation($oModuleMain->_oConfig->getHomePath(), $oModuleMain->_oConfig->getHomeUrl());
            bx_import('BxTemplFunctions');
            return BxTemplFunctions::getInstance()->centerContent ($s, '.bx-persons-unit');
        }
        return '';
    }

    function getMain() {
        return BxDolModule::getInstance($this->aCurrent['name']);
    }

    function getRssUnitLink (&$a) {
        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-persons-profile&id=' . $a['id']);
    }

    function _getPseud () {
        return array(
            'id' => 'id',
            'fullname' => 'fullname',
            'added' => 'added',
            'author' => 'author',
            'picture' => 'picture',
        );
    }
}

/** @} */

