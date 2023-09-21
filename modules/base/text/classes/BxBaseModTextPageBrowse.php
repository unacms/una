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

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!empty($CNF['OBJECT_CATEGORY']) && ($iCategory = bx_get('category')) !== false) {
            $iCategory = bx_process_input($iCategory, BX_DATA_INT);

            $this->addMarkers([
                'category_id' => $iCategory,
                'category_name' => BxDolCategory::getObjectInstance($CNF['OBJECT_CATEGORY'])->getCategoryTitle($iCategory),
            ]);
        }
    }
}

/** @} */
