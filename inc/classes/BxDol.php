<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
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
    public function __construct () {}
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
    public function getPicture();
    public function getAvatar();
    public function getThumb();
    public function getIcon();
    public function getIconModule();
    public function getEditUrl();
    public function isActive();

    /**
     * Check profile visibility
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */    
    public function checkAllowedProfileView();
    
    /**
     * Check if posting in profile is allowed
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */    
    public function checkAllowedPostInProfile();
}

/**
 * Profile services module interface, module class must implement basic profile services to be compliant with Profile interface
 */
interface iBxDolProfileService
{
    public function serviceProfileUnit ($iContentId);
    public function serviceProfilePicture ($iContentId);
    public function serviceProfileAvatar ($iContentId);
    public function serviceProfileThumb ($iContentId);
    public function serviceProfileIcon ($iContentId);
    public function serviceProfileName ($iContentId);
    public function serviceProfileUrl ($iContentId);
    public function serviceProfileEditUrl ($iContentId);

    /**
     * Check profile visibility
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */     
    public function serviceCheckAllowedProfileView($iContentId);

    /**
     * Check if posting in profile is allowed, for example posting in profile's timeline
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */    
    public function serviceCheckAllowedPostInProfile($iContentId);
    
    public function serviceProfilesSearch ($sTerm, $iLimit);
    public function serviceFormsHelper ();
    public function serviceActAsProfile ();
    public function servicePrepareFields ($aFieldsProfile);    
}

/**
 * Content info services module interface, module class must implement basic content info services to be compliant with ContentInfo interface
 */
interface iBxDolContentInfoService
{
    public function serviceGetAuthor ($iContentId);
    public function serviceGetDateAdded ($iContentId);
    public function serviceGetDateChanged ($iContentId);
    public function serviceGetLink ($iContentId);
    public function serviceGetTitle ($iContentId);
    public function serviceGetText ($iContentId);
    public function serviceGetThumb ($iContentId);
    public function serviceGetInfo ($iContentId, $bSearchableFieldsOnly = true);
    public function serviceGetSearchResultUnit ($iContentId, $sUnitTemplate = '');
    public function serviceGetAll ($aParams = array());

    public function serviceGetSearchableFieldsExtended();
    public function serviceGetSearchResultExtended($aParams);
}
/** @} */
