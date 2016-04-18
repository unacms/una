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
 *      $oCover->set ( // set cover with default template
 *          'text' => 'Hello World!',
 *          'image_url' => 'http://example.com/tile.png',
 *      ); 
 * @endcode
 *
 */
class BxDolCover extends BxDol implements iBxDolSingleton
{
    protected $_aOptions = array();
    protected $_sTemplateName = 'cover_short.html';

    /**
     * Constructor
     */
    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolCover']))
            trigger_error ('Multiple instances are not allowed for the BxDolCover class.', E_USER_ERROR);

        parent::__construct();
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
     * Set cover options
     * @param $aOptions - cover options, default template supports the following: image_url, menu, text
     * @param $sTemplateName - optional template name
     */
    public function set ($aOptions, $sTemplateName = false)
    {
        $this->_aOptions = $aOptions;
        if (false !== $sTemplateName)
            $this->_sTemplateName = $sTemplateName;

        bx_alert('system', 'set_cover', 0, false, array('options' => &$this->_aOptions, 'template_name' => &$this->_sTemplateName));
    }

}

/** @} */
