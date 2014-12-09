<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolCaptchaQuery');

/**
 * @page objects
 * @section captcha CAPTCHA
 * @ref BxDolCaptcha
 */

/**
 * CAPTCHA objects.
 *
 * Default captcha is stored in 'sys_captcha_default' setting option.
 *
 * @section captcha_create Creating the Captcha object:
 *
 *
 * Add record to 'sys_objects_captcha' table:
 *
 * - object: name of the captcha object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing; for example: bx_blogs - custom captcha in blogs module.
 * - title: captcha title.
 * - override_class_name: user defined class name which is derived from one of base captcha classes.
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 *
 * @section example Example of usage
 *
 * Display captcha:
 *
 * @code
 *  bx_import('BxDolCaptcha'); // import captcha class
 *  $oCaptcha = BxDolCaptcha::getObjectInstance(); // get default captcha object instance
 *  if ($oCaptcha) // check if captcha is available for using
 *      echo $oCaptcha->display (); // output HTML which will automatically show captcha
 * @endcode
 *
 * Check captcha:
 *
 * @code
 *  bx_import('BxDolCaptcha'); // import captcha class
 *  $oCaptcha = BxDolCaptcha::getObjectInstance(); // get default captcha object instance
 *  if ($oCaptcha && $oCaptcha->check ()) // check if captcha is correct
 *      echo 'captcha is OK';
 *  else
 *      echo 'captcha is incorrect'; //
 * @endcode
 */
class BxDolCaptcha extends BxDol implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;

    /**
     * Constructor
     * @param $aObject array of captcha options
     */
    public function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
    }

    /**
     * Get captcha object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject = false)
    {
        if (!$sObject)
            $sObject = getParam('sys_captcha_default');

        if (isset($GLOBALS['bxDolClasses']['BxDolCaptcha!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolCaptcha!'.$sObject];

        $aObject = BxDolCaptchaQuery::getCaptchaObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        if (empty($aObject['override_class_name']))
            return false;

        $sClass = $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);
        else
            bx_import($sClass);

        $o = new $sClass($aObject);
        if (!$o->isAvailable())
            return false;

        return ($GLOBALS['bxDolClasses']['BxDolCaptcha!'.$sObject] = $o);
    }

    /**
     * Display captcha.
     * @param $bDynamicMode - is AJAX mode or not, if true then HTML code with captcha is loaded dynamically.
     */
    public function display ($bDynamicMode = false)
    {
        // override this function in particular class
    }

    /**
     * Check captcha.
     */
    public function check ()
    {
        // override this function in particular class
    }

    /**
     * Return text entered by user
     */
    public function getUserResponse ()
    {
        // override this function in particular class
    }

    /**
     * Check if captcha is available, like all API keys are specified.
     */
    public function isAvailable ()
    {
        // override this function in particular class
    }
}

/** @} */
