<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Recommendation representation.
 * @see BxDolRecommendation
 */
class BxBaseRecommendation extends BxDolRecommendation
{
    protected $_oTemplate;

    protected $_sClassWrapper;

    public function __construct ($aOptions, $oTemplate)
    {
        parent::__construct ($aOptions);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    public function getCode($iProfileId = 0, $aParams = [])
    {
        $sResult = '';

        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        $iStart = !empty($aParams['start']) ? (int)$aParams['start'] : 0;
        $iPerPage = !empty($aParams['per_page']) ? (int)$aParams['per_page'] : $this->_iPerPageDefault;

        $aItems = $this->_oDb->get($iProfileId, $this->_aObject['id'], array_merge($aParams, ['per_page' => $iPerPage + 1]));
        if(empty($aItems) || !is_array($aItems))
            return $sResult;

        $iItems = count($aItems);
        $oPaginate = new BxTemplPaginate([
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}')",
            'num' => $iItems,
            'start' => $iStart,
            'per_page' => $iPerPage,
        ]);
        $sPaginate = $oPaginate->getSimplePaginate();

        if($iItems > $iPerPage)
            array_pop($aItems);

        foreach($aItems as $iId => $iCount)
            $sResult .= $this->getCodeItem($iId, $iCount);

        return $this->_oTemplate->parseHtmlByName('recommendation_block.html', [
            'class' => $this->_sClassWrapper,
            'content' => $sResult,
            'bx_if:show_paginate' => [
                'condition' => !empty($sPaginate),
                'content' => [
                    'paginate' => $sPaginate
                ]
            ]
        ]);
    }

    public function getCodeAPI($iProfileId = 0, $aParams = [])
    {
        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        $iStart = !empty($aParams['start']) ? (int)$aParams['start'] : 0;
        $iPerPage = !empty($aParams['per_page']) ? (int)$aParams['per_page'] : $this->_iPerPageDefault;

        if(empty($aParams['validate']) || !is_array($aParams['validate'])) {
            $aItems = [];
            if(!defined('BX_API_PAGE'))
                $aItems = $this->_oDb->get($iProfileId, $this->_aObject['id'], $aParams);

            $aData = [];
            foreach($aItems as $iId => $iCount) {
                $aItem = $this->getCodeItem($iId, $iCount);
                $aItem['id'] = $iId;

                $aData[] = $aItem;
            }
        }
        else
            $aData = $this->validate($iProfileId, $aParams);

        return [
            'request_url' => '',
            'data' => $aData,
            'params' => [
                'start' => $iStart,
                'per_page' => $iPerPage,
            ],
        ];
    }

    public function validate($iProfileId = 0, $aParams = [])
    {
        $aItems = $this->_oDb->get($iProfileId, $this->_aObject['id'], array_merge($aParams, [
            'start' => 0,
            'per_page' => count($aParams['validate'])
        ]));

        return sort(array_keys($aItems)) == sort($aParams['validate']) ? 'valid' : 'invalid';
    }
}

/** @} */
