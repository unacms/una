<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplFormView');

/**
 * Create/edit entry form
 */
class BxBaseModGeneralFormEntry extends BxTemplFormView
{
    protected $MODULE;

    protected $_oModule;

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

    protected function genCustomInputLocation ($aInput) 
    {
        $sProto = (0 == strncmp('https', BX_DOL_URL_ROOT, 5)) ? 'https' : 'http';
        $this->oTemplate->addJs($sProto . '://maps.google.com/maps/api/js?sensor=false');

        $aInput['checked'] = $this->getCleanValue($aInput['name'] . '_lat') && $this->getCleanValue($aInput['name'] . '_lng') ? 1 : 0;

        $aVars = array (
            'input' => $this->genInputSwitcher($aInput),
            'name' => $aInput['name'],
            'id_status' => $this->getInputId($aInput) . '_status',
            'location_string' => _t('_sys_location_undefined'),
            'lat' => $this->getCleanValue($aInput['name'] . '_lat'),
            'lng' => $this->getCleanValue($aInput['name'] . '_lng'),
            'country' => $this->getCleanValue($aInput['name'] . '_country'),
            'state' => $this->getCleanValue($aInput['name'] . '_state'),
            'city' => $this->getCleanValue($aInput['name'] . '_city'),
            'zip' => $this->getCleanValue($aInput['name'] . '_zip'),
        );
        if ($aVars['country']) {
            $aCountries = BxDolFormQuery::getDataItems('Country');
            $aVars['location_string'] = ($aVars['city'] ? $aVars['city'] . ', ' : '') . $aCountries[$aVars['country']];
        }
        return $this->oTemplate->parseHtmlByName('form_field_location.html', $aVars);
    }

    function addCssJs ()
    {
        if (!isset($this->aParams['view_mode']) || !$this->aParams['view_mode']) {
            if (self::$_isCssJsAdded)
                return;
            $this->_oModule->_oTemplate->addCss('form.css');
        }

        return parent::addCssJs ();
    }

}

/** @} */
