<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reviews Reviews
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxReviewsTemplate extends BxBaseModTextTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_reviews';

        parent::__construct($oConfig, $oDb);
    }

    protected function _addCommonJs() {
        $sRes = $this->addJs(['BxDolVoteStars.js', 'BxReviewsVoteStars.js'], bx_is_dynamic_request());
        if (bx_is_dynamic_request()) return $sRes;
    }

    /**
     * Use Gallery image for both because currently there is no Unit types with small thumbnails.
     */
    protected function getUnitThumbAndGallery ($aData)
    {
        list($sPhotoThumb, $sPhotoGallery) = parent::getUnitThumbAndGallery($aData);

        return array($sPhotoGallery, $sPhotoGallery);
    }

    public function getMultiVoting($aValue, $isAllowedVote) {
        $aOptions = $this->_oModule->_oDb->getVotingOptions();
        if (empty($aOptions)) return '';

        $CNF = &$this->_oConfig->CNF;

        $sJsObject = $this->_oConfig->getJsObject('multi_voting');
        $sJsCode = $this->getJsCode('multi_voting');

        $iMinValue = 1;
        $iMaxValue = $this->_oDb->getParam($CNF['PARAM_MAX_STARS']);

        $aTmplVarsStars = $aTmplVarsSlider = $aTmplVarsButtons = [];
        for($i = $iMinValue; $i <= $iMaxValue; $i++) {
            $aTmplVarsStars[] = ['style_prefix' => 'bx-vote', 'value' => $i];
            $aTmplVarsSlider[] = ['style_prefix' => 'bx-vote'];
            if($isAllowedVote) $aTmplVarsButtons[] = [
                'style_prefix' => 'bx-vote',
                'js_object' => $sJsObject,
                'value' => $i
            ];
        }

        $sStars = $this->parseHtmlByName('vote_do_vote_stars.html', [
            'style_prefix' => 'bx-vote',
            'bx_repeat:stars' => $aTmplVarsStars,
            'bx_repeat:slider' => $aTmplVarsSlider,
            'bx_repeat:buttons' => $aTmplVarsButtons,
            'bx_if:show_legend' => ['condition' => false, 'content' => []],
        ]);

        $aTmplOptions = [];
        foreach ($aOptions as $aOption) {
            $iRate = isset($aValue[$aOption['id']]) ? $aValue[$aOption['id']] : 0;
            if (!$isAllowedVote && !$iRate) continue;

            $aTmplOptions[] = [
                'voting_option_name' => htmlspecialchars_adv(_t($aOption['lkey'])),
                'style_prefix' => 'bx-vote',
                'rate' => $iRate,
                'stars' => $sStars,
                'bx_if:vote_allowed' => ['condition' => $isAllowedVote, 'content' => [
                    'option_id' => $aOption['id'],
                    'rate' => $iRate,
                ]],
            ];
        }
        if (empty($aTmplOptions)) return false;

        return $this->parseHtmlByName('voting_multi_stars.html', [
            'js_code' => $this->_addCommonJs().$sJsCode,
            'bx_repeat:voting_options' => $aTmplOptions,
        ]);
    }

    public function getOverallRating($iEntryId, $sEntryRatings) {
        if (empty($sEntryRatings)) return '';
        $aEntryRatings = unserialize($sEntryRatings);

        $sJsCode = $this->getJsCode('multi_voting');

        $iCount = 0;
        $iSum = 0;
        foreach ($aEntryRatings as $iRating) {
            if ($iRating) {
                $iSum += $iRating;
                $iCount++;
            }
        }
        if (!$iCount) return '';
        $fRate = $iSum/$iCount;

        $CNF = &$this->_oConfig->CNF;

        $iMinValue = 1;
        $iMaxValue = $this->_oDb->getParam($CNF['PARAM_MAX_STARS']);

        $aTmplVarsStars = $aTmplVarsSlider = $aTmplVarsButtons = [];
        for($i = $iMinValue; $i <= $iMaxValue; $i++) {
            $aTmplVarsStars[] = ['style_prefix' => 'bx-vote', 'value' => $i];
            $aTmplVarsSlider[] = ['style_prefix' => 'bx-vote'];
        }

        $sStars = $this->parseHtmlByName('vote_do_vote_stars.html', [
            'style_prefix' => 'bx-vote',
            'bx_repeat:stars' => $aTmplVarsStars,
            'bx_repeat:slider' => $aTmplVarsSlider,
            'bx_repeat:buttons' => $aTmplVarsButtons,
            'bx_if:show_legend' => ['condition' => false, 'content' => []],
        ]);

        return $this->parseHtmlByName('unit_rating.html', [
            'rate' => $fRate,
            'stars' => $sStars,
            'details_action_url' => BX_DOL_URL_ROOT.$this->_oConfig->getBaseUri().'get_review_rating_details/'.$iEntryId,
            'js_code' => $this->_addCommonJs().$sJsCode,
        ]);
    }

    protected function getUnit($aData, $aParams = array())
    {
        $aResult = parent::getUnit($aData, $aParams);

        $sEntryRating = $this->getOverallRating($aData['id'], $aData['voting_options']);
        if ($sEntryRating) {
            $aResult['bx_if:meta']['condition'] = true;
            if (!isset($aResult['bx_if:meta']['content']['meta'])) $aResult['bx_if:meta']['content']['meta'] = '';
            $aResult['bx_if:meta']['content']['meta'] = $sEntryRating.$aResult['bx_if:meta']['content']['meta'];
        }

        return $aResult;
    }
}

/** @} */
