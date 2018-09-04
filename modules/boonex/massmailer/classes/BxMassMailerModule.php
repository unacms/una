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

class BxMassMailerModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
    public function serviceEntityView ($iContentId = 0, $sDisplay = false)
    {
        return 'todo view campagn';
    }
    
    public function serviceManageTool1 ($iContentId = 0, $sDisplay = false)
    {
        return 'todo manage custom segments';
    }
    
    public function getSegmentValues()
    {
        $CNF = &$this->_oConfig->CNF;
        $aRv = array('lvl:0' => _t('_bx_massmailer_segments_all'));
        $oAccountQuery = BxDolAclQuery::getInstance();
        
        $aTmp = array();
        $oAccountQuery->getLevels(array('type' => 'all_active_pair'), $aTmp);
        foreach ($aTmp as $sKey => $sTmp){
            if ($sKey != MEMBERSHIP_ID_NON_MEMBER && $sKey != MEMBERSHIP_ID_ACCOUNT)
                $aRv['lvl:' . $sKey] = _t($sTmp);
        };
        
        $aTmp = $this->_oDb->getSegments();
        foreach ($aTmp as $sKey => $sTmp){
            $aRv['cst:' . $sKey] = _t($sTmp);
        };
        return $aRv;
    }
    
    public function sendTest($sEmail, $iCampaignId)
    {
        $aTmp = $this->getDataForCampaign($iCampaignId);
        $aTemplate = $aTmp[0];
        $aCustomHeaders = $aTmp[1];
         
        if (!$aTemplate)
            return false;
        
        return sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, array(), BX_EMAIL_SYSTEM, 'html', false, $aCustomHeaders, false);
    }
    
    public function sendAll($iCampaignId)
    {
        $aEmails = array();
        $aTmp = $this->getDataForCampaign($iCampaignId);
        $aTemplate = $aTmp[0];
        $aCustomHeaders = $aTmp[1];
        $aCampaign = $aTmp[2];
        
        if (!$aTemplate)
            return false;
        
        $aAccounts = $this->getEmailsBySegment($aCampaign['segments']);
        foreach ($aAccounts as $aAccount){
            array_push($aEmails, $aAccount['email']);
            sendMail($aAccount['email'], $aTemplate['Subject'], $aTemplate['Body'], 0, array(), BX_EMAIL_MASS, 'html', false, $aCustomHeaders, true);
        }
        
        $this->_oDb->sendCampaign($iCampaignId, implode(',', $aEmails));
        return true;
    }
    
    private function getEmailsBySegment($sSegment)
    {
        $aAccounts = array();
        if (substr_count($sSegment, 'lvl:') > 0){
            
            $sLvl = str_replace('lvl:', '', $sSegment);
            switch ($sLvl) {
                case 0:
                    $oAccountQuery = BxDolAccountQuery::getInstance();
                    $aAccounts = $oAccountQuery->getAccounts(array('type' => 'all'));
                    break;
                case MEMBERSHIP_ID_UNCONFIRMED:
                    $oAccountQuery = BxDolAccountQuery::getInstance();
                    $aAccounts = $oAccountQuery->getAccounts(array('type' => 'unconfirmed'));
                    break;
                case MEMBERSHIP_ID_SUSPENDED:
                    $aAccounts = $this->_oDb->getAccountsByAcl($this->_oDb->prepareAsString(" AND `tp`.`status`=?", 'suspended'));
                    break;
                case MEMBERSHIP_ID_PENDING:
                    $aAccounts = $this->_oDb->getAccountsByAcl($this->_oDb->prepareAsString(" AND `tp`.`status`=?", 'pending'));
                    break;
                case MEMBERSHIP_ID_STANDARD:
                    $aAccounts = $this->_oDb->getAccountsByAcl($this->_oDb->prepareAsString("  AND `tp`.`id` NOT IN (SELECT `IDMember` FROM `sys_acl_levels_members`) ", $sLvl));
                    break;
                default:
                    $aAccounts = $this->_oDb->getAccountsByAcl($this->_oDb->prepareAsString(" AND `tp`.`id` IN (SELECT `IDMember` FROM `sys_acl_levels_members` WHERE IDLevel = ?) ", $sLvl));
                    break;
            }
        }
        else{
            //for custom segments;
        }
        return $aAccounts;
    }
    
    private function getDataForCampaign($iCampaignId)
    {
        $aCampaign = $this->_oDb->getCampaignInfoById($iCampaignId);
        $oEmailTemplates = BxDolEmailTemplates::getInstance();
        $aTemplate = $oEmailTemplates->parseTemplate('bx_massmailer_email', array('Content' => $aCampaign['body']));
        $aTemplate['Subject'] = $aCampaign['subject'];
        
        $aCustomHeaders = array();
        if ($aCampaign['from_name'] != '')
            $aCustomHeaders['From'] = $aCampaign['from_name'];
        if ($aCampaign['reply_to'] != '')
            $aCustomHeaders['Reply-To'] = $aCampaign['reply_to'];
        
        return array($aTemplate, $aCustomHeaders, $aCampaign);
    }
    
    
}

/** @} */
