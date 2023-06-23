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

    public function getCode($iProfileId = 0, $iStart = 0, $iPerPage = 0)
    {
        $sResult = '';

        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        if(!$iPerPage)
            $iPerPage = $this->_iPerPageDefault;

        $aItems = $this->_oDb->get($iProfileId, $this->_aObject['id'], $iStart, $iPerPage + 1);
        if(empty($aItems) || !is_array($aItems))
            return '';

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

    public function getCodeAPI($iProfileId = 0, $iStart = 0, $iPerPage = 0)
    {
        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        if(!$iPerPage)
            $iPerPage = $this->_iPerPageDefault;

        $aItems = $this->_oDb->get($iProfileId, $this->_aObject['id'], $iStart, $iPerPage);

        $aData = [];
        foreach($aItems as $iId => $iCount)
            $aData[] = $this->getCodeItem($iId, $iCount);

        return [
            'request_url' => '',
            'data' => $aData,
            'params' => [
                'per_page' => $iStart,
                'start' => $iPerPage,
            ],
        ];
    }
}

/** @} */
