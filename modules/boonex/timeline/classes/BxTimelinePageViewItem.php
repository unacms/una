<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelinePageViewItem extends BxBaseModTextPageAuthor
{
    protected $_sModule;
    protected $_oModule;

    protected $_iItemId;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $this->_oModule->_oTemplate);

        $iItemId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iItemId) 
            return;

        $this->_iItemId = $iItemId;
    }

    public function getCode()
    {
        $aItemData = $this->_oModule->getItemData($this->_iItemId);

        if($aItemData['code'] != 0) {
            switch($aItemData['code']) {
                case 1:
                    $this->_oTemplate->displayPageNotFound();
                    break;

                case 2: 
                    $this->_oTemplate->displayAccessDenied($aItemData['content']);
                    break;

                default:
                    $this->_oTemplate->displayMsg($aItemData['content']);
            }
        }

        BxDolTemplate::getInstance()->setPageUrl('page.php?i=' . $this->_aObject['uri'] . '&id=' . $this->_iItemId);

        return parent::getCode();
    }
}

/** @} */
