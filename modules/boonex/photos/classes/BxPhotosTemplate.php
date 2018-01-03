<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxPhotosTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    public function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_photos';
        parent::__construct($oConfig, $oDb);
    }

    public function entryPhoto ($aContentInfo, $bAsArray = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if(empty($aContentInfo[$CNF['FIELD_THUMB']]))
            return $bAsArray ? array() : '';

        $iImage = (int)$aContentInfo[$CNF['FIELD_THUMB']];

        $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_COVER']);
        if($oImagesTranscoder)
            $sImage = $oImagesTranscoder->getFileUrl($iImage);
        
        if(empty($sImage)) {
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_PHOTOS']);
            if($oStorage)
                $sImage = $oStorage->getFileUrl($iImage);
        }

        if(empty($sImage))
            return $bAsArray ? array() : '';

        $aTmplVars = array(
            'title' => bx_process_output($aContentInfo['title']),
            'title_attr' => bx_html_attribute($aContentInfo['title']),
            'src' => $sImage
        );

        return $bAsArray ? $aTmplVars : $this->parseHtmlByName('entry-photo.html', $aTmplVars);
    }

    public function entryPhotoSwitcher($aContentInfo, $sMode = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sJsObject = $this->_oConfig->getJsObject('main');

        $sParams = '';
        $iIdPrw = $iIdNxt = 0;
        $bIdPrw = $bIdNxt = false;
        if(!empty($sMode)) {
            bx_import('SearchResult', $this->_oModule->_aModule);
            $sSearch = $this->_oConfig->getClassPrefix() . 'SearchResult';
            $oSearch = new $sSearch($sMode);

            $aData = $oSearch->getSearchData();
            $iData = count($aData);

            for($i = 0; $i < $iData; $i++) 
                if((int)$aData[$i]['id'] == $aContentInfo[$CNF['FIELD_ID']]) {
                    if(isset($aData[$i - 1])) {
                        $iIdPrw = (int)$aData[$i - 1]['id'];
                        $bIdPrw = !empty($iIdPrw);
                    }

                    if(isset($aData[$i + 1])) {
                        $iIdNxt = (int)$aData[$i + 1]['id'];
                        $bIdNxt = !empty($iIdNxt);
                    }

                    break;
                }

            if($bIdPrw || $bIdNxt) {
                $aParams = $oSearch->aParams;

                if(isset($oSearch->aCurrent['paginate']['start']))
                    $aParams['start'] = (int)$oSearch->aCurrent['paginate']['start'];

                if(isset($oSearch->aCurrent['paginate']['perPage']))
                    $aParams['per_page'] = (int)$oSearch->aCurrent['paginate']['perPage'];

                if(!empty($aParams))
                    $sParams = bx_html_attribute(json_encode($aParams));
            }
        }

        return $this->parseHtmlByName('entry-photo-switcher.html', array_merge($this->entryPhoto($aContentInfo, true), array(
            'bx_if:show_arrow_previous' => array(
                'condition' => $bIdPrw,
        		'content' => array(
                    'js_object' => $sJsObject,
                    'id' => $iIdPrw,
                    'mode' => $sMode,
            		'params' => $sParams,
                )
            ),
            'bx_if:show_arrow_next' => array(
                'condition' => $bIdNxt,
        		'content' => array(
                    'js_object' => $sJsObject,
                    'id' => $iIdNxt,
                    'mode' => $sMode,
            		'params' => $sParams,
                )
            )
        )));
    }

    public function entryRating($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

    	$sVotes = '';
        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_STARS'], $aData[$CNF['FIELD_ID']]);
        if($oVotes) {
			$sVotes = $oVotes->getElementBlock(array('show_counter' => true, 'show_legend' => true));
			if(!empty($sVotes))
				$sVotes = $this->parseHtmlByName('entry-rating.html', array(
		    		'content' => $sVotes,
		    	));
        }

    	return $sVotes; 
    }

    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ), $aParams);

        return parent::getJsCode($sType, $aParams, $bWrap);
    }

    protected function getUnit ($aData, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sMode = $aParams['context'];
        unset($aParams['context']);

        $aResult = parent::getUnit($aData, $aParams);
        $aResult['bx_if:thumb']['content']['content_onclick'] = $this->_oConfig->getJsObject('main') . ".viewEntry(" . $aData[$CNF['FIELD_ID']] . ", '" . $sMode . "', " . bx_html_attribute(json_encode($aParams)) . "); return false;";

        return $aResult;
    }
}

/** @} */
