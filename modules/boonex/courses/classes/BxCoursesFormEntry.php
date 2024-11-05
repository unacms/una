<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit Group Form.
 */
class BxCoursesFormEntry extends BxBaseModGroupsFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_courses';

        parent::__construct($aInfo, $oTemplate);

        if(isset($this->aInputs['initial_members']))
            $this->aInputs['initial_members']['value'] = [];
    }

    public function insert ($aValsToAdd = [], $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($this->_oModule->_oConfig->isContent())
            $aValsToAdd[$CNF['FIELD_STATUS']] = 'hidden';

        return parent::insert ($aValsToAdd, $isIgnore);
    }
}

/** @} */
