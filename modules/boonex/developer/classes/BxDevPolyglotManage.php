<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     TridentModules
 *
 * @{
 */

class BxDevPolyglotManage extends BxTemplStudioGrid
{
    private $sAllUri = 'all';
    private $aActions = array(
        'restore' => 'eraser',
        'recompile' => 'refresh'
    );

    protected $oModule;
    protected $aLanguage;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');

        $this->aLanguages = BxDolLanguages::getInstance()->getLanguages();

        $iWidth = floor(100 / (count($this->aLanguages) + 1));
        $this->_aOptions['fields']['title']['width'] = $iWidth;

        foreach($this->aLanguages as $sName => $sTitle)
            $this->_aOptions['fields'][$sName] = array(
                'title' => $sTitle,
                'width' => $iWidth,
                'translatable' => '0',
                'chars_limit' => '0'
            );
    }

    public function performActionRecompile()
    {
        $aResult = array('msg' => _t(BxDolStudioLanguagesUtils::getInstance()->compileLanguage() ? '_adm_pgt_scs_recompiled' : '_adm_pgt_err_cannot_recompile_lang'));

        $this->_echoResultJson($aResult, true);
    }

    public function performActionRestore()
    {
        $aResult = array('msg' => _t(BxDolStudioLanguagesUtils::getInstance()->restoreLanguage() ? '_adm_pgt_scs_restored' : '_adm_pgt_err_cannot_restore_lang'));

        $this->_echoResultJson($aResult, true);
    }

    protected function _getCellDefault($mixedValue, $sKey, $aField, $aRow)
    {
        $sJsObject = $this->oModule->_oConfig->getJsObject('polyglot');

        $aLangNames = array_keys($this->aLanguages);
        if(in_array($sKey, $aLangNames)) {
            $bAll = false;
            $aActions = array('restore');
            $sOnclickMask = "javascript:%s.%s('%s', '%s')";

            if($aRow['uri'] == $this->sAllUri) {
                $bAll = true;
                $aActions[] = 'recompile';
                $sOnclickMask = "javascript:%s.%s('%s')";
            }

            $mixedValue = '';
            foreach($aActions as $sAction) {
                $sIcon = $this->aActions[$sAction];
                $sTitle = _t('_bx_dev_pgt_txt_manage_' . $sAction . '_title' . ($bAll ? '_plur' : '_sing'));
                $sContent = _t('_bx_dev_pgt_txt_manage_' . $sAction);

                $mixedValue .= $this->_oTemplate->parseHtmlByName('bx_btn.html', array(
                    'title' => sprintf($sTitle, $aField['title'], $aRow['title']),
                    'bx_repeat:attrs' => array(
                        array('key' => 'class', 'value' => 'bx-btn bx-def-margin-sec-left-auto'),
                        array('key' => 'onclick', 'value' => sprintf($sOnclickMask, $sJsObject, $sAction, $sKey, $aRow['uri']))
                    ),
                    'content' => $this->_oTemplate->parseHtmlByName('bx_icon.html', array('name' => $sIcon))
                ));
            }
        }

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= " AND `name`<>'system'";

        $aData = array(
            array('id' => 0, 'title' => _t('_bx_dev_pgt_txt_manage_modules_all'), 'uri' => $this->sAllUri)
        );
        return array_merge($aData, parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage));
    }
}
/** @} */
