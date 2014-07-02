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

}

/** @} */
