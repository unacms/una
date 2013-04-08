<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once("BxPmtProvider.php");

define('PP_MODE_LIVE', 1);
define('PP_MODE_TEST', 2);

define('PP_PRC_TYPE_DIRECT', 1);
define('PP_PRC_TYPE_PDT', 2);
define('PP_PRC_TYPE_IPN', 3);

define('PP_CNT_TYPE_SSL', 1);
define('PP_CNT_TYPE_HTML', 2);

class BxPmtPayPal extends BxPmtProvider {
    var $_sDataReturnUrl;

    /**
     * Constructor
     */
    function BxPmtPayPal($oDb, $oConfig, $aConfig) {
        parent::BxPmtProvider($oDb, $oConfig, $aConfig);
        $this->_bRedirectOnResult = false;

        $this->_sDataReturnUrl = $this->_oConfig->getDataReturnUrl() . $this->_sName . '/';
    }
    function needRedirect() {
        return $this->_bRedirectOnResult;
    }
    function initializeCheckout($iPendingId, $aCartInfo, $bRecurring = false, $iRecurringDays = 0) {
        $iMode = (int)$this->getOption('mode');
        $sActionURL = $iMode == PP_MODE_LIVE ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';

        if($bRecurring)
            $aFormData = array(
                'cmd' => '_xclick-subscriptions',
                'a3' => sprintf("%.2f", (float)$aCartInfo['items_price']),
                'p3' => $iRecurringDays,
                't3' => 'D',
                'src' => '1', // repeat billings unles member cancels subscription
                'sra' => '1' // reattempt on failure
            );
        else
            $aFormData = array(
                'cmd' => '_xclick',
                'amount' => sprintf( "%.2f", (float)$aCartInfo['items_price'])
            );

        $aFormData = array_merge($aFormData, array(
            'business' => $iMode == PP_MODE_LIVE ? $this->getOption('business') : $this->getOption('sandbox'),
            'item_name' => _t('_payment_txt_payment_to', $aCartInfo['vendor_username']),
            'item_number' => $iPendingId,
            'currency_code' => $aCartInfo['vendor_currency_code'],
            'no_note' => '1',
            'no_shipping' => '1',
            'custom' => md5($aCartInfo['vendor_id'] . $iPendingId)
        ));

        $iIndex = 1;
        foreach($aCartInfo['items'] as $aItem)
            $aFormData['item_name'] .= ' ' . ($iIndex++) . '. ' . $aItem['title'];

        switch($this->getOption('prc_type')) {
            case PP_PRC_TYPE_PDT:
            case PP_PRC_TYPE_DIRECT:
                $aFormData = array_merge($aFormData, array(
                    'return' => $this->_sDataReturnUrl . $aCartInfo['vendor_id'],
                    'rm' => '2'
                ));
                break;
            case PP_PRC_TYPE_IPN:
                $aFormData = array_merge($aFormData, array(
                    'return' => $this->_oConfig->getReturnUrl(),
                    'notify_url' => $this->_sDataReturnUrl . $aCartInfo['vendor_id'],
                    'rm' => '1'
                ));
                break;
        }

        Redirect($sActionURL, $aFormData, 'post', $this->_sCaption);
        exit();
    }
    function finalizeCheckout(&$aData) {
        if($aData['txn_type'] == 'web_accept')
            return $this->_registerCheckout($aData);

        return array('code' => 2, 'message' => _t('_payment_pp_err_no_data_given'));
    }

    /**
     *
     * @param $aData - data from payment provider.
     * @param $bSubscription - Is not needed. May be used in the future for subscriptions.
     * @param $iPendingId - Is not needed. May be used in the future for subscriptions.
     * @return array with results.
     */
    function _registerCheckout(&$aData, $bSubscription = false, $iPendingId = 0) {
        if(empty($this->_aOptions) && isset($aData['item_number']))
            $this->_aOptions = $this->getOptionsByPending($aData['item_number']);

        if(empty($this->_aOptions))
            return array('code' => -1, 'message' => _t('_payment_pp_err_no_vendor_given'));

        $iPrcType = (int)$this->getOption('prc_type');
        if(($iPrcType == PP_PRC_TYPE_IPN || $iPrcType == PP_PRC_TYPE_DIRECT) && (!isset($aData['item_number']) || !isset($aData['txn_id'])))
            return array('code' => 2, 'message' => _t('_payment_pp_err_no_data_given'));
        else if($iPrcType == PP_PRC_TYPE_PDT && !isset($aData['tx']))
            return array('code' => 2, 'message' => _t('_payment_pp_err_no_data_given'));

        $aResult = $this->_validateCheckout($aData);

        if(!$bSubscription || empty($iPendingId))
            $iPendingId = (int)$aData['item_number'];

        $aPending = $this->_oDb->getPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']))
            return array('code' => -1, 'message' => _t('_payment_pp_err_already_processed'));

        //--- Update pending transaction ---//
        $this->_oDb->updatePending($iPendingId, array(
            'order' => $aData['txn_id'],
            'error_code' => $aResult['code'],
            'error_msg' => $aResult['message']
        ));

        $aResult['pending_id'] = $iPendingId;
        return $aResult;
    }

    function _validateCheckout(&$aData) {
        $iMode = (int)$this->getOption('mode');
        if($iMode == PP_MODE_LIVE) {
           $sBusiness = $this->getOption('business');
           $sConnectionUrl = 'www.paypal.com';
        }
        else {
            $sBusiness = $this->getOption('sandbox');
            $sConnectionUrl = 'www.sandbox.paypal.com';
        }

        $iPrcType = $this->getOption('prc_type');
        if($iPrcType == PP_PRC_TYPE_DIRECT || $iPrcType == PP_PRC_TYPE_IPN) {
            if($aData['payment_status'] != 'Completed' )
                return array('code' => 0, 'message' => _t('_payment_pp_err_not_completed'));

            if($aData['business'] != $sBusiness)
                return array('code' => -1, 'message' => _t('_payment_pp_err_wrong_business'));

            $sRequest = 'cmd=_notify-validate';
            foreach($aData as $sKey => $sValue) {
            	if(in_array($sKey, array('cmd')))
                    continue;

                $sRequest .= '&' . urlencode($sKey) . '=' . urlencode(process_pass_data($sValue));
            }
            $aResponse = $this->_readValidationData($sConnectionUrl, $sRequest);

            if((int)$aResponse['code'] !== 0)
               return $aResponse;

            array_walk($aResponse['content'], create_function('&$arg', "\$arg = trim(\$arg);"));
            if(strcmp($aResponse['content'][0], "INVALID") == 0)
                return array('code' => -1, 'message' => _t('_payment_pp_err_wrong_transaction'));
            else if(strcmp($aResponse['content'][0], "VERIFIED") != 0)
                return array('code' => 2, 'message' => _t('_payment_pp_err_wrong_verification_status'));
        }
        else if($iPrcType == PP_PRC_TYPE_PDT) {
            $sRequest = "cmd=_notify-synch&tx=" . $aData['tx'] . "&at=" . $this->getOption('token');
            $aResponse = $this->_readValidationData($sConnectionUrl, $sRequest);

            if((int)$aResponse['code'] !== 0)
               return $aResponse;

            if(strcmp($aResponse['content'][0], "FAIL") == 0)
                return array('code' => -1, 'message' => _t('_payment_pp_err_wrong_transaction'));
            else if(strcmp($aResponse['content'][0], "SUCCESS") != 0)
                return array('code' => 2, 'message' => _t('_payment_pp_err_wrong_verification_status'));

            $aKeys = array();
            foreach($aResponse['content'] as $sLine) {
                list($sKey, $sValue) = explode("=", $sLine);
                $aKeys[urldecode($sKey)] = urldecode($sValue);
            }

            $aData = array_merge($aData, $aKeys);

            if($aData['payment_status'] != 'Completed' )
                return array('code' => 0, 'message' => _t('_payment_pp_err_not_completed'));

            if($aData['business'] != $sBusiness)
                return array('code' => -1, 'message' => _t('_payment_pp_err_wrong_business'));
        }

        $aPending = $this->_oDb->getPending(array('type' => 'id', 'id' => $aData['item_number']));
        $aVendor = $this->_oDb->getVendorInfoProfile($aPending['seller_id']);
        $fAmount = (float)$this->_getReceivedAmount($aVendor['currency_code'], $aData);
        if($fAmount != (float)$aPending['amount'])
            return array('code' => -1, 'message' => _t('_payment_pp_err_wrong_amount'));

        if($aData['custom'] != md5($aPending['seller_id'] . $aPending['id']))
            return array('code' => -1, 'message' => _t('_payment_pp_err_wrong_custom_data'));

        return array('code' => 1, 'message' => _t('_payment_pp_msg_verified'));
    }

    function _readValidationData($sConnectionUrl, $sRequest) {
        $sHeader = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $sHeader .= "Host: " . $sConnectionUrl . "\r\n";
        $sHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $sHeader .= "Content-Length: " . strlen($sRequest) . "\r\n";
        $sHeader .= "Connection: close\r\n\r\n";

        $iErrCode = 0;
        $sErrMessage = "";
        if($this->getOption('cnt_type') == PP_CNT_TYPE_SSL)
            $rSocket = fsockopen("ssl://" . $sConnectionUrl, 443, $iErrCode, $sErrMessage, 60);
        else
            $rSocket = fsockopen("tcp://" . $sConnectionUrl, 80, $iErrCode, $sErrMessage, 60);

        if(!$rSocket)
            return array('code' => 2, 'message' => 'Can\'t connect to remote host for validation (' . $sErrMessage . ')');

        fputs($rSocket, $sHeader);
        fputs($rSocket, $sRequest);

        $sResponse = '';
        while(!feof($rSocket))
            $sResponse .= fread($rSocket, 1024);
        fclose($rSocket);

        list($sResponseHeader, $sResponseContent) = explode("\r\n\r\n", $sResponse);

        return array('code' => 0, 'content' => explode("\n", $sResponseContent));
    }
    function _getReceivedAmount($sCurrencyCode, &$aResultData) {
        $fAmount = 0.00;

        if($aResultData['mc_currency'] == $sCurrencyCode && isset($aResultData['payment_gross']) && !empty($aResultData['payment_gross']))
            $fAmount = (float)$aResultData['payment_gross'];
        else if($aResultData['mc_currency'] == $sCurrencyCode && isset($aResultData['mc_gross']) && !empty($aResultData['mc_gross']))
            $fAmount = (float)$aResultData['mc_gross'];
        else if($aResultData['settle_currency'] == $sCurrencyCode && isset($aResultData['settle_amount']) && !empty($aResultData['settle_amount']))
            $fAmount = (float)$aResultData['settle_amount'];

        return $fAmount;
    }
}
?>