<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_SYS_PER_PAGE_BROWSE_SHOWCASE', 32);

class BxBaseModGeneralSearchResult extends BxTemplSearchResult
{
    protected $oModule;
    protected $sFilterName;
    protected $bShowcaseView = false;
    protected $aUnitViews = array();
    protected $sUnitViewDefault = 'gallery';

    function __construct($sMode = '', $aParams = array())
    {
        $this->_sMode = $sMode;
        $this->_aParams = $aParams;

        parent::__construct();
    }

    function getMain()
    {
        if(!$this->oModule)
            $this->oModule = BxDolModule::getInstance($this->getModuleName());

        return $this->oModule;
    }

    function getContentInfoObject()
    {
        return BxDolContentInfo::getObjectInstance($this->getContentInfoName());
    }

    function getRssUnitLink (&$a)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        return bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $a[$CNF['FIELD_ID']]));
    }

    function getRssPageUrl ()
    {
        if (false === parent::getRssPageUrl())
            return false;

        $oPermalinks = BxDolPermalinks::getInstance();
        return bx_absolute_url($oPermalinks->permalink($this->aCurrent['rss']['link']));
    }

    function rss ()
    {
        if (!isset($this->aCurrent['rss']))
            return '';

        $this->aCurrent['paginate']['perPage'] = empty($this->oModule->_oConfig->CNF['PARAM_NUM_RSS']) ? 10 : getParam($this->oModule->_oConfig->CNF['PARAM_NUM_RSS']);

        return parent::rss();
    }

    function processingAPI () 
    {
        $aResult = parent::processingAPI();

        if(isset($this->_aParams['filters']) && is_array($this->_aParams['filters'])) {
            $oModule = $this->getMain();

            $aResult['params']['filters'] = $oModule->_oTemplate->getBrowsingFilters(['mode' => $this->_sMode]);
        }

        return $aResult;
    }

    protected function addCustomConditions($CNF, $oProfile, $sMode, $aParams)
    {
        $this->addConditionsForAuthorStatus($CNF);

        $this->addConditionsForCf($CNF);

        if(!empty($aParams['filter']) && is_array($aParams['filter']))
            $this->addConditionsForFilter($CNF, $sMode, $aParams);
    }

    protected function addConditionsForAuthorStatus($CNF)
    {
        if (empty($CNF['FIELD_AUTHOR']))
            return;

        $this->aCurrent['restriction']['statusAuthor'] = [
            'value' => 'active',
            'field' => 'status',
            'operator' => '=',
            'table' => 'sys_profiles',
        ];

        $this->aCurrent['join']['statusAuthor'] = [
            'type' => 'INNER',
            'table' => 'sys_profiles',
            'mainField' => $CNF['FIELD_AUTHOR'],
            'mainFieldFunc' => 'ABS',
            'onField' => 'id',
            'joinFields' => array(),
        ];
    }

    protected function addConditionsForCf($CNF)
    {
        if(empty($CNF['FIELD_CF']))
            return;

        $oCf = BxDolContentFilter::getInstance();
        if(!$oCf->isEnabled()) 
            return;

        $aConditions = $oCf->getConditions($this->aCurrent['table'], $CNF['FIELD_CF']);
        if(!empty($aConditions) && is_array($aConditions))
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aConditions);
    }
    
    protected function addConditionsForFilter($CNF, $sMode, $aParams)
    {
        $aFilter = $aParams['filter'];
        
        if(empty($aFilter['field']) || empty($aFilter['value']))
            return;
        
        $aRestriction = [
            'value' => $aFilter['value'],
            'field' => $aFilter['field'],
            'operator' => '=',
        ];

        if(isset($aFilter['operator']))
            $aRestriction['operator'] = $aFilter['operator'];

        if(isset($aFilter['table']))
            switch($aFilter['table']) {
                case 'table':
                    $aRestriction['table'] = $this->aCurrent['table'];
                    break;

                case 'tableSearch':
                    $aRestriction['table'] = $this->aCurrent['tableSearch'];
                    break;

                default: 
                    $aRestriction['table'] = $aFilter['table'];
            }

        $this->aCurrent['restriction']['filter'] = $aRestriction;
    }

    /**
     * Add conditions for private content
     */
    protected function addConditionsForPrivateContent($CNF, $oProfile, $aCustomGroup = array()) 
    {
        // default is bProcessPrivateContent = 1, 
        // so private items are shown as empty boxes with "Private" title

        // we can show public content when privacy object is available
        
        if(empty($CNF['OBJECT_PRIVACY_VIEW']))
            return;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        if(!$oPrivacy)
            return;

        // for posts in some context we need to show all items, not only public content
        if (!empty($this->aCurrent['restriction']['context']['value']) || !empty($this->aCurrent['restriction']['author']['value'])) {
            $this->setProcessPrivateContent(true);
            return;
        }

        // build condition to show only public content
        $aCondition = $oPrivacy->getContentPublicAsCondition($oProfile ? $oProfile->id() : 0, $aCustomGroup);
        if(empty($aCondition) || !is_array($aCondition))
            return;

        if(isset($aCondition['restriction'])) {
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aCondition['restriction']);
            $this->aPrivateConditionsIndexes['restriction'] = array_keys($aCondition['restriction']);
        }
        if(isset($aCondition['join'])) {
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aCondition['join']);
            $this->aPrivateConditionsIndexes['join'] = array_keys($aCondition['join']);
        }

        $this->setProcessPrivateContent(false);
    }

    function showPagination($bAdmin = false, $bChangePage = true, $bPageReload = true)
    {
        if($this->bShowcaseView)
            return '';

        $sPagination = parent::showPagination ($bAdmin, $bChangePage, $bPageReload);
        if(empty($sPagination))
            return '';

        return $sPagination;
    }

    protected function getItemPerPageInShowCase ()
    {
        $iPerPageInShowCase = BX_SYS_PER_PAGE_BROWSE_SHOWCASE;
        $CNF = &$this->oModule->_oConfig->CNF;
        if (isset($CNF['PARAM_PER_PAGE_BROWSE_SHOWCASE']))
            $iPerPageInShowCase = getParam($CNF['PARAM_PER_PAGE_BROWSE_SHOWCASE']);
        return $iPerPageInShowCase;
    }
    
    function displayResultBlock()
    {
        if ($this->bShowcaseView) {
            $this->addContainerClass(array('bx-base-unit-showcase-wrapper'));
            $this->aCurrent['paginate']['perPage'] = $this->getItemPerPageInShowCase();
            $this->oModule->_oTemplate->addCss(array(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flickity/|flickity.css'));
            $this->oModule->_oTemplate->addJs(array('flickity/flickity.pkgd.min.js','modules/base/general/js/|showcase.js'));
        }

        return parent::displayResultBlock();
    }

    function displaySearchBox ($sContent, $sPaginate = '')
    {
        $aResult = parent::displaySearchBox($sContent, $sPaginate = '');

        if(isset($this->_aParams['filters']) && is_array($this->_aParams['filters']))
            $aResult['buttons'] = [
                ['title' => _t('_Filters'), 'href' => 'javascript:void(0)', 'onclick' => 'javascript:' . $this->_aParams['filters']['onclick']]
            ];

        return $aResult;
    }

    function applyContainerId()
    {
        if(empty($this->aCurrent['name']) || empty($this->_sMode))
            return parent::applyContainerId();

        return str_replace('_', '-', $this->aCurrent['name'] . '-search-result-block-' . $this->_sMode);
    }

    function decodeDataAPI($a)
    {
        $bExtendedUnits = getParam('sys_api_extended_units') == 'on';

        foreach($a as $i => $r)
            $a[$i] = $this->oModule->getDataAPI($r, ['extended' => $bExtendedUnits]);

        return $a;
    }
}

/** @} */
