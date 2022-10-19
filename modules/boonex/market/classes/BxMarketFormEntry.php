<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxMarketFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_market';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $iProfileId = bx_get_logged_profile_id();

        if(isset($this->aInputs[$CNF['FIELD_TITLE']], $this->aInputs[$CNF['FIELD_NAME']])) {
            $sJsObject = $this->_oModule->_oConfig->getJsObject('form');

            $aMask = array('mask' => "javascript:%s.checkName('%s', '%s');", $sJsObject, $CNF['FIELD_TITLE'], $CNF['FIELD_NAME']);
            if($this->aParams['display'] == $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'] && bx_get('id') !== false) {
                $aMask['mask'] = "javascript:%s.checkName('%s', '%s', %d);";
                $aMask[] = (int)bx_get('id');
            }

            $sOnBlur = call_user_func_array('sprintf', array_values($aMask)); 
            $this->aInputs[$CNF['FIELD_TITLE']]['attrs']['onblur'] = $sOnBlur;
            $this->aInputs[$CNF['FIELD_NAME']]['attrs']['onblur'] = $sOnBlur;
        }

        if(isset($this->aInputs[$CNF['FIELD_COVER_RAW']]))
            $this->aInputs[$CNF['FIELD_COVER_RAW']]['code'] = 1;

        if(isset($this->aInputs[$CNF['FIELD_FILE']])) {
            $this->aInputs[$CNF['FIELD_FILE']]['storage_object'] = $CNF['OBJECT_STORAGE_FILES'];
            $this->aInputs[$CNF['FIELD_FILE']]['uploaders'] =  !empty($this->aInputs[$CNF['FIELD_FILE']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_FILE']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_FILE']]['images_transcoder'] = '';
            $this->aInputs[$CNF['FIELD_FILE']]['storage_private'] = 1;
            $this->aInputs[$CNF['FIELD_FILE']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_FILE']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['ghost_template'] = '';
        }

        $bRecurring = $this->_oModule->_oDb->getParam($CNF['OPTION_ENABLE_RECURRING']) == 'on';
        if(!$bRecurring) {
            $this->aInputs[$CNF['FIELD_DURATION_RECURRING']]['type'] = 'hidden';
            $this->aInputs[$CNF['FIELD_PRICE_RECURRING']]['type'] = 'hidden';
            $this->aInputs[$CNF['FIELD_PRICE_RECURRING']]['value'] = 0;

            unset($this->aInputs[$CNF['FIELD_HEADER_BEG_RECURRING']]);
            unset($this->aInputs[$CNF['FIELD_HEADER_END_RECURRING']]);
        }

        $oPayment = BxDolPayments::getInstance();
        if(isset($this->aInputs[$CNF['FIELD_WARNING_SINGLE']])) {
            if(!$oPayment->isAcceptingPayments($iProfileId, BX_PAYMENT_TYPE_SINGLE)) 
                $this->aInputs[$CNF['FIELD_WARNING_SINGLE']]['value'] = MsgBox(_t($this->aInputs[$CNF['FIELD_WARNING_SINGLE']]['value'], $oPayment->getDetailsUrl()));
            else 
                unset($this->aInputs[$CNF['FIELD_WARNING_SINGLE']]);
        }

        if(isset($this->aInputs[$CNF['FIELD_WARNING_RECURRING']])) {
            if($bRecurring && !$oPayment->isAcceptingPayments($iProfileId, BX_PAYMENT_TYPE_RECURRING))
                $this->aInputs[$CNF['FIELD_WARNING_RECURRING']]['value'] = MsgBox(_t($this->aInputs[$CNF['FIELD_WARNING_RECURRING']]['value'], $oPayment->getDetailsUrl()));
            else 
                unset($this->aInputs[$CNF['FIELD_WARNING_RECURRING']]);
        }

        if(isset($this->aInputs[$CNF['FIELD_ALLOW_PURCHASE_TO']]))
            $this->aInputs[$CNF['FIELD_ALLOW_PURCHASE_TO']] = BxDolPrivacy::getGroupChooser($CNF['OBJECT_PRIVACY_PURCHASE']);

        $aDynamicGroups = array(
            array ('key' => '', 'value' => '----'),
            array ('key' => 'c', 'value' => _t('_bx_market_privacy_group_customers'))
        );

        if(isset($this->aInputs[$CNF['FIELD_ALLOW_COMMENT_TO']])) {
            $this->aInputs[$CNF['FIELD_ALLOW_COMMENT_TO']] = BxDolPrivacy::getGroupChooser($CNF['OBJECT_PRIVACY_COMMENT'], $iProfileId, array('dynamic_groups' => $aDynamicGroups));
            $this->aInputs[$CNF['FIELD_ALLOW_COMMENT_TO']]['db']['pass'] = 'Xss';
        }

        if(isset($this->aInputs[$CNF['FIELD_ALLOW_VOTE_TO']])) {
            $this->aInputs[$CNF['FIELD_ALLOW_VOTE_TO']] = BxDolPrivacy::getGroupChooser($CNF['OBJECT_PRIVACY_VOTE'], $iProfileId, array('dynamic_groups' => $aDynamicGroups));
            $this->aInputs[$CNF['FIELD_ALLOW_VOTE_TO']]['db']['pass'] = 'Xss';
        }

        if(isset($this->aInputs[$CNF['FIELD_SUBENTRIES']]) && $this->_oModule->checkAllowedSetSubentries() !== CHECK_ACTION_RESULT_ALLOWED)
            unset($this->aInputs[$CNF['FIELD_SUBENTRIES']]);
    }

    function getCode($bDynamicMode = false)
    {
        $sCss = $this->_oModule->_oTemplate->addCss([BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css'], $bDynamicMode);
    	$sJs = $this->_oModule->_oTemplate->addJs([
            'codemirror/codemirror.min.js',
            'form.js'
        ], $bDynamicMode);

        $sCode = '';
        if($bDynamicMode)
            $sCode .= $sCss . $sJs;

        $sCode .= $this->_oModule->_oTemplate->getJsCode('form');
        $sCode .= parent::getCode($bDynamicMode);

        return $sCode;
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_SUBENTRIES']]) && !empty($aValues[$CNF['FIELD_ID']])) {
            $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION_SUBENTRIES']);
            if($oConnection)
                $this->aInputs[$CNF['FIELD_SUBENTRIES']]['value'] = $oConnection->getConnectedContent((int)$aValues[$CNF['FIELD_ID']]);
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

	public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_NAME']])) {
            $sName = BxDolForm::getSubmittedValue($CNF['FIELD_NAME'], $this->aFormAttrs['method'], $this->_aSpecificValues);
            $sName = $this->_oModule->_oConfig->getEntryName($sName);
            BxDolForm::setSubmittedValue($CNF['FIELD_NAME'], $sName, $this->aFormAttrs['method'], $this->_aSpecificValues);
        }

	    if($this->_oModule->checkAllowedSetCover() === CHECK_ACTION_RESULT_ALLOWED) {
            $aCover = isset($_POST[$CNF['FIELD_COVER']]) ? bx_process_input ($_POST[$CNF['FIELD_COVER']], BX_DATA_INT) : false;

            $aValsToAdd[$CNF['FIELD_COVER']] = 0;
            if(!empty($aCover) && is_array($aCover) && ($iFileCover = array_pop($aCover)))
                $aValsToAdd[$CNF['FIELD_COVER']] = $iFileCover;
        }

        $aPackage = bx_process_input(bx_get($CNF['FIELD_PACKAGE']), BX_DATA_INT);
		$aValsToAdd[$CNF['FIELD_PACKAGE']] = 0;
		if(!empty($aPackage) && is_array($aPackage) && ($iFilePackage = array_pop($aPackage)))
			$aValsToAdd[$CNF['FIELD_PACKAGE']] = $iFilePackage;

        $iContentId = parent::insert ($aValsToAdd, $isIgnore);
        if(!empty($iContentId))
            $this->processFiles($CNF['FIELD_FILE'], $iContentId, true, $CNF['OBJECT_STORAGE_FILES']);

        return $iContentId;
    }

	function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        

        $sNameKey = $CNF['FIELD_NAME'];
        if(isset($this->aInputs[$sNameKey])) {
            $sName = $this->getCleanValue($sNameKey);
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

            if($aContentInfo[$sNameKey] != $sName) {
                $sName = $this->_oModule->_oConfig->getEntryName($sName);
                BxDolForm::setSubmittedValue($sNameKey, $sName, $this->aFormAttrs['method'], $this->_aSpecificValues);
            }
        }

	    if($this->_oModule->checkAllowedSetCover() === CHECK_ACTION_RESULT_ALLOWED) {
            $aCover = bx_process_input (bx_get($CNF['FIELD_COVER']), BX_DATA_INT);

            $aValsToAdd[$CNF['FIELD_COVER']] = 0;
            if (!empty($aCover) && is_array($aCover) && ($iFileCover = array_pop($aCover)))
                $aValsToAdd[$CNF['FIELD_COVER']] = $iFileCover;
        }

		$aPackage = bx_process_input(bx_get($CNF['FIELD_PACKAGE']), BX_DATA_INT);
		$aValsToAdd[$CNF['FIELD_PACKAGE']] = 0;
		if(!empty($aPackage) && is_array($aPackage) && ($iFilePackage = array_pop($aPackage)))
			$aValsToAdd[$CNF['FIELD_PACKAGE']] = $iFilePackage;

        $iResult = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        $this->processFiles($CNF['FIELD_FILE'], $iContentId, false, $CNF['OBJECT_STORAGE_FILES']);

        return $iResult;
    }

	function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bResult = parent::delete($iContentId, $aContentInfo);
        if(!$bResult)
            return $bResult;

        // delete associated files
        if (!empty($CNF['OBJECT_STORAGE_FILES'])) {
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_FILES']);
            if($oStorage)
                $oStorage->queueFilesForDeletionFromGhosts($aContentInfo[$CNF['FIELD_AUTHOR']], $iContentId);
        }

        // delete associations
        $bResult &= $this->_oModule->_oDb->deassociatePhotoWithContent($iContentId, 0);
        $bResult &= $this->_oModule->_oDb->deassociateFileWithContent($iContentId, 0);

        return $bResult;
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField = '')
    {
        parent::_associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField);

        $sStorage = $oStorage->getObject();
        switch($sStorage) {
        	case $this->_oModule->_oConfig->CNF['OBJECT_STORAGE']:
        		$this->_oModule->_oDb->associatePhotoWithContent($iContentId, $iFileId, $this->getCleanValue('title-' . $iFileId));
        		break;

        	case $this->_oModule->_oConfig->CNF['OBJECT_STORAGE_FILES']:
        		$aParams = array(
        			'type' => $this->getCleanValue('type-' . $iFileId)        			
        		);

        		switch ($aParams['type']) {
        			case BX_MARKET_FILE_TYPE_VERSION:
        				$aParams = array_merge($aParams, array(
        					'version' => $this->getCleanValue('version-' . $iFileId),
        				));
        				break;
        				
        			case BX_MARKET_FILE_TYPE_UPDATE:
        				$aParams = array_merge($aParams, array(
        					'version' => $this->getCleanValue('version-from-' . $iFileId),
        					'version_to' => $this->getCleanValue('version-to-' . $iFileId),
        				));
        				break;
        		}

        		$this->_oModule->_oDb->associateFileWithContent($iContentId, $iFileId, $aParams);
        		break;
        }
    }

    protected function _getPhotoGhostTmplVars($aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = parent::_getPhotoGhostTmplVars($aContentInfo);
        $aResult = array_merge($aResult, array(
            'cover_id' => isset($aContentInfo[$CNF['FIELD_COVER']]) ? $aContentInfo[$CNF['FIELD_COVER']] : 0,
            'bx_if:set_cover' => array (
                'condition' => CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedSetCover(),
                'content' => array (
                    'name_cover' => $CNF['FIELD_COVER'],
                ),
            ),
        ));

        return $aResult;
    }

    protected function _getFileGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = parent::_getFileGhostTmplVars($aContentInfo);
        $aResult = array_merge($aResult, array(
            'thumb_id' => isset($aContentInfo[$CNF['FIELD_PACKAGE']]) ? $aContentInfo[$CNF['FIELD_PACKAGE']] : 0,
            'bx_if:set_thumb' => array (
                'condition' => true,
                'content' => array(
                    'name_thumb' => $CNF['FIELD_PACKAGE'],
                ),
            ),
        ));

    	return $aResult;
    }

    function genInputPrice(&$aInput)
    {
        if(!isset($aInput['value_currency']))
            $aInput['value_currency'] = BxDolPayments::getInstance()->getCurrencyCode(bx_get_logged_profile_id());

        return parent::genInputPrice($aInput);
    }

    protected function genCustomInputSubentries ($aInput)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'get_subentries';
        $aInput['placeholder'] = _t('_bx_market_form_entry_input_subentries_placeholder');

        $sVals = '';
        if(!empty($aInput['value']) && is_array($aInput['value'])) {
            foreach($aInput['value'] as $iValue) {
                $iValue = (int)$iValue;
                if(!$iValue)
                    continue;

                $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iValue);
                if(empty($aContentInfo) || !is_array($aContentInfo))
                    continue;
                
               $sVals .= '<b class="val bx-def-color-bg-hl bx-def-round-corners">' . $aContentInfo[$CNF['FIELD_TITLE']] . '<input type="hidden" name="' . $aInput['name'] . '[]" value="' . $iValue . '" /></b>';
            }
            $sVals = trim($sVals, ',');
        }
        $aInput['value'] = $sVals;

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
}

/** @} */
