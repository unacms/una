<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxCnvPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_convos';
        parent::__construct($aObject, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_oModule->_oDb->updateReadComments(bx_get_logged_profile_id(), $this->_aContentInfo[$CNF['FIELD_ID']], $this->_aContentInfo[$CNF['FIELD_COMMENTS']]);

        $iFolder = $this->_oModule->_oDb->getConversationFolder($this->_aContentInfo[$CNF['FIELD_ID']], bx_get_logged_profile_id());
        if (BX_CNV_FOLDER_DRAFTS == $iFolder) { // if draft is opened - redirect to compose page
            $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']);
            $sUrl = bx_append_url_params($sUrl, array(
                'draft_id' => $this->_aContentInfo[$CNF['FIELD_ID']],
            ));
            header('Location: ' . BX_DOL_URL_ROOT . $sUrl);
            exit;
        }

        //$this->_oModule->setModuleSubmenu ($iFolder);

        $this->_oModule->_oTemplate->addJs('main.js');
        $this->_oModule->_oTemplate->addCss(array('main-media-tablet.css', 'main-media-desktop.css'));
    }
}

/** @} */
