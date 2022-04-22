<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModTextSearchResult extends BxBaseModGeneralSearchResult
{
    protected $sUnitViewParamName = 'unit_view';

    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);
        $this->aUnitViews = array('extended' => 'unit.html', 'gallery' => 'unit_gallery.html', 'full' => 'unit_full.html', 'showcase' => 'unit_showcase.html');
		if (!empty($aParams['unit_views']))
            $this->aUnitViews = $aParams['unit_views'];
        if (!empty($aParams['unit_view']))
            $this->sUnitViewDefault = $aParams['unit_view'];
        $this->aGetParams = array($this->sUnitViewParamName);
        $this->sUnitTemplate = $this->aUnitViews[$this->sUnitViewDefault];
        if (isset($this->aUnitViews[bx_get($this->sUnitViewParamName)]))
            $this->sUnitTemplate = $this->aUnitViews[bx_get($this->sUnitViewParamName)];
		$this->addContainerClass ('bx-def-margin-bottom-neg');
        if ('unit_gallery.html' == $this->sUnitTemplate){
			$this->removeContainerClass ('bx-def-margin-bottom-neg');		
            $this->addContainerClass (array('bx-base-text-unit-gallery-wrapper', 'bx-def-margin-sec-neg'));
		}
		if ('unit_showcase.html' == $this->sUnitTemplate){
			$this->bShowcaseView = true;
		}
    }

    protected function checkRestrictionsForContext($sMode, $aParams, $oProfileAuthor){
        if(bx_get('context_id')){
            $aParams['context'] = bx_get('context_id');
            $oProfileAuthor = null;
            $this->_updateCurrentForContext($sMode, $aParams, $oProfileAuthor);
        }
    }
    
    function getAlterOrder()
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $sTable = $this->aCurrent['table'];

        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'last':
                $aSql['order'] = ' ORDER BY `' . $sTable . '`.`' . $CNF['FIELD_ADDED'] . '` DESC';
                break;

            case 'updated':
                $aSql['order'] = ' ORDER BY `' . $sTable . '`.`' . $CNF['FIELD_CHANGED'] . '` DESC';
                break;

            case 'featured':
                $aSql['order'] = ' ORDER BY `' . $sTable . '`.`featured` DESC';
                break;

            case 'popular':
                $aSql['order'] = ' ORDER BY `' . $sTable . '`.`' . $CNF['FIELD_VIEWS'] . '` DESC';
                break;

            case 'top':
                $aSql['order'] = '';

                $aPartsUp = $aPartsDown = array(0);
                if(!empty($CNF['OBJECT_VOTES']) && ($oVote = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], 0, false)) !== false && $oVote->isEnabled()) {
                    $aVote = $oVote->getSystemInfo();
                    if(!empty($aVote['trigger_table']) && !empty($aVote['trigger_field_count']))
                        $aPartsUp[] = '`' . $aVote['trigger_table'] . '`.`' . $aVote['trigger_field_count'] . '`';
                }

                if(!empty($CNF['OBJECT_REACTIONS']) && ($oReaction = BxDolVote::getObjectInstance($CNF['OBJECT_REACTIONS'], 0, false)) !== false && $oReaction->isEnabled()) {
                    $aReaction = $oReaction->getSystemInfo();
                    if(!empty($aReaction['trigger_table']) && !empty($aReaction['trigger_field_count']))
                        $aPartsUp[] = '`' . $aReaction['trigger_table'] . '`.`' . $aReaction['trigger_field_count'] . '`';
                }

                if(!empty($CNF['OBJECT_SCORES']) && ($oScore = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], 0, false)) !== false && $oScore->isEnabled()) {
                    $aScore = $oScore->getSystemInfo();
                    if(!empty($aScore['trigger_table']) && !empty($aScore['trigger_field_cup']) && !empty($aScore['trigger_field_cdown'])) {
                        $aPartsUp[] = '`' . $aScore['trigger_table'] . '`.`' . $aScore['trigger_field_cup'] . '`';
                        $aPartsDown[] = '`' . $aScore['trigger_table'] . '`.`' . $aScore['trigger_field_cdown'] . '`';
                    }
                }

                if(empty($aPartsUp) && empty($aPartsDown))
                    break;

                $aSql['order'] = ' ORDER BY ' . pow(10, 8) . ' * ((' . implode(' + ', $aPartsUp) . ') - (' . implode(' + ', $aPartsDown) . ')) / (UNIX_TIMESTAMP() - `' . $sTable . '`.`' . $CNF['FIELD_ADDED'] . '`) DESC';
                break;
        }

        return $aSql;
    }

    function getRssUnitImage (&$a, $sField)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        if(empty($sField) || empty($a[$sField]) || empty($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']))
            return '';

        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']);
        if(!$oTranscoder)
            return '';

        return $oTranscoder->getFileUrl($a[$sField]);      
    }

    protected function processReplaceableMarkers($oProfileAuthor) 
    {
        if ($oProfileAuthor) {
            $this->addMarkers($oProfileAuthor->getInfo()); // profile info is replaceable
            $this->addMarkers(array('profile_id' => $oProfileAuthor->id())); // profile id is replaceable
            $this->addMarkers(array('display_name' => $oProfileAuthor->getDisplayName())); // profile display name is replaceable
        }

        $this->sBrowseUrl = $this->_replaceMarkers($this->sBrowseUrl);
        $this->aCurrent['title'] = $this->_replaceMarkers($this->aCurrent['title']);
    }

    protected function getCurrentOnclick($aAdditionalParams = array(), $bReplacePagesParams = true) 
    {
        // always add UnitView as additional param
        $sUnitView = bx_process_input(bx_get($this->sUnitViewParamName));
        if ($sUnitView && isset($this->aUnitViews[$sUnitView]))
            $aAdditionalParams = array_merge(array($this->sUnitViewParamName => $sUnitView), $aAdditionalParams);

        return parent::getCurrentOnclick($aAdditionalParams, $bReplacePagesParams);
    }

    protected function _updateCurrentForAuthor($sMode, $aParams, &$oProfileAuthor)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['author']);
        if (!$oProfileAuthor) 
            return false;

        $iProfileAuthor = $oProfileAuthor->id();
        if (bx_get_logged_profile_id() == $iProfileAuthor || $this->oModule->_isModerator()) {
            // for real owner and for moderators show anonymous posts
            $this->aCurrent['restriction']['author']['operator'] = 'in';
            $this->aCurrent['restriction']['author']['value'] = array($iProfileAuthor, -$iProfileAuthor);
        } 
        else {
           $this->aCurrent['restriction']['author']['value'] = $iProfileAuthor;
        }

        if(!empty($aParams['except']))
        	$this->aCurrent['restriction']['except']['value'] = is_array($aParams['except']) ? $aParams['except'] : array($aParams['except']); 

        if(!empty($aParams['per_page']))
        	$this->aCurrent['paginate']['perPage'] = is_numeric($aParams['per_page']) ? (int)$aParams['per_page'] : (int)getParam($aParams['per_page']);

        $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id={profile_id}';
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
        );

        if(!empty($aParams['per_page']))
        	$this->aCurrent['paginate']['perPage'] = is_numeric($aParams['per_page']) ? (int)$aParams['per_page'] : (int)getParam($aParams['per_page']);

        $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_ENTRIES_BY_CONTEXT'] . '&profile_id={profile_id}';
        $this->aCurrent['title'] = _t($CNF['T']['txt_all_entries_by_context']);
        $this->aCurrent['rss']['link'] = 'modules/?r=' . $this->oModule->_oConfig->getUri() . '/rss/' . $sMode . '/' . $iProfileIdContext;

        return true;
    }
    
    protected function _updateCurrentForFavorite($sMode, $aParams, &$oProfileAuthor)
    {
        $CNF = &$this->oModule->_oConfig->CNF;
        
        $sSystem = '';
        $iListId = 0;
        
        if(!empty($aParams['system'])) {
            $sSystem = $aParams['system'];
            unset($aParams['system']);
        }
        
        if(!empty($aParams['list_id'])) {
            $iListId = $aParams['list_id'];
            unset($aParams['list_id']);
        }

        $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['user']);
        if(!$oProfileAuthor) 
            return false;

        $iProfileAuthor = $oProfileAuthor->id();
        $oFavorite = $this->oModule->getObjectFavorite($sSystem);
        if(!$oFavorite->isPublic() && $iProfileAuthor != bx_get_logged_profile_id()) 
            return false;

        $aConditions = $oFavorite->getConditionsTrack($this->aCurrent['table'], 'id', $iProfileAuthor, $iListId);
        if(!empty($aConditions) && is_array($aConditions)) {
            if(empty($this->aCurrent['restriction']) || !is_array($this->aCurrent['restriction']))
                $this->aCurrent['restriction'] = array();
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aConditions['restriction']);

            if(empty($this->aCurrent['join']) || !is_array($this->aCurrent['join']))
                $this->aCurrent['join'] = array();
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aConditions['join']);
        }
        
        $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id={profile_id}';
        $this->aCurrent['title'] = _t($CNF['T']['txt_all_entries_by_author']);
        $this->aCurrent['rss']['link'] = 'modules/?r=' . $this->oModule->_oConfig->getUri() . '/rss/' . $sMode . '/' . $iProfileAuthor;

        return true;
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
