<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxCreditsGridHistoryAdministration extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_iUserId;
    protected $_bWithdraw;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_credits';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    	if(!$oTemplate)
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $iUserId = bx_get_logged_profile_id();
        if($iUserId !== false) {
            $this->_iUserId = (int)$iUserId;
            $this->_aQueryAppend['user_id'] = $this->_iUserId;
        }

        $this->_bWithdraw = $this->_oModule->_oConfig->isWithdraw();
    }

    public function getCode($isDisplayHeader = true)
    {
        $sResult = parent::getCode($isDisplayHeader);
        if(!empty($sResult) && $isDisplayHeader)
            $sResult = $this->_oModule->_oTemplate->getJsCode('withdraw') . $sResult;

        return $sResult;
    }

    public function performActionGrant()
    {
        $sAction = 'grant';

        $this->_performActionWithProfileAmount($sAction);
    }

    public function performActionWithdrawConfirm()
    {
        if(!$this->_bWithdraw)
            return echoJson([]);

        $sAction = 'withdraw_confirm';

        $this->_performActionWithProfileAmount($sAction);
    }

    protected function _getActionWithdrawConfirm($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bWithdraw)
            $isDisabled = true;

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getCellDirection($mixedValue, $sKey, $aField, $aRow)
    {
        if(in_array($mixedValue, [BX_CREDITS_DIRECTION_IN, BX_CREDITS_DIRECTION_OUT]))
            $mixedValue = _t('_bx_credits_txt_direction_' . $mixedValue);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellFirstPid($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getProfile($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellSecondPid($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getProfile($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAmount($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->convertC2S($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellOrder($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }

    protected function _getCellCleared($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($aRow[$CNF['FIELD_H_DIRECTION']] == BX_CREDITS_DIRECTION_IN) {
            if(empty($mixedValue)) {
                $iPeriod = $this->_oModule->_oConfig->getWithdrawClearing();
                if($iPeriod == 0)
                    $iPeriod = 1;

                $oDate = date_create('@' . $aRow[$CNF['FIELD_H_DATE']]);
                date_add($oDate, new DateInterval('P' . $iPeriod . 'D'));
                $mixedValue = date_format($oDate, 'U');
            }

            $mixedValue = bx_time_js($mixedValue, BX_FORMAT_DATE, true);
        }
        else 
            $mixedValue = '';

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getProfile($mixedValue) 
    {
        if(is_numeric($mixedValue) && (int)$mixedValue == 0)
            $mixedValue = $this->_oModule->_oConfig->getAuthor();

        $oProfile = BxDolProfile::getInstanceMagic($mixedValue);
        if(!$oProfile)
            return $mixedValue;

        return $oProfile->getUnit(0, ['template' => ['name' => 'unit', 'size' => 'icon']]);
    }

    protected function _performActionWithProfileAmount($sAction)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $iProfile = (int)$oForm->getCleanValue('profile');
            $fAmount = (float)$oForm->getCleanValue('amount');
            $sMessage = $oForm->getCleanValue('message');
            $aResult = $this->_oModule->{'process' . bx_gen_method_name($sAction)}($this->_iUserId, $iProfile, $fAmount, $sMessage);

            if((int)$aResult['code'] == 0) {
                if(!empty($aResult['id']))
                    $aRes = ['grid' => $this->getCode(false), 'blink' => $aResult['id']];
                else
                    $aRes = ['msg' => _t(!empty($aResult['msg']) ? $aResult['msg'] : '_bx_credits_msg_action_performed')];
            }
            else
                $aRes = ['msg' => _t(!empty($aResult['msg']) ? $aResult['msg'] : '_bx_credits_err_cannot_perform_action')];

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds($sAction . '_popup'), _t($CNF['T'][$sAction . '_popup']), $this->_oModule->_oTemplate->parseHtmlByName('credit_form.html', [
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            ]));

            echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
        }
    }

    protected function _getFormObject($sAction)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_CREDIT'], $CNF['OBJECT_FORM_CREDIT_DISPLAY_' . strtoupper($sAction)]);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;

        return $oForm;
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addJs([
            'jquery.form.min.js', 
            'withdraw.js'
        ]);

        $this->_oModule->_oTemplate->addCss([
            'main.css',
            'withdraw.css'
        ]);

        $oForm = new BxTemplFormView([]);
        $oForm->addCssJs();
    }
}

/** @} */
