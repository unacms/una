<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Permalinks for any content.
 *
 * An object of the class allows to check whether permalink is enabled
 * and get it for specified standard URI.
 *
 *
 * All permalinks must match the whole URL, to be automatially determined.
 * There are only 2 permlinks which are replaced by prefix:
 * - modules/?r= - for module URLs
 * - page.php?i= - for pages URLs
 *
 *
 * @section example Example of usage:
 *
 * 1. Register permalink in database by adding necessary info in the `sys_permalinks` table.
 * 2. Create an object and process the URI
 * @code
 *   $oPermalinks = new BxDolPermalinks();
 *   $oPermalinks->permalink('modules/?r=news/home');
 * @endcode
 *
 *
 * @section acl Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * @section alerts Alerts:
 *
 * The following alert can be used when permalink call is performed:
 * - $sUnit - system
 * - $sAction - permalink | unpermalink
 * - $iObjectId - 0 (not used)
 * - $iSenderId - 0 (not used)
 * - $aExtra['link'] - the link to check permalink for
 * - $aExtra['return_data'] - it is possible to override default behavior, by setting 'return_data' to non NULL value.
 *
 */
class BxDolPermalinks extends BxDolDb implements iBxDolSingleton
{
    protected $sCacheFile;
    protected $aLinksStandard;
    protected $aLinksPermalink;
    protected $aPrefixesStandard;
    protected $aPrefixesPermalink;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        if (getParam('sys_db_cache_enable')) {
            $oCache = $this->getDbCacheObject();
            $sCacheKey = $this->genDbCacheKey('sys_permalinks');
            $aPermalinksData = $oCache->getData($sCacheKey);
            if (null === $aPermalinksData) {
                $aPermalinksData = $this->getPermalinksData();
                $oCache->setData ($sCacheKey, $aPermalinksData);
            }
        } else {
            $aPermalinksData = $this->getPermalinksData();
        }

        $this->aLinksStandard = $aPermalinksData['standard'];
        $this->aLinksPermalink = $aPermalinksData['permalink'];
        $this->aPrefixesStandard = $aPermalinksData['prefixes_standard'];
        $this->aPrefixesPermalink = $aPermalinksData['prefixes_permalink'];
    }

    public function cacheInvalidate()
    {
        return BxDolDb::getInstance()->cleanCache ('sys_permalinks');
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolPermalinks();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function getPermalinksData()
    {
        $aLinksStandard = $this->getAll("SELECT * FROM `sys_permalinks`");

        $aResult = array(
            'standard' => array(),
            'permalink' => array(),
            'prefixes_standard' => array(),
            'prefixes_permalink' => array(),
        );

        foreach ($aLinksStandard as $aLink) {
            $a = array(
                'permalink' => $aLink['permalink'],
                'standard' => $aLink['standard'],
                'check' => $aLink['check'],
                'enabled' => getParam($aLink['check']) == 'on',
            );
            $aResult['standard'][$aLink['standard']] = $a;
            $aResult['permalink'][$aLink['permalink']] = $a;
            if ($aLink['compare_by_prefix']) {
                $aResult['prefixes_standard'][$aLink['standard']] = strlen($aLink['standard']);
                $aResult['prefixes_permalink'][$aLink['permalink']] = strlen($aLink['permalink']);
            }
        }

        return $aResult;
    }

    /**
     * Get page name (changeable page of URL) from the URL.
     * @param $sLink - relative UNpermalinked, it is better to use unpermalink method to make sure that correct url is passed here
     * @param $iLength - position of the page name, or false to detect automatically
     * @return page name (changeable part of URL) or empty string if page name is not detectable.
     */
    function getPageNameFromLink($sLink, $iLength = false)
    {
        if (false == $iLength) {
            $sLink = $this->_fixUrl($sLink);
            foreach ($this->aPrefixesStandard as $sKey => $iLen) {
                if (strncmp($sLink, $sKey, $iLen) === 0) {
                    $iLength = $iLen;
                    break;
                }
            }
        }
        return false === $iLength ? '' : substr($sLink, $iLength);
    }

    /**
     * Make permalink from link.
     * @param $sLink - relative URL.
     * @param $aParams - params to add to the url.
     * @return - relative permalinked URL if it was detected and permalinks are ON or unchanged URL otherwise.
     */
    function permalink($sLink, $aParams = array())
    {
        $sRet = null;
        
        /**
         * @hooks
         * @hookdef hook-system-permalink 'system', 'permalink' - hook to override permalink during link to permalink conversion
         * - $unit_name - equals `system`
         * - $action - equals `permalink`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `link` - [string] input link
         *      - `params` - [array] by ref, additional params array as key&value pairs, can be overridden in hook processing
         *      - `return_data` - [string] by ref, output permalink, can be overridden in hook processing
         * @hook @ref hook-system-permalink
         */
        bx_alert('system', 'permalink', 0, 0, [
            'link' => $sLink, 
            'params' => &$aParams, 
            'return_data' => &$sRet
        ]);
        if (null !== $sRet)
            return $sRet;

        $sPrefix = '';
        if(strncmp($sLink, BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)) === 0) {
            $sPrefix = BX_DOL_URL_ROOT;

            $sLink = substr($sLink, strlen($sPrefix));
        }
            
        $sLink = $this->_fixUrl($sLink);

        if ($sSeoLink = BxDolPage::transformSeoLink ($sLink, $sPrefix, $aParams))
            return $sSeoLink;

        foreach ($this->aPrefixesStandard as $sKey => $iLength) {

            if (strncmp($sLink, $sKey, $iLength) !== 0)
                continue;

            $sPage = $this->getPageNameFromLink($sLink, $iLength);

            if (!$this->_isEnabled($sKey))
                return bx_append_url_params($sLink, $aParams);

            $sUrl = $this->_fixUrlAmpersand($this->aLinksStandard[$sKey]['permalink'] . $sPage);

            return $sPrefix . bx_append_url_params($sUrl, $aParams);

        }

        return $sPrefix . bx_append_url_params($this->_isEnabled($sLink) ? $this->aLinksStandard[$sLink]['permalink'] : $sLink, $aParams);
    }

    /**
     * Remove permalink from link.
     * @param $sLink - relative or absoulte URL.
     * @param $isStripBaseUrl - strip site prefix (absolute URL) automatically (enabled by default)
     * @return - relative UNpermalinked URL if it was detected or relative URL if URL withing the site or unchanged URL otherwise.
     */
    function unpermalink($sLink, $isStripBaseUrl = true)
    {
        $sRet = null;
        
        /**
         * @hooks
         * @hookdef hook-system-unpermalink 'system', 'unpermalink' - hook to override link during permalink to link conversion
         * - $unit_name - equals `system`
         * - $action - equals `unpermalink`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `link` - [string] input permalink
         *      - `strip_base_url` - [boolean] strip base URL from output link
         *      - `return_data` - [string] by ref, output link, can be overridden in hook processing
         * @hook @ref hook-system-unpermalink
         */
        bx_alert('system', 'unpermalink', 0, 0, [
            'link' => $sLink, 
            'strip_base_url' => $isStripBaseUrl, 
            'return_data' => &$sRet
        ]);
        if (null !== $sRet)
            return $sRet;

        $sPrefix = '';
        if(!$isStripBaseUrl)
            $sPrefix = BX_DOL_URL_ROOT;

        if(strncmp($sLink, BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)) === 0)
            $sLink = substr($sLink, strlen(BX_DOL_URL_ROOT));

        if ($sUnSeoLink = BxDolPage::untransformSeoLink ($sLink, $sPrefix))
            return $sUnSeoLink;

        foreach ($this->aPrefixesPermalink as $sKey => $iLength) {

            if (strncmp($sLink, $sKey, $iLength) !== 0)
                continue;

            $sPage = substr($sLink, $iLength);

            return $sPrefix . $this->aLinksPermalink[$sKey]['standard'] . str_replace('?', '&', $sPage);

        }

        return $sPrefix . (isset($this->aLinksPermalink[$sLink]) ? $this->aLinksPermalink[$sLink]['standard'] : $sLink);
    }

    function _isEnabled($sLink)
    {
        return !empty($sLink) && array_key_exists($sLink, $this->aLinksStandard) && $this->aLinksStandard[$sLink]['enabled'];
    }

    /**
     * redirect to the correct url after switching skin or language
     * only correct modules urls are supported
     */
    function redirectIfNecessary ($aSkip = array())
    {
        $sCurrentUrl = $_SERVER['PHP_SELF'] . '?' . bx_encode_url_params($_GET, $aSkip);

        if (!preg_match('/modules\/index.php\?r=(\w+)(.*)/', $sCurrentUrl, $m))
            return false;

        $sStandardLink = 'modules/?r=' . $m[1] . '/';
        $sPermalink = $this->permalink ($sStandardLink);

        if (false !== strpos($sCurrentUrl, $sPermalink))
            return false;

        header("HTTP/1.1 301 Moved Permanently");
        header ('Location:' . BX_DOL_URL_ROOT . $sPermalink . rtrim(trim(urldecode($m[2]), '/'), '&'));

        return true;
    }

    protected function _fixUrlAmpersand($sLink)
    {
        $iPosQuestion = strpos($sLink, '?');

        if (false === $iPosQuestion && false !== ($iPosAmpersand = strpos($sLink, '&')))
            $sLink = substr_replace($sLink, '?', $iPosAmpersand, 1);

        return $sLink;
    }

    protected function _fixUrl($sLink)
    {
        if (strncmp($sLink, 'modules/index.php?r=', 20) === 0)
            $sLink = str_replace('modules/index.php?r=', 'modules/?r=', $sLink);
        return $sLink;
    }
}

/** @} */
