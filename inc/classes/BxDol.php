<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */


/** 
 * @page objects Objects
 * Classes which represents high level programming constructions to generate ready functionality, like Comments, Votings, Forms. 
 */


/**
 * Base class for all Dolphin classes
 */
class BxDol {
    function BxDol () {

    }
}

/**
 * Singleton interface, for objects with one instance only
 */
interface iBxDolSingleton {
    public static function getInstance();
}

/**
 * Factory interface for object instances, once instance per object name
 */
interface iBxDolFactoryObject {
    static public function getObjectInstance($sObject);
}

/**
 * Profile interface, class must implement basic profile methods to be compliant with Profile interface
 */
interface iBxDolProfile {
    public function id();
    public function getDisplayName();
    public function getUrl();
    public function getUnit();
    public function getThumb();
}

/**
 * Profile services module interface, module class must implement basic profile services to be compliant with Profile interface
 */
interface iBxDolProfileService {
    public function serviceProfileUnit ($iContentId);
    public function serviceProfileThumb ($iContentId);
    public function serviceProfileName ($iContentId);
    public function serviceProfileUrl ($iContentId);
}

/** @} */
