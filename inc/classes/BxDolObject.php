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
}

/** @} */
