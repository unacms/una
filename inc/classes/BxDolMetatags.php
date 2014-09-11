<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolMetatagsQuery');

/**
 * @page objects
 * @section metatags Meta-tags
 * @ref BxDolMetatags
 */

// TODO: client side controls: new form input for getting user's location, some js to help to enter @mentions and #keywords(optional)
// TODO: implementation with some modules
// TODO: integrate to comments, try to integrate to one metatags object - maybe specify metatags object in comments object and make appropriate changes in comments, comments content should be treated as main conetent and main content should be shown in search results)
// TODO: pages for listing content by @keyword, @mention, country location, city location, or BETTER try integrate it to the search engine, to not make separate pages in each module

/**
 * Meta-tags for different content.
 */
class BxDolMetatags extends BxDol implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;
    protected $_mixedContentId;
    protected $_aMetas = array ();

    /**
     * Constructor
     * @param $aObject array of metags object options
     */
    public function __construct($aObject, $mixedContentId)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        $this->_mixedContentId = $mixedContentId;
        
        $a = array ('keywords', 'locations', 'mentions', 'pictures');
        foreach ($a as $sMeta) {
            if (empty($this->_aObject['table_' . $sMeta]))
                continue;
            $this->_aMetas[] = $sMeta;
        }
    }

    /**
     * Get metatags object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject, $mixedContentId)
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

        $o = new $sClass($aObject, $mixedId);

        return ($GLOBALS['bxDolClasses']['BxDolMetatags!'.$sObject] = $o);
    }

    /**
     * Add all available meta tags to the head section 
     * @return number of successfully added metas
     */
    public function metaAdd() {
        $i = 0;
        foreach ($this->_aMetas as $sMeta) {
            $sFunc = $sMeta . 'AddMeta';
            $i += $this->$sFunc();
        }
        return $i;
    }



    /**
     * Add #keywords from the string
     * @param $s string with #keywords
     * @return number of found keywords
     */
    public function keywordsAdd($s) {
        // TODO:        
    }

    /**
     * Add links to the #keywords in the string
     * @param $s string with #keywords
     * @return modified string where all #keywords are transformed to links with rel="tag" attribute
     */
    public function keywordsParse($s) {
        // TODO:
    }

    /**
     * Add keywords meta info to the head section
     */
    protected function keywordsAddMeta() {
        // TODO:
    }



    /**
     * Add location for the content
     * @param $sLatitude latitude
     * @param $sLongitude longitude
     * @param $sCountryCode optional 2 letters country code (ISO 3166-1)
     * @param $sState optional state/province/territory name
     * @param $sCity optional city name
     * @param $sZip optional ZIP/postcode
     * @return true if location was added, or false otherwise
     */
    public function locationsAdd($sLatitude, $sLongitude, $sCountryCode = '', $sState = '', $sCity = '', $sZip = '') {
        // TODO:        
    }

    /**
     * Get locations string with links
     * @return string with links to country and city
     */
    public function locationsString() {
        // TODO:
    }

    /**
     * Add keywords meta info to the head section
     */
    protected function locationsAddMeta() {
        // TODO:
    }



    /**
     * Add @mentions from the string (most probably @mentions will be some sort of links already, so parsing may have to look for smth like <a data-mention="bx_persons|123">mention name</a> instead of @mention, since there is no usernames for profiles modules and name could contain spaces and othr characters)
     * @param $s string with @mentions
     * @return number of found mentions 
     */
    public function mentionsAdd($s) {
        // TODO:        
    }

    /**
     * Add links to the @mentions in the string (actual tranformation may have to be performed with ready links like <a data-mention="bx_persons|123">mention name</a>)
     * @param $s string with @mentions
     * @return modified string where all @mentions are transformed to links
     */
    public function mentionsParse($s) {
        // TODO:
    }

    /**
     * No mentions meta info in the head section
     */
    protected function mentionsAddMeta() {
        return 0;
    }




    /**
     * Add thumb for the content
     * @param $sStorageObject storage object name where file is stored
     * @param $mixedFileId file id in the storage
     * @return true if file was added, or false otherwise
     */
    public function picturesAdd($sStorageObject, $mixedFileId) {
        // TODO:        
    }

    /**
     * Add thumb meta info to the head section
     */
    protected function picturesAddMeta() {
        // TODO:
    }

}

/** @} */
