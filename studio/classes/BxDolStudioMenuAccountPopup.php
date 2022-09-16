<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioMenuAccountPopup extends BxTemplStudioMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bInlineIcons = true;
    }

    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        $bResult = false;
        switch ($a['name']) {
            case 'edit':
                if(!BxDolStudioRolesUtils::getInstance()->isActionAllowed(BX_SRA_MANAGE_APPS))
                    break;

                list($sPageLink) = bx_get_base_url_inline();
                $sLauncherLink = BxTemplStudioLauncher::getInstance()->getPageUrl();
                if(strcmp($sPageLink, $sLauncherLink) != 0)
                    break;

                $bResult = true;
                break;

            case 'language':
                $aLanguages = BxDolLanguagesQuery::getInstance()->getLanguages(false, true);
                $bResult = count($aLanguages) > 1;
                break;

            default:
                $bResult = true;
        }

        return $bResult;
    }
}

/** @} */
