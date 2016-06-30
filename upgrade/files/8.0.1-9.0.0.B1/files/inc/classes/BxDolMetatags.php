<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_METATAGS_KEYWORDS_MAX', 9);

/**
 * @page objects
 * @section metatags Meta-tags
 * @ref BxDolMetatags
 */

// TODO: client side controls: some js to help to enter @mentions and #keywords(optional)
// TODO: integrate to comments, try to integrate to one metatags object - maybe specify metatags object in comments object and make appropriate changes in comments, 
//       comments content should be treated as main conetent and main content should be shown in search results
// TODO: integration with notifications, when smbd is @mentioned

/**
 * Meta-tags for different content. It can handle #keywords, @mentions, locations and meta image for the content.
 *
 * Keywords are parsed after content add and edit and added to the database, 
 * later when content is displayed #keywords are highlighted as links to the search page with all content with this keyword, 
 * also keywords are displayed as meta info in page header.
 * 
 * Location info upon content adding/editing is displayed as custom field, it detects current user location and attach location as hidden form fields.
 * When content is displayed location can be shown as clickable links to search page where results are other items from same location,
 * also location is displayed as meta info in page header as latutude and longitude.
 * 
 * Content image can be added as meta info using this class as well.
 *
 *
 *
 * @section metatags_create Add new meta-tags object.
 *
 * To add new meta-tags object insert new record into `sys_objects_metatags` table:
 * - object: name of the meta-tags object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing
 * - table_keywords: table name to store keywords, leave empty to disable keywords
 * - table_locations: table name to store locations, leave empty to disable locations
 * - table_mentions: table name to store mentions, leave empty to disable mentions
 * - override_class_name: class name to override  
 * - override_class_file: class file path to override
 *
 * To simplify operations with metatags in modules which are derived from some 'base' modules, specify it in module config class in 'CNF' array: 
 * - FIELDS_WITH_KEYWORDS: array or comma separated list of field names with keywords support, or 'auto' to automatically use all 'textarea' fields
 * - OBJECT_METATAGS: metatags object name
 *
 *
 *
 * @section metatags_locations Location
 *
 * Upon content 'add' and 'edit' form submit, call BxDolMetatags::locationsAddFromForm
 * @code
 *   $oMetatags = BxDolMetatags::getObjectInstance('object_name');
 *   $oMetatags->locationsAddFromForm($iContentId);
 * @endcode
 *
 * Upon 'edit' form display call BxDolMetatags::locationGet to fill-in location form input, for example:
 * @code
 *   $oMetatags = BxDolMetatags::getObjectInstance('object_name');
 *   $aSpecificValues = $oMetatags->locationGet($iContentId);
 *   $oForm->initChecker($aContentInfo, $aSpecificValues);
 * @endcode
 *
 * To display location call BxDolMetatags::locationsString:
 * @code
 *   $oMetatags = BxDolMetatags::getObjectInstance('object_name');
 *   echo $oMetatags->locationsString($iContentId)
 * @endcode
 *
 * Upon page display call BxDolMetatags::metaAdd to add location (and all other, including content image) meta information to the page header:
 * @code
 *   $o = BxDolMetatags::getObjectInstance('object_name');
 *   $o->metaAdd($iContentId, array('id' => $iFileId, 'object' => 'storage_object_name'));
 * @endcode
 * 
 * Form object must have location field, usual name for this field is 'location' (this name is used as $sPrefix in some functions), type is 'custom', 
 * also form object must be derived from BxBaseModGeneralFormEntry class, which has custom field declaration.
 *
 *
 *
 * @section metatags_keywords #keyword
 *
 * Upon content 'add' and 'edit' form submit, call BxDolMetatags::locationsAddFromForm
 * @code
 *   $oMetatags = BxDolMetatags::getObjectInstance('object_name');
 *   $oMetatags->keywordsAdd($iContentId, $aContentInfo['text_field_with_hash_tags']);
 * @endcode
 *
 * Before displaying the text with hashtags call BxDolMetatags::keywordsParse to highlight keywords:
 * @code
 *   $oMetatags = BxDolMetatags::getObjectInstance('object_name');
 *   $sText = $oMetatags->keywordsParse($iContentId, $sText);
 * @endcode
 *
 * Upon page display call BxDolMetatags::metaAdd to add keywords (and all other, including content image) meta information to the page header.
 *
 * To be able to search by tags metatgs object must be specified in *SearchResult class as 'object_metatags' in 'aCurrent' array.
 *
 *
 *
 * @section metatags_mention @mention
 * TODO:
 *
 *
 *
 * @section metatags_delete_content Content deletion
 * 
 * When content is deleted associated meta data can be deleted by calling BxDolMetatags::onDeleteContent:
 * @code
 *   $oMetatags = BxDolMetatags::getObjectInstance('object_name');
 *   $oMetatags->onDeleteContent($iContentId);
 * @endcode
 *
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
            
            if (!empty($mixedImage['object']))
                $o = BxDolStorage::getObjectInstance($mixedImage['object']);
            elseif (!empty($mixedImage['transcoder']))
                $o = BxDolTranscoder::getObjectInstance($mixedImage['transcoder']);

            $mixedImage = $o ? $o->getFileUrlById($mixedImage['id']) : false;
        }

        if ($mixedImage) 
            BxDolTemplate::getInstance()->addPageMetaImage($mixedImage);

        return $i;
    }


    /**
     * Checks if keywords enabled for current metatags object
     */
    public function keywordsIsEnabled() 
    {
        return empty($this->_aObject['table_keywords']) ? false : true;
    }

    /**
     * This function is specifically for formatting "Photo Camera" string, when "Photo Camera" is used as image hashtag
     */
    static public function keywordsCameraModel($aExif) 
    {
        if (!isset($aExif['Make']))
            return '';

        $sMake = trim($aExif['Make']);
        if (isset($aExif['Model'])) {
            $sModel = trim($aExif['Model']);
            if (0 === mb_strpos($sModel, $sMake))
                $sModel = mb_substr($sModel, mb_strlen($sMake));
        }

        return $sMake . ' ' . trim($sModel);
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
        if (!preg_match_all('/[^&](\#[\pL\pN]+)/u', strip_tags($s), $a)) {
            $this->_oQuery->keywordsDelete($iId);
            return 0;
        }

        $aTags = array_unique($a[1]);
        $aTags = array_slice($aTags, 0, BX_METATAGS_KEYWORDS_MAX);

        return $this->_oQuery->keywordsAdd($iId, $aTags);
    }

    /**
     * Add keyword from the whole string
     * @param $iId content id
     * @param $s keyword 
     * @return number of added keywords, should be 1
     */
    public function keywordsAddOne($iId, $s, $bDeletePreviousKeywords = true)
    {
        if ($bDeletePreviousKeywords)
            $this->_oQuery->keywordsDelete($iId);

        return $this->_oQuery->keywordsAdd($iId, array($s));
    }

    /**
     * Add #keywords from the content fields
     * @param $iId content id
     * @param $s string with #keywords
     * @return number of found keywords
     */
    public function keywordsAddAuto($iId, $aContentInfo, $CNF, $sFormDisplay) 
    {
        $aFields = $this->keywordsFields($aContentInfo, $CNF, $sFormDisplay); 
        $sTextWithKeywords = '';
        foreach ($aFields as $sField)
            $sTextWithKeywords .= $aContentInfo[$sField];

        return $this->keywordsAdd($iId, $sTextWithKeywords);
    }

    /**
     * Get field names which are subject to parse keywords
     */
    public function keywordsFields($aContentInfo, $CNF, $sFormDisplay)
    {
        $aFields = array();
        if (empty($CNF['FIELDS_WITH_KEYWORDS'])) {
            return array();
        }
        elseif (is_string($CNF['FIELDS_WITH_KEYWORDS']) && 'auto' == $CNF['FIELDS_WITH_KEYWORDS']) {
            if (!($oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sFormDisplay)))
                return array();

            foreach ($oForm->aInputs as $k => $a) {
                if ('textarea' == $a['type'])
                    $aFields[] = $a['name'];
            }
        } 
        elseif (is_array($CNF['FIELDS_WITH_KEYWORDS'])) {
            $aFields = $CNF['FIELDS_WITH_KEYWORDS'];
        } 
        elseif (is_string($CNF['FIELDS_WITH_KEYWORDS'])) {
            $aFields = explode(',', $CNF['FIELDS_WITH_KEYWORDS']);
        }

        return $aFields;
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
     * Add link to the provided keyword
     * @param $iId content id
     * @param $s keyword 
     * @return modified string where provided keyword is transformed to link with rel="tag" attribute
     */
    public function keywordsParseOne($iId, $s) 
    {   
        $a = $this->keywordsGet($iId);
        if (empty($a))
            return $s;
    
        foreach ($a as $sKeyword)
            if (0 == strcasecmp(mb_strtolower($s), mb_strtolower($sKeyword)))
                $s = '<a rel="tag" href="' . BX_DOL_URL_ROOT . 'searchKeyword.php?type=keyword&keyword=' . rawurlencode($sKeyword) . '">' . $sKeyword . '</a>';

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
            'mainTable' => !empty($oSearchResult->aCurrent['tableSearch']) ? $oSearchResult->aCurrent['tableSearch'] : $oSearchResult->aCurrent['table'],
            'onField' => 'object_id',
            'joinFields' => array(),
        );
    }

    public function keywordsPopularList($iLimit)
    {
        return $this->_oQuery->keywordsPopularList($iLimit);
    }


    /**
     * Checks if locations enabled for current metatags object
     */
    public function locationsIsEnabled() 
    {
        return empty($this->_aObject['table_locations']) ? false : true;
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

    /**
     * Add location for the content from POST data
     * @param $iId content id
     * @param $sPrefix field prefix for POST data, or empty -  if no prefix
     * @param $oForm form to use to get POST data, or null - then new form instance will be created
     */
    public function locationsAddFromForm($iId, $sPrefix = '', $oForm = null)
    {
        if (!$this->locationsIsEnabled())
            return;

        if ($sPrefix)
            $sPrefix .= '_';
        if (!$oForm)
            $oForm = new BxDolForm(array(), false);
        $this->locationsAdd($iId, $oForm->getCleanValue($sPrefix.'lat'), $oForm->getCleanValue($sPrefix.'lng'), $oForm->getCleanValue($sPrefix.'country'), $oForm->getCleanValue($sPrefix.'state'), $oForm->getCleanValue($sPrefix.'city'), $oForm->getCleanValue($sPrefix.'zip'));
    }

    /**
     * Get locations string with links
     * @param $iId content id
     * @return string with links to country and city
     */
    public function locationsString($iId)
    {
        bx_import('BxDolForm');
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
        if (empty($this->_aObject['table_locations'])) {
            $oSearchResult->aCurrent['restriction']['meta_location'] = array(
                'operator' => 'nothing',
                'field' => 'nofield',
                'value' => 'novalue',
            );
            return;
        }

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

        $oSearchResult->aCurrent['join']['meta_location'] = array(
            'type' => 'INNER',
            'table' => $this->_aObject['table_locations'],
            'mainField' => $oSearchResult->aCurrent['ident'],
            'mainTable' => !empty($oSearchResult->aCurrent['tableSearch']) ? $oSearchResult->aCurrent['tableSearch'] : $oSearchResult->aCurrent['table'],
            'onField' => 'object_id',
            'joinFields' => array(),
        );
    }

    /**
     * Get location
     * @param $iId content id
     * @param $sPrefix field prefix for returning data array
     * @return location array with the following keys (when no prefix specified): object_id, lat, lng, country, state, city, zip
     */
    public function locationGet($iId, $sPrefix = '')
    {
        if (!$this->locationsIsEnabled())
            return;

        $a = $this->_oQuery->locationGet($iId);
        if (!$sPrefix)
            return $a;

        $aRet = array();
        foreach ($a as $sKey => $sVal)
            $aRet[$sPrefix . '_' . $sKey] = $sVal;
        return $aRet;
    }

    /**
     * Checks if mentions enabled for current metatags object
     */
    public function mentionsIsEnabled() 
    {
        return empty($this->_aObject['table_mentions']) ? false : true;
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
     * Delete all data associated with the content
     * @param $iId content id
     */
    public function onDeleteContent($iId) 
    {
        $i = 0;
        foreach ($this->_aMetas as $sMeta) {
            $sFunc = $sMeta . 'Delete';
            $i += $this->_oQuery->$sFunc($iId);
        }
        return $i;
    }

}

/** @} */
