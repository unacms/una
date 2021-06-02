<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Help Tours Help Tours
 * @ingroup     UnaModules
 *
 * @{
 */

class BxHelpToursModule extends BxDolModule
{
    public function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceGetHelpTour($iTourId) {
        static $_bAlreadyPlacedATour;

        // avoid running multiple tours at once on a single page
        if ($_bAlreadyPlacedATour) return;

        if (!$iTourId) return;

        $aTour = $this->_oDb->getTourDetails($iTourId);
        if (!$aTour) return;

        $aHelpTourItems = $this->_oDb->getHelpTourItems($iTourId);
        if (!$aHelpTourItems) return;

        if ((!isAdmin() || !bx_get('help_tour_preview')) && $this->_oDb->isHelpTourSeen(getLoggedId(), $iTourId)) return;

        $_bAlreadyPlacedATour = true;
        return $this->_oTemplate->getHelpTourCode($aTour, $aHelpTourItems);
    }

    public function serviceResponseAccountDelete($oAlert) {
        if ('account' != $oAlert->sUnit || 'delete' != $oAlert->sAction) return;

        $this->_oDb->deleteAccountData($oAlert->iObject);
    }

    public function actionTourSeen() {
        if (!isLogged() || !$iTourId = bx_get('tour')) return;

        $this->_oDb->trackTourSeen(getLoggedId(), $iTourId);
    }
}

/** @} */
