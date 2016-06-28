<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects
 * @section cover Cover
 * @ref BxDolCover
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
class BxDolCover extends BxDol implements iBxDolSingleton
{
    protected $_aOptions = array();
    protected $_sTemplateName = 'cover.html';
    protected $_sTemplateNameEmpty = 'cover_empty.html';
    protected $_sCoverImageUrl = false;
    protected $_aOptiondDefault = array();

    /**
     * Constructor
     */
    public function __construct()
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
        if (is_string($mixedCoverImage)) {
            $this->_sCoverImageUrl = $mixedCoverImage;
        } 
        elseif (is_array($mixedCoverImage) && isset($mixedCoverImage['id']) && (isset($mixedCoverImage['object']) || isset($mixedCoverImage['transcoder']))) {

            if (!empty($mixedCoverImage['object']))
                $o = BxDolStorage::getObjectInstance($mixedCoverImage['object']);
            elseif (!empty($mixedCoverImage['transcoder']))
                $o = BxDolTranscoder::getObjectInstance($mixedCoverImage['transcoder']);
            if (!$o)
                return false;

            $this->_sCoverImageUrl = $o->getFileUrlById($mixedCoverImage['id']);
        } 
        else {
            return false;
        }

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
