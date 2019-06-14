<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Standard Player view.
 * @see BxDolPlayer::attachPlayer
 */
define('BX_PLAYER_STANDARD', 1);

/**
 * Mini Player view. If not supported by player, standard view is used.
 * @see BxDolPlayer::attachPlayer
 */
define('BX_PLAYER_MINI', 3);

/**
 * Player view in embed. If not supported by player, standard view is used.
 * @see BxDolPlayer::attachPlayer
 */
define('BX_PLAYER_EMBED', 2);

/**
 * Audio/Video player integration.
 *
 * Site owner can choose which audio/video can be user on the site.
 *
 * Default player is stored in 'sys_player_default' setting option.
 *
 * @section player_create Creating the Editor object:
 *
 *
 * Add record to 'sys_objects_player' table:
 *
 * - object: name of the object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing; 
 * - title: title of the player, displayed in the studio.
 * - skin: player skin, if player suports custom/multiple skins.
 * - override_class_name: user defined class name which is derived from one of base player classes.
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 *
 * @section example Example of usage
 *
 * Display player
 *
 * @code
 *
 *  $oPlayer = BxDolPlayer::getObjectInstance(); // get default player object instance
 *  if ($oPlayer) // check if player is available for using
 *      echo $oPlayer->getCodeVideo (BX_PLAYER_STANDARD, array(
 *          'poster' => $sUrlPoster, // optional, but very 
 *          'mp4' => $sUrlMP4, // or array of files, 
 *                   for example: array('sd' => $sUrl480, 'hd' => $sUrl720)
 *          'webm' => $sUrlWebM, // optional, mp4 format is enough for all moders browsers
 *          'attrs' => $aAttrs,  // optional, not supported by all player implementations
 *          'styles' => 'width:100%; height:auto;', // optional, not supported by all 
 *                                                     players implementations
 *      )); // output HTML player implementation
 * @endcode
 *
 */
class BxDolPlayer extends BxDolFactory implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;
    protected $_aConfCustom = array();

    protected $_sSkin;
    protected $_aSkins;

    protected $_aSizes = array(
        'sd' => 480,
        'hd' => 720,
    );    

    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        
        $this->_sSkin = !empty($aObject['skin']) ? $aObject['skin']: '';
        $this->_aSkins = array();
    }

    /**
     * Get player object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject = false, $oTemplate = false)
    {
        if (!$sObject)
            $sObject = getParam('sys_player_default');

        if (isset($GLOBALS['bxDolClasses']['BxDolPlayer!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolPlayer!'.$sObject];

        $aObject = BxDolPlayerQuery::getPlayerObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        if (empty($aObject['override_class_name']))
            return false;

        $sClass = $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass($aObject, $oTemplate);

        return ($GLOBALS['bxDolClasses']['BxDolPlayer!'.$sObject] = $o);
    }

    /**
     * Get object name
     */
    public function getObjectName ()
    {
        return $this->_sObject;
    }

    /**
     * Set custom player configuration options
     */
    public function setCustomConf ($a)
    {
        $this->_aConfCustom = $a;
    }

    /**
     * Set size for default resolutions: 'sd' and 'hd'
     */
    public function setVideoSize ($sName, $iSize)
    {
        if (isset($this->_aSizes[$sName]))
            $this->_aSizes[$sName] = $iSize;
    }
    
    /**
     * Get a list of available skins.
     */
    public function getSkins($bFullInfo = false)
    {
        if($bFullInfo)
            return $this->_aSkins;

        $aResults = array();
        foreach($this->_aSkins as $aSkin)
            $aResults[] = array('key' => $aSkin['name'], 'value' => _t($aSkin['title']));

        return $aResults;
    }

    public function setSkin($sSkin)
    {
        if(!in_array($sSkin, array_keys($this->_aSkins)))
            return;

        $this->_sSkin = $sSkin;
    }

    /**
     * Get player HTML code
     * @param $iViewMode - player view mode: BX_PLAYER_STANDARD, BX_PLAYER_MINI, BX_PLAYER_EMBED
     * @param $aParams - player params: mp3, ogg, attrs, styles - See usage example.
     * @param $bDynamicMode - is AJAX mode or not, the HTML with player is loaded dynamically.
     */
    public function getCodeAudio ($iViewMode, $aParams, $bDynamicMode = false)
    {
        // override this function in particular player class
    }
    
    /**
     * Get player HTML code
     * @param $iViewMode - player view mode: BX_PLAYER_STANDARD, BX_PLAYER_MINI, BX_PLAYER_EMBED
     * @param $aParams - player params: poster, mp4, webm, attrs, styles - See usage example.
     * @param $bDynamicMode - is AJAX mode or not, the HTML with player is loaded dynamically.
     */
    public function getCodeVideo ($iViewMode, $aParams, $bDynamicMode = false)
    {
        // override this function in particular player class
    }

    /**
     * Add css/js files which are needed for player display and functionality.
     */
    protected function _addJsCss ($bDynamicMode = false)
    {
        // override this function in particular player class
    }

    /**
     * Replace provided markers string.
     * @param $s - string to replace markers in
     * @param $a - markers array
     * @return string with replaces markers
     */
    protected function _replaceMarkers ($s, $a)
    {
        if (empty($s) || empty($a) || !is_array($a))
            return $s;
        return bx_replace_markers($s, $a);
    }

}

/** @} */
