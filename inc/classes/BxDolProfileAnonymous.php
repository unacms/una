<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * When profile is anonymous then use this special class.
 *
 * @section example Example of usage
 *
 * @code
 * if (0 > $iId)
 *     $oProfile = BxDolProfileAnonymous::getInstance();
 * else
 *     $oProfile = BxDolProfile::getInstance($iId);
 *
 * @endcode
 */
class BxDolProfileAnonymous extends BxDolProfileUndefined
{
    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        $sClass = get_class($this);
        if (isset($GLOBALS['bxDolClasses'][$sClass]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        $sClass = __CLASS__;
        if(!isset($GLOBALS['bxDolClasses'][$sClass]))
            $GLOBALS['bxDolClasses'][$sClass] = new $sClass();

        return $GLOBALS['bxDolClasses'][$sClass];
    }

    /**
     * Get profile display name
     */
    public function getDisplayName()
    {
        return _t('_anonymous');
    }
}

/** @} */
