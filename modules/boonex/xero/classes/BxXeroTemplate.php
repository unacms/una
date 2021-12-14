<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Xero Xero
 * @ingroup     UnaModules
 *
 * @{
 */

class BxXeroTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getIncludeCssJs()
    {
        return '';
    }

    public function getBlockAuthorize($iCode = 0)
    {
        $oApi = $this->getModule()->getApi();

        $bTmplVarsMessage = $iCode !== false;
        if(!$bTmplVarsMessage && $oApi->isAuthorized()) {
            $iCode = 0;
            $bTmplVarsMessage = true;
        }

        $aTmplVarsMessage = [];
        if($bTmplVarsMessage) {
            $iCode = (int)$iCode;
            switch($iCode) {
                case 0;
                    $sMessage = '_bx_xero_txt_msg_authorized';
                    break;

                case 1:
                    $sMessage = '_bx_xero_txt_err_wrong_data';
                    break;

                case 2:
                    $sMessage = '_bx_xero_txt_err_invalid_state';
                    break;

                case 3:
                    $sMessage = '_bx_xero_txt_err_api_call';
                    break;
            }

            $aTmplVarsMessage = [
                'message' => MsgBox(_t($sMessage))
            ];
        }

        return $this->parseHtmlByName('authorize.html', [
            'bx_if:show_message' => [
                'condition' => $bTmplVarsMessage,
                'content' => $aTmplVarsMessage
            ],
            'url' => $oApi->authorize(),
            'js_code' => $this->getJsCode('main')
        ]);
    }
}

/** @} */
