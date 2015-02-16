<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * When profile is not available (for example profile is deleted) then use this special class.
 *
 * @section example Example of usage
 *
 * @code
 * $oProfile = BxDolProfile::getInstance($iId);
 * if (!$oProfile)
 *     $oProfile = BxDolProfileUndefined::getInstance();
 * @endcode
 */
class BxDolProfileUndefined extends BxDol implements iBxDolSingleton, iBxDolProfile
{
    /**
     * Constructor
     */
    protected function __construct ()
    {
        parent::__construct();
    }

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
     * Get profile id
     */
    public function id()
    {
        return 0;
    }

    /**
     * Get profile display name
     */
    public function getDisplayName()
    {
        return _t('_uknown');
    }

    /**
     * Get profile url
     */
    public function getUrl()
    {
        return 'javascript:void(0);';
    }

    /**
     * Get profile unit
     */
    public function getUnit()
    {
        return '<div>' . $this->getDisplayName() . '</div>';
    }

    /**
     * Get profile avatar
     */
    public function getAvatar()
    {
        return BxDolTemplate::getInstance()->getImageUrl('account-avatar.png');
    }

    /**
     * Get profile thumb
     */
    public function getThumb()
    {
        return BxDolTemplate::getInstance()->getImageUrl('account-thumb.png');
    }

    /**
     * Get profile icon
     */
    public function getIcon()
    {
        return BxDolTemplate::getInstance()->getImageUrl('account-icon.png');
    }

    /**
     * Get profile edit url
     */
    public function getEditUrl()
    {
        return '';
    }

    /**
     * Check if profile is active
     */
    public function isActive()
    {
        return true;
    }
}

/** @} */
