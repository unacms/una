<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Create/Edit Profile Form.
 */
class BxBaseFormProfile extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);
    }

    public function setAction($sAction, $aParams = [])
    {
        $this->aFormAttrs['action'] = bx_append_url_params($sAction, $aParams);
    }

    public function initChecker($aValues = array (), $aSpecificValues = array())
    {
        parent::initChecker($aValues, $aSpecificValues);

        if(isset($this->aInputs['cfw_value']) && !empty($aValues['cfw_items'])) {
            $aCfwValueValues = [];
            foreach($this->aInputs['cfw_value']['values'] as $iValue => $sTitle)
                if((1 << ($iValue - 1)) & (int)$aValues['cfw_items'])
                    $aCfwValueValues[$iValue] = $sTitle;

            $this->aInputs['cfw_value']['values'] = $aCfwValueValues;
        }
    }
}

/** @} */
