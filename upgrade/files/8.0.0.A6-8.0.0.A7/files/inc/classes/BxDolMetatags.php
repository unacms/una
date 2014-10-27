<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolMetatagsQuery');

define('BX_METATAGS_KEYWORDS_MAX', 9);

/**
 * @page objects
 * @section metatags Meta-tags
 * @ref BxDolMetatags
 */

// TODO: client side controls: new form input for getting user's location, some js to help to enter @mentions and #keywords(optional)
// TODO: implementation with some modules
// TODO: integrate to comments, try to integrate to one metatags object - maybe specify metatags object in comments object and make appropriate changes in comments, comments content should be treated as main conetent and main content should be shown in search results)
// TODO: pages for listing content by @keyword, @mention, country location, city location, or BETTER try integrate it to the search engine, to not make separate pages in each module
// TODO: integration with notifications, when smbd is @mentioned

/**
 * Meta-tags for different content.
 */
class BxDolMetatags extends BxDol implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;
    protected $_oQuery;
    protected $_aMetas = array ();

    /**
     * Constructor
     * @param $aObject array of metags object options
     */
    public function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        
        $a = array ('keywords', 'locations', 'mentions', 'pictures');
        foreach ($a as $sMeta) {
            if (empty($this->_aObject['table_' . $sMeta]))
                continue;
            $this->_aMetas[] = $sMeta;
        }

        $this->_oQuery = new BxDolMetatagsQuery($this->_aObject);
    }

    /**
     * Get metatags object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolMetatags!'.$sObject])) 
            return $GLOBALS['bxDolClasses']['BxDolMetatags!'.$sObject];

        $aObject = BxDolMetatagsQuery::getMetatagsObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = $aObject['override_class_name'] ? $aObject['override_class_name'] : 'BxDolMetatags';
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);
        else
            bx_import($sClass);

        $o = new $sClass($aObject);

        return ($GLOBALS['bxDolClasses']['BxDolMetatags!'.$sObject] = $o);
    }

    /**
     * Add all available meta tags to the head section 
     * @return number of successfully added metas
     */
    public function metaAdd($iId, $mixedImage = false)
    {
        $i = 0;
        foreach ($this->_aMetas as $sMeta) {
            $sFunc = $sMeta . 'AddMeta';
            $i += $this->$sFunc($iId);
        }

        if ($mixedImage && is_array($mixedImage)) {
            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance($mixedImage['object']);
            $mixedImage = $oStorage ? $oStorage->getFileUrlById($mixedImage['id']) : false;
        }
        if ($mixedImage) 
            BxDolTemplate::getInstance()->addPageMetaImage($mixedImage);

        return $i;
    }



    /**
     * Add #keywords from the string
     * @param $iId content id
     * @param $s string with #keywords
     * @return number of found keywords
     */
    public function keywordsAdd($iId, $s) 
    {
        $a = array();
        if (!preg_match_all('/(\#[\pL\pN]+)/u', strip_tags($s), $a)) {
            $this->_oQuery->keywordsDelete($iId);
            return 0;
        }

        $a[0] = array_unique($a[0]);

        return $this->_oQuery->keywordsAdd($iId, array_slice($a[0], 0, BX_METATAGS_KEYWORDS_MAX));
    }

    /**
     * Add links to the #keywords in the string
     * @param $iId content id
     * @param $s string with #keywords
     * @return modified string where all #keywords are transformed to links with rel="tag" attribute
     */
    public function keywordsParse($iId, $s) 
    {   
        $a = $this->keywordsGet($iId);
        if (empty($a))
            return $s;
    
        foreach ($a as $sKeyword)
            $s = str_ireplace('#' . $sKeyword, '<a rel="tag" href="' . BX_DOL_URL_ROOT . 'searchKeyword.php?type=keyword&keyword=' . rawurlencode($sKeyword) . '"><s>#</s><b>' . $sKeyword . '</b></a>', $s);

        return $s;
    }

    /**
     * Add keywords meta info to the head section
     * @param $iId content id
     */
    protected function keywordsAddMeta($iId)
    {
        BxDolTemplate::getInstance()->addPageKeywords($this->keywordsGet($iId));
    }

    /**
     * Get list of keywords associated with the content
     * @return array of keywords
     */
    public function keywordsGet($iId)
    {
        return $this->_oQuery->keywordsGet($iId);
    }

    /**
     * Set condition for search results object for meta keyword
     * @param $oSearchResult search results object
     * @param $sKeyword keyword
     */
    public function keywordsSetSearchCondition($oSearchResult, $sKeyword)
    {
        $oSearchResult->aCurrent['restriction']['meta_keyword'] = array(
            'value' => $sKeyword,
            'field' => 'keyword',
            'operator' => '=',
            'table' => $this->_aObject['table_keywords'],
        );

        $oSearchResult->aCurrent['join']['meta_keyword'] = array(
            'type' => 'INNER',
            'table' => $this->_aObject['table_keywords'],
            'mainField' => $oSearchResult->aCurrent['ident'],
            'onField' => 'object_id',
            'joinFields' => array(),
        );
    }



    /**
     * Add location for the content
     * @param $iId content id
     * @param $sLatitude latitude
     * @param $sLongitude longitude
     * @param $sCountryCode optional 2 letters country code (ISO 3166-1)
     * @param $sState optional state/province/territory name
     * @param $sCity optional city name
     * @param $sZip optional ZIP/postcode
     * @return true if location was added, or false otherwise
     */
    public function locationsAdd($iId, $sLatitude, $sLongitude, $sCountryCode, $sState, $sCity, $sZip = '') 
    {
        return $this->_oQuery->locationsAdd($iId, $sLatitude, $sLongitude, $sCountryCode, $sState, $sCity, $sZip);
    }
    public function locationsAddFromForm($iId, $sName, $oForm = null) 
    {
        if (!$oForm)
            $oForm = new BxDolForm(array(), false);
        $this->locationsAdd($iId, $oForm->getCleanValue($sName.'_lat'), $oForm->getCleanValue($sName.'_lng'), $oForm->getCleanValue($sName.'_country'), $oForm->getCleanValue($sName.'_state'), $oForm->getCleanValue($sName.'_city'), $oForm->getCleanValue($sName.'_zip'));
    }

    /**
     * Get locations string with links
     * @param $iId content id
     * @return string with links to country and city
     */
    public function locationsString($iId)
    {
        bx_import('BxDolFormQuery');
        $aCountries = BxDolFormQuery::getDataItems('Country');
        $aLocation = $this->locationGet($iId);
        if (!$aLocation || !$aLocation['country'] || !isset($aCountries[$aLocation['country']]))
            return '';

        $sCountryUrl = '<a href="' . BX_DOL_URL_ROOT . 'searchKeyword.php?type=location_country&keyword=' . $aLocation['country'] . '">' . $aCountries[$aLocation['country']] . '</a>';
        if (!$aLocation['city'])
            return _t('_sys_location_country', $sCountryUrl);

        $sCityUrl = '<a href="' . BX_DOL_URL_ROOT . 'searchKeyword.php?type=location_country_city&keyword=' . $aLocation['country'] . '&state=' . rawurlencode($aLocation['state']) . '&city=' . rawurlencode($aLocation['city']) . '">' . $aLocation['city'] . '</a>';
            
        return _t('_sys_location_country_city', $sCountryUrl, $sCityUrl);
    }

    /**
     * Add keywords meta info to the head section
     * @param $iId content id
     */
    protected function locationsAddMeta($iId) 
    {
        $aLocation = $this->locationGet($iId);
        if (!empty($aLocation['lat']) && !empty($aLocation['lng']) && !empty($aLocation['country']))
            BxDolTemplate::getInstance()->addPageMetaLocation($aLocation['lat'], $aLocation['lng'], $aLocation['country']);
    }

    /**
     * Set condition for search results object for meta locations
     * @param $oSearchResult search results object
     * @param $sCountry country and other location info
     */
    public function locationsSetSearchCondition($oSearchResult, $sCountry, $sState = false, $sCity = false, $sZip = false)
    {
        $a = array('country' => 'sCountry', 'state' => 'sState', 'city' => 'sCity', 'zip' => 'sZip');
        foreach ($a as $sIndex => $sVar) {
            if (!$$sVar)
                continue;

            $oSearchResult->aCurrent['restriction']['meta_location_' . $sIndex] = array(
                'value' => $$sVar,
                'field' => $sIndex,
                'operator' => '=',
                'table' => $this->_aObject['table_locations'],
            );
        }

        $oSearchResult->aCurrent['join']['meta_keyword'] = array(
            'type' => 'INNER',
            'table' => $this->_aObject['table_locations'],
            'mainField' => $oSearchResult->aCurrent['ident'],
            'onField' => 'object_id',
            'joinFields' => array(),
        );
    }

    /**
     * Get location
     * @return location array
     */
    public function locationGet($iId, $sPrefix = '')
    {
        $a = $this->_oQuery->locationGet($iId);
        if (!$sPrefix)
            return $a;

        $aRet = array();
        foreach ($a as $sKey => $sVal)
            $aRet[$sPrefix . '_' . $sKey] = $sVal;
        return $aRet;
    }

    /**
     * Add @mentions from the string (most probably @mentions will be some sort of links already, so parsing may have to look for smth like <a data-mention="bx_persons|123">mention name</a> instead of @mention, since there is no usernames for profiles modules and name could contain spaces and othr characters)
     * @param $iId content id
     * @param $s string with @mentions
     * @return number of found mentions 
     */
    public function mentionsAdd($iId, $s) 
    {
        // TODO:        
    }

    /**
     * Add links to the @mentions in the string (actual tranformation may have to be performed with ready links like <a data-mention="bx_persons|123">mention name</a>)
     * @param $iId content id
     * @param $s string with @mentions
     * @return modified string where all @mentions are transformed to links
     */
    public function mentionsParse($iId, $s) 
    {
        // TODO:
    }

    /**
     * No mentions meta info in the head section
     */
    protected function mentionsAddMeta($iId) 
    {
        return 0;
    }

    /**
     * Set condition for search results object for mentions
     * @param $oSearchResult search results object
     * @param $sMention smbd
     */
    public function mentionsSetSearchCondition($oSearchResult, $sMention)
    {
        // TODO:
    }



    /**
     * Add thumb for the content
     * @param $iId content id
     * @param $sStorageObject storage object name where file is stored
     * @param $mixedFileId file id in the storage
     * @return true if file was added, or false otherwise
     */
    public function picturesAdd($iId, $sStorageObject, $mixedFileId) 
    {
        // TODO:        
    }

    /**
     * Add thumb meta info to the head section
     * @param $iId content id
     */
    protected function picturesAddMeta($iId) 
    {
        // TODO:
    }



    /**
     * Delete all data associated with the content
     * @param $iId content id
     */
    public function onDeleteContent($iId) 
    {
        // TODO: 
    }

}

/** @} */
