<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Cover.
 *
 * It displays cover area for different pages, usually it have background image, text and some buttons on it.
 *
 * @section example Example of usage
 *
 * Setting cover:
 *
 * @code
 *  $oCover = BxDolCover::getInstance(); // get object instance
 *  if ($oCover) // check if Cover is available for using
 *      $oCover->set (array( // set cover with default template
 *          'title' => 'Hello World!',
 *      )); 
 * @endcode
 *
 */
class BxDolCover extends BxDolFactory implements iBxDolSingleton
{
    protected $_aOptions = array();
    protected $_sTemplateName = 'cover.html';
    protected $_sTemplateNameEmpty = 'cover_empty.html';
    protected $_sCoverImageUrl = false;
    protected $_aOptiondDefault = array();

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolCover']))
            trigger_error ('Multiple instances are not allowed for the BxDolCover class.', E_USER_ERROR);

        parent::__construct();

        $this->_aOptiondDefault = array(
            'class' => '',
            'title' => '',
            'actions' => '',
            'bx_if:image' => array (
                'condition' => false,
                'content' => array(),
            ),
            'bx_if:icon' => array (
                'condition' => false,
                'content' => array(),
            ),
            'bx_if:bg' => array (
                'condition' => false,
                'content' => array(),
            ),
            'bx_if:logged_in' => array(
                'condition' => isLogged(),
                'content' => array(),
            ),
        );
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolCover']))
            trigger_error('Clone is not allowed for the BxDolCover class.', E_USER_ERROR);
    }

    /**
     * Get Cover object instance
     * @return object instance or false on error
     */
    public static function getInstance($oTemplate = false)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolCover']))
            return $GLOBALS['bxDolClasses']['BxDolCover'];

        $o = new BxTemplCover($oTemplate);

        return ($GLOBALS['bxDolClasses']['BxDolCover'] = $o);
    }

    /**
     * Get cover image URL by descriptive array. 
     * @param mixed $mixedImage - image descriptive array.
     * @return string with image URL or false on error. 
     */
    public static function getCoverImageUrl($mixedImage)
    {
        $bObject = !empty($mixedImage['object']);
        $bTranscoder = !empty($mixedImage['transcoder']);
        if(!is_array($mixedImage) || !isset($mixedImage['id']) || (!$bObject && !$bTranscoder))
            return false;

        if($bObject)
            $o = BxDolStorage::getObjectInstance($mixedImage['object']);
        else if($bTranscoder)
            $o = BxDolTranscoder::getObjectInstance($mixedImage['transcoder']);

        if(!$o)
            return false;

        return $o->getFileUrlById($mixedImage['id']);
    }

    /**
     * Determine whether cover is enabled globally or not.
     */
    public function isEnabled()
    {
        return getParam('sys_site_cover_disabled') != 'on';
    }

    /**
     * Determine whether cover is already set for the page or not.  
     */
    public function isCover()
    {
    	return !empty($this->_sCoverImageUrl) || !empty($this->_aOptions);
    }

    /**
     * Set custom cover options
     * @param $aOptions - cover options, default template supports the following: title. For more info @see template/cover.html
     * @param $sTemplateName - optional template name
     */
    public function set ($aOptions, $sTemplateName = false)
    {
        $this->_aOptions = $aOptions;
        if (false !== $sTemplateName)
            $this->_sTemplateName = $sTemplateName;

        bx_alert('system', 'set_cover', 0, false, array('options' => &$this->_aOptions, 'template_name' => &$this->_sTemplateName, 'cover' => $this));
    }

    /**
     * Set cover image url for default cover template
     * @param $mixedCoverImageUrl - cover image url, or array with file id and storage or transcoder object:
     * @code
     *   array('id' => 12, 'object' => 'sample');
     *   // or 
     *   array('id' => 21, 'transcoder' => 'sample');
     * @endcode
     */
    public function setCoverImageUrl ($mixedCoverImage)
    {
        if (is_string($mixedCoverImage))
            $this->_sCoverImageUrl = $mixedCoverImage;
        else
            $this->_sCoverImageUrl = self::getCoverImageUrl($mixedCoverImage);

        if(!$this->_sCoverImageUrl)
            return false;

        bx_alert('system', 'set_cover_image', 0, false, array('cover_image' => &$this->_sCoverImageUrl, 'cover' => $this));

        return true;
    }

    /**
     * Set cover area class
     */
    public function setCoverClass ($sClass)
    {
        $this->_aOptiondDefault['class'] = $sClass;
    }
}

/** @} */
