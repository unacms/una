<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary 
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxGlsrFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_glossary';
        parent::__construct($aInfo, $oTemplate);
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        if(!BxDolAcl::getInstance()->isMemberLevelInSet(192)){
            if (getParam('bx_glossary_activate_terms_after_creation') != 'on')
                $aValsToAdd['status_admin'] = 'pending';  
        }
        return parent::insert ($aValsToAdd, $isIgnore);
    }
}

/** @} */
