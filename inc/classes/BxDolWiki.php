<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * WIKI object.
 *
 * It's possble to create different WIKI object which will different URLs, Menus and permissions.
 * For example it's possible to create 
 * http://example.com/wiki/somepageshere and http://example.com/cocs/anotherpageshere
 *
 * @section wiki_create Creating the WIKI object:
 *
 *
 * Add record to 'sys_objects_wiki' table:
 *
 * - object: name of the WIKI object, this name will be user in URL as well, 
 *          for example, 'wiki' will have URLs like this: http://example.com/wiki/somepageshere
 * - title: title of the WIKI, for example, documentation, help, tutorial
 * - module: module name WIKI object belongs to
 * - allow_add_for_levels: allow to add pages and blocks for this member levels
 * - allow_edit_for_levels: allow to edit block for this member levels
 * - allow_delete_for_levels: allow to delet pages and blocks for this member levels
 * - allow_translate_for_levels: allow to add translations for this member levels
 * - override_class_name: user defined class name 
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 * Add record to 'sys_rewrite_rules' table:
 * - preg - regular expression which matches some URL
 * - service - service method to call if abbe regular expression matches
 * - active - active flag
 *
 * Add record to 'sys_permalinks' table:
 * - standard - how link should look like when permalinks are off
 * - permalink - how link should look like when permalinks are on, 
 *      some special server configuration may be required to make permalink to work, 
 *      such as 'mod_rewrite' and .htaccess file for Apache
 * - check - option name which enables/disables permalinks
 * - compare_by_prefix - compare by prefix
 */
class BxDolWiki extends BxDolFactory implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;

    /**
     * Constructor
     * @param $aObject array of WIKI options
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
        $this->_oQuery = new BxDolWikiQuery($aObject);
    }

    /**
     * Get WIKI object instance by object URI
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstanceByUri($sUri, $oTemplate = false)
    {
        $aObject = BxDolWikiQuery::getWikiObjectByUri($sUri);
        if (!$aObject || !is_array($aObject))
            return false;

        return self::getObjectInstance($aObject['object'], $oTemplate);
    }

    /**
     * Get WIKI object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject, $oTemplate = false)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolWiki!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolWiki!'.$sObject];

        $aObject = BxDolWikiQuery::getWikiObject($sObject);
        if (!$aObject || !is_array($aObject))
            $aObject = BxDolWikiQuery::getWikiObject('system');

        $sClass = empty($aObject['override_class_name']) ? 'BxDolWiki' : $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass($aObject, $oTemplate);

        return ($GLOBALS['bxDolClasses']['BxDolWiki!'.$sObject] = $o);
    }

    /**
     * Get object name
     */
    public function getObjectName ()
    {
        return $this->_sObject;
    }

    /**
     * Get wiki URi
     */
    public function getWikiUri ()
    {
        return $this->_aObject['uri'];
    }

    /**
     * Get WIKI block content
     * @param $iBlockId block ID
     * @param $sLang optional language name
     * @return block content
     */
    public function getBlockContent ($iBlockId, $sLang = false)
    {
        if (!$sLang)
            $sLang = bx_lang_name();
        $aWikiVer = $this->_oQuery->getBlockContent ($iBlockId, $sLang);

        require_once(BX_DIRECTORY_PATH_PLUGINS . "parsedown/Parsedown.php");
        $oParsedown = new Parsedown();
        $oParsedown->setSafeMode($aWikiVer['unsafe'] ? false : true);
        $s = $oParsedown->text($aWikiVer['content']);

        $sControls = BxDolService::call('system', 'wiki_controls', array($this, $aWikiVer, $iBlockId), 'TemplServiceWiki');

        return $s . $sControls;
    }

    /**
     * Check if partucular action is allowed
     * @param $sType action type: add, edit, delete, translate
     * @param $sProfileId profile to check, if not provided then current profile is used
     * @return true if action is allowed, false otherwise
     */
    public function isAllowed ($sType, $iProfileId = false)
    {
        if (isAdmin())
            return true;

        if ('history' == $sType)
            return true;

        $aTypes = array(
            'add' => 'allow_add_for_levels',
            'edit' => 'allow_edit_for_levels',
            'translate' => 'allow_translate_for_levels',
            'delete-version' => 'allow_delete_for_levels',
            'delete-block' => 'allow_delete_for_levels',
            'get-traaslation' => 'allow_translate_for_levels',
            'history' => true,
            'unsafe' => 'allow_unsafe_for_levels',
        );
        if (!isset($aTypes[$sType]))
            return false;

        if (true === $aTypes[$sType] || false === $aTypes[$sType])
            return $aTypes[$sType];

        if (!isset($this->_aObject[$aTypes[$sType]]))
            return false;

        $oAcl = BxDolAcl::getInstance();
        $bResult = $oAcl->isMemberLevelInSet($this->_aObject[$aTypes[$sType]], $iProfileId); 
        if ($bResult && 'translate' == $sType) {
            $aLangs = BxDolLanguages::getInstance()->getLanguages(false, true);
            return count($aLangs) > 1;
        }
        return $bResult;
    }

    public function actionGetTranslation ()
    {
        $aWikiVer = $this->_oQuery->getBlockContent ((int)bx_get('block_id'), bx_get('lang'), false, false);
        if (!$aWikiVer)
            return array('code' => 1, 'actions' => 'ShowMsg', 'msg' => 'no translation was found');
        else
            return array('code' => 0, 'lang' => $aWikiVer['lang'], 'content' => $aWikiVer['content'], 'block_id' => $aWikiVer['block_id']);
    }

    public function actionTranslate ()
    {
        return $this->actionEdit (true);
    }

    public function actionEdit ($bTranslate = false)
    {
        $iBlockId = (int)bx_get('block_id');
        $sMainLangLabel = '';
        $aWikiVerMain = array();
        $sLangForTranslate = '';
        $aLangsForInput = $this->getLangsForInput ($iBlockId, $bTranslate, $sMainLangLabel, $aWikiVerMain, $sLangForTranslate);

        // get latest revision for block with current lang
        if ($bTranslate) {
            $aWikiVer = $this->_oQuery->getBlockContent ($iBlockId, $sLangForTranslate, false, false);
        }
        else {
            $aWikiVer = $this->_oQuery->getBlockContent ($iBlockId, bx_lang_name());
        }

        unset($aWikiVer['notes']); // unset notes since we need this field empty in the form
        if (!$aWikiVer) // check for new block, so initialize with default values
            $aWikiVer = array('block_id' => $iBlockId);

        // init form object
        $oForm = BxDolForm::getObjectInstance('sys_wiki', $bTranslate ? 'sys_wiki_translate' : 'sys_wiki_edit');
        if (!$oForm)
            return _t('_sys_txt_error_occured');

        if (isset($oForm->aInputs['lang']))
            $oForm->aInputs['lang']['values'] = $aLangsForInput;
        if (isset($oForm->aInputs['content_main']) && $sMainLangLabel) {
            $oForm->aInputs['content_main']['caption'] = $sMainLangLabel;
            $oForm->aInputs['content_main']['content'] = $aWikiVerMain ? BxDolTemplate::getInstance()->parseHtmlByName('wiki_content.html', array('content' => $aWikiVerMain['content'])) : '';
        }

        $oForm->initChecker($aWikiVer);
        if (!$oForm->isSubmittedAndValid()) {
            // display form
            return BxDolTemplate::getInstance()->parseHtmlByName('wiki_form.html', array(
                'form' => $oForm->getCode(),
                'block_id' => $iBlockId,
                'wiki_action_uri' => $this->getWikiUri(),
                'action' => $bTranslate ? 'translate' : 'edit',
            ));
        } 
        else {
            $sLang = $oForm->getCleanValue('lang');

            // get previous revision with priority for current language
            $aWikiVer = $this->_oQuery->getBlockContent ($iBlockId, $sLang);

            $bMainLang = $this->getFieldMainLangFlag ($oForm, $sLang, $aWikiVer);
            $sRev = $this->getFieldRev ($oForm, $sLang, $aWikiVer);
            $bUnsafe = $this->getFieldUnsafeFlag ($oForm, $sLang, $aWikiVer);

            // insert new revision
            $iTime = time();
            if (!$bMainLang || ($bMainLang && $this->isAllowed('edit'))) {
                $sId = $oForm->insert(array(
                    'added' => $iTime, 
                    'revision' => $sRev,
                    'main_lang' => $bMainLang, 
                    'profile_id' => bx_get_logged_profile_id(),
                    'unsafe' => $bUnsafe,
                ));
            }

            // process translations if available
            if (($sTranslations = bx_get('translations')) && ($aTranslations = json_decode($sTranslations, true))) {
                foreach ($aTranslations as $sLang => $sContent) {
                    if ($sLang == $oForm->getCleanValue('lang'))
                        continue;

                    // get previous revision with priority for current language
                    $aWikiVer = $this->_oQuery->getBlockContent ($iBlockId, $sLang);

                    $bMainLang = $this->getFieldMainLangFlag ($oForm, $sLang, $aWikiVer);
                    $sRev = $this->getFieldRev ($oForm, $sLang, $aWikiVer);
                    $bUnsafe = $this->getFieldUnsafeFlag ($oForm, $sLang, $aWikiVer);

                    // insert new revision
                    if (!$bMainLang || ($bMainLang && $this->isAllowed('edit'))) {
                        $sId =  $oForm->insert(array(
                            'added' => $iTime, 
                            'revision' => $sRev,
                            'main_lang' => $bMainLang, 
                            'profile_id' => bx_get_logged_profile_id(),
                            'unsafe' => $bUnsafe,
                            'lang' => $sLang,
                            'content' => $sContent,
                        ));
                    }
                }
            }

            return array('code' => 0, 'actions' => array('Reload', 'ClosePopup'), 'block_id' => $aWikiVer['block_id']);
        }
    }

    protected function getFieldRev ($oForm, $sLang, $aWikiVer) {
        // increase revision for particular language or start with first revision
        return $aWikiVer && $sLang == $aWikiVer['lang'] ? $aWikiVer['revision'] + 1 : 1;
    }

    protected function getFieldMainLangFlag ($oForm, $sLang, $aWikiVer) {
        // detect main language flag
        if ($aWikiVer && $sLang == $aWikiVer['lang']) 
            return $aWikiVer['main_lang']; // when revision is increased (language matches) - copy this flag from previous revision
        elseif (!$aWikiVer) 
            return 1; // when it's first revision and no translations - start as main language
        else
            return 0;
    }

    protected function getFieldUnsafeFlag ($oForm, $sLang, $aWikiVer) {

        if ($aWikiVer && $sLang == $aWikiVer['lang'] && !$aWikiVer['unsafe'])
            return 0; // copy 'unsafe' from previous revision only when unsafe = 0
        else
            return $this->isAllowed('unsafe') ? 1 : 0; // in other cases - check permissions
    }

    protected function getLangsForInput ($iBlockId, $bTranslateForm, &$sMainLangLabel, &$aWikiVerMain, &$sLangForTranslate)
    {
        // get main lang update time
        $aWikiVerMain = $this->_oQuery->getBlockContent ($iBlockId, 'neverhood');
        $iUpdatedMainLang = $aWikiVerMain ? $aWikiVerMain['added'] : 0;

        // generate values for radio set
        $aLangs = BxDolLanguages::getInstance()->getLanguages(false, true);
        foreach ($aLangs as $sKey => $sLang) {
            $aWikiVer = $this->_oQuery->getBlockContent ($iBlockId, $sKey, false, false);
            $sMainLang = $aWikiVer && $aWikiVer['main_lang'] ? '★' : '';
            $sComment = !$aWikiVer ? _t('_sys_wiki_lang_missing') : bx_time_js($aWikiVer['added']);
            if (!$aWikiVer || $iUpdatedMainLang > $aWikiVer['added'])
                $aLangs[$sKey] = _t('_sys_wiki_lang_mask_warn', $sLang, $sMainLang, $sComment);
            else
                $aLangs[$sKey] = _t('_sys_wiki_lang_mask', $sLang, $sMainLang, $sComment);


            if ($bTranslateForm && !$aWikiVer['main_lang'] && !$sLangForTranslate) {
                $sLangForTranslate = $sKey;
            }

            if ($bTranslateForm && $aWikiVer['main_lang']) {
                $sMainLangLabel = $aLangs[$sKey];
                unset($aLangs[$sKey]);
                continue;
            }            
        }
        return $aLangs;
    }
}

/** @} */
