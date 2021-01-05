<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioModules extends BxDol implements iBxDolSingleton
{
    protected $sLangPrefix;
    protected $sParamPrefix;

    protected $sActionUri;
    protected $sJsClass;
    protected $sJsObject;

    protected $_oDb;

    public function __construct()
    {
        parent::__construct();

        $this->sLangPrefix = 'mod';
        $this->sParamPrefix = 'mod';

        $this->sActionUri = 'module.php';
        $this->sJsClass = 'BxDolStudioModule';
        $this->sJsObject = 'oBxDolStudioModule';

        $this->_oDb = new BxDolStudioModulesQuery();
    }

    public static function getInstance()
    {
        $sClass = get_called_class();
        $sParent = str_replace('Templ', 'Dol', $sClass);

        if(!isset($GLOBALS['bxDolClasses'][$sParent]))
            $GLOBALS['bxDolClasses'][$sParent] = new $sClass();

        return $GLOBALS['bxDolClasses'][$sParent];
    }

    public function serviceGetActions($aWidget)
    {
        $sJsObject = $this->getJsObject();

        $aResult = array(
            array (
                'name' => 'settings',
                'caption' => _t('_adm_txt_settings'),
                'link' => '',
                'click' => $sJsObject . ".settings('" . $aWidget['page_name'] . "', " . $aWidget['id'] . ")",
                'icon' => 'cog',
                'check_func' => ''
            )
        );

        if(!BxDolModuleQuery::getInstance()->isEnabledByName($aWidget['module']))
            $aResult[] = array (
                'name' => 'uninstall',
                'caption' => _t('_adm_txt_uninstall'),
                'link' => '',
                'click' => $sJsObject . ".uninstall('" . $aWidget['page_name'] . "', " . $aWidget['id'] . ", 0)",
                'icon' => 'times',
                'check_func' => 'is_disabled'
            );

        return $aResult;
    }
}

/** @} */
