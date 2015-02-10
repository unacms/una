<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Create/edit entry form
 */
class BxBaseModGeneralFormEntry extends BxTemplFormView
{
    protected $MODULE;

    protected $_oModule;

    protected $_aMetatagsFieldsWithKeywords = array();
    protected $_oMetatagsObject = null;
    protected $_oMetatagsContentId = 0;

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($CNF['FIELD_AUTHOR']) && empty($aValsToAdd[$CNF['FIELD_AUTHOR']]))
            $aValsToAdd[$CNF['FIELD_AUTHOR']] = bx_get_logged_profile_id ();

        if (isset($CNF['FIELD_ADDED']) && empty($aValsToAdd[$CNF['FIELD_ADDED']]))
            $aValsToAdd[$CNF['FIELD_ADDED']] = time();

        if (isset($CNF['FIELD_CHANGED']) && empty($aValsToAdd[$CNF['FIELD_CHANGED']]))
            $aValsToAdd[$CNF['FIELD_CHANGED']] = time();

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($CNF['FIELD_CHANGED']))
            $aValsToAdd[$CNF['FIELD_CHANGED']] = time();

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // delete associated files

        if (!empty($CNF['OBJECT_STORAGE'])) {
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
            if ($oStorage)
                $oStorage->queueFilesForDeletionFromGhosts($aContentInfo[$CNF['FIELD_AUTHOR']], $iContentId);
        }

        // delete associated objects data

        if (!empty($CNF['OBJECT_VIEWS'])) {
            $o = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_VOTES'])) {
            $o = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_COMMENTS'])) {
            $o = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            $oMetatags->onDeleteContent($iContentId);
        }

        // delete db record

        return parent::delete($iContentId);
    }

    protected function genCustomInputLocation ($aInput) 
    {
        $sProto = (0 == strncmp('https', BX_DOL_URL_ROOT, 5)) ? 'https' : 'http';
        $this->oTemplate->addJs($sProto . '://maps.google.com/maps/api/js?sensor=false');

        $aInput['checked'] = $this->getCleanValue($aInput['name'] . '_lat') && $this->getCleanValue($aInput['name'] . '_lng') ? 1 : 0;

        $aVars = array (
            'name' => $aInput['name'],
            'id_status' => $this->getInputId($aInput) . '_status',
            'location_string' => _t('_sys_location_undefined'),
        );
        $aLocationIndexes = array ('lat', 'lng', 'country', 'state', 'city', 'zip');
        foreach ($aLocationIndexes as $sKey)
            $aVars[$sKey] = $this->getLoctionVal($aInput, $sKey);
        if ($aVars['country']) {
            $aCountries = BxDolFormQuery::getDataItems('Country');
            $aVars['location_string'] = ($aVars['city'] ? $aVars['city'] . ', ' : '') . $aCountries[$aVars['country']];
        }
        if ($this->getLoctionVal($aInput, 'lat') && $this->getLoctionVal($aInput, 'lng'))
            $aInput['checked'] = true;
        $aVars['input'] = $this->genInputSwitcher($aInput);

        return $this->oTemplate->parseHtmlByName('form_field_location.html', $aVars);
    }

    protected function getLoctionVal ($aInput, $sIndex) 
    {
        $s = $aInput['name'] . '_' . $sIndex;
        if (isset($this->_aSpecificValues[$s]))
            return $this->_aSpecificValues[$s];
        return $this->getCleanValue($s);
    }

    function addCssJs ()
    {
        if (!isset($this->aParams['view_mode']) || !$this->aParams['view_mode']) {
            if (self::$_isCssJsAdded)
                return;
            $this->_oModule->_oTemplate->addCss('forms.css');
        }

        return parent::addCssJs ();
    }

    function genViewRowValue(&$aInput)
    {
        $s = parent::genViewRowValue($aInput);

        if ($this->_oMetatagsObject && in_array($aInput['name'], $this->_aMetatagsFieldsWithKeywords) && $s)
            $s = $this->_oMetatagsObject->keywordsParse($this->_oMetatagsContentId, $s);

        return $s;
    }

    function setMetatagsKeywordsData($iId, $a, $o)
    {
        $this->_oMetatagsContentId = $iId;
        $this->_aMetatagsFieldsWithKeywords = $a;
        $this->_oMetatagsObject = $o;
    }

}

/** @} */
