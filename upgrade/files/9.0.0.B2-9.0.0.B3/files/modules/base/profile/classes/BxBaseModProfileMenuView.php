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
 * View profile entry menu
 */
class BxBaseModProfileMenuView extends BxBaseModGeneralMenuView
{
    protected $_oProfile;
    protected $_aContentInfo;
    protected $_aProfileInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $CNF = $this->_oModule->_oConfig->CNF;

        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iProfileId)
            $this->_oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$this->_oProfile && $iContentId)
            $this->_oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->MODULE);

        if ($this->_oProfile) {
            $this->_aProfileInfo = $this->_oProfile->getInfo();

            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_aProfileInfo['content_id']);

            $this->addMarkers($this->_aProfileInfo);
            $this->addMarkers(array('profile_id' => $this->_oProfile->id()));

            $aTitles = $this->_oModule->serviceGetConnectionButtonsTitles($this->_oProfile->id());
            if ($aTitles) {
                $this->addMarkers(array(
                    'title_add_friend' => $aTitles['add'],
                    'title_remove_friend' => $aTitles['remove'],
                ));
            }
        }
    }

}

/** @} */
