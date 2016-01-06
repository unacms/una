<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Base class for all "Object" classes.
 * Child classes usually represents high level programming constructions to generate ready 'objects' functionality, like Comments, Votings, Forms.
 */
class BxDolObject extends BxDol
{
    protected $_iId = 0; ///< item id the action to be performed with
    protected $_sSystem = ''; ///< current system name
    protected $_aSystem = array(); ///< current system array

    protected $_oQuery = null;

    public function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct();

        $aSystems = $this->getSystems();
        if(!isset($aSystems[$sSystem]))
            return;

        $this->_sSystem = $sSystem;
        $this->_aSystem = $aSystems[$sSystem];

        if(!$this->isEnabled())
            return;

        if($iInit)
            $this->init($iId);
    }

    /**
     * it is called on cron every day or similar period to clean old votes
     */
    public static function maintenance()
    {
        $iResult = 0;
        $oDb = BxDolDb::getInstance();

        $aSystems = self::getSystems();
        foreach($aSystems as $aSystem) {
            if(!$aSystem['is_on'])
                continue;

            $sQuery = $oDb->prepare("DELETE FROM `{$aSystem['table_track']}` WHERE `date` < (UNIX_TIMESTAMP() - ?)", BX_DOL_VOTE_OLD_VOTES);
            $iDeleted = (int)$oDb->query($sQuery);
            if($iDeleted > 0)
                $oDb->query("OPTIMIZE TABLE `{$aSystem['table_track']}`");

            $iResult += $iDeleted;
        }

        return $iResult;
    }

    public function init($iId)
    {
        if(!$this->isEnabled())
            return false;

        if(empty($this->_iId) && $iId)
            $this->setId($iId);

        return true;
    }

    public function getSystemId()
    {
        return $this->_aSystem['id'];
    }

    public function getSystemName()
    {
        return $this->_sSystem;
    }

    public function getSystemInfo()
    {
        return $this->_aSystem;
    }

    public function getId()
    {
        return $this->_iId;
    }

    public function setId($iId)
    {
        if($iId == $this->getId())
            return;

        $this->_iId = $iId;
    }

    public function isEnabled ()
    {
        return $this->_aSystem && (int)$this->_aSystem['is_on'] == 1;
    }

	/**
	 * Interface functions for outer usage
	 */
	public function getSqlParts($sMainTable, $sMainField)
    {
        if(!$this->isEnabled())
            return array();

        return $this->_oQuery->getSqlParts($sMainTable, $sMainField);
    }

	/**
     * Permissions functions
     */
	public function checkAction ($sAction, $isPerformAction = false)
    {
        $iId = $this->_getAuthorId();
        $a = checkActionModule($iId, $sAction, 'system', $isPerformAction);
        return $a[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkActionErrorMsg ($sAction)
    {
        $iId = $this->_getAuthorId();
        $a = checkActionModule($iId, $sAction, 'system');
        return $a[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $a[CHECK_ACTION_MESSAGE] : '';
    }

	/**
     * Actions' response functions
     */
	public function onObjectDelete($iObjectId = 0)
    {
        $this->_oQuery->deleteObjectEntries($iObjectId ? $iObjectId : $this->getId());
    }

	/**
     * Internal functions
     */
	protected function _getAuthorId ()
    {
        return isMember() ? bx_get_logged_profile_id() : 0;
    }

    protected function _getAuthorPassword ()
    {
        return isMember() ? $_COOKIE['memberPassword'] : "";
    }

    protected function _getAuthorIp ()
    {
        return getVisitorIP();
    }

    protected function _getAuthorInfo($iAuthorId = 0)
    {
        $oProfile = $this->_getAuthorObject($iAuthorId);

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit()
        );
    }

    protected function _getAuthorObject($iAuthorId = 0)
    {
        $oProfile = BxDolProfile::getInstance($iAuthorId);
        if (!$oProfile)
            $oProfile = BxDolProfileUndefined::getInstance();

        return $oProfile;
    }

	protected function _trigger()
    {
        if(!$this->_aSystem['trigger_table'])
            return false;

        $iId = $this->getId();
        if(!$iId)
            return false;

        return $this->_oQuery->updateTriggerTable($iId);
    }
}

/** @} */
