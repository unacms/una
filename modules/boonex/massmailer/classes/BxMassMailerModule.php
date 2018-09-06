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
    private $aSegments = array();
    
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
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
        $this->aSegments = $aRv;
    }
    
    /**
     * ACTION METHODS
     */
    public function actionSetSeenMark($Code)
    {
        //header('Content-Type: image/png');
        if (isset($Code) && trim($Code) != "")
            $this->_oDb->updateDateSeenForLetter($Code);
    }
    
    /**
     * SERVICE METHODS
     */
    
    /**
     * @page service Service Calls
     * @section bx_massmailer Mass mailer
     * @subsection bx_massmailer-page_blocks Page Blocks
     * @subsubsection bx_massmailer-entity_view entity_view
     * 
     * @code bx_srv('bx_massmailer', 'get_results_search_extended', [...]); @endcode
     * 
     * Get page block with the campagn info
     *
     * @param $aParams an array with search params.
     * @return HTML string with block content to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxMassMailerModule::serviceEntityView
     */
    /** 
     * @ref bx_massmailer-entity_view "entity_view"
     */
    public function serviceEntityView ($iContentId = 0, $sDisplay = false)
    {
        if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iContentId)
            return false;
        
        $aData = $this->_oDb->getLettersByCampaignId($iContentId);
        if (count($aData) > 0)
            return $this->_oTemplate->getCampagnInfo($aData);
        return $iContentId;
    }
    
    public function getSegments($sKey = "")
    {
        if ($sKey == ""){
            return $this->aSegments;
        }
        else{
            if (isset($this->aSegments[$sKey]))
                return $this->aSegments[$sKey];
            return false;
        }
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
            $sCode = $this->_oDb->addLetter($iCampaignId, $aAccount['email']);
            $aAccount['email'] = 'user251@roma1.ru';
            sendMail($aAccount['email'], $aTemplate['Subject'], $aTemplate['Body'], 0, array('seen_image_url' => $this->getSeenImageUrl($sCode)), BX_EMAIL_MASS, 'html', false, $aCustomHeaders, true);
        }
        
        $this->_oDb->sendCampaign($iCampaignId, implode(',', $aEmails));
        return true;
    }
    
    public function getEmailCountInSegment($iCampaignId)
    {
        $aTmp = $this->getDataForCampaign($iCampaignId);
        $aCampaign = $aTmp[2];
        $aAccounts = $this->getEmailsBySegment($aCampaign['segments']);
        return count($aAccounts);
    }
    
    private function getEmailsBySegment($sSegment)
    {
        $aAccounts = array();
        if (substr_count($sSegment, 'lvl:') > 0){ 
            $sLvl = str_replace('lvl:', '', $sSegment);
            switch ($sLvl) {
                case 0:
                    $aAccounts = $this->_oDb->getAccountsByTerms();
                    break;
                case MEMBERSHIP_ID_UNCONFIRMED:
                    $aAccounts = $this->_oDb->getAccountsByTerms(" AND `ta`.`email_confirmed` = 0 AND `ta`.`phone_confirmed` = 0 ");
                    break;
                case MEMBERSHIP_ID_SUSPENDED:
                    $aAccounts = $this->_oDb->getAccountsByTerms($this->_oDb->prepareAsString(" AND `tp`.`status`=?", 'suspended'));
                    break;
                case MEMBERSHIP_ID_PENDING:
                    $aAccounts = $this->_oDb->getAccountsByTerms($this->_oDb->prepareAsString(" AND `tp`.`status`=?", 'pending'));
                    break;
                case MEMBERSHIP_ID_STANDARD:
                    $aAccounts = $this->_oDb->getAccountsByTerms($this->_oDb->prepareAsString("  AND `tp`.`id` NOT IN (SELECT `IDMember` FROM `sys_acl_levels_members`) ", $sLvl));
                    break;
                default:
                    $aAccounts = $this->_oDb->getAccountsByTerms($this->_oDb->prepareAsString(" AND `tp`.`id` IN (SELECT `IDMember` FROM `sys_acl_levels_members` WHERE IDLevel = ?) ", $sLvl));
                    break;
            }
        }
        return $aAccounts;
    }
    
    private function getDataForCampaign($iCampaignId)
    {
        $aCampaign = $this->_oDb->getCampaignInfoById($iCampaignId);
        $oEmailTemplates = BxDolEmailTemplates::getInstance();
        $aTemplate = $oEmailTemplates->parseTemplate('bx_massmailer_email', array('content' => $aCampaign['body']));
        $aTemplate['Subject'] = $aCampaign['subject'];
        
        $aCustomHeaders = array();
        if ($aCampaign['from_name'] != '')
            $aCustomHeaders['From'] = $aCampaign['from_name'];
        if ($aCampaign['reply_to'] != '')
            $aCustomHeaders['Reply-To'] = $aCampaign['reply_to'];
        
        return array($aTemplate, $aCustomHeaders, $aCampaign);
    }
    
    private function getSeenImageUrl($sKey)
    {
        return  BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'SetSeenMark/' . $sKey . "/";
    }
}

/** @} */