<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects Objects
 * Classes which represents high level programming constructions to generate ready functionality, like Comments, Votings, Forms.
 */

/**
 * Classes instances are stored here
 */
$GLOBALS['bxDolClasses'] = array();

/**
 * Base class for all classes
 */
class BxDol
{
    public function BxDol () {}
}

/**
 * Singleton interface, for objects with one instance only
 */
interface iBxDolSingleton
{
    public static function getInstance();
}

/**
 * Factory interface for object instances, once instance per object name
 */
interface iBxDolFactoryObject
{
    static public function getObjectInstance($sObject);
}

/**
 * Replacable interface, class has an ability to replace markers somewhere
 */
interface iBxDolReplaceable
{
    public function addMarkers ($a);
}

/**
 * Profile interface, class must implement basic profile methods to be compliant with Profile interface
 */
interface iBxDolProfile
{
    public function id();
    public function getDisplayName();
    public function getUrl();
    public function getUnit();
    public function getAvatar();
    public function getThumb();
    public function getIcon();
    public function getEditUrl();
    public function isActive();
}

/**
 * Profile services module interface, module class must implement basic profile services to be compliant with Profile interface
 */
interface iBxDolProfileService
{
    public function serviceProfileUnit ($iContentId);
    public function serviceProfileAvatar ($iContentId);
    public function serviceProfileThumb ($iContentId);
    public function serviceProfileIcon ($iContentId);
    public function serviceProfileName ($iContentId);
    public function serviceProfileUrl ($iContentId);
    public function serviceProfileEditUrl ($iContentId);
    public function serviceProfilesSearch ($sTerm, $iLimit);
}

/** @} */
