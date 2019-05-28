<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View profile entry actions menu
 */
class BxBaseModProfileMenuViewActions extends BxBaseModGeneralMenuView
{
    protected $_oProfile;
    protected $_aProfileInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $CNF = $this->_oModule->_oConfig->CNF;

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(empty($iContentId) && bx_get('profile_id') !== false)
            $iContentId = BxDolProfile::getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT))->getContentId();

        if(!empty($iContentId))
            $this->setContentId($iContentId);
    }

    public function setContentId($iContentId)
    {
        $this->_iContentId = (int)$iContentId;
        $this->_oProfile = BxDolProfile::getInstanceByContentAndType($this->_iContentId, $this->MODULE);

        if(!$this->_oProfile) 
            return;

        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iContentId);
        $this->_aProfileInfo = $this->_oProfile->getInfo();     

        $this->addMarkers($this->_aProfileInfo);
        $this->addMarkers(array(
            'profile_id' => $this->_oProfile->id()
        ));

        $aTitles = $this->_oModule->serviceGetConnectionButtonsTitles($this->_oProfile->id());
        if($aTitles) {
            $this->addMarkers(array(
                'title_add_friend' => $aTitles['add'],
                'title_remove_friend' => $aTitles['remove'],
            ));
        }
    }
}

/** @} */
