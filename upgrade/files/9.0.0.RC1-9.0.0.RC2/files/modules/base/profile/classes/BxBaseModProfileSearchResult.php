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
    public function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);
        $this->sCenterContentUnitSelector = '.bx-base-pofile-unit';

        $this->addContainerClass (array('bx-def-margin-sec-lefttopright-neg', 'bx-base-pofile-units-wrapper'));
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
}

/** @} */
