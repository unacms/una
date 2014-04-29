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

bx_import('BxBaseModProfileSearchResult');

class BxPersonsSearchResult extends BxBaseModProfileSearchResult 
{

    function __construct($sMode = '', $aParams = false) 
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent =  array(
            'name' => 'bx_persons',
            'title' => _t('_bx_persons_page_title_browse'),
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

        $this->sFilterName = 'bx_persons_filter';
        $this->oModule = $this->getMain();

        switch ($sMode) {

            case 'connections':
                bx_import('BxDolConnection');
                $oConnection = isset($aParams['object']) ? BxDolConnection::getObjectInstance($aParams['object']) : false;
                if ($oConnection && isset($aParams['profile']) && (int)$aParams['profile']) {

                    $oProfile = BxDolProfile::getInstance($aParams['profile']);

                    $sMethod = 'getConnectedContentAsCondition';
                    if (isset($aParams['type']) && $aParams['type'] == 'initiators')
                        $sMethod = 'getConnectedInitiatorsAsCondition';

                    if (isset($aParams['type']) && $aParams['type'] == 'common') {

                        $a = $oConnection->getCommonContentAsCondition('id', (int)$aParams['profile'], (int)$aParams['profile2'], isset($aParams['mutual']) ? $aParams['mutual'] : false);
                        $oProfile2 = BxDolProfile::getInstance($aParams['profile2']);
                        if ($oProfile && $oProfile2)
                            $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_connections_mutual', $oProfile->getDisplayName(), $oProfile2->getDisplayName());

                    } else {

                        $a = $oConnection->$sMethod('id', (int)$aParams['profile'], isset($aParams['mutual']) ? $aParams['mutual'] : false);
                        if ($oProfile)
                            $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_connections', $oProfile->getDisplayName());

                    }

                    $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
                    $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);
                    $this->aCurrent['rss']['link'] = 'modules/?r=persons/rss/' . $sMode . '/' . $aParams['object'] . '/' . $aParams['type'] . '/' . (int)$aParams['profile'] . '/' . (int)$aParams['profile2'] . '/' . (int)$aParams['mutual'];

                }
                break;

            case 'search':
                $sValue = isset($aParams['q']) ? $aParams['q'] : false;
                if ($sValue)
                    $this->aCurrent['restriction']['keyword'] = array('value' => $sValue,'field' => '','operator' => 'against');

                $this->sBrowseUrl = "search/?q=" . $sValue;
                $this->aCurrent['title'] = _t('_bx_persons_page_title_search_results', $sValue);
                unset($this->aCurrent['rss']);
                break;

            case 'recent':
            case '':
                $this->aCurrent['rss']['link'] = 'modules/?r=persons/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_recent');
                break;

            default:
                $this->isError = true;
        }

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
            BxDolTemplate::getInstance()->addDynamicLocation($this->oModule->_oConfig->getHomePath(), $this->oModule->_oConfig->getHomeUrl());
            bx_import('BxTemplFunctions');
            return BxTemplFunctions::getInstance()->centerContent ($s, '.bx-persons-unit');
        }
        return '';
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

