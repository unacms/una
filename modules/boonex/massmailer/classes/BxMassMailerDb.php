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

class BxMassMailerDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
    
    public function copyCampaign($iCampaignId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'campaign_id' => $iCampaignId,
            'date_created' => time()
        );
        $this->query("INSERT INTO `" . $CNF['TABLE_CAMPAIGNS'] . "` (`" . $CNF['FIELD_AUTHOR'] . "`, `" . $CNF['FIELD_TITLE'] . "`, `" . $CNF['FIELD_BODY'] . "`, `" . $CNF['FIELD_SEGMENTS'] . "`, `" . $CNF['FIELD_DATE_CREATED'] . "`, `" . $CNF['FIELD_SUBJECT'] . "`, `" . $CNF['FIELD_FROM_NAME'] . "`, `" . $CNF['FIELD_REPLY_TO'] . "`) SELECT `" . $CNF['FIELD_AUTHOR'] . "`, CONCAT(`" . $CNF['FIELD_TITLE'] . "`, '" . _t('_bx_massmailer_txt_copy_title') . "'), `" . $CNF['FIELD_BODY'] . "`, `" . $CNF['FIELD_SEGMENTS'] . "`, :date_created, `" . $CNF['FIELD_SUBJECT'] . "`, `" . $CNF['FIELD_FROM_NAME'] . "`, `" . $CNF['FIELD_REPLY_TO'] . "`  FROM `" . $CNF['TABLE_CAMPAIGNS'] . "` WHERE `" . $CNF['FIELD_ID'] . "` = :campaign_id", $aBindings);
        return $this->lastId();
    }
    
    public function sendCampaign($iCampaignId, $sEmailList)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'campaign_id' => $iCampaignId,
            'email_list' => $sEmailList,
            'date_sent' => time()
        );
        $this->query("UPDATE `" . $CNF['TABLE_CAMPAIGNS'] . "` SET `" . $CNF['FIELD_EMAIL_LIST'] . "` = :email_list, `" . $CNF['FIELD_DATE_SENT'] . "` = :date_sent WHERE `" . $CNF['FIELD_ID'] . "` = :campaign_id", $aBindings);
    }
    
    public function getCampaignInfoById ($iCampaignId)
    {
        $CNF = &$this->_oConfig->CNF;
        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_CAMPAIGNS'] . "` WHERE `" . $CNF['FIELD_ID'] . "` = ?", $iCampaignId);
        return $this->getRow($sQuery);
    }
    
    public function getSegmentInfoById ($iSegmentId)
    {
        $CNF = &$this->_oConfig->CNF;
        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_SEGMENTS'] . "` WHERE `" . $CNF['FIELD_ID'] . "` = ?", $iSegmentId);
        return $this->getRow($sQuery);
    }
    
    public function getSegments()
    {
        $CNF = &$this->_oConfig->CNF;
        $sQuery = $this->prepare ("SELECT `" . $CNF['FIELD_ID'] . "`, `" . $CNF['FIELD_TITLE'] . "` FROM `" . $CNF['TABLE_SEGMENTS'] . "`");
        return $this->getPairs($sQuery, $CNF['FIELD_ID'], $CNF['FIELD_TITLE']);
    }
    
    public function getAccountsByTerms($sTerms = "")
    {
        $sSql = "SELECT `ta`.* FROM  `sys_accounts` `ta` INNER JOIN `sys_profiles` AS `tp`  ON `tp`.`account_id`=`ta`.`id` " . $sTerms . " WHERE `ta`.`receive_news` <> 0";
        return $this->getAll($sSql);
    }
    
    public function addLetter($iCampaignId,$sEmail)
    {
        $iTime = time();
        $sCode = md5($iCampaignId . $sEmail . $iTime);
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'campaign_id' => $iCampaignId,
            'email' => $sEmail,
            'date_sent' => $iTime,
            'code' => $sCode,
        );
        $this->query("INSERT INTO `" . $CNF['TABLE_LETTERS'] . "` (`" . $CNF['FIELD_CAMPAIGN_ID'] . "`, `" . $CNF['FIELD_EMAIL'] . "`, `" . $CNF['FIELD_DATE_SENT'] . "`,  `" . $CNF['FIELD_CODE'] . "`) VALUES (:campaign_id, :email, :date_sent, :code)", $aBindings);
        return $sCode;
    }
    
    public function updateDateSeenForLetter($sCode)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'code' => $sCode,
            'date_seen' => time()
        );
        $this->query("UPDATE `" . $CNF['TABLE_LETTERS'] . "` SET `" . $CNF['FIELD_DATE_SEEN'] . "` = :date_seen WHERE `" . $CNF['FIELD_CODE'] . "` = :code", $aBindings);
    } 
    
    public function deleteLettersForCampaign($iCampaignId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'campaign_id' => $iCampaignId
        );
        $this->query("DELETE FROM `" . $CNF['TABLE_LETTERS'] . "` WHERE `" . $CNF['FIELD_CAMPAIGN_ID'] . "` = :campaign_id", $aBindings);
    } 
    
    public function getLettersByCampaignId ($iCampaignId)
    {
        $CNF = &$this->_oConfig->CNF;
        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_LETTERS'] . "` WHERE `" . $CNF['FIELD_CAMPAIGN_ID'] . "` = ?", $iCampaignId);
        return $this->getAll($sQuery);
    }
}

/** @} */
