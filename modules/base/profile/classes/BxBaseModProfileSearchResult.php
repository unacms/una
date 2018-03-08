<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileSearchResult extends BxBaseModGeneralSearchResult
{      
    protected $bRecommendedView = false;
    
    public function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);
        $this->aUnitViews = array('gallery' => 'unit_with_cover.html', 'showcase' => 'unit_with_cover_showcase.html');
        if (!empty($aParams['unit_views']))
            $this->aUnitViews = $aParams['unit_views'];
        if (!empty($aParams['unit_view']))
            $this->sUnitViewDefault = $aParams['unit_view'];
        
        $this->sUnitTemplate = $this->aUnitViews[$this->sUnitViewDefault];
        $this->sCenterContentUnitSelector = '.bx-base-pofile-unit';
        $this->addContainerClass (array('bx-def-margin-sec-lefttopright-neg', 'bx-base-pofile-units-wrapper', 'bx-def-margin-sec-bottom-neg'));
		if ('unit_with_cover_showcase.html' == $this->sUnitTemplate){
			$this->bShowcaseView = true;
			$this->removeContainerClass ('bx-def-margin-bottom-neg');			
        }
        if ($sMode == 'recommended')
            $this->bRecommendedView=true;
    }

    protected function _setConnectionsConditions ($aParams)
    {
        $oConnection = isset($aParams['object']) ? BxDolConnection::getObjectInstance($aParams['object']) : false;
        if (!$oConnection || !isset($aParams['profile']) || !(int)$aParams['profile'])
            return false;

        $sContentType = isset($aParams['type']) ? $aParams['type'] : BX_CONNECTIONS_CONTENT_TYPE_CONTENT;
        $isMutual = isset($aParams['mutual']) ? $aParams['mutual'] : false;
        $a = $oConnection->getConnectionsAsCondition ($sContentType, 'id', (int)$aParams['profile'], (int)$aParams['profile2'], $isMutual);

        $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
        $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);

        return true;
    }
    
    protected function _setConditionsForRecommended ()
    {
        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions'); 
        $a = $oConnection->getConnectedContentAsSQLPartsExt ($this->aCurrent['table'], '', '');
        
        $aTmp = array(
            'recommended' => array(
            'type' => 'LEFT',
            'table' =>  $a['join']['table'],
            'mainField' => 'id` AND ' . bx_get_logged_profile_id() . ' = `' . $a['join']['table'] . '`.`initiator',
            'onField' => 'content',
            'joinFields' => array(),
            )
        );
        $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aTmp);
        
        if (isset($this->aCurrent['restriction_sql'])) 
            $this->aCurrent['restriction_sql'] .= '  AND `' . $a['join']['table'] . '`.`initiator` IS NULL';
        else
            $this->aCurrent['restriction_sql'] = '  AND `' . $a['join']['table'] . '`.`initiator` IS NULL';
    }

	protected function _setAclConditions ($aParams)
    {
        $oAcl =  BxDolAcl::getInstance();
        if(!$oAcl || empty($aParams['level']))
            return false;

		if(!is_array($aParams['level']))
			$aParams['level'] = array($aParams['level']);

		$this->aCurrent['title'] = array();
		foreach($aParams['level'] as $iLevelId) {
			$aInfo = $oAcl->getMembershipInfo($iLevelId);
			if(empty($aInfo) || !is_array($aInfo))
				continue;

			$this->aCurrent['title'][] = _t($aInfo['name']);
		}

        $aCondition = $oAcl->getContentByLevelAsCondition('id', $aParams['level']);        
        $this->aCurrent['restriction_sql'] = (!empty($this->aCurrent['restriction_sql']) ? $this->aCurrent['restriction_sql'] : '') . $aCondition['restriction_sql'];
        $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aCondition['restriction']);
        $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aCondition['join']);

        return true;
    }

    protected function _setFavoriteConditions($sMode, $aParams, &$oProfileAuthor)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['user']);
        if(!$oProfileAuthor) 
            return false;

        $iProfileAuthor = $oProfileAuthor->id();
        $oFavorite = $this->oModule->getObjectFavorite();
        if(!$oFavorite->isPublic() && $iProfileAuthor != bx_get_logged_profile_id()) 
            return false;

        $aConditions = $oFavorite->getConditionsTrack($CNF['TABLE_ENTRIES'], 'id', $iProfileAuthor);
        if(!empty($aConditions) && is_array($aConditions)) {
            if(empty($this->aCurrent['restriction']) || !is_array($this->aCurrent['restriction']))
                $this->aCurrent['restriction'] = array();
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aConditions['restriction']);

            if(empty($this->aCurrent['join']) || !is_array($this->aCurrent['join']))
                $this->aCurrent['join'] = array();
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aConditions['join']);
        }

        $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_VIEW_FAVORITES'] . '&profile_id={profile_id}';
        $this->aCurrent['title'] = _t($CNF['T']['txt_browse_favorites']);
        $this->aCurrent['rss']['link'] = 'modules/?r=' . $this->oModule->_oConfig->getUri() . '/rss/' . $sMode . '/' . $iProfileAuthor;

        return true;
    }
	
	function getItemPerPageInShowCase ()
    {
        $iPerPageInShowCase = parent::getItemPerPageInShowCase();
        $CNF = &$this->oModule->_oConfig->CNF;
		if ($this->bRecommendedView && isset($CNF['PARAM_PER_PAGE_BROWSE_RECOMMENDED'])){
			$iPerPageInShowCase =  getParam($CNF['PARAM_PER_PAGE_BROWSE_RECOMMENDED']);
        }
        return $iPerPageInShowCase;
    }
    
    function displayResultBlock ()
    {
		$this->oModule->_oTemplate->addJs(array('modules/base/profile/js/|searchresult.js'));
		return parent::displayResultBlock ();
	}
}

/** @} */
