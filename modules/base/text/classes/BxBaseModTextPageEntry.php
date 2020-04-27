<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxBaseModTextPageEntry extends BxBaseModGeneralPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iContentId)
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        $bLoggedOwner = isset($this->_aContentInfo[$CNF['FIELD_AUTHOR']]) && $this->_aContentInfo[$CNF['FIELD_AUTHOR']] == bx_get_logged_profile_id();
        $bLoggedModerator = $this->_oModule->checkAllowedEditAnyEntry() === CHECK_ACTION_RESULT_ALLOWED;

        $sTitle = $sUrl = $sIcon = "";
        if ($this->_aContentInfo && CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedView($this->_aContentInfo)) {
            $sTitle = $this->_oModule->_oTemplate->getTitleAuto($this->_aContentInfo);
            $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $this->_aContentInfo[$CNF['FIELD_ID']]);
            $sIcon = $CNF['ICON'];

            $this->addMarkers($this->_aContentInfo); // every field can be used as marker
        }
        
        $this->addMarkers(array(
            'title' => $sTitle,
            'entry_link' => $sUrl,
        ));

        // select view entry submenu
        $this->_setSubmenu(array(
            'title' => $sTitle,
            'link' => $sUrl,
            'icon' => $sIcon
        ));
        
        $aInformers = array ();
        $oInformer = BxDolInformer::getInstance($this->_oTemplate);
        if($oInformer && ($bLoggedOwner || $bLoggedModerator)) {
            $iNow = time();
            $bFieldPublished = isset($CNF['FIELD_PUBLISHED']);
            $sStatus = isset($CNF['FIELD_STATUS']) && isset($this->_aContentInfo[$CNF['FIELD_STATUS']]) ? $this->_aContentInfo[$CNF['FIELD_STATUS']] : '';

            //--- Display 'processing' informer if an item was already published but awaiting/failed to be processed.
            if(!$bFieldPublished || (isset($this->_aContentInfo[$CNF['FIELD_PUBLISHED']]) && $this->_aContentInfo[$CNF['FIELD_PUBLISHED']] <= $iNow)) {
                if(!empty($CNF['INFORMERS']['processing']) && isset($CNF['INFORMERS']['processing']['map'][$sStatus])) {
                    $aInformer = $CNF['INFORMERS']['processing'];
                    $aInformers[] = array ('name' => $aInformer['name'], 'msg' => _t($aInformer['map'][$sStatus]['msg']), 'type' => $aInformer['map'][$sStatus]['type']);
                }
            }
            //--- Display 'scheduled' informer if an item wasn't published yet.
            else if($bFieldPublished) {
                if(!empty($CNF['INFORMERS']['scheduled']) && isset($CNF['INFORMERS']['scheduled']['map'][$sStatus])) {
                    $this->addMarkers(array(
                        'date_publish_uf' => bx_time_js((int)$this->_aContentInfo[$CNF['FIELD_PUBLISHED']], BX_FORMAT_DATE, true)
                    ));

                    $aInformer = $CNF['INFORMERS']['scheduled'];
                    $aInformers[] = array ('name' => $aInformer['name'], 'msg' => _t($aInformer['map'][$sStatus]['msg']), 'type' => $aInformer['map'][$sStatus]['type']);
                }
            }

            // add informers
            if($aInformers)
                foreach($aInformers as $aInformer)
                    $oInformer->add($aInformer['name'], $this->_replaceMarkers($aInformer['msg']), $aInformer['type']);
        }
    }

    public function isActive()
    {
        return $this->_oModule->isEntryActive($this->_aContentInfo);
    }

    public function getCode ()
    {
        $mixedResult = parent::getCode();

        /*
         * Hide actions menu from View Page cover because 
         * cover area should be decorative in Text Based modules.
         */
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if($oMenuSubmenu)
            $oMenuSubmenu->setObjectActionsMenu(false);

        return $mixedResult;
    }

    protected function _setSubmenu($aParams)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if(!$oMenuSubmenu)
            return false;

        $oMenuSubmenu->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'], array_merge(array(
            'title' => '',
            'link' => '',
            'icon' => '',
        ), $aParams));

        return true;
    }

    protected function _getBlockService ($aBlock)
    {
        $a = parent::_getBlockService ($aBlock);
        $sTest = '_view_entry_comments';
        if (false !== strpos($aBlock['content'], 'entity_comments') && substr_compare($this->_sObject, $sTest, strlen($this->_sObject) - strlen($sTest), strlen($sTest)) === 0)
            unset($a['title']);            
        return $a;
    }

    protected function _getImageForPageCover ()
    {
        return false;
    }
}

/** @} */
