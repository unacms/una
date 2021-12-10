<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

/**
 * @page objects Objects
 * Classes which represents high level programming constructions to generate ready functionality, like Comments, Votings, Forms.
 *
 * @section alerts Alerts(hooks)
 * @ref BxDolAlerts
 *
 * @section captcha CAPTCHA
 * @ref BxDolCaptcha
 *
 * @section category Category
 * @ref BxDolCategory
 *
 * @section comments Comments
 * @ref BxDolCmts
 *
 * @section connection Connection
 * @ref BxDolConnection
 *
 * @section content_info Content Info
 * @ref BxDolContentInfo
 *
 * @section cover Cover
 * @ref BxDolCover
 *
 * @section editor Editor
 * @ref BxDolEditor
 *
 * @section email_templates Email Templates
 * @ref BxDolEmailTemplates
 *
 * @section favorites Favorites
 * @ref BxDolFavorite
 *
 * @section feature Feature
 * @ref BxDolFeature
 *
 * @section file_handler File View Handlers
 * @ref BxDolFileHandler
 *
 * @section forms Forms
 * @ref BxDolForm
 * 
 * @section grid Grid
 * @ref BxDolGrid
 *
 * @section informer Informer
 * @ref BxDolInformer
 *
 * @section key Keys
 * @ref BxDolKey
 * 
 * @section live_updates Live Updates
 * @ref BxDolLiveUpdates
 * 
 * @section menu Menu
 * @ref BxDolMenu
 *
 * @section metatags Meta Tags
 * @ref BxDolMetatags
 * 
 * @section page Page
 * @ref BxDolPage
 *
 * @section permalinks Permalinks
 * @ref BxDolPermalinks
 * 
 * @section privacy Privacy
 * @ref BxDolPrivacy
 *
 * @section reports Reports
 * @ref BxDolReport
 * 
 * @section rss RSS
 * @ref BxDolRss
 *
 * @section search Search
 * @ref BxDolSearch
 *
 * @section storage Storage
 * @ref BxDolStorage
 * 
 * @section transcoder_images Transcoder: Images 
 * @ref BxDolTranscoderImage
 *
 * @section transcoder_proxy Transcoder: Proxy
 * @ref BxDolTranscoderProxy
 *
 * @section transcoder_videos Transcoder: Videos
 * @ref BxDolTranscoderVideo
 * 
 * @section uploader Uploader
 * @ref BxDolUploader
 * 
 * @section views Views
 * @ref BxDolView
 *
 * @section votes Votes
 * @ref BxDolVote
 */

/**
 * @page public_api API Public
 * Public API for getting secure token for @ref private_api calls and other calls which don't require user authentication
 */

/**
 * @page private_api API Private
 * Private API, which uses secure token for communication, token can be retrived via @ref public_api
 */ 

/**
 * @page service Service Calls
 * Service calls
 */

/**
 * @defgroup    UnaCore UNA Core
 * @{
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
    public function hasImage();
    public function getPicture();
    public function getAvatar();
    public function getCover();
    public function getThumb();
    public function getIcon();
    public function getIconModule();
    public function getEditUrl();
    public function isActive();
    public function isOnline();
    public function getStatus();
    public function getModule();

    /**
     * Check profile visibility
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */    
    public function checkAllowedProfileView();

    /**
     * Check if profile can be contacted.
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */    
    public function checkAllowedProfileContact();

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
    public function serviceProfileCover ($iContentId); 
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
     * Check if profile can be contacted
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */     
    public function serviceCheckAllowedProfileContact($iContentId);
    
    /**
     * Check if posting of a certain type of content in profile is allowed, for example posting in profile's timeline
     * @param $iContentId content ID
     * @param $sPostModule type of content to be posted
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */    
    public function serviceCheckAllowedPostInProfile($iContentId, $sPostModule = '');

    /**
     * Check if a certain type of action in a content module in profile is allowed, for example deleting timeline's posts inside a context
     * @param $iContentId content ID
     * @param $sPostModule type of content to be posted
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */
    public function serviceCheckAllowedModuleActionInProfile($iContentId, $sModule, $sActionName);

    /**
     * Get profiles, current profile have relation with, like friends, joined groups and events
     * @param $iProfileId profile to get relations for
     * @param $aConnectionObject look for relations in these connection objects
     * @return array
     */
    public function serviceGetParticipatingProfiles($iProfileId, $aConnectionObject = false);

    /**
     * Get space title
     * @return string
     */ 
    public function serviceGetSpaceTitle();

    /**
     * Check space privacy, when some profile is used for privacy checking of some content
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */ 
    public function serviceCheckSpacePrivacy($iContentId);
        
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

    public function serviceGetSearchableFieldsExtended($aInputsAdd = array());
    public function serviceGetSearchResultExtended($aParams, $iStart = 0, $iPerPage = 0, $bFilterMode = false);
}

/**
 * Calendar info services module interface, module class must implement get_calendar_entries service to be compliant with Calendar interface
 */
interface iBxDolCalendarService 
{
    public function serviceGetCalendarEntries ($iContentId);
}
/** @} */
