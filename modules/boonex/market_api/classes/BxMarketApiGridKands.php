<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    MarketApi MarketApi
 * @ingroup     TridentModules
 *
 * @{
 */

class BxMarketApiGridKands extends BxTemplGrid
{
	protected static $LENGTH_ID = 10;
	protected static $LENGTH_SECRET = 32;

	protected $MODULE;
	protected $_oModule;

	protected $_sFormClass;
	protected $_sFormClassPass;
	protected $_sFormTemplate;
	protected $_iUserId;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_market_api';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);
    	if(!$oTemplate)
			$oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);

        $this->_sFormClass = 'FormAdd';
        $this->_sFormClassPass = 'FormPass';
        $this->_sFormTemplate = 'kands_add_form.html';
        $this->_sFormTemplatePass = 'kands_pass_form.html';

        $this->_iUserId = bx_get_logged_profile_id();
    }

	public function getCode($isDisplayHeader = true)
    {
    	return $this->_oTemplate->getJsCode('kands') . parent::getCode($isDisplayHeader);
    }

    public function performActionAdd()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        bx_import($this->_sFormClass, $this->_oModule->_aModule);
        $sForm = $this->_oModule->_oConfig->getClassPrefix() . $this->_sFormClass;
        $oForm = new $sForm();
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) {
			$iNewId = BxDolService::call($CNF['OAUTH'], 'add_client', array(array(
				'title' => $oForm->getCleanValue('title'),
				'client_id' => strtolower(genRndPwd(self::$LENGTH_ID, false)),
				'client_secret' => strtolower(genRndPwd(self::$LENGTH_SECRET, false)),
				'redirect_uri' => $oForm->getCleanValue('redirect_uri'),
				'scope' => 'market',
				'parent_id' => $this->_getKeyParentId($oForm), 
				'user_id' => $this->_getKeyUserId($oForm)
			)));

            if($iNewId)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iNewId);
            else
                $aRes = array('msg' => _t('_error occured'));

            return echoJson($aRes);
        }

		echoJson(array('popup' => array('html' => BxTemplFunctions::getInstance()->transBox('', $this->_oTemplate->parseHtmlByName($this->_sFormTemplate, array(
			'js_object' => $this->_oModule->_oConfig->getJsObject('kands'),
			'form' => $oForm->getCode(),
			'form_id' => $oForm->aFormAttrs['id'],
			'object' => $this->_sObject
		))), 'options' => array('closeOnOuterClick' => false))));
    }

	public function performActionPass()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) {
                echoJson(array());
                exit;
            }

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        bx_import($this->_sFormClassPass, $this->_oModule->_aModule);
        $sForm = $this->_oModule->_oConfig->getClassPrefix() . $this->_sFormClassPass;
        $oForm = new $sForm($iId);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) {
        	$aSet = array('user_id' => $oForm->getCleanValue('user_id'));
        	$aWhere = array('id' => $iId);
            if(BxDolService::call($CNF['OAUTH'], 'update_clients_by', array($aSet, $aWhere)))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_error occured'));

            return echoJson($aRes);
        }

		echoJson(array('popup' => array('html' => BxTemplFunctions::getInstance()->transBox('', $this->_oTemplate->parseHtmlByName($this->_sFormTemplatePass, array(
			'js_object' => $this->_oModule->_oConfig->getJsObject('kands'),
			'form' => $oForm->getCode(),
			'form_id' => $oForm->aFormAttrs['id'],
			'object' => $this->_sObject
		))), 'options' => array('closeOnOuterClick' => false))));
    }

	public function performActionDelete()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            if(!BxDolService::call($CNF['OAUTH'], 'delete_clients_by', array(array('id' => $iId))))
                continue;

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_error occured')));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oTemplate->addJs(array(
        	'jquery.form.min.js',
        	'jquery-ui/jquery.ui.widget.min.js',
        	'jquery-ui/jquery.ui.menu.min.js',
        	'jquery-ui/jquery.ui.autocomplete.min.js',
        	'kands.js'
        ));

        $this->_oTemplate->addCss(array(
        	'kands.css'
        ));

        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

	protected function _getActionPass($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
		if($this->_oModule->checkAllowedPass($aRow) !== CHECK_ACTION_RESULT_ALLOWED)
			return '';

    	return $this->_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

	protected function _getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		$this->_aOptions['source'] = BxDolService::call($CNF['OAUTH'], 'get_clients_by', array(array('type' => 'user_id', 'user_id' => $this->_iUserId)));

        return parent::_getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _getKeyParentId(&$oForm)
    {
    	return 0;
    }

    protected function _getKeyUserId(&$oForm)
    {
    	return $this->_iUserId;
    }
}

/** @} */
