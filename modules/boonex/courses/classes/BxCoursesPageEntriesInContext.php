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

class BxCoursesPageEntriesInContext extends BxBaseModGroupsPageJoinedEntries
{
    protected $MODULE;

    protected $_oModule;
    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_courses';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        
        parent::__construct($aObject, $oTemplate);
    }
    
    public function getCode ()
    {
        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if ($iProfileId) {
            if (!$this->_oModule->serviceIsEnableForContext($iProfileId)){
                $this->_oTemplate->displayPageNotFound();
                return;
            }
        }
        
        return parent::getCode();
    }
}

/** @} */
