<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Mass mailer
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMassMailerTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }
    
    function getCampagnInfo($aData)
    {
        $aList =array();
        foreach($aData as $aItem){
            $aList[] = array('email' => $aItem['email'], 'date_sent' =>  $aItem['date_sent'] > 0 ? bx_time_js($aItem['date_sent']) : '', 'date_seen' =>  $aItem['date_seen'] > 0 ? bx_time_js($aItem['date_seen']) : '');
        }
        
        $this->addJs(array(BX_DIRECTORY_PATH_MODULES . 'boonex/massmailer/plugins/datatables/|datatables.min.js'));
        $this->addCss(array(BX_DIRECTORY_PATH_MODULES . 'boonex/massmailer/plugins/datatables/|datatables.min.css'));
        return $this->parseHtmlByName('entity_view.html', array(
             'bx_repeat:items' => $aList,
             'email_title' => _t('_bx_massmailer_txt_title_email'),
             'date_sent_title' => _t('_bx_massmailer_txt_title_date_sent'),
             'date_seen_title' => _t('_bx_massmailer_txt_title_date_seen'),
            ));
    }
}

/** @} */
