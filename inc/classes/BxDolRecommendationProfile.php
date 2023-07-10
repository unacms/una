<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxDolRecommendationProfile extends BxTemplRecommendation
{
    protected function __construct($aOptions, $oTemplate)
    {
        parent::__construct($aOptions, $oTemplate);
    }

    public function actionAdd($iProfileId = 0, $iItemId = 0)
    {
        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        if(!$iItemId)
            $iItemId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!empty($this->_aObject['connection'])) {
            $aResult = BxDolConnection::getObjectInstance($this->_aObject['connection'])->actionAdd($iItemId, $iProfileId);
            if($aResult['err'] == true)
                return ['code' => 2, 'msg' => $aResult['msg']];
        }

        if(!$this->add($iProfileId, $iItemId))
            return ['code' => 1, 'msg' => '_sys_txt_error_occured'];

        return ['code' => 0];
    }
}

/** @} */
