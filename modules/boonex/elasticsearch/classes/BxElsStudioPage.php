<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_DOL_STUDIO_MOD_TYPE_MANAGE', 'manage');

class BxElsStudioPage extends BxTemplStudioModule
{
	protected $_sModule;
	protected $_oModule;

    function __construct($sModule = "", $sPage = "")
    {
    	$this->_sModule = 'bx_elasticsearch';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $sPage);

        $this->aMenuItems[] = array('name' => BX_DOL_STUDIO_MOD_TYPE_MANAGE, 'icon' => 'wrench', 'title' => '_bx_elasticsearch_lmi_cpt_manage');

        $this->_oModule->_oTemplate->addStudioJs(array('jquery.anim.js', 'jquery.form.min.js', 'manage.js'));
    }

	protected function getManage()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sForm = $CNF['OBJECT_FORM_MANAGE'];
        $sFormDisplay = $CNF['OBJECT_FORM_MANAGE_DISPLAY_INDEX'];

        $oForm = BxDolForm::getObjectInstance($sForm, $sFormDisplay, $this->_oModule->_oTemplate);
        if(!$oForm)
            return '';

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sIndex = $oForm->getCleanValue('index');
            $sType = $oForm->getCleanValue('type');

            if(BxDolCronQuery::getInstance()->addTransientJobService($sFormDisplay, array($this->_sModule, 'index', array($sIndex, $sType)))) {
                $sJsObject = $this->_oModule->_oConfig->getJsObject('manage');
				$sNotification = BxTemplStudioFunctions::getInstance()->inlineBox('', $this->_oModule->_oTemplate->parseHtmlByName('manage_form_result_inline.html', array(
					'content' => _t('_bx_elasticsearch_msg_index_planned')
				)), true);

			    $aResult = array('code' => 0, 'notification' => $sNotification, 'eval' => $sJsObject . ".onIndex(oData);");
            }
            else 
                $aResult = array('code' => 1, 'message' => _t('_error occured'));

            echoJson($aResult);
            exit;
        }         

        return $oForm->getCode();
    }
}

/** @} */
