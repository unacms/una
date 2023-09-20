<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Browse entries pages.
 */
class BxBaseModTextPageBrowse extends BxBaseModGeneralPageBrowse
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
        
        $iCategory = bx_process_input(bx_get('category'), BX_DATA_INT);
        if(!empty($iCategory))
            $this->addMarkers(array(
                'category_id' => $iCategory,
                'category_name' => BxDolCategory::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_CATEGORY'])->getCategoryTitle($iCategory),
        ));
    }
}

/** @} */
