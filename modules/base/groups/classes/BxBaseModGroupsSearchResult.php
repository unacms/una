<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModGroupsSearchResult extends BxBaseModProfileSearchResult
{    
    public function __construct($sMode = '', $aParams = array())
    {
        if (!isset($aParams['unit_views'])) {
            if (!is_array($aParams))
                $aParams = [];
            $aParams['unit_views'] = array('gallery' => 'unit.html', 'showcase' => 'unit_with_cover_showcase.html');
        }
        parent::__construct($sMode, $aParams);
    }

    protected function addConditionsForPrivateContent($CNF, $oProfile, $aCustomGroup = array()) 
    {
        if(empty($CNF['OBJECT_PRIVACY_VIEW']))
            return;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        if(!$oPrivacy)
            return;

        parent::addConditionsForPrivateContent($CNF, $oProfile, array_merge($aCustomGroup, $oPrivacy->getPartiallyVisiblePrivacyGroups()));
    }

    protected function addConditionsForAuthorStatus($CNF)
    {
        return;
    }

    protected function addConditionsForCf($CNF)
    {
        if(empty($CNF['FIELD_CF']))
            return;

        $oCf = BxDolContentFilter::getInstance();
        if(!$oCf->isEnabled()) 
            return;

        $aConditions = $oCf->getConditions($this->aCurrent['join']['profile']['table'], $CNF['FIELD_CF']);
        if(!empty($aConditions) && is_array($aConditions))
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aConditions);
    }

    protected function _setAuthorConditions($sMode, $aParams, &$oProfileAuthor)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['author']);
        if (!$oProfileAuthor) 
            return false;

        $iProfileAuthor = $oProfileAuthor->id();
        $this->aCurrent['restriction']['owner']['value'] = $iProfileAuthor;

        if(!empty($aParams['per_page']))
        	$this->aCurrent['paginate']['perPage'] = is_numeric($aParams['per_page']) ? (int)$aParams['per_page'] : (int)getParam($aParams['per_page']);

        $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_JOINED_ENTRIES'] . '&profile_id={profile_id}';
        $this->aCurrent['title'] = _t($CNF['T']['txt_all_entries_by_author']);
        $this->aCurrent['rss']['link'] = 'modules/?r=' . $this->oModule->_oConfig->getUri() . '/rss/' . $sMode . '/' . $iProfileAuthor;

        return true;
    }

    protected function _setFavoriteConditions($sMode, $aParams, &$oProfileAuthor)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['user']);
        if (!$oProfileAuthor) 
            return false;

        $iListId = 0;
        if(isset($aParams['list_id']))
            $iListId = (int)$aParams['list_id'];

        $iProfileAuthor = $oProfileAuthor->id();
        $oFavorite = $this->oModule->getObjectFavorite();
        if(!$oFavorite || (!$oFavorite->isPublic() && $iProfileAuthor != bx_get_logged_profile_id())) 
            return false;

        $aConditions = $oFavorite->getConditionsTrack($CNF['TABLE_ENTRIES'], 'id', $iProfileAuthor, $iListId);
        if(!empty($aConditions) && is_array($aConditions)) {
            if(empty($this->aCurrent['restriction']) || !is_array($this->aCurrent['restriction']))
                $this->aCurrent['restriction'] = array();
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aConditions['restriction']);

            if(empty($this->aCurrent['join']) || !is_array($this->aCurrent['join']))
                $this->aCurrent['join'] = array();
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aConditions['join']);
        }

        $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_JOINED_ENTRIES'] . '&profile_id={profile_id}';
        $this->aCurrent['title'] = _t($CNF['T']['txt_all_entries_by_author']);
        $this->aCurrent['rss']['link'] = 'modules/?r=' . $this->oModule->_oConfig->getUri() . '/rss/' . $sMode . '/' . $iProfileAuthor;

        return true;
    }

    protected function _updateCurrentForContext($sMode, $aParams, &$oProfileContext)
    {
        $CNF = &$this->oModule->_oConfig->CNF;
        
        $oProfileContext = BxDolProfile::getInstance((int)$aParams['context']);
        if (!$oProfileContext) 
            return false;

        $iProfileIdContext = $oProfileContext->id();
        $this->aCurrent['restriction']['context'] = array(
            'value' => -$iProfileIdContext,
            'field' => $CNF['FIELD_ALLOW_VIEW_TO'],
            'operator' => '=',
            'table' => $CNF['TABLE_ENTRIES']
        );

        if(!empty($aParams['per_page']))
            $this->aCurrent['paginate']['perPage'] = is_numeric($aParams['per_page']) ? (int)$aParams['per_page'] : (int)getParam($aParams['per_page']);

        $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_ENTRIES_BY_CONTEXT'] . '&profile_id={profile_id}';
        $this->aCurrent['title'] = _t($CNF['T']['txt_all_entries_by_context']);
        $this->aCurrent['rss']['link'] = 'modules/?r=' . $this->oModule->_oConfig->getUri() . '/rss/' . $sMode . '/' . $iProfileIdContext;

        return true;
    }
}

/** @} */
